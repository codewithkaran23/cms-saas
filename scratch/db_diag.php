<?php
require_once 'core/init.php';
$db = getDB();

echo "<h3>users Table:</h3>";
$res = $db->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
foreach($res as $r) echo $r['Field'] . " - " . $r['Type'] . "<br>";

echo "<h3>patient_profiles Table:</h3>";
$res = $db->query("DESCRIBE patient_profiles")->fetchAll(PDO::FETCH_ASSOC);
foreach($res as $r) echo $r['Field'] . " - " . $r['Type'] . "<br>";
?>
