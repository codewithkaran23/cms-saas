<?php
// clinic-admin/doctors.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Fetch all doctors for THIS clinic
$stmt = $db->prepare("
    SELECT u.*, dp.specialization 
    FROM users u 
    LEFT JOIN doctor_profiles dp ON u.id = dp.user_id 
    WHERE u.clinic_id = ? 
    AND u.role_id = (SELECT id FROM roles WHERE name = 'Doctor')
    AND u.deleted_at IS NULL
");
$stmt->execute([$clinic_id]);
$doctors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors | <?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
    </style>
</head>
<body class="bg-gray-50 flex">

    <!-- Sidebar (Same as index.php) -->
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-6 sticky top-0 h-screen">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 bg-primary rounded"></div>
            <h1 class="text-xl font-bold text-gray-800">Clinic Admin</h1>
        </div>
        <nav class="space-y-4">
            <a href="<?php echo base_url('clinic-admin/index.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Dashboard</a>
            <a href="<?php echo base_url('clinic-admin/doctors.php'); ?>" class="block py-2 px-4 bg-primary text-white rounded-lg font-bold shadow-md">Doctors</a>
            <a href="<?php echo base_url('clinic-admin/patients.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Patients</a>
            <a href="<?php echo base_url('clinic-admin/appointments.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Appointments</a>
            <a href="<?php echo base_url('clinic-admin/settings.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Website Builder</a>
            <div class="pt-10">
                <a href="<?php echo base_url('admin/logout.php'); ?>" class="block py-2 px-4 text-red-500 font-bold">Logout</a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-10">
        <header class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800">Our Doctors</h2>
            <a href="<?php echo base_url('clinic-admin/doctor-add.php'); ?>" class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:opacity-90 transition">
                + Add New Doctor
            </a>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($doctors as $doc): ?>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl mb-4 flex items-center justify-center text-gray-400 text-3xl font-bold">
                        <?php echo substr($doc['name'], 0, 1); ?>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800"><?php echo e($doc['name']); ?></h4>
                    <p class="text-primary text-sm font-bold uppercase tracking-wider mb-4"><?php echo e($doc['specialization'] ?? 'General Physician'); ?></p>
                    <p class="text-gray-500 text-sm mb-6"><?php echo e($doc['email']); ?></p>
                    <div class="flex gap-3 w-full">
                        <button class="flex-1 py-2 bg-gray-50 text-gray-600 rounded-lg font-bold text-sm">Schedule</button>
                        <button class="flex-1 py-2 bg-gray-50 text-gray-600 rounded-lg font-bold text-sm">Edit</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
