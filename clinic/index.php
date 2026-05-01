<?php
// clinic/index.php
require_once '../core/init.php';

// Protect: Must be logged in as Clinic Admin AND assigned to a specific clinic
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Metrics for THIS clinic only
$doctor_count = $db->prepare("SELECT COUNT(*) FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Doctor')");
$doctor_count->execute([$clinic_id]);
$doctors = $doctor_count->fetchColumn();

$patient_count = $db->prepare("SELECT COUNT(*) FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient')");
$patient_count->execute([$clinic_id]);
$patients = $patient_count->fetchColumn();

$appointment_count = $db->prepare("SELECT COUNT(*) FROM appointments WHERE clinic_id = ? AND date_time >= CURDATE()");
$appointment_count->execute([$clinic_id]);
$appointments = $appointment_count->fetchColumn();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<header class="mb-12">
    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Welcome back, <?php echo e($_SESSION['user_name']); ?></h2>
    <p class="text-slate-500 mt-2">Here is what's happening at <?php echo e($clinic['name']); ?> today.</p>
</header>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <!-- Metric 1 -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition">
            <span class="text-8xl">👨‍⚕️</span>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1 relative z-10">Total Doctors</p>
        <h3 class="text-5xl font-black text-slate-900 relative z-10"><?php echo $doctors; ?></h3>
    </div>
    <!-- Metric 2 -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition">
            <span class="text-8xl">👥</span>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1 relative z-10">Total Patients</p>
        <h3 class="text-5xl font-black text-slate-900 relative z-10"><?php echo $patients; ?></h3>
    </div>
    <!-- Metric 3 -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-md transition">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition">
            <span class="text-8xl">📅</span>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1 relative z-10">Upcoming Appointments</p>
        <h3 class="text-5xl font-black text-primary relative z-10"><?php echo $appointments; ?></h3>
    </div>
</div>

<div class="bg-white p-10 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
    <div class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-primary/5 to-transparent pointer-events-none"></div>
    <h4 class="text-xl font-bold text-slate-900 mb-6 relative z-10">Quick Actions</h4>
    <div class="flex flex-wrap gap-4 relative z-10">
        <a href="patients.php" class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-md hover:bg-primary/90 transition">
            <span>+</span> Add New Patient
        </a>
        <a href="appointments.php" class="flex items-center gap-2 bg-white text-slate-700 border border-slate-200 px-6 py-3 rounded-xl font-bold hover:bg-slate-50 transition">
            <span>📅</span> View Calendar
        </a>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
