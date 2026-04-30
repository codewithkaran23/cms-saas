<?php
// create_clinic_admin.php
require_once 'core/init.php';

$db = getDB();

// 1. Get Clinic Admin role ID
$role_stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Clinic Admin'");
$role_stmt->execute();
$role_id = $role_stmt->fetchColumn();

// 2. Get 'citycare' clinic ID
$clinic_stmt = $db->prepare("SELECT id FROM clinics WHERE subdomain = 'citycare'");
$clinic_stmt->execute();
$clinic_id = $clinic_stmt->fetchColumn();

if (!$clinic_id) {
    die("CityCare clinic not found. Please create it in the Super Admin panel first.");
}

$name = "CityCare Staff";
$email = "staff@citycare.com";
$password = "staff123";

try {
    $stmt = $db->prepare("INSERT INTO users (clinic_id, role_id, name, email, password_hash) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$clinic_id, $role_id, $name, $email, password_hash($password, PASSWORD_DEFAULT)]);
    echo "Clinic Admin created successfully!<br>";
    echo "Clinic: City Care<br>";
    echo "Email: staff@citycare.com<br>";
    echo "Password: staff123";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
