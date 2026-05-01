<?php
require_once 'core/init.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE clinics ADD COLUMN config JSON AFTER primary_color");
    echo "Successfully added 'config' column to 'clinics' table.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
