<?php
// clinic/appointments.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$view = $_GET['view'] ?? 'all'; // Default changed to 'all' for overview focus

// Handle status flow actions (Start, Complete, Cancel)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = $_POST['appointment_id'];
    $status = $_POST['status'];
    $upd = $db->prepare("UPDATE appointments SET status = ? WHERE id = ? AND clinic_id = ?");
    $upd->execute([$status, $id, $clinic_id]);
    redirect('doctor/appointments.php?view=' . $view);
}

// Stats for the "Today" header row
$stats_stmt = $db->prepare("
    SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
        COUNT(CASE WHEN status = 'booked' OR status = 'in_progress' THEN 1 END) as pending,
        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled
    FROM appointments 
    WHERE clinic_id = ? AND DATE(date_time) = CURDATE()
");
$stats_stmt->execute([$clinic_id]);
$stats = $stats_stmt->fetch();

// Fetch appointments based on view
$query = "
    SELECT a.*, p.name as patient_name, d.name as doctor_name 
    FROM appointments a
    JOIN users p ON a.patient_id = p.id
    JOIN users d ON a.doctor_id = d.id
    WHERE a.clinic_id = ?
";

if ($view === 'today') {
    $query .= " AND DATE(a.date_time) = CURDATE()";
} elseif ($view === 'upcoming') {
    $query .= " AND DATE(a.date_time) > CURDATE()";
}

$query .= " ORDER BY a.date_time ASC";

$stmt = $db->prepare($query);
$stmt->execute([$clinic_id]);
$appointments = $stmt->fetchAll();

// Stats for the "All" overview (Dashboard style)
$all_stats = $db->prepare("
    SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled,
        (COUNT(CASE WHEN status = 'completed' THEN 1 END) * 50) as revenue
    FROM appointments 
    WHERE clinic_id = ?
");
$all_stats->execute([$clinic_id]);
$as = $all_stats->fetch();

// Trend Chart Data (Weekly Bookings)
$trend_stmt = $db->prepare("
    SELECT 
        WEEK(date_time, 1) - WEEK(DATE_SUB(date_time, INTERVAL DAYOFMONTH(date_time) - 1 DAY), 1) + 1 AS week_num,
        COUNT(*) as count
    FROM appointments
    WHERE clinic_id = ? AND MONTH(date_time) = MONTH(CURDATE()) AND YEAR(date_time) = YEAR(CURDATE())
    GROUP BY week_num
");
$trend_stmt->execute([$clinic_id]);
$trend_res = $trend_stmt->fetchAll(PDO::FETCH_ASSOC);
$weekly_data = [0, 0, 0, 0];
foreach($trend_res as $row) {
    if ($row['week_num'] >= 1 && $row['week_num'] <= 4) {
        $weekly_data[$row['week_num'] - 1] = $row['count'];
    }
}
$trend_data_str = implode(',', $weekly_data);

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Header with Primary Action -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Appointments <span class="text-teal-600">Overview</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Detailed analytics and history of all patient bookings.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                <input type="text" placeholder="Search patient..." class="bg-white border border-slate-200 rounded-2xl py-3 pl-10 pr-4 text-xs focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all w-64">
            </div>
            <a href="appointment-add.php" class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-3 rounded-2xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Appointment
            </a>
        </div>
    </header>

    <!-- Analytics Cards (Only for All Overview) -->
    <?php if ($view === 'all'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm group">
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bookings</p>
            <h4 class="text-3xl font-black text-slate-900 mt-1"><?php echo number_format($as['total']); ?></h4>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm group">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Completion Rate</p>
            <h4 class="text-3xl font-black text-slate-900 mt-1">
                <?php echo $as['total'] > 0 ? round(($as['completed'] / $as['total']) * 100) : 0; ?>%
            </h4>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm group">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <i data-lucide="x-circle" class="w-6 h-6"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Cancellations</p>
            <h4 class="text-3xl font-black text-slate-900 mt-1"><?php echo number_format($as['cancelled']); ?></h4>
        </div>
        <div class="bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-900/20 group">
            <div class="w-12 h-12 bg-white/10 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform backdrop-blur-md">
                <i data-lucide="dollar-sign" class="w-6 h-6"></i>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Total Value</p>
            <h4 class="text-3xl font-black text-white mt-1">$<?php echo number_format($as['revenue']); ?></h4>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <h4 class="text-lg font-black text-slate-900 tracking-tight mb-8">Monthly Booking Trends</h4>
        <canvas id="appointmentTrends" height="100"></canvas>
    </div>
    <?php endif; ?>

    <!-- View Switcher Tabs -->
    <!-- View Switcher Tabs -->
    <div class="flex items-center gap-2 p-1.5 bg-slate-100/80 w-fit rounded-[1.25rem]">
        <a href="?view=all" class="px-8 py-3 rounded-2xl text-[13px] font-bold transition-all <?php echo $view === 'all' ? 'bg-white text-teal-600 shadow-md shadow-teal-600/5' : 'text-slate-500 hover:text-slate-700'; ?>">
            All Appointments
        </a>
        <a href="?view=today" class="px-8 py-3 rounded-2xl text-[13px] font-bold transition-all <?php echo $view === 'today' ? 'bg-white text-teal-600 shadow-md shadow-teal-600/5' : 'text-slate-500 hover:text-slate-700'; ?>">
            Today
        </a>
        <a href="?view=upcoming" class="px-8 py-3 rounded-2xl text-[13px] font-bold transition-all <?php echo $view === 'upcoming' ? 'bg-white text-teal-600 shadow-md shadow-teal-600/5' : 'text-slate-500 hover:text-slate-700'; ?>">
            Upcoming
        </a>
    </div>

    <!-- Stats Row (Only for Today) -->
    <?php if ($view === 'today'): ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Today's Total</p>
            <h4 class="text-2xl font-black text-slate-900 mt-1"><?php echo $stats['total']; ?></h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Completed</p>
            <h4 class="text-2xl font-black text-emerald-700 mt-1"><?php echo $stats['completed']; ?></h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-teal-600 uppercase tracking-widest">Pending</p>
            <h4 class="text-2xl font-black text-teal-700 mt-1"><?php echo $stats['pending']; ?></h4>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">Cancelled</p>
            <h4 class="text-2xl font-black text-red-700 mt-1"><?php echo $stats['cancelled']; ?></h4>
        </div>
    </div>
    <?php endif; ?>

    <!-- Appointments List -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-50">
                        <th class="px-10 py-6">Patient Name</th>
                        <th class="px-8 py-6">Time Slot</th>
                        <th class="px-8 py-6">Doctor</th>
                        <th class="px-8 py-6">Status</th>
                        <th class="px-10 py-6 text-right">Quick Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($appointments as $app): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center font-black text-xs border border-teal-100/50">
                                        <?php echo substr($app['patient_name'], 0, 1); ?>
                                    </div>
                                    <p class="text-sm font-black text-slate-900"><?php echo e($app['patient_name']); ?></p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900 tracking-tight"><?php echo date('h:i A', strtotime($app['date_time'])); ?></p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?php echo date('M d, Y', strtotime($app['date_time'])); ?></p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="user-check" class="w-3.5 h-3.5 text-teal-500"></i>
                                    <p class="text-xs font-bold text-slate-600">Dr. <?php echo e($app['doctor_name']); ?></p>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <?php 
                                $status = strtolower($app['status'] ?? 'booked');
                                $color = 'bg-slate-100 text-slate-600'; // Default
                                if ($status === 'booked') $color = 'bg-blue-50 text-blue-600';
                                if ($status === 'in_progress') $color = 'bg-amber-50 text-amber-600';
                                if ($status === 'completed') $color = 'bg-green-50 text-green-600';
                                if ($status === 'cancelled') $color = 'bg-red-50 text-red-600';
                                ?>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider <?php echo $color; ?>">
                                    <?php echo str_replace('_', ' ', $status); ?>
                                </span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <form method="POST" class="flex items-center justify-end gap-2">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="appointment_id" value="<?php echo $app['id']; ?>">
                                    
                                    <?php if ($status === 'booked'): ?>
                                        <button type="submit" name="status" value="in_progress" class="px-4 py-2 bg-teal-50 text-teal-600 border border-teal-100/50 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-600 hover:text-white transition-all shadow-sm">Start Session</button>
                                        <button type="submit" name="status" value="cancelled" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100/50 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all shadow-sm">Cancel</button>
                                    <?php elseif ($status === 'in_progress'): ?>
                                        <button type="submit" name="status" value="completed" class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100/50 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all shadow-sm">Complete</button>
                                    <?php endif; ?>
                                    <button type="button" class="w-8 h-8 flex items-center justify-center hover:bg-slate-50 rounded-xl transition-all text-slate-400 hover:text-slate-600 border border-transparent hover:border-slate-100">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-slate-400">
                                <i data-lucide="calendar-x" class="w-12 h-12 opacity-20 mb-4 mx-auto"></i>
                                <p class="text-sm font-medium">No appointments to show for this view.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($view === 'all'): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctxTrends = document.getElementById('appointmentTrends').getContext('2d');
        new Chart(ctxTrends, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Bookings',
                    data: [<?php echo $trend_data_str; ?>],
                    borderColor: '#0d9488',
                    backgroundColor: '#0d948810',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
<?php endif; ?>

<?php require_once 'components/footer.php'; ?>
