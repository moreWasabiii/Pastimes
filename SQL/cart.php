<?php
include 'DBConn.php';
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php?msg=Please login to view cart");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$msgClass = "success";

// Handle remove from cart
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart.php?msg=Item removed from cart");
    exit();
}

// Handle update quantity
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        if ($qty > 0) {
            $_SESSION['cart'][$id] = $qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
    header("Location: cart.php?msg=Cart updated");
    exit();
}

// Get cart items
$cart_items = [];
$cart_total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = $conn->query("SELECT c.*, u.full_name as seller_name, u.user_id as seller_user_id 
                            FROM tblClothes c 
                            LEFT JOIN tblUser u ON c.seller_id = u.user_id 
                            WHERE c.clothes_id IN ($ids) AND c.is_available=1 AND c.is_removed=0");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['qty'] = $_SESSION['cart'][$row['clothes_id']];
            $row['subtotal'] = $row['price'] * $row['qty'];
            $cart_total += $row['subtotal'];
            $cart_items[] = $row;
        }
    }
}

// Get unread message count
$unread_count = 0;
if (isset($_SESSION['user_id'])) {
    $unread = $conn->query("SELECT COUNT(*) as c FROM tblMessage WHERE receiver_id={$_SESSION['user_id']} AND is_read=0");
    $unread_count = $unread->fetch_assoc()['c'];
}

$cartCount = array_sum($_SESSION['cart'] ?? []);
$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Pastimes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-item {display: grid; grid-template-columns: 100px 1fr auto auto auto; gap: 1rem; align-items: center; background: white; padding: 1rem; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 1rem;}
        .cart-item img {width: 100px; height: 100px; object-fit: cover; border-radius: 6px;}
        .cart-item h3 {margin: 0; font-size: 1.1rem;}
        .cart-item .seller {font-size: 0.85rem; color: var(--text-light);}
        .cart-item .price {font-weight: 700; color: var(--accent);}
        .cart-item input[type="number"] {width: 60px; padding: 0.5rem; border: 1px solid var(--border); border-radius: 4px;}
        .cart-total {background: var(--bg-alt); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); text-align: right; margin-top: 2rem;}
        .cart-total h3 {margin: 0 0 1rem 0; font-size: 1.5rem;}
        .empty-cart {text-align: center; padding: 4rem 2rem; color: var(--text-light);}
        @media (max-width: 768px) {
            .cart-item {grid-template-columns: 80px 1fr; grid-template-rows: auto auto auto;}
            .cart-item img {grid-row: 1 / 3;}
        }
    </style>
</head>
<body>

<div class="container">
<h2>🛒 Shopping Cart</h2>

<div class="nav-links">
    <a href="index.php">✨ Welcome</a>
    <a href="shop.php">🛍 Collection</a>
    <a href="cart.php" style="font-weight: 700;">🛒 My Cart</a>
    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <a href="messages.php">💬 Messages <?php if($unread_count > 0): ?><span style="background: #e74c3c; color: white; padding: 0.1rem 0.4rem; border-radius: 10px; font-size: 0.75rem;"><?= $unread_count ?></span><?php endif; ?></a>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin_dashboard.php" style="background: var(--primary-dark); color: white; padding: 0.5rem 1rem; border-radius: 6px;">🛠 Admin</a>
        <?php endif; ?>
        <a href="logout.php">🚪 Logout</a>
    <?php endif; ?>
</div>

<?php if ($msg): ?><p class="success"><?= htmlspecialchars($msg) ?></p><?php endif; ?>

<?php if (!empty($cart_items)): ?>
    <form method="POST">
        <?php foreach ($cart_items as $item): ?>
        <div class="cart-item">
            <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            <div>
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p class="seller">by <?= htmlspecialchars($item['seller_name']) ?></p>
                <p class="price">R<?= number_format($item['price'], 2) ?> each</p>
            </div>
            <div>
                <input type="number" name="qty[<?= $item['clothes_id'] ?>]" value="<?= $item['qty'] ?>" min="0" max="10">
            </div>
            <div>
                <strong>R<?= number_format($item['subtotal'], 2) ?></strong>
            </div>
            <div>
                <a href="?remove=<?= $item['clothes_id'] ?>" style="color: #e74c3c; text-decoration: none; font-weight: 700;">✗ Remove</a>
            </div>
        </div>
        <?php endforeach; ?>
        
        <button type="submit" name="update_cart" class="btn-primary" style="margin-bottom: 1rem;">Update Cart</button>
    </form>

    <div class="cart-total">
        <h3>Total: R<?= number_format($cart_total, 2) ?></h3>
        <a href="checkout.php" class="btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">Proceed to Checkout</a>
    </div>

<?php else: ?>
    <div class="empty-cart">
        <p style="font-size: 3rem; margin-bottom: 1rem;">🛒</p>
        <h3>Your cart is empty</h3>
        <p>Discover timeless pieces in our collection</p>
        <a href="shop.php" class="btn-primary" style="margin-top: 1rem; display: inline-block;">Browse Collection</a>
    </div>
<?php endif; ?>

</div>
</body>
</html>