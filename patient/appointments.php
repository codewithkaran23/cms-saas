<?php
// patient/appointments.php — Synced with doctor session flow
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

// Fetch all appointments with doctor details
$stmt = $db->prepare("
    SELECT a.*, d.name as doctor_name, COALESCE(dp.specialization, 'General Physician') as specialization
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    LEFT JOIN doctor_profiles dp ON d.id = dp.user_id
    WHERE a.patient_id = ? 
    ORDER BY a.date_time DESC
");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();

// Split into upcoming and past
$upcoming = array_filter($appointments, fn($a) => in_array($a['status'], ['pending', 'confirmed', 'in_progress']));
$history  = array_filter($appointments, fn($a) => in_array($a['status'], ['completed', 'cancelled']));

$page_title = "My Appointments";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-5xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">My Appointments</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">View and manage your consultations.</p>
        </div>
        <a href="doctors.php" class="bg-teal-600 text-white px-7 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Book Appointment
        </a>
    </div>

    <!-- Active / Live Session Banner -->
    <?php 
    $live = array_filter($appointments, fn($a) => $a['status'] === 'in_progress');
    $live_apt = !empty($live) ? array_values($live)[0] : null;
    ?>
    <?php if ($live_apt): ?>
        <div class="bg-emerald-600 text-white p-6 rounded-[2rem] shadow-xl shadow-emerald-600/30 flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i data-lucide="video" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="font-black text-lg">Your session is live!</p>
                    <p class="text-emerald-100 text-sm font-medium"><?php echo e($live_apt['doctor_name']); ?> is ready for your consultation.</p>
                </div>
            </div>
            <a href="session.php?id=<?php echo $live_apt['id']; ?>" class="bg-white text-emerald-600 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 transition-all shrink-0 flex items-center gap-2">
                <i data-lucide="video" class="w-4 h-4"></i> Join Now
            </a>
        </div>
    <?php endif; ?>

    <!-- Upcoming Appointments -->
    <div class="space-y-4">
        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Active Appointments (<?php echo count($upcoming); ?>)</h3>

        <?php if (empty($upcoming)): ?>
            <div class="bg-white border border-dashed border-slate-200 rounded-[2rem] p-12 text-center">
                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="calendar" class="w-7 h-7 text-slate-300"></i>
                </div>
                <p class="text-slate-500 font-bold text-sm">No upcoming appointments</p>
                <p class="text-slate-400 text-xs mt-1 mb-6">Book a consultation with one of our specialists.</p>
                <a href="doctors.php" class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all">
                    Book Now
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
            <?php foreach($upcoming as $app):
                $status = strtolower($app['status']);
                $status_config = [
                    'pending'     => ['label' => 'Awaiting Approval', 'class' => 'bg-amber-50 text-amber-600 border-amber-100'],
                    'confirmed'   => ['label' => 'Confirmed',         'class' => 'bg-blue-50 text-blue-600 border-blue-100'],
                    'in_progress' => ['label' => 'In Progress',       'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                    'completed'   => ['label' => 'Completed',         'class' => 'bg-teal-50 text-teal-700 border-teal-100'],
                ];
                $s = $status_config[$status] ?? ['label' => ($status ?: 'Unknown'), 'class' => 'bg-slate-100 text-slate-500 border-slate-200'];
            ?>
                <div class="bg-white border border-slate-100 rounded-[2rem] p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm hover:shadow-md hover:border-teal-100 transition-all">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-teal-50 rounded-2xl flex flex-col items-center justify-center shrink-0">
                            <span class="text-[10px] font-black text-teal-500 uppercase"><?php echo date('M', strtotime($app['date_time'])); ?></span>
                            <span class="text-2xl font-black text-teal-700 leading-none"><?php echo date('d', strtotime($app['date_time'])); ?></span>
                        </div>
                        <div>
                            <p class="text-base font-black text-slate-900"><?php echo e($app['doctor_name']); ?></p>
                            <p class="text-xs font-bold text-teal-600 mt-0.5"><?php echo e($app['specialization']); ?></p>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1.5">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <?php echo date('h:i A', strtotime($app['date_time'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <?php if ($status === 'in_progress'): ?>
                            <a href="session.php?id=<?php echo $app['id']; ?>" class="bg-teal-600 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/20 flex items-center gap-2">
                                <i data-lucide="video" class="w-4 h-4"></i> Join Session
                            </a>
                        <?php else: ?>
                            <span class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border <?php echo $s['class']; ?>">
                                <?php echo $s['label']; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Visit History -->
    <?php if (!empty($history)): ?>
    <div class="space-y-4">
        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Visit History (<?php echo count($history); ?>)</h3>
        <div class="bg-white border border-slate-100 rounded-[2rem] overflow-hidden shadow-sm">
            <div class="divide-y divide-slate-50">
            <?php foreach($history as $app):
                $is_cancelled = strtolower($app['status']) === 'cancelled';
            ?>
                <div class="flex items-center justify-between px-8 py-6 hover:bg-slate-50/50 transition-colors <?php echo $is_cancelled ? 'opacity-60' : ''; ?>">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex flex-col items-center justify-center shrink-0">
                            <span class="text-[9px] font-black text-slate-400 uppercase"><?php echo date('M', strtotime($app['date_time'])); ?></span>
                            <span class="text-lg font-black text-slate-600 leading-none"><?php echo date('d', strtotime($app['date_time'])); ?></span>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-700"><?php echo e($app['doctor_name']); ?></p>
                            <p class="text-[10px] font-bold text-slate-400 mt-0.5"><?php echo date('h:i A', strtotime($app['date_time'])); ?> &bull; <?php echo e($app['specialization']); ?></p>
                        </div>
                    </div>
                    <?php if ($is_cancelled): ?>
                        <span class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-red-50 text-red-400 border border-red-100">Cancelled</span>
                    <?php else: ?>
                        <span class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3 h-3"></i> Completed
                        </span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
<?php require_once 'components/footer.php'; ?>
