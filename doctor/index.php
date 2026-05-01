<?php
// doctor/index.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$doctor_id = $_SESSION['user_id'];
$clinic_id = $_SESSION['clinic_id'];

// Fetch today's appointments for this doctor
$stmt = $db->prepare("
    SELECT a.*, p.name as patient_name 
    FROM appointments a 
    JOIN users p ON a.patient_id = p.id 
    WHERE a.doctor_id = ? 
    AND DATE(a.date_time) = CURDATE() 
    AND a.status = 'confirmed'
    ORDER BY a.date_time ASC
");
$stmt->execute([$doctor_id]);
$today_appointments = $stmt->fetchAll();

$total_patients = $db->prepare("SELECT COUNT(DISTINCT patient_id) FROM appointments WHERE doctor_id = ?");
$total_patients->execute([$doctor_id]);
$patients_count = $total_patients->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard | <?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
    </style>
</head>
<body class="bg-gray-50 flex">

    <!-- Doctor Sidebar -->
    <aside class="w-64 bg-indigo-950 min-h-screen text-white p-6 sticky top-0 h-screen">
        <div class="mb-10 text-center">
            <div class="w-16 h-16 bg-white/10 rounded-2xl mx-auto mb-4 flex items-center justify-center text-2xl font-bold">
                <?php echo substr($_SESSION['user_name'], 0, 1); ?>
            </div>
            <h1 class="text-sm font-bold opacity-60 uppercase tracking-widest">Doctor Panel</h1>
            <p class="text-lg font-black mt-1"><?php echo e($_SESSION['user_name']); ?></p>
        </div>
        <nav class="space-y-4">
            <a href="index.php" class="block py-2 px-4 bg-indigo-800 rounded-lg font-bold">Today's Schedule</a>
            <a href="appointments.php" class="block py-2 px-4 hover:bg-white/10 rounded-lg text-indigo-300 transition">All Appointments</a>
            <a href="patients.php" class="block py-2 px-4 hover:bg-white/10 rounded-lg text-indigo-300 transition">My Patients</a>
            <div class="pt-10">
                <a href="../super-admin/logout.php" class="block py-2 px-4 text-red-400 font-bold">Logout</a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-10">
        <header class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900">Today's <span class="text-indigo-600">Patient Flow</span></h2>
                <p class="text-gray-500"><?php echo date('l, F jS Y'); ?></p>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100">
                <span class="text-sm text-gray-400 font-bold uppercase mr-4">Total Patients Managed</span>
                <span class="text-2xl font-black text-indigo-600"><?php echo $patients_count; ?></span>
            </div>
        </header>

        <div class="space-y-6">
            <?php if (empty($today_appointments)): ?>
                <div class="bg-white p-20 rounded-3xl border-2 border-dashed border-gray-200 text-center">
                    <p class="text-gray-400 font-medium">No confirmed appointments for today yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($today_appointments as $app): ?>
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:border-indigo-200 transition">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex flex-col items-center justify-center">
                                <span class="text-xs font-bold uppercase"><?php echo date('h:i', strtotime($app['date_time'])); ?></span>
                                <span class="text-sm font-black"><?php echo date('A', strtotime($app['date_time'])); ?></span>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900"><?php echo e($app['patient_name']); ?></h4>
                                <p class="text-gray-400 text-sm">Regular Consultation</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="visit.php?appointment_id=<?php echo $app['id']; ?>" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                                Start Consultation
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
