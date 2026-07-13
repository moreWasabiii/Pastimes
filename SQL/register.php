<?php
include 'DBConn.php';
session_start();

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        // Check if email exists - FIXED: removed username check
        $stmt = $conn->prepare("SELECT user_id FROM tblUser WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Email already registered";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO tblUser (full_name, email, password, role, is_seller, is_verified) VALUES (?, ?, ?, 'customer', 0, 1)");
            $stmt->bind_param("sss", $full_name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Registration successful! You can now sign in.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Pastimes - Curated Timeless Style</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;}
        .register-container {display: grid; grid-template-columns: 1fr 1fr; max-width: 1000px; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);}
        .register-left {background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 4rem 3rem; display: flex; flex-direction: column; justify-content: center;}
        .register-left h1 {font-size: 2.5rem; margin-bottom: 1rem; color: white;}
        .register-left p {font-size: 1.1rem; opacity: 0.9; line-height: 1.8; margin-bottom: 2rem;}
        .feature {display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;}
        .feature-icon {width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;}
        .register-right {padding: 4rem 3rem;}
        .register-right h2 {margin: 0 0 0.5rem 0; font-size: 2rem;}
        .register-right .subtitle {color: var(--text-light); margin-bottom: 2rem;}
        .form-group {margin-bottom: 1.5rem;}
        .form-group label {display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text);}
        .form-group input {width: 100%; padding: 0.9rem; border: 2px solid var(--border); border-radius: 8px; font-size: 1rem; transition: all 0.2s;}
        .form-group input:focus {border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);}
        .btn-register {width: 100%; padding: 1rem; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;}
        .btn-register:hover {background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(139, 90, 43, 0.4);}
        .login-link {text-align: center; margin-top: 1.5rem; color: var(--text-light);}
        .login-link a {color: var(--primary); font-weight: 700; text-decoration: none;}
        .error {background: #fee; border: 1px solid #fcc; color: #c33; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;}
        .success {background: #efe; border: 1px solid #cfc; color: #363; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;}
        @media (max-width: 768px) {
            .register-container {grid-template-columns: 1fr;}
            .register-left {display: none;}
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-left">
        <h1>Pastimes</h1>
        <p>Join a community that values timeless style and conscious choices</p>
        
        <div class="feature">
            <div class="feature-icon">✨</div>
            <div>
                <strong>Curated Quality</strong><br>
                Every piece hand-selected
            </div>
        </div>
        
        <div class="feature">
            <div class="feature-icon">♻️</div>
            <div>
                <strong>Sustainable Style</strong><br>
                Fashion with purpose
            </div>
        </div>
        
        <div class="feature">
            <div class="feature-icon">🏷️</div>
            <div>
                <strong>Sell With Us</strong><br>
                Give pieces new life
            </div>
        </div>
    </div>
    
    <div class="register-right">
        <h2>Create Account</h2>
        <p class="subtitle">Start your timeless style journey</p>
        
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required placeholder="Jane Doe">
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="jane@example.com">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="At least 6 characters">
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="Re-enter password">
            </div>
            
            <button type="submit" class="btn-register">Create Account</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Sign In</a>
        </div>
    </div>
</div>

</body>
</html>