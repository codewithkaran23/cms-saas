<?php
// clinic-admin/appointments.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Handle Status Changes (Approve/Cancel)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    $status = ($action === 'approve') ? 'confirmed' : 'cancelled';
    
    $upd = $db->prepare("UPDATE appointments SET status = ? WHERE id = ? AND clinic_id = ?");
    $upd->execute([$status, $id, $clinic_id]);
    redirect('clinic-admin/appointments.php');
}

// Fetch all appointments for THIS clinic
$stmt = $db->prepare("
    SELECT a.*, p.name as patient_name, d.name as doctor_name 
    FROM appointments a 
    JOIN users p ON a.patient_id = p.id 
    JOIN users d ON a.doctor_id = d.id 
    WHERE a.clinic_id = ? 
    ORDER BY a.date_time ASC
");
$stmt->execute([$clinic_id]);
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments | <?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
    </style>
</head>
<body class="bg-gray-50 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-6 sticky top-0 h-screen">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 bg-primary rounded"></div>
            <h1 class="text-xl font-bold text-gray-800">Clinic Admin</h1>
        </div>
        <nav class="space-y-4">
            <a href="<?php echo base_url('clinic-admin/index.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Dashboard</a>
            <a href="<?php echo base_url('clinic-admin/doctors.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Doctors</a>
            <a href="<?php echo base_url('clinic-admin/appointments.php'); ?>" class="block py-2 px-4 bg-primary text-white rounded-lg font-bold shadow-md">Appointments</a>
            <a href="<?php echo base_url('clinic-admin/settings.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Website Builder</a>
            <div class="pt-10">
                <a href="<?php echo base_url('admin/logout.php'); ?>" class="block py-2 px-4 text-red-500 font-bold">Logout</a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-10">
        <header class="mb-10">
            <h2 class="text-3xl font-bold text-gray-800">Appointment Queue</h2>
            <p class="text-gray-500">Manage incoming patient requests.</p>
        </header>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Patient</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Doctor</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Schedule</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-gray-400 font-medium">No appointments scheduled yet.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($appointments as $app): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900"><?php echo e($app['patient_name']); ?></td>
                            <td class="px-6 py-4 text-gray-600">Dr. <?php echo e($app['doctor_name']); ?></td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800"><?php echo date('M d, Y', strtotime($app['date_time'])); ?></div>
                                <div class="text-xs text-gray-500"><?php echo date('h:i A', strtotime($app['date_time'])); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'confirmed' => 'bg-green-100 text-green-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'completed' => 'bg-blue-100 text-blue-700'
                                    ];
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?php echo $statusClasses[$app['status']]; ?>">
                                    <?php echo e($app['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <?php if ($app['status'] === 'pending'): ?>
                                    <a href="?action=approve&id=<?php echo $app['id']; ?>" class="text-green-600 font-bold text-sm hover:underline">Approve</a>
                                    <a href="?action=cancel&id=<?php echo $app['id']; ?>" class="text-red-500 font-bold text-sm hover:underline">Cancel</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
