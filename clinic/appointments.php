<?php
// clinic/appointments.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = $_POST['appointment_id'];
    $status = $_POST['status'];
    
    $upd = $db->prepare("UPDATE appointments SET status = ? WHERE id = ? AND clinic_id = ?");
    $upd->execute([$status, $id, $clinic_id]);
    redirect('clinic/appointments.php');
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

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<header class="mb-10 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Appointments</h2>
        <p class="text-slate-500 mt-2">Manage all upcoming and past clinic appointments.</p>
    </div>
</header>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-200">
                    <th class="p-5 font-bold">Date & Time</th>
                    <th class="p-5 font-bold">Patient</th>
                    <th class="p-5 font-bold">Doctor</th>
                    <th class="p-5 font-bold">Status</th>
                    <th class="p-5 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($appointments as $app): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-5 font-medium text-slate-900">
                            <?php echo date('M d, Y', strtotime($app['date_time'])); ?> <br>
                            <span class="text-sm text-slate-500"><?php echo date('h:i A', strtotime($app['date_time'])); ?></span>
                        </td>
                        <td class="p-5 font-bold text-slate-700"><?php echo e($app['patient_name']); ?></td>
                        <td class="p-5 text-slate-600"><?php echo e($app['doctor_name']); ?></td>
                        <td class="p-5">
                            <?php 
                            $badge_class = 'bg-gray-100 text-gray-700';
                            if ($app['status'] === 'scheduled') $badge_class = 'bg-blue-100 text-blue-700';
                            if ($app['status'] === 'completed') $badge_class = 'bg-green-100 text-green-700';
                            if ($app['status'] === 'cancelled') $badge_class = 'bg-red-100 text-red-700';
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?php echo $badge_class; ?>">
                                <?php echo e($app['status']); ?>
                            </span>
                        </td>
                        <td class="p-5 text-right">
                            <form method="POST" class="inline-flex gap-2">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="appointment_id" value="<?php echo $app['id']; ?>">
                                <?php if ($app['status'] === 'scheduled'): ?>
                                    <button type="submit" name="status" value="completed" class="text-xs bg-green-50 text-green-600 border border-green-200 px-3 py-1.5 rounded-lg hover:bg-green-100 transition font-bold">Complete</button>
                                    <button type="submit" name="status" value="cancelled" class="text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition font-bold">Cancel</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-500">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
