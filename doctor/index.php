<?php
// clinic/index.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Stats for the cards (Real counts + some mockup trend data)
$doctors = $db->prepare("SELECT COUNT(*) FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Doctor')");
$doctors->execute([$clinic_id]);
$doctor_count = $doctors->fetchColumn();

$patients = $db->prepare("SELECT COUNT(*) FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient')");
$patients->execute([$clinic_id]);
$patient_count = $patients->fetchColumn();

$appointments = $db->prepare("SELECT COUNT(*) FROM appointments WHERE clinic_id = ?");
$appointments->execute([$clinic_id]);
$appointment_count = $appointments->fetchColumn();

// Demographics
$demo_stmt = $db->prepare("
    SELECT 
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) <= 18 THEN 1 ELSE 0 END) as age_0_18,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 19 AND 35 THEN 1 ELSE 0 END) as age_19_35,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 55 THEN 1 ELSE 0 END) as age_36_55,
        SUM(CASE WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 55 THEN 1 ELSE 0 END) as age_55_plus
    FROM patient_profiles
    WHERE clinic_id = ? AND dob IS NOT NULL
");
$demo_stmt->execute([$clinic_id]);
$demo = $demo_stmt->fetch(PDO::FETCH_ASSOC);

$total_demo = array_sum((array)$demo);
if ($total_demo > 0) {
    $demo_data = [
        round(($demo['age_0_18'] / $total_demo) * 100),
        round(($demo['age_19_35'] / $total_demo) * 100),
        round(($demo['age_36_55'] / $total_demo) * 100),
        round(($demo['age_55_plus'] / $total_demo) * 100)
    ];
} else {
    $demo_data = [0, 0, 0, 0];
}

// Chart Overview Data
$overview_stmt = $db->prepare("
    SELECT MONTH(created_at) as month, COUNT(*) as count 
    FROM users 
    WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient')
    AND YEAR(created_at) = YEAR(CURDATE())
    GROUP BY MONTH(created_at)
");
$overview_stmt->execute([$clinic_id]);
$overview_res = $overview_stmt->fetchAll(PDO::FETCH_ASSOC);

$monthly_patients = array_fill(1, 12, 0);
foreach($overview_res as $row) {
    $monthly_patients[$row['month']] = $row['count'];
}
$overview_data = implode(',', array_values($monthly_patients));
$months_labels = "['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']";

// Recent Patients
$recent_stmt = $db->prepare("
    SELECT name as patient_name, email, created_at 
    FROM users 
    WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient')
    ORDER BY created_at DESC 
    LIMIT 5
");
$recent_stmt->execute([$clinic_id]);
$recent_patients_db = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<!-- Dashboard Content -->
<div class="space-y-10 animate-in fade-in duration-700">
    
    <!-- Hero Section -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    System Operational
                </span>
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Practice <span class="text-teal-600">Overview</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Monitoring clinic performance and patient growth.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-2xl font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i> Download Logs
            </button>
            <a href="patient-add.php" class="bg-teal-600 text-white px-6 py-3 rounded-2xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Add Patient
            </a>
        </div>
    </header>
    
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 duration-300">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
                <div class="flex items-center gap-1 text-emerald-600 font-black text-[10px] bg-emerald-50 px-2 py-1 rounded-lg">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    100% Growth
                </div>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Total Patients</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1"><?php echo number_format($patient_count); ?></h3>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 duration-300">
                    <i data-lucide="zap" class="w-7 h-7"></i>
                </div>
                <div class="text-slate-400 font-black text-[10px] uppercase tracking-widest mt-2">
                    Real-time
                </div>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Active Sessions</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">1</h3>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 duration-300">
                    <i data-lucide="users-2" class="w-7 h-7"></i>
                </div>
                <div class="text-indigo-600 font-black text-[10px] uppercase tracking-widest mt-2">
                    Doctors & Staff
                </div>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Platform Users</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1"><?php echo number_format($doctor_count + 1); ?></h3>
        </div>

        <!-- Card 4 -->
        <div class="bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-900/20 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-all"></div>
            <div class="flex justify-between items-start mb-6 relative z-10">
                <div class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center backdrop-blur-md">
                    <i data-lucide="credit-card" class="w-7 h-7"></i>
                </div>
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest relative z-10">Platform MRR</p>
            <h3 class="text-3xl font-black text-white mt-1 relative z-10">$29.00</h3>
            <p class="text-[10px] font-bold text-teal-400 mt-2 relative z-10">Based on Active Plan</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Line Chart -->
        <div class="lg:col-span-3 bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h4 class="text-lg font-black text-slate-900 tracking-tight">Recently Added Patients</h4>
                    <p class="text-slate-400 text-xs font-medium mt-1">The latest patients registered at your practice.</p>
                </div>
                <a href="patients.php" class="text-teal-600 font-black text-xs hover:underline flex items-center gap-1">
                    View Directory <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            
            <div class="space-y-4">
                <?php foreach($recent_patients_db as $rp): ?>
                    <div class="flex items-center justify-between p-6 bg-slate-50/50 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-teal-600 font-black text-lg border border-slate-100 shadow-sm">
                                <?php echo strtoupper(substr($rp['patient_name'], 0, 1)); ?>
                            </div>
                            <div>
                                <h5 class="font-black text-slate-900"><?php echo e($rp['patient_name']); ?></h5>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5"><?php echo e($rp['email']); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-10">
                            <div class="text-right">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Signed Up</p>
                                <p class="text-xs font-bold text-slate-700 mt-0.5"><?php echo date('M d, Y', strtotime($rp['created_at'])); ?></p>
                            </div>
                            <span class="px-4 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase tracking-widest">Active</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- System Health Section -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h4 class="text-lg font-black text-slate-900 tracking-tight mb-2">System Health</h4>
            <p class="text-slate-400 text-xs font-medium mb-10">Infrastructure status & diagnostics.</p>

            <div class="space-y-8">
                <div class="space-y-2">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Core Database</p>
                            <h6 class="text-xs font-black text-slate-900 mt-1">SQL Sync Optimized</h6>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400">12ms</span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full" style="width: 92%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Storage Cluster</p>
                            <h6 class="text-xs font-black text-slate-900 mt-1">Assets & Media</h6>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400">49%</span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: 49%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Uptime SLA</p>
                            <h6 class="text-xs font-black text-slate-900 mt-1">Server Availability</h6>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400">99.1%</span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: 99%"></div>
                    </div>
                </div>
            </div>

            <button class="w-full mt-12 py-3 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">Run Diagnostics</button>
        </div>
    </div>

</div>

<script>
    // Patients Overview Line Chart
    const ctxOverview = document.getElementById('patientsOverview').getContext('2d');
    new Chart(ctxOverview, {
        type: 'line',
        data: {
            labels: <?php echo $months_labels; ?>,
            datasets: [{
                label: 'Patients',
                data: [<?php echo $overview_data; ?>],
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

    // Demographics Donut Chart
    const ctxDemo = document.getElementById('demographicsChart').getContext('2d');
    new Chart(ctxDemo, {
        type: 'doughnut',
        data: {
            labels: ['0-18', '19-35', '36-55', '55+'],
            datasets: [{
                data: [<?php echo implode(',', $demo_data); ?>],
                backgroundColor: ['#fb923c', '<?php echo $clinic['primary_color']; ?>', '#2dd4bf', '#94a3b8'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>

<?php require_once 'components/footer.php'; ?>
