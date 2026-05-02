<?php
require_once 'core/init.php';

$db = getDB();

try {
    // Disable foreign key checks temporarily to allow truncating
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");

    // 1. Drop legacy SaaS tables
    $db->exec("DROP TABLE IF EXISTS subscription_plans");
    $db->exec("DROP TABLE IF EXISTS system_settings");
    $db->exec("DROP TABLE IF EXISTS template_settings");

    // 2. Truncate core tables to clear all old test data
    $tablesToTruncate = [
        'appointments', 'audit_logs', 'doctor_profiles', 'patient_documents', 
        'patient_profiles', 'visits', 'users', 'roles', 'clinics'
    ];

    foreach ($tablesToTruncate as $table) {
        $db->exec("TRUNCATE TABLE $table");
    }

    // 3. Seed Fresh Roles
    $db->exec("INSERT INTO roles (id, name) VALUES (1, 'Doctor'), (2, 'Receptionist'), (3, 'Patient')");

    // 4. Seed Fresh Single Practice
    $db->exec("INSERT INTO clinics (id, name) VALUES (1, 'Emerald Medical Practice')");

    // 5. Seed Default Users
    $hash = password_hash('password123', PASSWORD_DEFAULT);
    
    // Primary Doctor
    $stmt = $db->prepare("INSERT INTO users (clinic_id, role_id, name, email, password_hash) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([1, 1, 'Dr. Emerald', 'doctor@practice.com', $hash]);
    
    // Test Patient
    $stmt->execute([1, 3, 'Test Patient', 'patient@practice.com', $hash]);

    // Re-enable foreign key checks
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "✅ Database completely reset and initialized for Emerald Medical Practice!\n";
    echo "Doctor Login: doctor@practice.com | password123\n";
    echo "Patient Login: patient@practice.com | password123\n";

} catch (PDOException $e) {
    echo "Error resetting database: " . $e->getMessage() . "\n";
}
?>
