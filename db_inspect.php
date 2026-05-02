<?php
require_once 'core/init.php';
$db = getDB();

echo "<h3>system_settings:</h3>";
$desc = $db->query("DESCRIBE system_settings")->fetchAll(PDO::FETCH_ASSOC);
print_r($desc);

echo "<h3>template_settings:</h3>";
$desc = $db->query("DESCRIBE template_settings")->fetchAll(PDO::FETCH_ASSOC);
print_r($desc);
?>
