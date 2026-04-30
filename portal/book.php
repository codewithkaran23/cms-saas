<?php
// portal/book.php
require_once '../core/init.php';

if (!$clinic) die("Clinic context required.");

$db = getDB();
$doctor_id = $_GET['doctor_id'] ?? null;

if (!$doctor_id) redirect('portal/index.php');

// Fetch doctor details
$stmt = $db->prepare("SELECT u.*, dp.specialization FROM users u JOIN doctor_profiles dp ON u.id = dp.user_id WHERE u.id = ? AND u.clinic_id = ?");
$stmt->execute([$doctor_id, $clinic['id']]);
$doctor = $stmt->fetch();

if (!$doctor) redirect('portal/index.php');

$success = '';
$error = '';

// Handle Booking Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $p_name = $_POST['patient_name'] ?? '';
    $p_email = $_POST['patient_email'] ?? '';
    $p_phone = $_POST['patient_phone'] ?? '';

    if (empty($date) || empty($time) || empty($p_name) || empty($p_email)) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            $db->beginTransaction();

            // 1. Identify or Create Patient
            // Check if user exists as a patient in this clinic
            $p_stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND clinic_id = ?");
            $p_stmt->execute([$p_email, $clinic['id']]);
            $patient_id = $p_stmt->fetchColumn();

            if (!$patient_id) {
                // Create new Patient account
                $role_stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Patient'");
                $role_stmt->execute();
                $role_id = $role_stmt->fetchColumn();

                $new_p = $db->prepare("INSERT INTO users (clinic_id, role_id, name, email, password_hash, phone) VALUES (?, ?, ?, ?, ?, ?)");
                $new_p->execute([$clinic['id'], $role_id, $p_name, $p_email, password_hash('patient123', PASSWORD_DEFAULT), $p_phone]);
                $patient_id = $db->lastInsertId();
            }

            // 2. Create Appointment
            $app_stmt = $db->prepare("INSERT INTO appointments (clinic_id, patient_id, doctor_id, date_time, status) VALUES (?, ?, ?, ?, 'pending')");
            $app_stmt->execute([$clinic['id'], $patient_id, $doctor_id, "$date $time"]);

            $db->commit();
            $success = "Your appointment with " . $doctor['name'] . " has been requested successfully!";
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Booking failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book with <?php echo e($doctor['name']); ?> | <?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
        <a href="index.php" class="text-primary font-bold">← Back to Doctors</a>
        <span class="text-xl font-bold text-gray-800"><?php echo e($clinic['name']); ?></span>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            
            <!-- Doctor Summary -->
            <div class="md:col-span-1">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center sticky top-24">
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl mx-auto mb-4 flex items-center justify-center text-gray-400 text-3xl font-black">
                        <?php echo substr($doctor['name'], 0, 1); ?>
                    </div>
                    <h3 class="font-bold text-gray-900"><?php echo e($doctor['name']); ?></h3>
                    <p class="text-primary text-xs font-black uppercase tracking-widest mb-4"><?php echo e($doctor['specialization']); ?></p>
                    <div class="text-left mt-6 space-y-4 pt-6 border-t border-gray-50">
                        <div class="flex items-center gap-3 text-sm text-gray-500">
                            <span>⭐ 4.9 (120+ Reviews)</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-500">
                            <span>📍 <?php echo e($clinic['name']); ?> Main</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="md:col-span-2">
                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-200 text-green-700 p-8 rounded-3xl mb-8 text-center">
                        <div class="text-4xl mb-4">✅</div>
                        <h2 class="text-2xl font-bold mb-2">Booking Success!</h2>
                        <p><?php echo e($success); ?></p>
                        <a href="index.php" class="inline-block mt-6 bg-green-600 text-white px-6 py-2 rounded-xl font-bold">Done</a>
                    </div>
                <?php else: ?>
                    <form method="POST" class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 space-y-8">
                        <?php if ($error): ?><div class="bg-red-100 text-red-700 p-4 rounded-xl text-sm"><?php echo e($error); ?></div><?php endif; ?>
                        
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 mb-4">1. Personal Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" name="patient_name" placeholder="Full Name" required class="w-full border border-gray-200 p-4 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                                <input type="email" name="patient_email" placeholder="Email Address" required class="w-full border border-gray-200 p-4 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                                <input type="text" name="patient_phone" placeholder="Phone Number" class="w-full border border-gray-200 p-4 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none md:col-span-2">
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-bold text-gray-900 mb-4">2. Select Date & Time</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>" class="w-full border border-gray-200 p-4 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                                <select name="time" required class="w-full border border-gray-200 p-4 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                                    <option value="">Select Time</option>
                                    <option value="09:00:00">09:00 AM</option>
                                    <option value="10:00:00">10:00 AM</option>
                                    <option value="11:00:00">11:00 AM</option>
                                    <option value="14:00:00">02:00 PM</option>
                                    <option value="15:00:00">03:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white font-bold py-5 rounded-2xl shadow-xl hover:opacity-90 transition transform hover:scale-[1.01]">
                            Confirm Appointment
                        </button>
                    </form>
                <?php endif; ?>
            </div>

        </div>
    </main>

</body>
</html>
