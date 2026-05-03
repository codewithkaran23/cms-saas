<?php
require_once '../core/init.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$db = getDB();
$user_id = $_SESSION['user_id'];
$user_type = strtolower($_SESSION['user_role']); // 'doctor' or 'patient'

header('Content-Type: application/json');

// --- SEND MESSAGE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $apt_id = (int)$_POST['appointment_id'];
    $message = trim($_POST['message'] ?? '');
    $attachment_path = null;
    $attachment_name = null;

    // Handle File Upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/chat/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $file_name = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
            $attachment_path = 'uploads/chat/' . $file_name;
            $attachment_name = $_FILES['attachment']['name'];
        }
    }

    if (empty($message) && empty($attachment_path)) {
        echo json_encode(['error' => 'Message empty']);
        exit;
    }

    // Verify user is part of this appointment
    $stmt = $db->prepare("SELECT id FROM appointments WHERE id = ? AND (doctor_id = ? OR patient_id = ?)");
    $stmt->execute([$apt_id, $user_id, $user_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    $insert = $db->prepare("INSERT INTO chat_messages (appointment_id, sender_id, sender_type, message, attachment_path, attachment_name) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->execute([$apt_id, $user_id, $user_type, $message, $attachment_path, $attachment_name]);

    // CREATE NOTIFICATION FOR THE RECIPIENT
    // Find recipient ID
    $apt_stmt = $db->prepare("SELECT doctor_id, patient_id FROM appointments WHERE id = ?");
    $apt_stmt->execute([$apt_id]);
    $apt = $apt_stmt->fetch();
    
    $recipient_id = ($user_type === 'doctor') ? $apt['patient_id'] : $apt['doctor_id'];
    $sender_name = $_SESSION['user_name'];
    
    $notif_insert = $db->prepare("INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
    $notif_insert->execute([
        $recipient_id, 
        'normal', 
        "New message from $sender_name", 
        $attachment_path ? "Sent an attachment: $attachment_name" : (strlen($message) > 50 ? substr($message, 0, 47) . '...' : $message),
        ($user_type === 'doctor' ? '../patient/messages.php' : '../doctor/messages.php')
    ]);

    echo json_encode(['success' => true]);
    exit;
}

// --- FETCH MESSAGES ---
if (isset($_GET['fetch'])) {
    $apt_id = (int)$_GET['appointment_id'];
    $last_id = (int)($_GET['last_id'] ?? 0);

    // Fetch messages since last_id
    $stmt = $db->prepare("
        SELECT * FROM chat_messages 
        WHERE appointment_id = ? AND id > ? 
        ORDER BY created_at ASC
    ");
    $stmt->execute([$apt_id, $last_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mark these messages as read for the recipient
    if (!empty($messages)) {
        $update = $db->prepare("
            UPDATE chat_messages 
            SET is_read = 1 
            WHERE appointment_id = ? AND sender_type != ? AND id <= ?
        ");
        $update->execute([$apt_id, $user_type, $messages[count($messages)-1]['id']]);
    }

    echo json_encode(['messages' => $messages]);
    exit;
}

// --- FETCH UNREAD COUNTS ---
if (isset($_GET['unread_counts'])) {
    $stmt = $db->prepare("
        SELECT appointment_id, COUNT(*) as count 
        FROM chat_messages 
        WHERE (appointment_id IN (SELECT id FROM appointments WHERE doctor_id = ? OR patient_id = ?))
        AND sender_type != ? AND is_read = 0
        GROUP BY appointment_id
    ");
    $stmt->execute([$user_id, $user_id, $user_type]);
    $counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    echo json_encode(['unread_counts' => $counts]);
    exit;
}
?>
