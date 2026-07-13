<?php
include 'DBConn.php';
session_start();

$error = "";
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // FIXED: Using email, not username
    $stmt = $conn->prepare("SELECT user_id, full_name, email, password, role, is_seller FROM tblUser WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_seller'] = $user['is_seller'];
            
            header("Location: $redirect");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Pastimes</title>
    <style>
        * {margin: 0; padding: 0; box-sizing: border-box;}
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-card h1 {
            font-size: 2rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .login-card .subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2d3748;
            font-size: 0.9rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s;
            font-family: inherit;
        }
        .form-group input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-signin {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }
        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            color: #a0aec0;
            font-size: 0.85rem;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e2e8f0;
        }
        .divider::before {left: 0;}
        .divider::after {right: 0;}
        .register-link {
            text-align: center;
            color: #718096;
            font-size: 0.95rem;
        }
        .register-link a {
            color: #667eea;
            font-weight: 700;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .error {
            background: #fed7d7;
            border: 1px solid #fc8181;
            color: #c53030;
            padding: 0.9rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            text-align: center;
        }
        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-home a {
            color: #a0aec0;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-home a:hover {
            color: #667eea;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h1>Welcome Back</h1>
    <p class="subtitle">Sign in to continue your timeless style journey</p>
    
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    
    <form method="POST" autocomplete="off">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required autocomplete="email" placeholder="you@example.com" autofocus>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
        </div>
        
        <button type="submit" class="btn-signin">Sign In</button>
    </form>
    
    <div class="divider">or</div>
    
    <div class="register-link">
        New to Pastimes? <a href="register.php">Create an account</a>
    </div>
    
    <div class="back-home">
        <a href="index.php">← Back to Home</a>
    </div>
</div>

</body>
</html>