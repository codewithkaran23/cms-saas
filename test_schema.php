<?php
require_once 'core/init.php';
$db = getDB();
$cols = $db->query("DESCRIBE appointments")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($cols);
