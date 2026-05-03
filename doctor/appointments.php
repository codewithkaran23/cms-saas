<?php
// doctor/appointments.php — Clean rebuild
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$doctor_id = $_SESSION['user_id'];
$view = $_GET['view'] ?? 'all';

if (isset($_GET['approve'])) {
    $db->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ? AND clinic_id = ?")->execute([$_GET['approve'], $clinic_id]);
    header('Location: appointments.php?view=' . $view);
    exit;
}
if (isset($_GET['reject'])) {
    $db->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND clinic_id = ?")->execute([$_GET['reject'], $clinic_id]);
    header('Location: appointments.php?view=' . $view);
    exit;
}

// ── Stats for Today ──
$stats_stmt = $db->prepare("
    SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_requests,
        COUNT(CASE WHEN status IN ('confirmed', 'in_progress') THEN 1 END) as confirmed
    FROM appointments 
    WHERE clinic_id = ? AND DATE(date_time) = CURDATE()
");
$stats_stmt->execute([$clinic_id]);
$stats = $stats_stmt->fetch();

// ── Fetch Appointments ──
$query = "
    SELECT a.*, p.name as patient_name, d.name as doctor_name 
    FROM appointments a
    JOIN users p ON a.patient_id = p.id
    JOIN users d ON a.doctor_id = d.id
    WHERE a.clinic_id = ?
";

if ($view === 'today') {
    $query .= " AND DATE(a.date_time) = CURDATE()";
} elseif ($view === 'pending') {
    $query .= " AND a.status = 'pending'";
} elseif ($view === 'upcoming') {
    $query .= " AND DATE(a.date_time) > CURDATE() AND a.status NOT IN ('cancelled', 'completed')";
}

$query .= " ORDER BY a.date_time ASC";
$stmt = $db->prepare($query);
$stmt->execute([$clinic_id]);
$appointments = $stmt->fetchAll();

// ── Overview Stats ──
$all_stats = $db->prepare("
    SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled
    FROM appointments 
    WHERE clinic_id = ?
");
$all_stats->execute([$clinic_id]);
$as = $all_stats->fetch();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Clinical Schedule</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Manage patient bookings and consultations.</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap items-center gap-2 p-2 bg-slate-100/50 w-fit rounded-[1.5rem] border border-slate-100">
        <a href="?view=all" class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo $view === 'all' ? 'bg-white text-emerald-600 shadow-sm border border-emerald-100/50' : 'text-slate-500 hover:text-slate-700'; ?>">
            All
        </a>
        <a href="?view=pending" class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo $view === 'pending' ? 'bg-white text-amber-600 shadow-sm border border-amber-100/50' : 'text-slate-500 hover:text-slate-700'; ?>">
            Requests (<?php echo $stats['pending_requests'] ?? 0; ?>)
        </a>
        <a href="?view=today" class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo $view === 'today' ? 'bg-white text-emerald-600 shadow-sm border border-emerald-100/50' : 'text-slate-500 hover:text-slate-700'; ?>">
            Today's List
        </a>
        <a href="?view=upcoming" class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo $view === 'upcoming' ? 'bg-white text-blue-600 shadow-sm border border-blue-100/50' : 'text-slate-500 hover:text-slate-700'; ?>">
            Upcoming
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Active</p>
            <h4 class="text-3xl font-black text-slate-900 mt-2"><?php echo count($appointments); ?></h4>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Today's Completed</p>
            <h4 class="text-3xl font-black text-emerald-700 mt-2"><?php echo $stats['completed'] ?? 0; ?></h4>
        </div>
        <div class="bg-amber-500 p-8 rounded-[2rem] shadow-xl shadow-amber-500/20">
            <p class="text-white/60 text-[10px] font-black uppercase tracking-widest">Pending Requests</p>
            <h4 class="text-3xl font-black text-white mt-2"><?php echo $stats['pending_requests'] ?? 0; ?></h4>
        </div>
        <div class="bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-900/20">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Success Rate</p>
            <h4 class="text-3xl font-black text-white mt-2"><?php echo $as['total'] > 0 ? round(($as['completed'] / $as['total']) * 100) : 100; ?>%</h4>
        </div>
    </div>

    <!-- Appointment Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-50">
                        <th class="px-10 py-8">Patient</th>
                        <th class="px-8 py-8">Schedule</th>
                        <th class="px-8 py-8">Consultant</th>
                        <th class="px-8 py-8">Status</th>
                        <th class="px-10 py-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="5" class="px-10 py-16 text-center text-slate-400 font-bold text-sm">No appointments found for this view.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($appointments as $app): ?>
                        <?php
                        $status = strtolower($app['status'] ?? 'pending');
                        $badge = [
                            'pending'     => 'bg-amber-50 text-amber-600 border-amber-100',
                            'confirmed'   => 'bg-blue-50 text-blue-600 border-blue-100',
                            'in_progress' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'completed'   => 'bg-teal-50 text-teal-700 border-teal-100',
                            'cancelled'   => 'bg-red-50 text-red-500 border-red-100',
                        ][$status] ?? 'bg-slate-100 text-slate-500 border-slate-200';
                        $label = [
                            'pending' => 'Pending', 'confirmed' => 'Confirmed',
                            'in_progress' => 'In Progress', 'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ][$status] ?? ($status ? ucfirst($status) : 'Missing Status');
                        ?>
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center font-black text-sm border border-slate-100 group-hover:bg-emerald-50 group-hover:text-emerald-600 group-hover:border-emerald-100 transition-all">
                                        <?php echo strtoupper(substr($app['patient_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900"><?php echo e($app['patient_name']); ?></p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Patient</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <p class="text-sm font-black text-slate-900"><?php echo date('h:i A', strtotime($app['date_time'])); ?></p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5"><?php echo date('D, M d', strtotime($app['date_time'])); ?></p>
                            </td>
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                    <p class="text-xs font-black text-slate-700"><?php echo e($app['doctor_name']); ?></p>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border <?php echo $badge; ?>">
                                    <?php echo $label; ?>
                                </span>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if ($status === 'pending'): ?>
                                        <a href="?approve=<?php echo $app['id']; ?>&view=<?php echo $view; ?>" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/10">Approve</a>
                                        <a href="?reject=<?php echo $app['id']; ?>&view=<?php echo $view; ?>" class="bg-red-50 text-red-600 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 transition-all" onclick="return confirm('Reject this appointment?')">Reject</a>

                                    <?php elseif ($status === 'confirmed'): ?>
                                        <a href="session.php?id=<?php echo $app['id']; ?>" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/10 inline-flex items-center gap-2">
                                            <i data-lucide="play" class="w-3.5 h-3.5"></i> Start Session
                                        </a>

                                    <?php elseif ($status === 'in_progress'): ?>
                                        <a href="session.php?id=<?php echo $app['id']; ?>" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/10 inline-flex items-center gap-2">
                                            <i data-lucide="video" class="w-3.5 h-3.5"></i> Resume
                                        </a>

                                    <?php elseif ($status === 'completed'): ?>
                                        <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Done
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>

<?php require_once 'components/footer.php'; ?>
