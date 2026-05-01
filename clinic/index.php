<?php
// clinic/index.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

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

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<!-- Dashboard Grid -->
<div class="space-y-8">
    
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-500/30 transition-all">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 bg-slate-50 text-slate-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="stethoscope" class="w-5 h-5 inline-block min-w-[1.25rem]"></i>
                </div>
                <div class="flex items-center gap-1 text-green-600 font-bold text-[10px]">
                    <i data-lucide="trending-up" class="w-3 h-3 inline-block min-w-[0.75rem]"></i>
                    +9.01%
                </div>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Doctors</p>
            <h3 class="text-xl font-bold text-slate-900 mt-0.5"><?php echo number_format($doctor_count); ?></h3>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-500/30 transition-all">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 bg-slate-50 text-slate-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div class="flex items-center gap-1 text-red-500 font-bold text-[10px]">
                    <i data-lucide="trending-down" class="w-3 h-3"></i>
                    -11.01%
                </div>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Patients</p>
            <h3 class="text-xl font-bold text-slate-900 mt-0.5"><?php echo number_format($patient_count > 0 ? $patient_count : 22000); ?></h3>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div class="flex items-center gap-1 text-green-600 font-bold text-[10px]">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +8.01%
                </div>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Appointments</p>
            <h3 class="text-xl font-bold text-slate-900 mt-0.5"><?php echo number_format($appointment_count > 0 ? $appointment_count : 12900); ?></h3>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                </div>
                <div class="flex items-center gap-1 text-green-600 font-bold text-[10px]">
                    <i data-lucide="trending-up" class="w-3 h-3"></i>
                    +10.01%
                </div>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Revenue</p>
            <h3 class="text-xl font-bold text-slate-900 mt-0.5">$72,400</h3>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Line Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Patients Overview</h4>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Patient
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Inpatient
                    </div>
                </div>
            </div>
            <canvas id="patientsOverview" height="130"></canvas>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-tight mb-6">Patients Demographics</h4>
            <div class="relative h-44 flex items-center justify-center">
                <canvas id="demographicsChart"></canvas>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-3 text-[10px] font-bold">
                <div class="flex items-center gap-2 text-slate-500"><span class="w-2 h-2 rounded-full bg-orange-400"></span> 0-18 (15%)</div>
                <div class="flex items-center gap-2 text-slate-500"><span class="w-2 h-2 rounded-full bg-blue-600"></span> 19-35 (30%)</div>
                <div class="flex items-center gap-2 text-slate-500"><span class="w-2 h-2 rounded-full bg-teal-400"></span> 36-55 (40%)</div>
                <div class="flex items-center gap-2 text-slate-500"><span class="w-2 h-2 rounded-full bg-blue-300"></span> 55+ (15%)</div>
            </div>
        </div>
    </div>

    <!-- Recent Patients Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 flex justify-between items-center border-b border-slate-100">
            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Recent Patient</h4>
            <div class="flex gap-4">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                    <input type="text" placeholder="Search..." class="bg-slate-50 border border-slate-200 rounded-lg py-2 pl-10 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/10 w-64">
                </div>
                <button class="flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-800 transition">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    Filter
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                        <th class="px-8 py-4"><input type="checkbox" class="rounded border-slate-300"></th>
                        <th class="px-4 py-4">Name</th>
                        <th class="px-4 py-4">Age</th>
                        <th class="px-4 py-4">Gender</th>
                        <th class="px-4 py-4">Appointment Date</th>
                        <th class="px-4 py-4">Condition</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php 
                    $recent_patients = [
                        ['name' => 'Omar Farooq', 'age' => 33, 'gender' => 'Male', 'date' => '25 May 2025, 10:00 AM', 'condition' => 'Hypertension', 'status' => 'Critical', 'status_color' => 'bg-red-50 text-red-600'],
                        ['name' => 'Mohammed', 'age' => 22, 'gender' => 'Male', 'date' => '20 April 2025, 10:44 AM', 'condition' => 'Diabetes', 'status' => 'Stable', 'status_color' => 'bg-green-50 text-green-600'],
                        ['name' => 'Hafsa Noor', 'age' => 44, 'gender' => 'Female', 'date' => '12 June 2025, 11:00 AM', 'condition' => 'Hypertension', 'status' => 'Improving', 'status_color' => 'bg-amber-50 text-amber-600'],
                        ['name' => 'Omar Siddiqui', 'age' => 60, 'gender' => 'Male', 'date' => '18 May 2025, 12:00 AM', 'condition' => 'Arthritis', 'status' => 'Critical', 'status_color' => 'bg-red-50 text-red-600'],
                        ['name' => 'Asma Nadeema', 'age' => 49, 'gender' => 'Female', 'date' => '16 May 2025, 09:00 AM', 'condition' => 'Diabetes', 'status' => 'Stable', 'status_color' => 'bg-green-50 text-green-600'],
                    ];
                    foreach ($recent_patients as $index => $rp):
                    ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4"><input type="checkbox" class="rounded border-slate-300"></td>
                            <td class="px-4 py-4 font-bold text-slate-700"><?php echo ($index + 1) . '. ' . $rp['name']; ?></td>
                            <td class="px-4 py-4 text-slate-500"><?php echo $rp['age']; ?></td>
                            <td class="px-4 py-4 text-slate-500"><?php echo $rp['gender']; ?></td>
                            <td class="px-4 py-4 text-slate-500 text-sm"><?php echo $rp['date']; ?></td>
                            <td class="px-4 py-4 text-slate-500"><?php echo $rp['condition']; ?></td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider <?php echo $rp['status_color']; ?>">
                                    <?php echo $rp['status']; ?>
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <button class="text-slate-400 hover:text-slate-600 transition"><i data-lucide="more-vertical" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Patients Overview Line Chart
    const ctxOverview = document.getElementById('patientsOverview').getContext('2d');
    new Chart(ctxOverview, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            datasets: [{
                label: 'Patient',
                data: [40, 60, 55, 60, 45, 65, 55, 70, 60, 80],
                borderColor: '<?php echo $clinic['primary_color']; ?>',
                backgroundColor: '<?php echo $clinic['primary_color']; ?>20',
                fill: true,
                tension: 0.4
            }, {
                label: 'Inpatient',
                data: [30, 40, 35, 70, 40, 50, 75, 45, 50, 95],
                borderColor: '#64748b',
                backgroundColor: '#64748b20',
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
                data: [15, 30, 40, 15],
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
