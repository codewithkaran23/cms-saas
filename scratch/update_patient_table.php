<?php
require_once 'core/init.php';
$db = getDB();

try {
    // Add additional columns to patient_profiles to match the reference UI
    $db->exec("ALTER TABLE patient_profiles 
        ADD COLUMN marital_status VARCHAR(20) DEFAULT 'Single' AFTER sex,
        ADD COLUMN city VARCHAR(100) AFTER address,
        ADD COLUMN state VARCHAR(100) AFTER city,
        ADD COLUMN zip_code VARCHAR(20) AFTER state
    ");
    echo "Database updated: Added marital_status, city, state, and zip_code to patient_profiles.";
} catch (Exception $e) {
    echo "Error or columns already exist: " . $e->getMessage();
}
?>
