<?php
require_once 'core/init.php';
$db = getDB();
try {
    $stmt = $db->query("DESCRIBE clinics");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Columns in 'clinics' table:\n";
    print_r($columns);
} catch (Exception $e) {
    echo "Error describing clinics: " . $e->getMessage() . "\n";
}

try {
    $stmt = $db->query("DESCRIBE template_settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nColumns in 'template_settings' table:\n";
    print_r($columns);
} catch (Exception $e) {
    echo "Error describing template_settings: " . $e->getMessage() . "\n";
}
?>
