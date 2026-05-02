<?php
require_once __DIR__.'/../core/init.php';
$db = getDB();

try {
    // 1. Create patient_profiles
    $db->exec("
        CREATE TABLE IF NOT EXISTS `patient_profiles` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `clinic_id` INT NOT NULL,
            `id_no` VARCHAR(50),
            `first_name` VARCHAR(100),
            `last_name` VARCHAR(100),
            `phone_no` VARCHAR(20),
            `mobile_no` VARCHAR(20),
            `blood_group` VARCHAR(10),
            `sex` VARCHAR(20),
            `dob` DATE,
            `address` TEXT,
            `picture_url` VARCHAR(255),
            `status` VARCHAR(20) DEFAULT 'Active',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`clinic_id`) REFERENCES `clinics`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "patient_profiles created successfully.\n";

    // 2. Create patient_documents
    $db->exec("
        CREATE TABLE IF NOT EXISTS `patient_documents` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `patient_id` INT NOT NULL,
            `clinic_id` INT NOT NULL,
            `file_url` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`patient_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`clinic_id`) REFERENCES `clinics`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "patient_documents created successfully.\n";

} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
