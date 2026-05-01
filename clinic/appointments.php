<?php
// clinic/appointments.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$view = $_GET['view'] ?? 'all'; // Default changed to 'all' for overview focus

// Handle status flow actions (Start, Complete, Cancel)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = $_POST['appointment_id'];
    $status = $_POST['status'];
    $upd = $db->prepare("UPDATE appointments SET status = ? WHERE id = ? AND clinic_id = ?");
    $upd->execute([$status, $id, $clinic_id]);
    redirect('clinic/appointments.php?view=' . $view);
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

// DEMO: Inject dummy data if DB is empty for UI/UX preview
if (empty($appointments)) {
    if ($view === 'today') {
        $appointments = [
            ['id' => 101, 'patient_name' => 'Omar Farooq', 'doctor_name' => 'Sarah Khan', 'date_time' => date('Y-m-d H:i:s', strtotime('10:30 AM')), 'status' => 'in_progress'],
            ['id' => 102, 'patient_name' => 'Hafsa Noor', 'doctor_name' => 'Zaid Ahmed', 'date_time' => date('Y-m-d H:i:s', strtotime('11:45 AM')), 'status' => 'booked'],
        ];
    } elseif ($view === 'upcoming') {
        $appointments = [
            ['id' => 103, 'patient_name' => 'Mohammed Ali', 'doctor_name' => 'Sarah Khan', 'date_time' => date('Y-m-d H:i:s', strtotime('+1 day 02:00 PM')), 'status' => 'booked'],
        ];
    }
}

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

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-8">
    <!-- Header with Primary Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Appointments Overview</h2>
            <p class="text-sm text-slate-500 mt-1">Detailed analytics and history of all patient bookings.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                <input type="text" placeholder="Search patient..." class="bg-white border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/10">
            </div>
            <a href="appointment-add.php" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Appointment
            </a>
        </div>
    </div>

    <!-- Analytics Cards (Only for All Overview) -->
    <?php if ($view === 'all'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                <i data-lucide="calendar" class="w-5 h-5 inline-block min-w-[1.25rem]"></i>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Bookings</p>
            <h4 class="text-2xl font-bold text-slate-900 mt-1"><?php echo number_format($as['total']); ?></h4>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center mb-4">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Completion Rate</p>
            <h4 class="text-2xl font-bold text-slate-900 mt-1">
                <?php echo $as['total'] > 0 ? round(($as['completed'] / $as['total']) * 100) : 0; ?>%
            </h4>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="w-10 h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center mb-4">
                <i data-lucide="x-circle" class="w-5 h-5"></i>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Cancellations</p>
            <h4 class="text-2xl font-bold text-slate-900 mt-1"><?php echo number_format($as['cancelled']); ?></h4>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center mb-4">
                <i data-lucide="dollar-sign" class="w-5 h-5"></i>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Value</p>
            <h4 class="text-2xl font-bold text-slate-900 mt-1">$<?php echo number_format($as['revenue']); ?></h4>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="text-lg font-bold text-slate-900 mb-6">Monthly Booking Trends</h4>
        <canvas id="appointmentTrends" height="100"></canvas>
    </div>
    <?php endif; ?>

    <!-- View Switcher Tabs -->
    <div class="flex items-center gap-2 p-1 bg-slate-100 w-fit rounded-2xl">
        <a href="?view=all" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all <?php echo $view === 'all' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'; ?>">
            All Appointments
        </a>
        <a href="?view=today" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all <?php echo $view === 'today' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'; ?>">
            Today
        </a>
        <a href="?view=upcoming" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all <?php echo $view === 'upcoming' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'; ?>">
            Upcoming
        </a>
    </div>

    <!-- Stats Row (Only for Today) -->
    <?php if ($view === 'today'): ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Today's Total</p>
            <h4 class="text-lg font-bold text-slate-900 mt-1"><?php echo $stats['total']; ?></h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-black text-green-600 uppercase tracking-widest">Completed</p>
            <h4 class="text-lg font-bold text-green-700 mt-1"><?php echo $stats['completed']; ?></h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Pending</p>
            <h4 class="text-lg font-bold text-blue-700 mt-1"><?php echo $stats['pending']; ?></h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">Cancelled</p>
            <h4 class="text-lg font-bold text-red-700 mt-1"><?php echo $stats['cancelled']; ?></h4>
        </div>
    </div>
    <?php endif; ?>

    <!-- Appointments List -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                        <th class="px-8 py-5">Patient Name</th>
                        <th class="px-6 py-5">Time Slot</th>
                        <th class="px-6 py-5">Doctor</th>
                        <th class="px-6 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Quick Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($appointments as $app): ?>
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 font-bold uppercase text-xs">
                                        <?php echo substr($app['patient_name'], 0, 1); ?>
                                    </div>
                                    <p class="text-sm font-bold text-slate-900"><?php echo e($app['patient_name']); ?></p>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-bold text-slate-900"><?php echo date('h:i A', strtotime($app['date_time'])); ?></p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase"><?php echo date('M d, Y', strtotime($app['date_time'])); ?></p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-slate-600">Dr. <?php echo e($app['doctor_name']); ?></p>
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
                            <td class="px-8 py-5 text-right">
                                <form method="POST" class="flex items-center justify-end gap-2">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="appointment_id" value="<?php echo $app['id']; ?>">
                                    
                                    <?php if ($status === 'booked'): ?>
                                        <button type="submit" name="status" value="in_progress" class="px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-[10px] font-black uppercase hover:bg-blue-100 transition shadow-sm">Start</button>
                                        <button type="submit" name="status" value="cancelled" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-100 rounded-lg text-[10px] font-black uppercase hover:bg-red-100 transition shadow-sm">Cancel</button>
                                    <?php elseif ($status === 'in_progress'): ?>
                                        <button type="submit" name="status" value="completed" class="px-3 py-1.5 bg-green-50 text-green-600 border border-green-100 rounded-lg text-[10px] font-black uppercase hover:bg-green-100 transition shadow-sm">Complete</button>
                                    <?php endif; ?>
                                    <button type="button" class="p-2 hover:bg-slate-100 rounded-lg transition text-slate-400">
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
                    data: [12, 19, 15, 25],
                    borderColor: '<?php echo $clinic['primary_color']; ?>',
                    backgroundColor: '<?php echo $clinic['primary_color']; ?>20',
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
