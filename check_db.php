<?php
require_once 'core/init.php';
$db = getDB();

echo "Clinics count: " . $db->query("SELECT COUNT(*) FROM clinics")->fetchColumn() . "\n";
echo "Users count: " . $db->query("SELECT COUNT(*) FROM users")->fetchColumn() . "\n";
echo "Roles count: " . $db->query("SELECT COUNT(*) FROM roles")->fetchColumn() . "\n";

$users = $db->query("SELECT email FROM users")->fetchAll(PDO::FETCH_ASSOC);
foreach($users as $u) {
    echo "- " . $u['email'] . "\n";
}

$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables: " . implode(', ', $tables) . "\n";
?>
