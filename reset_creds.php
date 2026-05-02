<?php
require_once 'core/init.php';

$db = getDB();

echo "<h2>MedOS Master Credential Reset</h2>";

// Get Doctor role ID
$stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Doctor'");
$stmt->execute();
$doctorRole = $stmt->fetch();
$doctorRoleId = $doctorRole['id'] ?? null;

// Find a user who is Doctor or Clinic Admin
$stmt = $db->prepare("SELECT u.* FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name = 'Doctor' OR r.name = 'Clinic Admin' LIMIT 1");
$stmt->execute();
$doctor = $stmt->fetch();

$hash = password_hash('password123', PASSWORD_DEFAULT);

if ($doctor) {
    if ($doctorRoleId) {
        // Move the user to the existing Doctor role
        $db->prepare("UPDATE users SET password_hash = ?, role_id = ? WHERE id = ?")->execute([$hash, $doctorRoleId, $doctor['id']]);
    } else {
        $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?")->execute([$hash, $doctor['id']]);
    }

    echo "<div style='padding: 15px; background: #f0fdfa; border: 1px solid #14b8a6; margin-bottom: 20px; border-radius: 10px; font-family: sans-serif;'>";
    echo "<h3 style='color: #0f766e; margin-top: 0;'>🩺 Doctor Credentials:</h3>";
    echo "<b>Email:</b> " . htmlspecialchars($doctor['email']) . "<br>";
    echo "<b>Password:</b> password123<br>";
    echo "</div>";
} else {
    echo "<p>No Doctor account found in the system!</p>";
}

// Find a Patient
$stmt = $db->prepare("SELECT u.* FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name = 'Patient' LIMIT 1");
$stmt->execute();
$patient = $stmt->fetch();

if ($patient) {
    $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?")->execute([$hash, $patient['id']]);
    echo "<div style='padding: 15px; background: #f0fdfa; border: 1px solid #14b8a6; margin-bottom: 20px; border-radius: 10px; font-family: sans-serif;'>";
    echo "<h3 style='color: #0f766e; margin-top: 0;'>🧑‍⚕️ Patient Credentials:</h3>";
    echo "<b>Email:</b> " . htmlspecialchars($patient['email']) . "<br>";
    echo "<b>Password:</b> password123<br>";
    echo "</div>";
} else {
    echo "<p>No Patient account found in the system!</p>";
}

echo "<br><a href='login.php' style='display: inline-block; padding: 10px 20px; background: #0f766e; color: white; text-decoration: none; border-radius: 5px; font-family: sans-serif; font-weight: bold;'>Go to Login</a>";
?>
