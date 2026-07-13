<?php
include 'DBConn.php';
session_start();

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) || !empty($_SESSION['is_admin'])) {
    header("Location: login.php?msg=Please login to become a seller");
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$message = "";
$msgClass = "error";

$check = $conn->query("SELECT * FROM tblseller WHERE user_id = $user_id LIMIT 1");
if ($check && $check->num_rows > 0) {
    $seller = $check->fetch_assoc();
    if ((int)$seller['is_approved'] === 1) {
        header("Location: seller_dashboard.php?msg=You're already an approved seller");
        exit();
    } else {
        $message = "You already have a pending seller application. Please wait for admin approval.";
    }
}

if (isset($_POST['submit_request']) && empty($message)) {
    $business_name = trim($_POST['business_name']);
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);

    $stmt = $conn->prepare("INSERT INTO tblseller (user_id, business_name, contact_number, address, is_approved) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("isss", $user_id, $business_name, $contact_number, $address);

    if ($stmt->execute()) {
        $message = "Application submitted! Our team will review it and notify you once approved.";
        $msgClass = "success";
    } else {
        $message = "Error submitting application. Please try again.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Seller - Pastimes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container" style="max-width: 600px;">
    <h2>🏷️ Sell With Pastimes</h2>
    <p style="color: var(--text-light); margin-bottom: 2rem;">
        Have timeless pieces ready for their next chapter? Join our curated community of sellers.
    </p>

    <div class="nav-links">
        <a href="index.php">✨ Welcome</a>
        <a href="shop.php">🛍️ Collection</a>
        <a href="cart.php">🛒 Cart</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <?php if ($message): ?>
        <p class="<?= $msgClass ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($msgClass !== "success"): ?>
    <div class="form-card">
        <h3 style="margin-bottom: 1.5rem;">Seller Application</h3>
        <form method="POST">
            <label>Business Name / Brand</label>
            <input type="text" name="business_name" placeholder="e.g. Vintage Finds by Sarah" required>

            <label>Contact Number</label>
            <input type="tel" name="contact_number" placeholder="e.g. 082 123 4567" required>

            <label>Address / Pickup Location</label>
            <textarea name="address" rows="3" placeholder="Suburb, City (e.g. Sea Point, Cape Town)" required></textarea>

            <p style="color: var(--text-light); font-size: 0.9rem; margin: 1rem 0;">
                <strong>Note:</strong> Our team reviews each application to maintain Pastimes' curated quality.
                You'll be notified once approved and can start listing items.
            </p>

            <button type="submit" name="submit_request" class="btn-primary" style="width: 100%;">Submit Application</button>
        </form>
    </div>
    <?php else: ?>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="index.php" class="btn-primary">Back to Welcome</a>
            <a href="shop.php">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
