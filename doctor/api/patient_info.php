<?php
require_once '../core/init.php';
header('Content-Type: application/json');

$db = getDB();
$user_id = $_SESSION['user_id'] ?? 0;
$user_role = $_SESSION['user_role'] ?? '';

if (!$user_id || $user_role !== 'Doctor') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$patient_id = (int)($_GET['patient_id'] ?? 0);
if (!$patient_id) {
    echo json_encode(['error' => 'Patient ID required']);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'history') {
    $stmt = $db->prepare("
        SELECT v.*, d.name as doctor_name 
        FROM visits v 
        JOIN users d ON v.doctor_id = d.id 
        WHERE v.patient_id = ? AND v.status = 'completed' 
        ORDER BY v.completed_at DESC
    ");
    $stmt->execute([$patient_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action === 'reports') {
    $stmt = $db->prepare("SELECT * FROM patient_documents WHERE patient_id = ? ORDER BY created_at DESC");
    $stmt->execute([$patient_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}
?>
