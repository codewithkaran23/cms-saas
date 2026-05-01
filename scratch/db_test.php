<?php
require_once '../core/init.php';

echo "<body style='font-family:sans-serif; background:#f8fafc; padding:40px;'>";
echo "<div style='max-width:600px; margin:0 auto; background:white; padding:30px; border-radius:20px; shadow:0 10px 15px -3px rgba(0,0,0,0.1); border:1px solid #e2e8f0;'>";
echo "<h2 style='margin-top:0;'>🛠️ Database Health Check</h2>";

try {
    $db = getDB();
    echo "<p style='color:green; font-weight:bold;'>✅ Connection: Successful</p>";

    // Check for config column in clinics
    $stmt = $db->query("DESCRIBE clinics");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('config', $columns)) {
        echo "<p style='color:green; font-weight:bold;'>✅ 'config' column: Found in 'clinics' table</p>";
    } else {
        echo "<p style='color:red; font-weight:bold;'>❌ 'config' column: MISSING in 'clinics' table</p>";
    }

    // Check for essential tables
    $tables = ['clinics', 'users', 'roles', 'appointments', 'visits', 'doctor_profiles'];
    echo "<h4>Table Status:</h4><ul style='list-style:none; padding:0;'>";
    foreach ($tables as $table) {
        $check = $db->query("SHOW TABLES LIKE '$table'")->fetch();
        if ($check) {
            echo "<li style='margin-bottom:8px;'>✅ Table <b>$table</b> exists</li>";
        } else {
            echo "<li style='margin-bottom:8px; color:red;'>❌ Table <b>$table</b> is MISSING</li>";
        }
    }
    echo "</ul>";

    echo "<div style='margin-top:20px; padding:15px; background:#f1f5f9; border-radius:10px; font-size:14px;'>";
    echo "<strong>System Note:</strong> If all items above are green, your database is fully compatible with the new Patients and Settings modules.";
    echo "</div>";

} catch (Exception $e) {
    echo "<p style='color:red; font-weight:bold;'>❌ Connection Failed: " . $e->getMessage() . "</p>";
}

echo "</div></body>";
?>
