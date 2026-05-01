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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-round">medical_services</span>
                </div>
                <div class="flex items-center gap-1 text-green-600 font-bold text-xs">
                    <span class="material-icons-round text-sm">north_east</span>
                    +9.01%
                </div>
            </div>
            <p class="text-slate-500 text-sm font-semibold">Total Doctors</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1"><?php echo number_format($doctor_count); ?></h3>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-round">group</span>
                </div>
                <div class="flex items-center gap-1 text-red-500 font-bold text-xs">
                    <span class="material-icons-round text-sm">south_east</span>
                    -11.01%
                </div>
            </div>
            <p class="text-slate-500 text-sm font-semibold">Total Patients</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1"><?php echo number_format($patient_count > 0 ? $patient_count : 22000); ?></h3>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-round">calendar_today</span>
                </div>
                <div class="flex items-center gap-1 text-green-600 font-bold text-xs">
                    <span class="material-icons-round text-sm">north_east</span>
                    +8.01%
                </div>
            </div>
            <p class="text-slate-500 text-sm font-semibold">Total Appointments</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1"><?php echo number_format($appointment_count > 0 ? $appointment_count : 12900); ?></h3>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                    <span class="material-icons-round">payments</span>
                </div>
                <div class="flex items-center gap-1 text-green-600 font-bold text-xs">
                    <span class="material-icons-round text-sm">north_east</span>
                    +10.01%
                </div>
            </div>
            <p class="text-slate-500 text-sm font-semibold">Total Revenue</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">$72,400</h3>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Line Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-base font-bold text-slate-900">Patients Overview</h4>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-400"></span> Patient
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Inpatient
                    </div>
                </div>
            </div>
            <canvas id="patientsOverview" height="150"></canvas>
        </div>

        <!-- Donut Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h4 class="text-base font-bold text-slate-900 mb-6">Patients Demographics</h4>
            <div class="relative h-48 flex items-center justify-center">
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
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-8 flex justify-between items-center border-b border-slate-100">
            <h4 class="text-lg font-bold text-slate-900">Recent Patient</h4>
            <div class="flex gap-4">
                <div class="relative">
                    <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input type="text" placeholder="Search..." class="bg-slate-50 border border-slate-200 rounded-xl py-2 pl-10 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/10">
                </div>
                <button class="w-10 h-10 border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">
                    <span class="material-icons-round">filter_list</span>
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
                                <button class="text-slate-400 hover:text-slate-600 transition"><span class="material-icons-round">more_vert</span></button>
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
                borderColor: '#2dd4bf',
                backgroundColor: 'rgba(45, 212, 191, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Inpatient',
                data: [30, 40, 35, 70, 40, 50, 75, 45, 50, 95],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
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
                backgroundColor: ['#fb923c', '#2563eb', '#2dd4bf', '#93c5fd'],
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
