<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Get order info + verify it belongs to this user
$stmt = $conn->prepare("SELECT * FROM tblaorder WHERE order_id = ? AND buyer_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found");
}

// Get order items
$stmt = $conn->prepare("
    SELECT oi.*, c.title, c.image, c.price 
    FROM tblorderitem oi
    JOIN tblclothes c ON oi.clothes_id = c.clothes_id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order #<?= $order_id ?> - Pastimes</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        .item { display: flex; gap: 20px; border-bottom: 1px solid #eee; padding: 15px 0; }
        .item img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; }
        .total { text-align: right; font-size: 20px; font-weight: bold; margin-top: 20px; }
        .btn { background: #A68A64; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="orders.php">← Back to Orders</a>
        <h1>Order #<?= $order['order_id'] ?></h1>
        <p>Status: <strong><?= ucfirst($order['status']) ?></strong></p>
        <p>Date: <?= date('M d, Y', strtotime($order['order_date'])) ?></p>
        
        <h3>Items</h3>
        <?php if (empty($items)): ?>
            <p>No items found. This order only has one item: </p>
            <?php
            // Fallback for old orders without tblorderitem
            $stmt2 = $conn->prepare("SELECT * FROM tblclothes WHERE clothes_id = ?");
            $stmt2->bind_param("i", $order['clothes_id']);
            $stmt2->execute();
            $single_item = $stmt2->get_result()->fetch_assoc();
            if ($single_item): ?>
                <div class="item">
                    <img src="uploads/<?= htmlspecialchars($single_item['image']) ?>" alt="<?= htmlspecialchars($single_item['title']) ?>">
                    <div>
                        <h4><?= htmlspecialchars($single_item['title']) ?></h4>
                        <p>Price: R<?= number_format($order['amount'], 2) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
                <div class="item">
                    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                    <div>
                        <h4><?= htmlspecialchars($item['title']) ?></h4>
                        <p>Qty: <?= $item['quantity'] ?> × R<?= number_format($item['price'], 2) ?></p>
                        <p>Subtotal: R<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div class="total">Total: R<?= number_format($order['amount'], 2) ?></div>
    </div>
</body>
</html>