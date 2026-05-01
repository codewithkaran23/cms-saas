<?php
require_once 'core/init.php';
$db = getDB();

echo "<h2>Database Structural Check</h2>";

try {
    // 1. Show all columns in clinics
    echo "<h4>Columns in 'clinics' table:</h4>";
    $cols = $db->query("DESCRIBE clinics")->fetchAll();
    $found = false;
    foreach ($cols as $col) {
        echo "- " . $col['Field'] . " (" . $col['Type'] . ")<br>";
        if ($col['Field'] === 'config') $found = true;
    }

    if (!$found) {
        echo "<h3 style='color:red'>❌ 'config' column is MISSING.</h3>";
        echo "Attempting force add...<br>";
        $db->exec("ALTER TABLE clinics ADD config LONGTEXT NULL");
        echo "<h3 style='color:green'>✅ ADDED! Refresh this page to verify.</h3>";
    } else {
        echo "<h3 style='color:green'>✅ 'config' column is PRESENT.</h3>";
    }

} catch (Exception $e) {
    echo "<h3>❌ DB Error: " . $e->getMessage() . "</h3>";
}
