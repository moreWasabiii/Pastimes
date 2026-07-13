<?php
include 'DBConn.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = false;
$order_id = 0;

// Get cart items from SESSION - matches your cart.php
$cart_items = [];
$grand_total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = $conn->query("SELECT clothes_id, title, price, image, is_available 
                            FROM tblclothes 
                            WHERE clothes_id IN ($ids) AND is_available=1 AND is_removed=0");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['quantity'] = $_SESSION['cart'][$row['clothes_id']];
            $row['subtotal'] = $row['price'] * $row['quantity'];
            $grand_total += $row['subtotal'];
            $cart_items[] = $row;
        }
    }
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cart_items)) {
    $conn->begin_transaction();
    try {
        // 1. Create order header in tblaorder
        $stmt = $conn->prepare("INSERT INTO tblaorder (buyer_id, total_amount, status, order_date) VALUES (?, ?, 'pending', CURDATE())");
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("id", $user_id, $grand_total);
        $stmt->execute();
        $order_id = $conn->insert_id;
        $stmt->close();

        if ($order_id == 0) throw new Exception("Order insert failed");

        // 2. Insert each cart item into tblorderitem
        $stmt = $conn->prepare("INSERT INTO tblorderitem (order_id, clothes_id, quantity, price) VALUES (?, ?, ?, ?)");
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        foreach ($cart_items as $item) {
            $stmt->bind_param("iiid", $order_id, $item['clothes_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
        $stmt->close();

        $conn->commit();
        
        // 3. THIS IS THE KEY FIX - Empty the SESSION cart
        unset($_SESSION['cart']);
        $success = true;
        $cart_items = [];
        $grand_total = 0;
        
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Checkout failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Pastimes</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Space Grotesk', sans-serif; background: #0f0f0f; color: #f7fafc; margin: 0; padding: 0; }
        .checkout-wrap { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        .order-box { background: #1a1a1a; border-radius: 16px; padding: 2.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: 1px solid #2d2d2d; }
        h1 { font-weight: 700; letter-spacing: -1px; margin: 0 0 2rem 0; color: #f7fafc; }
        h3 { color: #a0aec0; font-weight: 600; margin: 1.5rem 0 1rem 0; border-bottom: 2px solid #2d2d2d; padding-bottom: 0.5rem; }
        .cart-row { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #2d2d2d; }
        .item-info { display: flex; align-items: center; gap: 1rem; flex: 1; }
        .item-thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 10px; border: 1px solid #2d2d2d; }
        .item-details span { display: block; }
        .item-title { font-weight: 600; color: #f7fafc; }
        .item-qty { font-size: 0.9rem; color: #718096; }
        .item-price { font-weight: 600; color: #f7fafc; font-size: 1.1rem; }
        .total-row { font-weight: 700; font-size: 1.3rem; border-top: 2px solid #48bb78; margin-top: 1rem; padding-top: 1rem; color: #48bb78; }
        .btn-place { width: 100%; padding: 1.2rem; background: #48bb78; color: #0f0f0f; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 700; cursor: pointer; margin-top: 1.5rem; transition: all 0.2s; text-transform: uppercase; letter-spacing: 1px; }
        .btn-place:hover { background: #38a169; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(72,187,120,0.3); }
        .alert-error { background: #2d1b1b; border: 1px solid #fc8181; color: #fed7d7; padding: 1rem; border-radius: 12px; margin: 1rem 0; }
        .back-link { display: inline-block; margin-top: 2rem; color: #48bb78; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .empty-cart { text-align: center; padding: 3rem 1rem; color: #718096; }

        /* THANK YOU SCREEN - PASTIMES */
        .thank-you { text-align: center; padding: 2rem 1rem; }
        .thank-you-icon {
            width: 120px; height: 120px; margin: 0 auto 2rem;
            background: linear-gradient(135deg, #48bb78, #38a169);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        @keyframes popIn {
            0% { transform: scale(0) rotate(-180deg); opacity: 0; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .thank-you-icon svg { width: 60px; height: 60px; color: #0f0f0f; stroke-width: 3; }
        .thank-you .tagline {
            font-size: 1rem; color: #a0aec0; margin-bottom: 0.5rem;
            text-transform: uppercase; letter-spacing: 3px; font-weight: 600;
        }
        .thank-you h2 {
            font-size: 3.5rem; font-weight: 700; margin: 0 0 1rem; line-height: 1;
            background: linear-gradient(135deg, #f7fafc 0%, #48bb78 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
            letter-spacing: -3px; animation: slideUp 0.6s ease-out 0.2s both;
        }
        @keyframes slideUp {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .thank-you .order-num {
            font-size: 1.1rem; color: #48bb78; font-weight: 600; margin: 1.5rem 0;
            background: #1a2e1a; display: inline-block; padding: 0.6rem 1.8rem; border-radius: 50px;
            border: 1px solid #2d4a2d; animation: fadeIn 0.6s ease-out 0.4s both;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .thank-you p { color: #cbd5e0; max-width: 500px; margin: 1.5rem auto; line-height: 1.7; font-size: 1.05rem; animation: fadeIn 0.6s ease-out 0.5s both; }
        .thank-you-actions { margin-top: 2.5rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; animation: fadeIn 0.6s ease-out 0.6s both; }
        .btn-secondary {
            padding: 1rem 2rem; background: transparent; color: #f7fafc; border: 2px solid #2d2d2d;
            border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-block;
        }
        .btn-secondary:hover { border-color: #48bb78; background: #1a2e1a; transform: translateY(-2px); }
        .pastimes-mark {
            font-size: 0.85rem; color: #4a5568; margin-top: 3.5rem; 
            letter-spacing: 5px; text-transform: uppercase; font-weight: 600;
            animation: fadeIn 0.6s ease-out 0.7s both;
        }
    </style>
</head>
<body>
<div class="checkout-wrap">
    <div class="order-box">
        <?php if ($success): ?>
            <div class="thank-you">
                <div class="thank-you-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="tagline">Order Confirmed</div>
                <h2>THANK YOU FOR SHOPPING</h2>
                <div class="order-num">Order #<?= $order_id ?></div>
                <p>
                    Your style just got an upgrade. We’re getting your Pastimes pieces ready.<br>
                    Check your email for order details and tracking info.
                </p>
                <div class="thank-you-actions">
                    <a href="shop.php" class="btn-place" style="text-decoration: none; display: inline-block; width: auto; padding: 1rem 2rem;">Keep Shopping</a>
                    <a href="orders.php" class="btn-secondary">View Orders</a>
                </div>
                <div class="pastimes-mark">Pastimes — Wear Your Pastime</div>
            </div>

        <?php else: ?>
            <h1>Checkout</h1>
            
            <?php if ($error): ?>
                <div class="alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (empty($cart_items)): ?>
                <div class="empty-cart">
                    <p>Your cart is empty.</p>
                    <p><a href="shop.php" style="color: #48bb78;">Go shopping</a></p>
                </div>
            <?php else: ?>
                <h3>Order Summary</h3>
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-row">
                    <div class="item-info">
                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="item-thumb" onerror="this.src='uploads/placeholder.jpg'">
                        <div class="item-details">
                            <span class="item-title"><?= htmlspecialchars($item['title']) ?></span>
                            <span class="item-qty">Qty: <?= $item['quantity'] ?> × R<?= number_format($item['price'], 2) ?></span>
                        </div>
                    </div>
                    <span class="item-price">R<?= number_format($item['subtotal'], 2) ?></span>
                </div>
                <?php endforeach; ?>
                
                <div class="cart-row total-row">
                    <span>Total</span>
                    <span>R<?= number_format($grand_total, 2) ?></span>
                </div>

                <form method="POST">
                    <button type="submit" class="btn-place">Place Order</button>
                </form>
            <?php endif; ?>
            
            <a href="cart.php" class="back-link">← Back to Cart</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>