<?php
include 'DBConn.php';
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php?redirect=messages.php&msg=Please log in to view messages");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$current_user_role = $_SESSION['role'] ?? 'customer';
$active_chat_user = isset($_GET['to']) ? (int)$_GET['to'] : null;
$clothes_id = isset($_GET['item']) ? (int)$_GET['item'] : null;
$message = "";
$msgClass = "success";

// Handle sending message
if (isset($_POST['send_message']) && $active_chat_user) {
    $message_text = trim($_POST['message']);
    $message_text = str_replace(["\r\n", "\r"], "\n", $message_text);
    $message_text = $conn->real_escape_string($message_text);
    $subject = $conn->real_escape_string($_POST['subject'] ?? '');
    $item_id = !empty($_POST['clothes_id']) ? (int)$_POST['clothes_id'] : null;
    
    // RULE: Sellers can message admin, other sellers, or reply to buyers
    // Can't initiate to buyers who haven't messaged first
    if ($current_user_role !== 'admin') {
        $is_seller = $conn->query("SELECT is_seller FROM tblUser WHERE user_id = $current_user_id")->fetch_assoc()['is_seller'];
        if ($is_seller == 1) {
            $receiver_info = $conn->query("SELECT role, is_seller FROM tblUser WHERE user_id = $active_chat_user")->fetch_assoc();
            
            // Allow if messaging admin OR messaging another seller
            if ($receiver_info['role'] === 'admin' || $receiver_info['is_seller'] == 1) {
                $msgClass = "success"; // Allow it
            } else {
                // Messaging a buyer - check if they messaged first
                $existing = $conn->query("SELECT message_id FROM tblMessage WHERE sender_id = $active_chat_user AND receiver_id = $current_user_id LIMIT 1");
                if ($existing->num_rows === 0) {
                    $message = "Sellers can only reply to buyers who message them first. You can message admin or other sellers.";
                    $msgClass = "error";
                }
            }
        }
    }
    
    if ($msgClass !== "error" && !empty($message_text)) {
        $stmt = $conn->prepare("INSERT INTO tblMessage (sender_id, receiver_id, clothes_id, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiss", $current_user_id, $active_chat_user, $item_id, $subject, $message_text);
        $stmt->execute();
        $stmt->close();
        
        header("Location: messages.php?to=$active_chat_user" . ($item_id ? "&item=$item_id" : ""));
        exit();
    }
}

// Mark messages as read
if ($active_chat_user) {
    $conn->query("UPDATE tblMessage SET is_read=1 WHERE sender_id=$active_chat_user AND receiver_id=$current_user_id AND is_read=0");
}

// Get unread count
$unread_count = $conn->query("SELECT COUNT(*) as c FROM tblMessage WHERE receiver_id=$current_user_id AND is_read=0")->fetch_assoc()['c'];

