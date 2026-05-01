<?php
require_once 'core/init.php';
$db = getDB();
$users = $db->query("SELECT u.id, u.name, u.email, u.clinic_id, r.name as role FROM users u JOIN roles r ON u.role_id = r.id LIMIT 10")->fetchAll();
header('Content-Type: application/json');
echo json_encode($users, JSON_PRETTY_PRINT);
