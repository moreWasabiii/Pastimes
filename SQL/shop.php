<?php
include 'DBConn.php';
session_start();

if (isset($_POST['ajax_add'])) {
    $id = (int)$_POST['ajax_add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;

    header('Content-Type: application/json');
    $cartCount = array_sum($_SESSION['cart']);
    echo json_encode(['success' => true, 'cartCount' => $cartCount]);
    exit();
}

$cartCount = array_sum($_SESSION['cart'] ?? []);
$unread_count = 0;
$is_seller = false;

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    if (!empty($_SESSION['is_admin'])) {
        $admin_id = (int)$_SESSION['admin_id'];
        $unread = $conn->query("SELECT COUNT(*) as c FROM tblmessage WHERE receiver_type='admin' AND receiver_id=$admin_id AND is_read=0");
        $unread_count = $unread ? (int)$unread->fetch_assoc()['c'] : 0;
    } elseif (!empty($_SESSION['user_id'])) {
        $user_id = (int)$_SESSION['user_id'];
        $unread = $conn->query("SELECT COUNT(*) as c FROM tblmessage WHERE receiver_type='user' AND receiver_id=$user_id AND is_read=0");
        $unread_count = $unread ? (int)$unread->fetch_assoc()['c'] : 0;

        $stmt = $conn->prepare("SELECT seller_id FROM tblseller WHERE user_id = ? AND is_approved = 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $is_seller = $stmt->get_result()->num_rows > 0;
        $stmt->close();
    }
}

$items = $conn->query("SELECT c.*, u.user_id as seller_user_id, u.full_name as seller_name
                       FROM tblclothes c
                       LEFT JOIN tbluser u ON c.seller_id = u.user_id
                       WHERE c.is_available=1 AND c.is_removed=0
                       ORDER BY c.clothes_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection - Pastimes</title>
    <link rel="stylesheet" href="style.css">
    <style>
       .cart-toast {position: fixed; top: 20px; right: 20px; background: var(--primary); color: white; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; opacity: 0; transform: translateY(-20px); transition: all 0.3s ease;}
       .cart-toast.show {opacity: 1; transform: translateY(0);}
       .add-btn.adding {background: var(--accent) !important; transform: scale(0.95);}
       .btn-message {width: 100%; margin-top: 0.5rem; background: var(--primary-dark); color: white; padding: 0.7rem; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; text-decoration: none; display: block; text-align: center; transition: all 0.2s;}
       .btn-message:hover {background: var(--primary);}
       .badge {background: #e74c3c; color: white; padding: 0.1rem 0.4rem; border-radius: 10px; font-size: 0.75rem; margin-left: 0.25rem;}
    </style>
</head>
<body>

<div class="container">
<h2>Pastimes Collection</h2>
<p style="color: var(--text-light); margin-bottom: 2rem;">Timeless pieces, carefully curated</p>

<div class="nav-links">
    <a href="index.php">✨ Welcome</a>
    <a href="shop.php" style="font-weight: 700;">🛍 Collection</a>
    <a href="cart.php">🛒 My Cart <span id="cart-count"><?php if($cartCount > 0) echo "(" . $cartCount . ")"; ?></span></a>

    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <?php if (!empty($_SESSION['is_admin'])): ?>
            <a href="admin_dashboard.php">🛠 Admin Dashboard</a>
            <a href="messages.php">💬 Messages <?php if($unread_count > 0): ?><span class="badge"><?= $unread_count ?></span><?php endif; ?></a>
            <a href="logout.php">🚪 Logout</a>
        <?php else: ?>
            <a href="<?= $is_seller ? 'seller_dashboard.php' : 'seller_request.php' ?>">🏷 Sell</a>
            <a href="messages.php">💬 Messages <?php if($unread_count > 0): ?><span class="badge"><?= $unread_count ?></span><?php endif; ?></a>
            <a href="logout.php">🚪 Logout</a>
        <?php endif; ?>
    <?php else: ?>
        <a href="login.php">👤 Sign In</a>
        <a href="register.php">📝 Join</a>
    <?php endif; ?>
</div>

<div class="items-grid">
<?php if ($items && $items->num_rows > 0): ?>
    <?php while($row = $items->fetch_assoc()): ?>
    <div class="item-card">
        <?php if (!empty($row['image']) && $row['image'] !== 'placeholder.jpg'): ?>
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
        <?php else: ?>
            <img src="[via.placeholder.com](https://via.placeholder.com/300x300?text=Pastimes)" alt="No image">
        <?php endif; ?>

        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <p style="color: var(--accent); font-weight: 700; margin: 0.5rem 0;"><?= htmlspecialchars($row['brand']) ?></p>
        <p style="color: var(--text-light); font-size: 0.9rem;"><?= htmlspecialchars($row['category']) ?></p>
        <?php if ($row['seller_name']): ?>
            <p style="color: var(--text-light); font-size: 0.85rem;">Seller: <?= htmlspecialchars($row['seller_name']) ?></p>
        <?php endif; ?>
        <p class="price">R<?= number_format($row['price'], 2) ?></p>

        <button class="add-btn btn-primary" data-item-id="<?= $row['clothes_id'] ?>" data-item-title="<?= htmlspecialchars($row['title']) ?>">
            Add to Cart
        </button>

        <?php if (
            isset($_SESSION['logged_in']) &&
            $_SESSION['logged_in'] &&
            empty($_SESSION['is_admin']) &&
            $row['seller_user_id'] &&
            $row['seller_user_id'] != ($_SESSION['user_id'] ?? 0)
        ): ?>
            <a href="messages.php?to=<?= $row['seller_user_id'] ?>&item=<?= $row['clothes_id'] ?>" class="btn-message">
                💬 Message Seller
            </a>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center; grid-column: 1/-1; color: var(--text-light);">No items available right now. Check back soon!</p>
<?php endif; ?>
</div>
</div>

<div id="cart-toast" class="cart-toast"></div>

<script>
document.querySelectorAll('.add-btn').forEach(btn => {
    btn.addEventListener('click', async function(e) {
        e.preventDefault();

        const itemId = this.dataset.itemId;
        const itemTitle = this.dataset.itemTitle;

        this.classList.add('adding');
        this.textContent = 'Adding...';

        try {
            const formData = new FormData();
            formData.append('ajax_add', itemId);

            const response = await fetch('shop.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('cart-count').textContent = `(${data.cartCount})`;
                showToast(`${itemTitle} added to cart`);

                this.textContent = '✓ Added';
                setTimeout(() => {
                    this.textContent = 'Add to Cart';
                    this.classList.remove('adding');
                }, 1000);
            }
        } catch (error) {
            this.textContent = 'Error';
            setTimeout(() => {
                this.textContent = 'Add to Cart';
                this.classList.remove('adding');
            }, 1000);
        }
    });
});

function showToast(message) {
    const toast = document.getElementById('cart-toast');
    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 2000);
}
</script>

</body>
</html>
