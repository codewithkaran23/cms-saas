<?php
require_once '../../core/init.php';
Auth::protect('Doctor');

header('Content-Type: application/json');

$db = getDB();
$user_id = $_SESSION['user_id'];
$user_type = 'doctor';

// If marking as read
if (isset($_GET['read_all'])) {
    $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$user_id]);
    echo json_encode(['success' => true]);
    exit;
}

// Fetch unread notifications
$stmt = $db->prepare("
    SELECT * FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

// Count unread system notifications
$count_stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
$count_stmt->execute([$user_id]);
$sys_unread = (int)$count_stmt->fetchColumn();

// Count unread chat messages
$chat_stmt = $db->prepare("
    SELECT COUNT(*) 
    FROM chat_messages 
    WHERE appointment_id IN (SELECT id FROM appointments WHERE doctor_id = ?)
    AND sender_type != ? AND is_read = 0
");
$chat_stmt->execute([$user_id, $user_type]);
$chat_unread = (int)$chat_stmt->fetchColumn();

echo json_encode([
    'notifications' => $notifications,
    'unread_count' => $sys_unread + $chat_unread,
    'chat_unread' => $chat_unread
]);
?>
