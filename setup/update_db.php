<?php
require_once 'core/init.php';
$db = getDB();

echo "Running Database Updates...\n";

try {
    // 1. Create system_settings table
    $db->exec("CREATE TABLE IF NOT EXISTS `system_settings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `setting_key` VARCHAR(100) NOT NULL UNIQUE,
        `setting_value` TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Created system_settings table.\n";

    // 2. Create subscription_plans table
    $db->exec("CREATE TABLE IF NOT EXISTS `subscription_plans` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `tier_name` VARCHAR(50) NOT NULL UNIQUE,
        `price` DECIMAL(10,2) NOT NULL,
        `features` TEXT,
        `is_active` BOOLEAN DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Created subscription_plans table.\n";

    // 3. Insert default plans if they don't exist
    $checkPlans = $db->query("SELECT COUNT(*) FROM subscription_plans")->fetchColumn();
    if ($checkPlans == 0) {
        $db->exec("INSERT INTO `subscription_plans` (`tier_name`, `price`, `features`) VALUES
            ('basic', 29.00, '5 Doctors, 100 Appointments/mo'),
            ('premium', 49.00, 'Unlimited Doctors, Unlimited Appointments'),
            ('enterprise', 199.00, 'Multi-location, API Access')
        ");
        echo "Inserted default plans.\n";
    }

    // 4. Insert default settings if they don't exist
    $checkSettings = $db->query("SELECT COUNT(*) FROM system_settings")->fetchColumn();
    if ($checkSettings == 0) {
        $db->exec("INSERT INTO `system_settings` (`setting_key`, `setting_value`) VALUES
            ('smtp_host', 'smtp.gmail.com'),
            ('smtp_port', '587'),
            ('daily_backup_enabled', '1')
        ");
        echo "Inserted default settings.\n";
    }

    echo "Update complete!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
