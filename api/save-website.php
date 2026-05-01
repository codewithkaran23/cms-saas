<?php
// api/save-website.php
require_once '../core/init.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Security Check: Must be logged in as a Clinic Admin
if (!Auth::check() || !Auth::hasRole('Clinic Admin')) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$clinic_id = $_SESSION['clinic_id'];
$db = getDB();

// Fetch current configuration so we don't overwrite unrelated keys
$stmt = $db->prepare("SELECT config FROM clinics WHERE id = ? AND deleted_at IS NULL");
$stmt->execute([$clinic_id]);
$clinic = $stmt->fetch();

if (!$clinic) {
    http_response_code(404);
    echo json_encode(['error' => 'Clinic not found']);
    exit;
}

$currentConfig = json_decode($clinic['config'] ?? '{}', true);

// Update keys
if (isset($input['hero_title'])) $currentConfig['hero_title'] = $input['hero_title'];
if (isset($input['about'])) $currentConfig['about'] = $input['about'];
if (isset($input['services']) && is_array($input['services'])) {
    $currentConfig['services'] = $input['services'];
}

// Save back to DB
$updateStmt = $db->prepare("UPDATE clinics SET config = ? WHERE id = ?");
$updateStmt->execute([json_encode($currentConfig), $clinic_id]);

// Get current status to return to frontend
$status = $clinic['status'] ?? 'pending';

echo json_encode(['success' => true, 'message' => 'Configuration saved.', 'status' => $status]);
