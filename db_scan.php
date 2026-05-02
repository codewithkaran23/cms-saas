<?php
require_once 'core/init.php';
$db = getDB();
$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

echo "<h3>Database Tables:</h3><ul>";
foreach ($tables as $table) {
    echo "<li>$table</li>";
}
echo "</ul>";

$roles = $db->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>Roles:</h3><ul>";
foreach ($roles as $role) {
    echo "<li>" . $role['id'] . " - " . $role['name'] . "</li>";
}
echo "</ul>";

$clinics = $db->query("SELECT id, name FROM clinics")->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>Practices (Clinics):</h3><ul>";
foreach ($clinics as $clinic) {
    echo "<li>" . $clinic['id'] . " - " . $clinic['name'] . "</li>";
}
echo "</ul>";
?>
