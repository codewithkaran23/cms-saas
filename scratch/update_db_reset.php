<?php
require_once 'core/init.php';
$db = getDB();

try {
    // Add require_reset column to users table
    $db->exec("ALTER TABLE users ADD COLUMN require_reset TINYINT(1) DEFAULT 1 AFTER password_hash");
    echo "Column 'require_reset' added successfully to users table.";
} catch (Exception $e) {
    echo "Error or column already exists: " . $e->getMessage();
}
?>
