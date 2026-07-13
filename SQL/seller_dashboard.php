<?php
include 'DBConn.php';
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || empty($_SESSION['user_id']) || !empty($_SESSION['is_admin'])) {
    header("Location: login.php?msg=Please login");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$message = "";
$msgClass = "success";

$seller_check = $conn->query("SELECT s.*, u.full_name FROM tblseller s JOIN tbluser u ON s.user_id=u.user_id WHERE s.user_id=$user_id AND s.is_approved=1 LIMIT 1");
if (!$seller_check || $seller_check->num_rows === 0) {
    header("Location: seller_request.php?msg=You need to be an approved seller");
    exit();
}
$seller_info = $seller_check->fetch_assoc();

if (isset($_POST['add_item'])) {
    $title = trim($_POST['title']);
    $brand = trim($_POST['brand']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];

    $image = 'placeholder.jpg';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $original_name = $_FILES["image"]["name"];
        $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        if (in_array($extension, $allowed_extensions, true)) {
            $image = time() . '_' . preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($original_name));
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
        }
    }

    $stmt = $conn->prepare("INSERT INTO tblclothes (title, brand, category, description, price, image, seller_id, is_available, is_removed) VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0)");
    $stmt->bind_param("ssssdsi", $title, $brand, $category, $description, $price, $image, $user_id);

    if ($stmt->execute()) {
        $message = "Item submitted for approval! It will appear in the shop once reviewed.";
    } else {
        $message = "Error adding item.";
        $msgClass = "error";
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $conn->query("UPDATE tblclothes SET is_removed=1, is_available=0 WHERE clothes_id=$delete_id AND seller_id=$user_id");
    header("Location: seller_dashboard.php?msg=Item removed");
    exit();
}

$my_items = $conn->query("SELECT * FROM tblclothes WHERE seller_id=$user_id ORDER BY clothes_id DESC");

$total_items = $conn->query("SELECT COUNT(*) as c FROM tblclothes WHERE seller_id=$user_id")->fetch_assoc()['c'];
$active_items = $conn->query("SELECT COUNT(*) as c FROM tblclothes WHERE seller_id=$user_id AND is_available=1 AND is_removed=0")->fetch_assoc()['c'];
$pending_items = $conn->query("SELECT COUNT(*) as c FROM tblclothes WHERE seller_id=$user_id AND is_available=0 AND is_removed=0")->fetch_assoc()['c'];

$unread_count = 0;
$unread = $conn->query("SELECT COUNT(*) as c FROM tblmessage WHERE receiver_type='user' AND receiver_id=$user_id AND is_read=0");
if ($unread) {
    $unread_count = $unread->fetch_assoc()['c'];
}

$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Pastimes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-header {background: var(--primary); color: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;}
        .dashboard-header h1 {margin: 0; color: white;}
        .stats-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;}
        .stat-card {background: white; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); border-left: 4px solid var(--accent);}
        .stat-card .number {font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;}
        .stat-card .label {color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;}
        .add-item-form {background: white; padding: 2rem; border-radius: 8px; border: 1px solid var(--border); margin-bottom: 2rem;}
        .items-grid {display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;}
        .item-card {background: white; border: 1px solid var(--border); border-radius: 8px; padding: 1rem;}
        .item-card img {width: 100%; height: 200px; object-fit: cover; border-radius: 6px; margin-bottom: 1rem;}
        .item-card h3 {margin: 0 0 0.5rem 0; font-size: 1.1rem;}
        .item-card .status {display: inline-block; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 700;}
        .status-approved {background: #d4edda; color: #155724;}
        .status-pending {background: #fff3cd; color: #856404;}
        .status-removed {background: #f8d7da; color: #721c24;}
        .item-actions {display: flex; gap: 0.5rem; margin-top: 1rem;}
        .btn-delete {background: #e74c3c; color: white; padding: 0.5rem 1rem; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 0.9rem;}
    </style>
</head>
<body>

<div class="container">

<div class="dashboard-header">
    <h1>🏷️ Seller Dashboard</h1>
    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Welcome back, <?= htmlspecialchars($seller_info['business_name']) ?></p>
    <div style="margin-top: 1rem;">
        <a href="index.php" style="color: white; text-decoration: underline;">← Welcome Page</a> |
        <a href="shop.php" style="color: white; text-decoration: underline;">Shop</a> |
        <a href="messages.php" style="color: white; text-decoration: underline;">💬 Messages <?php if($unread_count > 0): ?><span style="background: #e74c3c; padding: 0.1rem 0.4rem; border-radius: 10px;"><?= $unread_count ?></span><?php endif; ?></a> |
        <a href="logout.php" style="color: white; text-decoration: underline;">Logout</a>
    </div>
</div>

<?php if ($message || $msg): ?>
    <p class="<?= $msgClass ?>"><?= htmlspecialchars($message ?: $msg) ?></p>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card"><p class="number"><?= $total_items ?></p><p class="label">Total Items</p></div>
    <div class="stat-card"><p class="number"><?= $active_items ?></p><p class="label">Listed Items</p></div>
    <div class="stat-card"><p class="number"><?= $pending_items ?></p><p class="label">Pending Approval</p></div>
</div>

<div class="add-item-form">
    <h3>Add New Item</h3>
    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <input name="title" placeholder="Item Title" required>
            <input name="brand" placeholder="Brand" required>
            <input name="category" placeholder="Category (e.g. Dress, Jacket)" required>
            <input name="price" type="number" step="0.01" placeholder="Price (R)" required>
        </div>
        <textarea name="description" placeholder="Description, condition, size, etc." rows="3" required style="margin-top: 1rem;"></textarea>
        <input type="file" name="image" accept="image/*" style="margin-top: 1rem;">
        <button type="submit" name="add_item" class="btn-primary" style="margin-top: 1rem;">Submit for Approval</button>
    </form>
</div>

<h2>Your Items</h2>
<div class="items-grid">
    <?php if ($my_items && $my_items->num_rows > 0): ?>
        <?php while($item = $my_items->fetch_assoc()): ?>
        <div class="item-card">
            <img src="uploads/<?= htmlspecialchars($item['image'] ?: 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            <h3><?= htmlspecialchars($item['title']) ?></h3>
            <p style="color: var(--accent); font-weight: 700;"><?= htmlspecialchars($item['brand']) ?></p>
            <p class="price">R<?= number_format($item['price'], 2) ?></p>

            <?php if ((int)$item['is_removed'] === 1): ?>
                <span class="status status-removed">Removed</span>
            <?php elseif ((int)$item['is_available'] === 1): ?>
                <span class="status status-approved">✓ Listed</span>
            <?php else: ?>
                <span class="status status-pending">⏳ Pending</span>
            <?php endif; ?>

            <div class="item-actions">
                <a href="?delete=<?= $item['clothes_id'] ?>" class="btn-delete" onclick="return confirm('Remove this item?')">Remove</a>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="grid-column: 1/-1; text-align: center; color: var(--text-light); padding: 3rem;">No items yet. Add your first item above!</p>
    <?php endif; ?>
</div>

</div>
</body>
</html>
