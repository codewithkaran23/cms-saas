<?php
require_once 'config/database.php';
$db = getDB();
$stmt = $db->query("SELECT * FROM roles");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