// Get conversation list
$conversations = $conn->query("
    SELECT DISTINCT 
        CASE WHEN m.sender_id = $current_user_id THEN m.receiver_id ELSE m.sender_id END as other_user_id,
        u.full_name, u.email, u.role, u.is_seller,
        (SELECT message FROM tblMessage m2 WHERE 
            (m2.sender_id = $current_user_id AND m2.receiver_id = other_user_id) OR 
            (m2.sender_id = other_user_id AND m2.receiver_id = $current_user_id) 
         ORDER BY m2.sent_at DESC LIMIT 1) as last_message,
        (SELECT sent_at FROM tblMessage m2 WHERE 
            (m2.sender_id = $current_user_id AND m2.receiver_id = other_user_id) OR 
            (m2.sender_id = other_user_id AND m2.receiver_id = $current_user_id) 
         ORDER BY m2.sent_at DESC LIMIT 1) as last_time,
        (SELECT COUNT(*) FROM tblMessage m2 WHERE m2.sender_id = other_user_id AND m2.receiver_id = $current_user_id AND m2.is_read = 0) as unread,
        (SELECT clothes_id FROM tblMessage m2 WHERE 
            ((m2.sender_id = $current_user_id AND m2.receiver_id = other_user_id) OR 
            (m2.sender_id = other_user_id AND m2.receiver_id = $current_user_id)) 
            AND m2.clothes_id IS NOT NULL
         ORDER BY m2.sent_at DESC LIMIT 1) as related_item
    FROM tblMessage m
    JOIN tblUser u ON u.user_id = CASE WHEN m.sender_id = $current_user_id THEN m.receiver_id ELSE m.sender_id END
    WHERE m.sender_id = $current_user_id OR m.receiver_id = $current_user_id
    ORDER BY last_time DESC
");

// Get active chat messages
$chat_messages = [];
$chat_user_info = null;
$item_info = null;

if ($active_chat_user) {
    $chat_user_info = $conn->query("SELECT user_id, full_name, email, role, is_seller FROM tblUser WHERE user_id=$active_chat_user")->fetch_assoc();
    
    $chat_messages = $conn->query("SELECT m.*, u.full_name as sender_name FROM tblMessage m 
        JOIN tblUser u ON m.sender_id = u.user_id 
        WHERE (m.sender_id = $current_user_id AND m.receiver_id = $active_chat_user) 
           OR (m.sender_id = $active_chat_user AND m.receiver_id = $current_user_id)
        ORDER BY m.sent_at ASC");
    
    if ($clothes_id) {
        $item_info = $conn->query("SELECT clothes_id, title, price, image FROM tblClothes WHERE clothes_id=$clothes_id")->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Pastimes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .messages-container {display: grid; grid-template-columns: 320px 1fr; gap: 0; height: calc(100vh - 220px); background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;}
        .conversations {border-right: 1px solid var(--border); overflow-y: auto; background: var(--bg-alt);}
        .convo-item {padding: 1rem; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.2s; text-decoration: none; display: block; color: var(--text);}
        .convo-item:hover, .convo-item.active {background: white;}
        .convo-item .name {font-weight: 700; margin-bottom: 0.25rem; display: flex; justify-content: space-between; align-items: center;}
        .convo-item .preview {font-size: 0.85rem; color: var(--text-light); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;}
        .convo-item .badge {background: #e74c3c; color: white; padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.7rem;}
        .role-badge {font-size: 0.7rem; padding: 0.1rem 0.4rem; border-radius: 4px; margin-left: 0.5rem;}
        .role-admin {background: var(--primary-dark); color: white;}
        .role-seller {background: var(--accent); color: white;}
        .chat-area {display: flex; flex-direction: column;}
        .chat-header {padding: 1rem; border-bottom: 1px solid var(--border); background: white;}
        .chat-messages {flex: 1; padding: 1rem; overflow-y: auto; background: var(--bg);}
        .message-bubble {margin-bottom: 1rem; max-width: 70%;}
        .message-bubble.sent {margin-left: auto;}
        .message-bubble .bubble {padding: 0.75rem 1rem; border-radius: 12px; background: white; border: 1px solid var(--border); word-wrap: break-word; white-space: pre-wrap;}
        .message-bubble.sent .bubble {background: var(--primary); color: white; border: none;}
        .message-bubble .time {font-size: 0.75rem; color: var(--text-light); margin-top: 0.25rem;}
        .message-bubble.sent .time {text-align: right;}
        .chat-input {padding: 1rem; border-top: 1px solid var(--border); background: white;}
        .chat-input form {display: flex; gap: 0.5rem; flex-direction: column;}
        .chat-input textarea {padding: 0.75rem; border: 1px solid var(--border); border-radius: 6px; resize: none; font-family: inherit;}
        .item-tag {background: var(--bg-alt); padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; margin-bottom: 1rem; border-left: 3px solid var(--accent);}
        .empty-chat {display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-light); flex-direction: column;}
        .notice-alert {background: #fff3cd; border: 1px solid #ffc107; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;}
    </style>
</head>
<body>

<div class="container">
    <h2>💬 Messages <?php if($unread_count > 0): ?><span style="background: #e74c3c; color: white; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.9rem;"><?= $unread_count ?></span><?php endif; ?></h2>
    
    <div class="nav-links">
        <a href="index.php">✨ Welcome</a>
        <a href="shop.php">🛍️ Collection</a>
        <?php if($current_user_role === 'admin'): ?>
            <a href="admin_dashboard.php">🛠 Admin Dashboard</a>
        <?php endif; ?>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <?php if ($message): ?><p class="<?= $msgClass ?>"><?= htmlspecialchars($message) ?></p><?php endif; ?>

    <div class="messages-container">
        <div class="conversations">
            <?php if ($conversations && $conversations->num_rows > 0): ?>
                <?php while($c = $conversations->fetch_assoc()): ?>
                <a href="messages.php?to=<?= $c['other_user_id'] ?><?= $c['related_item'] ? '&item='.$c['related_item'] : '' ?>" 
                   class="convo-item <?= $active_chat_user == $c['other_user_id'] ? 'active' : '' ?>">
                    <div class="name">
                        <span>
                            <?= htmlspecialchars($c['full_name']) ?>
                            <?php if($c['role'] === 'admin'): ?><span class="role-badge role-admin">Admin</span><?php endif; ?>
                            <?php if($c['is_seller'] == 1 && $c['role'] !== 'admin'): ?><span class="role-badge role-seller">Seller</span><?php endif; ?>
                        </span>
                        <?php if($c['unread'] > 0): ?><span class="badge"><?= $c['unread'] ?></span><?php endif; ?>
                    </div>
                    <div class="preview"><?= htmlspecialchars(substr($c['last_message'], 0, 40)) ?>...</div>
                </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="padding: 2rem; text-align: center; color: var(--text-light);">No messages yet. Browse items and message a seller to start.</p>
            <?php endif; ?>
        </div>
        
        <div class="chat-area">
            <?php if ($active_chat_user && $chat_user_info): ?>
                <div class="chat-header">
                    <h3>
                        <?= htmlspecialchars($chat_user_info['full_name']) ?>
                        <?php if($chat_user_info['role'] === 'admin'): ?><span class="role-badge role-admin">Admin</span><?php endif; ?>
                        <?php if($chat_user_info['is_seller'] == 1 && $chat_user_info['role'] !== 'admin'): ?><span class="role-badge role-seller">Seller</span><?php endif; ?>
                    </h3>
                    <?php if ($item_info): ?>
                        <div class="item-tag">
                            💬 About: <strong><?= htmlspecialchars($item_info['title']) ?></strong> - R<?= number_format($item_info['price'], 2) ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="chat-messages">
                    <?php 
                    // Show removal notices
                    $notices = $conn->query("SELECT n.*, u.full_name as admin_name FROM tblNotice n 
                        JOIN tblUser u ON n.admin_id = u.user_id 
                        WHERE n.user_id = $current_user_id ORDER BY n.created_at DESC LIMIT 1");
                    if ($notices->num_rows > 0):
                        $notice = $notices->fetch_assoc();
                    ?>
                    <div class="notice-alert">
                        <strong>Notice from <?= htmlspecialchars($notice['admin_name']) ?>:</strong><br>
                        <?= nl2br(htmlspecialchars($notice['reason'])) ?><br><br>
                        <strong>How to respond:</strong> <?= nl2br(htmlspecialchars($notice['rebut_info'])) ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($chat_messages && $chat_messages->num_rows > 0): ?>
                        <?php while($msg = $chat_messages->fetch_assoc()): ?>
                        <div class="message-bubble <?= $msg['sender_id'] == $current_user_id ? 'sent' : '' ?>">
                            <div class="bubble"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
                            <div class="time"><?= date('M j, g:i A', strtotime($msg['sent_at'])) ?></div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--text-light); margin-top: 2rem;">Start the conversation</p>
                    <?php endif; ?>
                </div>
                
                <div class="chat-input">
                    <form method="POST">
                        <?php if ($item_info): ?>
                            <input type="hidden" name="clothes_id" value="<?= $item_info['clothes_id'] ?>">
                            <input type="hidden" name="subject" value="Re: <?= htmlspecialchars($item_info['title']) ?>">
                        <?php endif; ?>
                        <textarea name="message" rows="2" placeholder="Type your message..." required></textarea>
                        <button type="submit" name="send_message" class="btn-primary">Send Message</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="empty-chat">
                    <p style="font-size: 3rem; margin-bottom: 1rem;">💬</p>
                    <p>Select a conversation or message a seller from an item listing</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const chatMessages = document.querySelector('.chat-messages');
if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
</script>

</body>
</html>