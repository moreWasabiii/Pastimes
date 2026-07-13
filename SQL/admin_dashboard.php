<?php
include 'DBConn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

$message = "";
$msgClass = "success";
$admin_id = $_SESSION['user_id'];

// Handle seller approval/rejection
if (isset($_POST['approve_seller'])) {
    $seller_id = (int)$_POST['seller_id'];
    $conn->query("UPDATE tblSeller SET is_approved=1 WHERE seller_id=$seller_id");
    $conn->query("UPDATE tblUser SET is_seller=1 WHERE user_id=(SELECT user_id FROM tblSeller WHERE seller_id=$seller_id)");
    $message = "Seller approved successfully!";
}

if (isset($_POST['reject_seller'])) {
    $seller_id = (int)$_POST['seller_id'];
    $conn->query("DELETE FROM tblSeller WHERE seller_id=$seller_id");
    $message = "Seller request rejected.";
    $msgClass = "error";
}

// Handle remove seller - KEEP ITEMS BUT MARK UNAVAILABLE
if (isset($_POST['remove_seller'])) {
    $seller_id = (int)$_POST['seller_id'];
    $user_id = (int)$_POST['user_id'];
    $reason = $conn->real_escape_string($_POST['reason']);
    $custom_rebut = $conn->real_escape_string($_POST['rebut_info']);
    
    $rebut_info = !empty($custom_rebut) ? $custom_rebut : "If you believe this removal was in error, please email admin@pastimes.com within 7 days with case #$seller_id and supporting evidence. We will review and respond within 48 hours.";
    
    // 1. Create notice record
    $stmt = $conn->prepare("INSERT INTO tblNotice (target_type, target_id, user_id, reason, admin_id, rebut_info) VALUES ('seller', ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $seller_id, $user_id, $reason, $admin_id, $rebut_info);
    $stmt->execute();
    $stmt->close();
    
    // 2. Send AUTOMATED message to seller
    $subject = "Important: Your seller account has been removed";
    $auto_msg = "Dear Seller,\n\nYour Pastimes seller account has been removed for the following reason:\n\n$reason\n\n$rebut_info\n\nThank you,\nPastimes Admin Team";
    $stmt = $conn->prepare("INSERT INTO tblMessage (sender_id, receiver_id, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $admin_id, $user_id, $subject, $auto_msg);
    $stmt->execute();
    $stmt->close();
    
    // 3. Mark all their items as removed, NOT deleted
    $conn->query("UPDATE tblClothes SET is_removed=1, is_available=0 WHERE seller_id=$user_id");
    $conn->query("DELETE FROM tblSeller WHERE seller_id=$seller_id");
    $conn->query("UPDATE tblUser SET is_seller=0 WHERE user_id=$user_id");
    
    $message = "Seller removed. Items marked unavailable but remain visible. Rebuttal sent.";
}

// Handle remove item with notice
if (isset($_POST['remove_item'])) {
    $clothes_id = (int)$_POST['clothes_id'];
    $seller_user_id = (int)$_POST['seller_user_id'];
    $reason = $conn->real_escape_string($_POST['reason']);
    $rebut_info = $conn->real_escape_string($_POST['rebut_info']);
    
    $stmt = $conn->prepare("INSERT INTO tblNotice (target_type, target_id, user_id, reason, admin_id, rebut_info) VALUES ('item', ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $clothes_id, $seller_user_id, $reason, $admin_id, $rebut_info);
    $stmt->execute();
    $stmt->close();
    
    $subject = "Important: Your item has been removed";
    $auto_msg = "Your item listing has been removed for the following reason:\n\n$reason\n\n$rebut_info\n\nPastimes Admin Team";
    $stmt = $conn->prepare("INSERT INTO tblMessage (sender_id, receiver_id, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $admin_id, $seller_user_id, $subject, $auto_msg);
    $stmt->execute();
    $stmt->close();
    
    $conn->query("UPDATE tblClothes SET is_removed=1, is_available=0 WHERE clothes_id=$clothes_id");
    $message = "Item removed and notice sent to seller.";
}

// Handle item approval
if (isset($_POST['approve_item'])) {
    $clothes_id = (int)$_POST['clothes_id'];
    $conn->query("UPDATE tblClothes SET is_available=1 WHERE clothes_id=$clothes_id");
    $message = "Item approved and listed!";
}

// Admin unread message count
$unread_count = $conn->query("SELECT COUNT(*) as c FROM tblMessage WHERE receiver_id=$admin_id AND is_read=0")->fetch_assoc()['c'];

// Stats for dashboard
$total_users = $conn->query("SELECT COUNT(*) as c FROM tblUser")->fetch_assoc()['c'];
$total_sellers = $conn->query("SELECT COUNT(*) as c FROM tblSeller WHERE is_approved=1")->fetch_assoc()['c'];
$total_items = $conn->query("SELECT COUNT(*) as c FROM tblClothes WHERE is_available=1")->fetch_assoc()['c'];
$pending_sellers = $conn->query("SELECT COUNT(*) as c FROM tblSeller WHERE is_approved=0")->fetch_assoc()['c'];
$pending_items = $conn->query("SELECT COUNT(*) as c FROM tblClothes WHERE is_available=0 AND is_removed=0")->fetch_assoc()['c'];
$total_orders = $conn->query("SELECT COUNT(*) as c FROM tblAorder")->fetch_assoc()['c'];

// Check what columns exist in tblAorder to avoid errors
$order_columns = $conn->query("SHOW COLUMNS FROM tblAorder");
$order_cols = [];
while($col = $order_columns->fetch_assoc()) {
    $order_cols[] = $col['Field'];
}

$has_total_amount = in_array('total_amount', $order_cols);
$has_created_at = in_array('created_at', $order_cols);
$has_order_date = in_array('order_date', $order_cols);
$user_col = in_array('user_id', $order_cols) ? 'user_id' : (in_array('customer_id', $order_cols) ? 'customer_id' : (in_array('buyer_id', $order_cols) ? 'buyer_id' : null));

$revenue = 0;
if ($has_total_amount) {
    $revenue = $conn->query("SELECT SUM(total_amount) as sum FROM tblAorder WHERE status='completed'")->fetch_assoc()['sum'] ?? 0;
} else {
    $revenue = $total_orders * 100; // placeholder
}

// Recent activity
$recent_users = $conn->query("SELECT full_name, email, created_at FROM tblUser ORDER BY user_id DESC LIMIT 5");

// Recent orders - dynamic based on columns
$recent_orders = null;
if ($user_col) {
    $date_col = $has_created_at ? 'created_at' : ($has_order_date ? 'order_date' : null);
    $order_query = "SELECT o.order_id" . ($date_col ? ", o.$date_col" : "") . ", u.full_name FROM tblAorder o JOIN tblUser u ON o.$user_col=u.user_id ORDER BY o.order_id DESC LIMIT 5";
    $recent_orders = $conn->query($order_query);
}

// Get data for tabs
$seller_requests = $conn->query("SELECT s.*, u.full_name, u.email FROM tblSeller s JOIN tblUser u ON s.user_id=u.user_id WHERE s.is_approved=0 ORDER BY s.seller_id DESC");
$active_sellers = $conn->query("SELECT s.*, u.full_name, u.email, u.user_id FROM tblSeller s JOIN tblUser u ON s.user_id=u.user_id WHERE s.is_approved=1 ORDER BY s.seller_id DESC");
$item_requests = $conn->query("SELECT c.*, u.full_name as seller_name, u.user_id as seller_user_id FROM tblClothes c LEFT JOIN tblUser u ON c.seller_id=u.user_id WHERE c.is_available=0 AND c.is_removed=0 ORDER BY c.clothes_id DESC");
$all_items = $conn->query("SELECT c.*, u.full_name as seller_name, u.user_id as seller_user_id FROM tblClothes c LEFT JOIN tblUser u ON c.seller_id=u.user_id WHERE c.is_available=1 ORDER BY c.clothes_id DESC");

$active_tab = $_GET['tab'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pastimes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-header {background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%); color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;}
        .admin-header h1 {margin: 0; color: white; font-size: 2rem;}
        .admin-nav {display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;}
        .admin-nav a {background: rgba(255,255,255,0.2); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.2s; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);}
        .admin-nav a:hover {background: rgba(255,255,255,0.3); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2);}
        .stats-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;}
        .stat-card {background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border); border-left: 4px solid var(--accent); box-shadow: 0 2px 8px rgba(0,0,0,0.05);}
        .stat-card .number {font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;}
        .stat-card .label {color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;}
        .stat-card.pending {border-left-color: #e74c3c;}
        .stat-card.pending .number {color: #e74c3c;}
        .tabs {display: flex; gap: 0.5rem; border-bottom: 2px solid var(--border); margin-bottom: 2rem; flex-wrap: wrap;}
        .tab {padding: 0.8rem 1.5rem; background: transparent; border: none; color: var(--text-light); font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.2s; text-decoration: none;}
        .tab:hover, .tab.active {color: var(--primary); border-bottom-color: var(--primary);}
        .tab .badge {background: #e74c3c; color: white; padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.75rem; margin-left: 0.5rem;}
        .request-card {background: white; border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);}
        .request-header {display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;}
        .request-actions {display: flex; gap: 0.5rem; flex-wrap: wrap;}
        .btn-approve {background: #27ae60; color: white; padding: 0.6rem 1.2rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;}
        .btn-reject, .btn-remove {background: #e74c3c; color: white; padding: 0.6rem 1.2rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;}
        .btn-message {background: var(--primary); color: white; padding: 0.6rem 1.2rem; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 600;}
        .modal {display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);}
        .modal-content {background: white; margin: 5% auto; padding: 2rem; border-radius: 12px; width: 90%; max-width: 500px;}
        .empty-state {text-align: center; padding: 3rem; color: var(--text-light);}
        .activity-card {background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 1rem;}
        .activity-item {padding: 0.75rem 0; border-bottom: 1px solid var(--border);}
        .activity-item:last-child {border-bottom: none;}
        .dashboard-grid {display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;}
        @media (max-width: 768px) {
            .dashboard-grid {grid-template-columns: 1fr;}
        }
    </style>
</head>
<body>

<div class="container">

<div class="admin-header">
    <h1>🛠️ Pastimes Admin Dashboard</h1>
    <div class="admin-nav">
        <a href="index.php">✨ Welcome</a>
        <a href="shop.php">🛍 Shop</a>
        <a href="messages.php">💬 Messages <?php if($unread_count > 0): ?><span style="background: #e74c3c; padding: 0.1rem 0.4rem; border-radius: 10px; font-size: 0.75rem;"><?= $unread_count ?></span><?php endif; ?></a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<?php if ($message): ?>
    <p class="<?= $msgClass ?>"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card"><p class="number"><?= $total_users ?></p><p class="label">Total Users</p></div>
    <div class="stat-card"><p class="number"><?= $total_sellers ?></p><p class="label">Active Sellers</p></div>
    <div class="stat-card"><p class="number"><?= $total_items ?></p><p class="label">Listed Items</p></div>
    <div class="stat-card pending"><p class="number"><?= $pending_sellers ?></p><p class="label">Pending Sellers</p></div>
    <div class="stat-card pending"><p class="number"><?= $pending_items ?></p><p class="label">Pending Items</p></div>
    <div class="stat-card"><p class="number"><?= $total_orders ?></p><p class="label">Total Orders</p></div>
</div>

<div class="tabs">
    <a href="?tab=dashboard" class="tab <?= $active_tab == 'dashboard' ? 'active' : '' ?>">Dashboard</a>
    <a href="?tab=sellers" class="tab <?= $active_tab == 'sellers' ? 'active' : '' ?>">Seller Requests <?php if($pending_sellers > 0) echo "<span class='badge'>$pending_sellers</span>"; ?></a>
    <a href="?tab=active_sellers" class="tab <?= $active_tab == 'active_sellers' ? 'active' : '' ?>">Active Sellers</a>
    <a href="?tab=items" class="tab <?= $active_tab == 'items' ? 'active' : '' ?>">Item Approvals <?php if($pending_items > 0) echo "<span class='badge'>$pending_items</span>"; ?></a>
    <a href="?tab=all_items" class="tab <?= $active_tab == 'all_items' ? 'active' : '' ?>">All Items</a>
</div>

<?php if ($active_tab == 'dashboard'): ?>
    <div class="dashboard-grid">
        <div class="activity-card">
            <h3>Recent Users</h3>
            <?php if ($recent_users && $recent_users->num_rows > 0): ?>
                <?php while($u = $recent_users->fetch_assoc()): ?>
                <div class="activity-item">
                    <strong><?= htmlspecialchars($u['full_name']) ?></strong><br>
                    <span style="color: var(--text-light); font-size: 0.85rem;"><?= htmlspecialchars($u['email']) ?><br>Joined <?= date('M j, Y', strtotime($u['created_at'])) ?></span>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: var(--text-light);">No users yet</p>
            <?php endif; ?>
        </div>
        
        <div class="activity-card">
            <h3>Recent Orders</h3>
            <?php if ($recent_orders && $recent_orders->num_rows > 0): ?>
                <?php while($o = $recent_orders->fetch_assoc()): ?>
                <div class="activity-item">
                    <strong>Order #<?= $o['order_id'] ?></strong><br>
                    <span style="color: var(--text-light); font-size: 0.85rem;">by <?= htmlspecialchars($o['full_name']) ?><?php if(isset($o['created_at'])): ?> on <?= date('M j, Y g:i A', strtotime($o['created_at'])) ?><?php elseif(isset($o['order_date'])): ?> on <?= date('M j, Y g:i A', strtotime($o['order_date'])) ?><?php endif; ?></span>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: var(--text-light);">No orders yet</p>
            <?php endif; ?>
        </div>
    </div>

<?php elseif ($active_tab == 'sellers'): ?>
    <h2>Pending Seller Applications</h2>
    <?php if ($seller_requests && $seller_requests->num_rows > 0): ?>
        <?php while($s = $seller_requests->fetch_assoc()): ?>
        <div class="request-card">
            <div class="request-header">
                <div>
                    <h3><?= htmlspecialchars($s['business_name']) ?></h3>
                    <p><strong>Owner:</strong> <?= htmlspecialchars($s['full_name']) ?> | <?= htmlspecialchars($s['email']) ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($s['contact_number']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($s['address']) ?></p>
                </div>
                <div class="request-actions">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="seller_id" value="<?= $s['seller_id'] ?>">
                        <button type="submit" name="approve_seller" class="btn-approve">✓ Approve</button>
                    </form>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="seller_id" value="<?= $s['seller_id'] ?>">
                        <button type="submit" name="reject_seller" class="btn-reject">✗ Reject</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state"><p>No pending seller applications</p></div>
    <?php endif; ?>

<?php elseif ($active_tab == 'active_sellers'): ?>
    <h2>Active Sellers</h2>
    <?php if ($active_sellers && $active_sellers->num_rows > 0): ?>
        <?php while($s = $active_sellers->fetch_assoc()): ?>
        <div class="request-card">
            <div class="request-header">
                <div>
                    <h3><?= htmlspecialchars($s['business_name']) ?></h3>
                    <p><strong>Owner:</strong> <?= htmlspecialchars($s['full_name']) ?> | <?= htmlspecialchars($s['email']) ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($s['contact_number']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($s['address']) ?></p>
                </div>
                <div class="request-actions">
                    <a href="messages.php?to=<?= $s['user_id'] ?>" class="btn-message">💬 Message Seller</a>
                    <button onclick="openRemoveSellerModal(<?= $s['seller_id'] ?>, <?= $s['user_id'] ?>, '<?= htmlspecialchars($s['business_name']) ?>')" class="btn-remove">Remove Seller</button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state"><p>No active sellers yet</p></div>
    <?php endif; ?>

<?php elseif ($active_tab == 'items'): ?>
    <h2>Pending Item Approvals</h2>
    <?php if ($item_requests && $item_requests->num_rows > 0): ?>
        <?php while($item = $item_requests->fetch_assoc()): ?>
        <div class="request-card">
            <div class="request-header">
                <div>
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p><strong>Brand:</strong> <?= htmlspecialchars($item['brand']) ?> | <strong>Category:</strong> <?= htmlspecialchars($item['category']) ?></p>
                    <p><strong>Price:</strong> R<?= number_format($item['price'], 2) ?></p>
                    <p><strong>Seller:</strong> <?= htmlspecialchars($item['seller_name'] ?? 'Unknown') ?></p>
                    <p><?= htmlspecialchars($item['description']) ?></p>
                </div>
                <div class="request-actions">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="clothes_id" value="<?= $item['clothes_id'] ?>">
                        <button type="submit" name="approve_item" class="btn-approve">✓ Approve</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state"><p>No pending items</p></div>
    <?php endif; ?>

<?php elseif ($active_tab == 'all_items'): ?>
    <h2>All Listed Items</h2>
    <div class="items-grid">
        <?php if ($all_items && $all_items->num_rows > 0): ?>
            <?php while($item = $all_items->fetch_assoc()): ?>
            <div class="item-card">
                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p style="color: var(--accent); font-weight: 700;"><?= htmlspecialchars($item['brand']) ?></p>
                <p class="price">R<?= number_format($item['price'], 2) ?></p>
                <p style="font-size: 0.85rem; color: var(--text-light);">Seller: <?= htmlspecialchars($item['seller_name'] ?? 'Unknown') ?></p>
                <div style="margin-top: 1rem; display: flex; gap: 0.5rem; flex-direction: column;">
                    <a href="messages.php?to=<?= $item['seller_user_id'] ?>" class="btn-message">💬 Message Seller</a>
                    <button onclick="openRemoveItemModal(<?= $item['clothes_id'] ?>, <?= $item['seller_user_id'] ?>, '<?= htmlspecialchars($item['title']) ?>')" class="btn-remove">Remove Item</button>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center;">No items listed yet</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

</div>

<!-- Remove Seller Modal -->
<div id="removeSellerModal" class="modal">
    <div class="modal-content">
        <h3>Remove Seller</h3>
        <form method="POST">
            <input type="hidden" name="seller_id" id="remove_seller_id">
            <input type="hidden" name="user_id" id="remove_seller_user_id">
            <p id="remove_seller_name" style="font-weight: 700; margin-bottom: 1rem;"></p>
            <p style="color: #e74c3c; font-size: 0.9rem; margin-bottom: 1rem;">⚠️ This will mark all items as unavailable but keep them visible in shop with a notice.</p>
            <label>Reason for Removal *</label>
            <textarea name="reason" rows="3" required placeholder="e.g. Violates terms, repeated policy violations, fraudulent activity..."></textarea>
            <label>Rebuttal Instructions (leave blank for default)</label>
            <textarea name="rebut_info" rows="3" placeholder="Default: Email admin@pastimes.com within 7 days with case # and evidence..."></textarea>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" name="remove_seller" class="btn-remove">Confirm Removal</button>
                <button type="button" onclick="closeModal('removeSellerModal')" class="btn-primary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Remove Item Modal -->
<div id="removeItemModal" class="modal">
    <div class="modal-content">
        <h3>Remove Item</h3>
        <form method="POST">
            <input type="hidden" name="clothes_id" id="remove_item_id">
            <input type="hidden" name="seller_user_id" id="remove_item_seller_id">
            <p id="remove_item_name" style="font-weight: 700; margin-bottom: 1rem;"></p>
            <label>Reason for Removal *</label>
            <textarea name="reason" rows="3" required placeholder="e.g. Counterfeit, prohibited item, misleading photos..."></textarea>
            <label>Rebuttal Instructions (leave blank for default)</label>
            <textarea name="rebut_info" rows="3" placeholder="Default: Contact admin with proof of authenticity..."></textarea>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" name="remove_item" class="btn-remove">Confirm Removal</button>
                <button type="button" onclick="closeModal('removeItemModal')" class="btn-primary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRemoveSellerModal(sellerId, userId, name) {
    document.getElementById('remove_seller_id').value = sellerId;
    document.getElementById('remove_seller_user_id').value = userId;
    document.getElementById('remove_seller_name').textContent = 'Removing: ' + name;
    document.getElementById('removeSellerModal').style.display = 'block';
}
function openRemoveItemModal(itemId, sellerUserId, name) {
    document.getElementById('remove_item_id').value = itemId;
    document.getElementById('remove_item_seller_id').value = sellerUserId;
    document.getElementById('remove_item_name').textContent = 'Removing: ' + name;
    document.getElementById('removeItemModal').style.display = 'block';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

</body>
</html>