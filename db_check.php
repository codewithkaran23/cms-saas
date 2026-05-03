<?php
require_once 'core/init.php';
$db = getDB();

echo "<h3>Full Appointments Schema:</h3>";
$stmt = $db->query("SHOW CREATE TABLE appointments");
$res = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<pre>" . e($res['Create Table']) . "</pre>";

echo "<h3>Data for APT 2:</h3>";
$row = $db->query("SELECT * FROM appointments WHERE id = 2")->fetch(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($row);
echo "</pre>";
?>
