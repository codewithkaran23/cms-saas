<?php
require_once '../core/init.php';
header('Content-Type: application/json');

$db = getDB();
$user_id = $_SESSION['user_id'] ?? 0;
$user_role = $_SESSION['user_role'] ?? '';

if (!$user_id || ($user_role !== 'Doctor' && $user_role !== 'Patient')) {
    echo json_encode(['error' => 'Unauthorized', 'debug_role' => $user_role]);
    exit;
}

$action = $_GET['action'] ?? '';

// --- START CONSULTATION ---
if ($action === 'start' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $apt_id = (int)$_POST['appointment_id'];
    
    // Verify appointment belongs to this doctor
    $stmt = $db->prepare("SELECT patient_id FROM appointments WHERE id = ? AND doctor_id = ?");
    $stmt->execute([$apt_id, $user_id]);
    $apt = $stmt->fetch();
    
    if (!$apt) {
        echo json_encode(['error' => 'Appointment not found']);
        exit;
    }

    // Check if visit already exists
    $v_stmt = $db->prepare("SELECT id FROM visits WHERE appointment_id = ?");
    $v_stmt->execute([$apt_id]);
    $existing_visit = $v_stmt->fetch();

    if (!$existing_visit) {
        // Create visit
        $insert = $db->prepare("INSERT INTO visits (appointment_id, doctor_id, patient_id, status) VALUES (?, ?, ?, 'ongoing')");
        $insert->execute([$apt_id, $user_id, $apt['patient_id']]);
        $visit_id = $db->lastInsertId();
    } else {
        $visit_id = $existing_visit['id'];
    }

    // Update appointment status
    $update = $db->prepare("UPDATE appointments SET status = 'in_progress' WHERE id = ?");
    $update->execute([$apt_id]);

    echo json_encode(['success' => true, 'visit_id' => $visit_id]);
    exit;
}

// --- UPDATE VISIT DATA (Auto-save) ---
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_id = (int)$_POST['visit_id'];
    $symptoms = $_POST['symptoms'] ?? '';
    $diagnosis = $_POST['diagnosis'] ?? '';
    $notes = $_POST['notes'] ?? '';

    // Verify visit belongs to this doctor
    $stmt = $db->prepare("UPDATE visits SET symptoms = ?, diagnosis = ?, notes = ? WHERE id = ? AND doctor_id = ? AND status = 'ongoing'");
    $stmt->execute([$symptoms, $diagnosis, $notes, $visit_id, $user_id]);

    echo json_encode(['success' => true]);
    exit;
}

// --- END CONSULTATION ---
if ($action === 'end' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_id = (int)$_POST['visit_id'];
    
    // Get appointment_id
    $v_stmt = $db->prepare("SELECT appointment_id FROM visits WHERE id = ? AND doctor_id = ?");
    $v_stmt->execute([$visit_id, $user_id]);
    $visit = $v_stmt->fetch();
    
    if (!$visit) {
        echo json_encode(['error' => 'Visit not found']);
        exit;
    }

    // Mark visit as completed
    $db->prepare("UPDATE visits SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$visit_id]);
    
    // Mark appointment as completed
    $db->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?")->execute([$visit['appointment_id']]);

    echo json_encode(['success' => true]);
    exit;
}

// --- FETCH CURRENT VISIT ---
if ($action === 'fetch') {
    $apt_id = (int)$_GET['appointment_id'];
    $stmt = $db->prepare("SELECT * FROM visits WHERE appointment_id = ?");
    $stmt->execute([$apt_id]);
    echo json_encode($stmt->fetch() ?: (object)[]);
    exit;
}
?>
