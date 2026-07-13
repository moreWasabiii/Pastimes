<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get all orders for this user
$stmt = $conn->prepare("
    SELECT o.*, 
           COUNT(oi.order_item_id) as item_count 
    FROM tblaorder o
    LEFT JOIN tblorderitem oi ON o.order_id = oi.order_id
    WHERE o.buyer_id = ?
    GROUP BY o.order_id
    ORDER BY o.order_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders - Pastimes</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5; 
            padding: 20px; 
            color: #333;
        }
        .container { 
            max-width: 1100px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #333; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #A68A64;
            padding-bottom: 10px;
        }
        .nav { 
            margin-bottom: 30px; 
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .nav a { 
            margin-right: 20px; 
            color: #A68A64; 
            text-decoration: none; 
            font-weight: 500;
        }
        .nav a:hover { text-decoration: underline; }
        .order-card { 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            padding: 20px; 
            margin-bottom: 20px;
            transition: box-shadow 0.3s;
        }
        .order-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .order-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            margin-bottom: 15px; 
        }
        .order-id { 
            font-weight: bold; 
            color: #A68A64; 
            font-size: 18px;
        }
        .status { 
            padding: 6px 16px; 
            border-radius: 20px; 
            font-size: 14px; 
            font-weight: 500;
        }
        .status.pending { background: #FFF3CD; color: #856404; }
        .status.completed { background: #D4EDDA; color: #155724; }
        .status.shipped { background: #D1ECF1; color: #0C5460; }
        .status.cancelled { background: #F8D7DA; color: #721C24; }
        .order-details { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            color: #666; 
            flex-wrap: wrap;
            gap: 15px;
        }
        .order-info span {
            margin-right: 25px;
        }
        .btn { 
            background: #A68A64; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 4px; 
            display: inline-block;
            transition: background 0.3s;
        }
        .btn:hover { background: #8B7355; }
        .no-orders { 
            text-align: center; 
            padding: 60px 20px; 
            color: #999; 
        }
        .no-orders p {
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="shop.php">← Back to Shop</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
        </div>
        
        <h1>My Orders</h1>
        
        <?php if (empty($orders)): ?>
            <div class="no-orders">
                <p>You haven't placed any orders yet.</p>
                <a href="shop.php" class="btn">Start Shopping</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">Order #<?= $order['order_id'] ?></span>
                        <span class="status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                    </div>
                    <div class="order-details">
                        <div class="order-info">
                            <span><strong>Date:</strong> <?= date('M d, Y', strtotime($order['order_date'])) ?></span>
                            <span><strong>Items:</strong> <?= $order['item_count'] > 0 ? $order['item_count'] : 1 ?></span>
                            <span><strong>Total:</strong> R<?= number_format($order['amount'], 2) ?></span>
                        </div>
                        <a href="order-details.php?id=<?= $order['order_id'] ?>" class="btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>