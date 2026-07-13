<?php
include 'DBConn.php';
session_start();
$msg = $_GET['msg'] ?? '';

$unread_count = 0;
$is_seller = false;

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    if (!empty($_SESSION['is_admin'])) {
        $stmt = $conn->prepare("SELECT COUNT(*) as c FROM tblmessage WHERE receiver_type='admin' AND receiver_id=? AND is_read=0");
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $unread_count = $stmt->get_result()->fetch_assoc()['c'] ?? 0;
        $stmt->close();
    } elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
        $stmt = $conn->prepare("SELECT COUNT(*) as c FROM tblmessage WHERE receiver_type='user' AND receiver_id=? AND is_read=0");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $unread_count = $stmt->get_result()->fetch_assoc()['c'] ?? 0;
        $stmt->close();

        $stmt = $conn->prepare("SELECT seller_id FROM tblseller WHERE user_id = ? AND is_approved = 1");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $is_seller = $stmt->get_result()->num_rows > 0;
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastimes - Curated Timeless Style</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hero {text-align: center; padding: 4rem 2rem; background: linear-gradient(135deg, var(--bg-alt) 0%, var(--bg) 100%); border-radius: 12px; margin-bottom: 3rem; border: 1px solid var(--border);}
        .hero h1 {font-size: 3.5rem; margin-bottom: 0.5rem; color: var(--primary-dark); letter-spacing: -1px;}
        .hero .tagline {font-size: 1.3rem; color: var(--text-light); font-style: italic; margin-bottom: 2rem;}
        .features {display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin: 3rem 0;}
        .feature-card {background: white; padding: 2rem; border-radius: 8px; border: 1px solid var(--border); text-align: center; transition: transform 0.2s;}
        .feature-card:hover {transform: translateY(-4px); box-shadow: 0 4px 12px rgba(44, 62, 80, 0.1);}
        .feature-card .icon {font-size: 2.5rem; margin-bottom: 1rem;}
        .how-it-works {background: white; padding: 3rem 2rem; border-radius: 12px; border: 1px solid var(--border); margin: 3rem 0;}
        .how-it-works h2 {text-align: center; margin-bottom: 3rem; font-size: 2rem;}
        .steps {display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; text-align: center;}
        .step-number {width: 50px; height: 50px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.3rem; margin: 0 auto 1rem;}
        .testimonials {margin: 3rem 0;}
        .testimonials h2 {text-align: center; margin-bottom: 2rem; font-size: 2rem;}
        .testimonial-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;}
        .testimonial-card {background: white; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); border-left: 4px solid var(--accent);}
        .testimonial-text {font-style: italic; color: var(--text); margin-bottom: 1rem; line-height: 1.7;}
        .testimonial-author {font-weight: 700; color: var(--primary); font-size: 0.9rem;}
        .testimonial-location {color: var(--text-light); font-size: 0.85rem;}
        .cta-section {text-align: center; padding: 3rem 2rem; background: var(--bg-alt); border-radius: 12px; border: 1px solid var(--border); margin-top: 3rem;}
        .badge {background: #e74c3c; color: white; padding: 0.1rem 0.4rem; border-radius: 10px; font-size: 0.75rem; margin-left: 0.25rem;}
    </style>
</head>
<body>

<div class="container">

<?php if ($msg): ?>
    <p class="success"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<div class="hero">
    <h1>Pastimes</h1>
    <p class="tagline">Where timeless pieces find their next chapter</p>
    <p style="max-width: 600px; margin: 0 auto 2rem; color: var(--text-light); line-height: 1.8;">
        Carefully curated pre-loved clothing from brands you trust. Each piece is hand-selected,
        quality-checked, and ready for its second life. Shop consciously, dress timelessly.
    </p>

    <div class="nav-links" style="justify-content: center;">
        <a href="shop.php" class="btn-primary" style="font-size: 1.1rem; padding: 1rem 2.5rem;">🛍 Explore Collection</a>
        <?php if(!isset($_SESSION['logged_in'])): ?>
            <a href="register.php">Join the Pastimes Community</a>
        <?php endif; ?>
    </div>
</div>

<div class="features">
    <div class="feature-card">
        <div class="icon">✨</div>
        <h3>Curated Quality</h3>
        <p>Every item is hand-selected by our team. We check condition, authenticity, and timelessness. Only the best makes it to Pastimes.</p>
    </div>

    <div class="feature-card">
        <div class="icon">♻</div>
        <h3>Sustainable Style</h3>
        <p>Fashion shouldn't cost the earth. Give quality pieces a second life and reduce textile waste without compromising on style.</p>
    </div>

    <div class="feature-card">
        <div class="icon">🏷</div>
        <h3>Sell With Us</h3>
        <p>Have pieces ready for their next chapter? Submit your items. Our team reviews each piece and lists approved items in the shop.</p>
    </div>
</div>

<div class="how-it-works">
    <h2>How Pastimes Works</h2>
    <div class="steps">
        <div class="step">
            <div class="step-number">1</div>
            <h3>Browse</h3>
            <p>Explore our curated collection of pre-loved pieces from brands you know and love.</p>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <h3>Shop</h3>
            <p>Add to cart and checkout securely. All items are quality-checked and ready to wear.</p>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <h3>Sell</h3>
            <p>Submit your own pieces. Once approved by our team, they're listed and you earn when they sell.</p>
        </div>
        <div class="step">
            <div class="step-number">4</div>
            <h3>Connect</h3>
            <p>Message sellers, track orders, and join a community that values timeless style.</p>
        </div>
    </div>
</div>

<div class="testimonials">
    <h2>What Our Community Says</h2>
    <div class="testimonial-grid">
        <div class="testimonial-card">
            <p class="testimonial-text">"Found a vintage Woolworths blazer I'd been hunting for years. The quality is impeccable and it fits like it was tailored for me. Pastimes gets it."</p>
            <p class="testimonial-author">Sarah M.</p>
            <p class="testimonial-location">Cape Town</p>
        </div>

        <div class="testimonial-card">
            <p class="testimonial-text">"I've sold 12 pieces through Pastimes. The process is so easy and I love knowing my clothes are going to someone who'll actually wear them."</p>
            <p class="testimonial-author">Liam K.</p>
            <p class="testimonial-location">Johannesburg</p>
        </div>

        <div class="testimonial-card">
            <p class="testimonial-text">"Finally, a place that curates instead of just dumping everything online. Every piece feels intentional. My new go-to for sustainable fashion."</p>
            <p class="testimonial-author">Aisha R.</p>
            <p class="testimonial-location">Durban</p>
        </div>
    </div>
</div>

<div class="cta-section">
    <h2 style="margin-bottom: 1rem;">Start Your Pastimes Journey</h2>
    <p style="color: var(--text-light); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
        Whether you're buying or selling, you're part of a community that believes great style is timeless.
    </p>

    <div class="nav-links" style="justify-content: center;">
        <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <?php if (!empty($_SESSION['is_admin'])): ?>
                <a href="admin_dashboard.php" class="btn-primary">🛠 Admin Dashboard</a>
                <a href="messages.php">💬 Messages <?php if($unread_count > 0): ?><span class="badge"><?= $unread_count ?></span><?php endif; ?></a>
                <a href="logout.php">🚪 Logout</a>
            <?php else: ?>
                <a href="shop.php" class="btn-primary">🛍 Continue Shopping</a>
                <a href="cart.php">🛒 My Cart</a>
                <a href="<?= $is_seller ? 'seller_dashboard.php' : 'seller_request.php' ?>">🏷️ Sell an Item</a>
                <a href="messages.php">💬 Messages <?php if($unread_count > 0): ?><span class="badge"><?= $unread_count ?></span><?php endif; ?></a>
                <a href="logout.php">🚪 Logout</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="shop.php" class="btn-primary">🛍 Browse Collection</a>
            <a href="login.php">👤 Sign In</a>
            <a href="register.php">📝 Create Account</a>
            <a href="admin_login.php">🛠 Admin</a>
        <?php endif; ?>
    </div>

    <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <p style="margin-top: 2rem; color: var(--accent); font-weight: 700; font-size: 1.1rem;">
            Welcome back, <?= htmlspecialchars($_SESSION['username'] ?? $_SESSION['email']) ?> 👋
        </p>
    <?php endif; ?>
</div>

<footer style="text-align: center; margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--border); color: var(--text-light);">
    <p><strong>Pastimes</strong> - Timeless style, conscious choices</p>
    <p style="font-size: 0.9rem; margin-top: 0.5rem;">Curated in Cape Town, South Africa 🇿🇦</p>
    <p style="font-size: 0.8rem; margin-top: 1rem;">© 2026 Pastimes. All rights reserved.</p>
</footer>

</div>
</body>
</html>
