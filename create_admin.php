<?php
// create_admin.php
require_once 'config/database.php';

$name = "Super Admin";
$email = "admin@cms.local";
$password = "admin123"; // Change this!
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$db = getDB();

// Get Super Admin role ID
$role_stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Super Admin'");
$role_stmt->execute();
$role_id = $role_stmt->fetchColumn();

if (!$role_id) {
    die("Roles not found. Please import schema.sql first.");
}

try {
    $stmt = $db->prepare("INSERT INTO users (role_id, name, email, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->execute([$role_id, $name, $email, $hashed_password]);
    echo "Super Admin user created successfully!<br>";
    echo "Email: admin@cms.local<br>";
    echo "Password: admin123";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
