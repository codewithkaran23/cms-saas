<?php
// clinic-admin/index.php
require_once '../core/init.php';

// Protect: Must be logged in as Clinic Admin AND assigned to a specific clinic
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Metrics for THIS clinic only
$doctor_count = $db->prepare("SELECT COUNT(*) FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Doctor')");
$doctor_count->execute([$clinic_id]);
$doctors = $doctor_count->fetchColumn();

$patient_count = $db->prepare("SELECT COUNT(*) FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient')");
$patient_count->execute([$clinic_id]);
$patients = $patient_count->fetchColumn();

$appointment_count = $db->prepare("SELECT COUNT(*) FROM appointments WHERE clinic_id = ? AND date_time >= CURDATE()");
$appointment_count->execute([$clinic_id]);
$appointments = $appointment_count->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($clinic['name']); ?> | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
    </style>
</head>
<body class="bg-gray-50 flex">

    <!-- Clinic Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-6 sticky top-0 h-screen">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 bg-primary rounded"></div>
            <h1 class="text-xl font-bold text-gray-800">Clinic Admin</h1>
        </div>
        <nav class="space-y-4">
            <a href="<?php echo base_url('clinic-admin/index.php'); ?>" class="block py-2 px-4 bg-primary text-white rounded-lg font-bold shadow-md">Dashboard</a>
            <a href="<?php echo base_url('clinic-admin/doctors.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Doctors</a>
            <a href="<?php echo base_url('clinic-admin/patients.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Patients</a>
            <a href="<?php echo base_url('clinic-admin/appointments.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Appointments</a>
            <a href="<?php echo base_url('clinic-admin/settings.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Website Builder</a>
            <div class="pt-10">
                <a href="<?php echo base_url('admin/logout.php'); ?>" class="block py-2 px-4 text-red-500 font-bold">Logout</a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-10">
        <header class="mb-10">
            <h2 class="text-3xl font-bold text-gray-800">Welcome back, <?php echo e($_SESSION['user_name']); ?></h2>
            <p class="text-gray-500">Overview for <?php echo e($clinic['name']); ?></p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 border-b-4 border-b-primary">
                <p class="text-gray-500 text-sm font-bold uppercase tracking-wider">Total Doctors</p>
                <h3 class="text-4xl font-black mt-2"><?php echo $doctors; ?></h3>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 border-b-4 border-b-primary">
                <p class="text-gray-500 text-sm font-bold uppercase tracking-wider">Total Patients</p>
                <h3 class="text-4xl font-black mt-2"><?php echo $patients; ?></h3>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 border-b-4 border-b-primary">
                <p class="text-gray-500 text-sm font-bold uppercase tracking-wider">Today's Appointments</p>
                <h3 class="text-4xl font-black mt-2"><?php echo $appointments; ?></h3>
            </div>
        </div>

        <div class="mt-12 bg-white p-10 rounded-3xl border border-gray-100 shadow-sm">
            <h4 class="text-xl font-bold mb-6">Quick Actions</h4>
            <div class="flex gap-4">
                <a href="doctors.php" class="bg-primary/10 text-primary px-6 py-3 rounded-xl font-bold">Add New Doctor</a>
                <a href="appointments.php" class="bg-gray-100 text-gray-600 px-6 py-3 rounded-xl font-bold">Manage Calendar</a>
            </div>
        </div>
    </main>

</body>
</html>
