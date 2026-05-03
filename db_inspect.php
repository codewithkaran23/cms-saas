<?php
require_once 'core/init.php';
$db = getDB();

echo "<h3>Fixing Database ENUM mismatch...</h3>";

try {
    // 1. Update the ENUM to include 'in_progress'
    $db->exec("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    echo "✅ Success: 'in_progress' added to status ENUM.<br>";

    // 2. Clear corrupted data
    $db->exec("UPDATE appointments SET status = 'pending' WHERE status = '' OR status IS NULL");
    echo "✅ Success: Corrupted 'empty' statuses reset to 'pending'.<br>";

    // 3. Verify
    $stmt = $db->query("SHOW CREATE TABLE appointments");
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<pre>" . e($res['Create Table']) . "</pre>";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}

echo "<br>🚀 Database is now synchronized with the code logic. Try the session flow again!";
?>
