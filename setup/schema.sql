-- Clinic Management System SaaS Database Schema
-- Multi-tenant: Shared Schema, Isolated Tenant

CREATE DATABASE IF NOT EXISTS `cms_saas`;
USE `cms_saas`;

-- 1. Clinics (Tenants)
CREATE TABLE `clinics` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `subdomain` VARCHAR(100) NOT NULL UNIQUE,
    `subscription_tier` ENUM('basic', 'premium', 'enterprise') DEFAULT 'basic',
    `status` ENUM('active', 'suspended', 'pending') DEFAULT 'pending',
    `logo_url` VARCHAR(255),
    `primary_color` VARCHAR(7) DEFAULT '#3b82f6',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Roles
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE -- 'Super Admin', 'Clinic Admin', 'Doctor', 'Receptionist', 'Patient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`name`) VALUES ('Super Admin'), ('Clinic Admin'), ('Doctor'), ('Receptionist'), ('Patient');

-- 3. Users
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `clinic_id` INT DEFAULT NULL, -- NULL for Super Admin
    `role_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`clinic_id`) REFERENCES `clinics`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`),
    UNIQUE KEY `unique_email_per_clinic` (`email`, `clinic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Template Settings
CREATE TABLE `template_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `clinic_id` INT NOT NULL,
    `active_theme` VARCHAR(50) DEFAULT 'modern',
    `config` JSON, -- Stores hero text, banners, enabled sections
    FOREIGN KEY (`clinic_id`) REFERENCES `clinics`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Doctors (Profiles)
CREATE TABLE `doctor_profiles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `clinic_id` INT NOT NULL,
    `specialization` VARCHAR(255),
    `biography` TEXT,
    `availability_json` JSON,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`clinic_id`) REFERENCES `clinics`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Appointments
CREATE TABLE `appointments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `clinic_id` INT NOT NULL,
    `patient_id` INT NOT NULL,
    `doctor_id` INT NOT NULL,
    `date_time` DATETIME NOT NULL,
    `status` ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    `payment_status` ENUM('unpaid', 'paid', 'partially_paid') DEFAULT 'unpaid',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`clinic_id`) REFERENCES `clinics`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`patient_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Visits (EMR)
CREATE TABLE `visits` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `appointment_id` INT NOT NULL,
    `symptoms` TEXT,
    `diagnosis` TEXT,
    `prescription_notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Audit Logs
CREATE TABLE `audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `clinic_id` INT,
    `action` VARCHAR(255),
    `table_name` VARCHAR(100),
    `record_id` INT,
    `old_values` JSON,
    `new_values` JSON,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`clinic_id`) REFERENCES `clinics`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. System Settings
CREATE TABLE `system_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Subscription Plans
CREATE TABLE `subscription_plans` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tier_name` VARCHAR(50) NOT NULL UNIQUE,
    `price` DECIMAL(10,2) NOT NULL,
    `features` TEXT,
    `is_active` BOOLEAN DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
