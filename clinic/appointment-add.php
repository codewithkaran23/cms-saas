<?php
// clinic/appointment-add.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$error = '';
$success = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $date_time = $_POST['date_time'];
    $notes = $_POST['notes'] ?? '';

    if (empty($patient_id) || empty($doctor_id) || empty($date_time)) {
        $error = 'Please fill in all required fields.';
    } else {
        $stmt = $db->prepare("INSERT INTO appointments (clinic_id, patient_id, doctor_id, date_time, status) VALUES (?, ?, ?, ?, 'booked')");
        if ($stmt->execute([$clinic_id, $patient_id, $doctor_id, $date_time])) {
            $success = 'Appointment scheduled successfully!';
        } else {
            $error = 'Something went wrong. Please try again.';
        }
    }
}

// Fetch Patients and Doctors for dropdowns
$patients = $db->prepare("SELECT id, name FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient') ORDER BY name ASC");
$patients->execute([$clinic_id]);
$patient_list = $patients->fetchAll();

$doctors = $db->prepare("SELECT id, name FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Doctor') ORDER BY name ASC");
$doctors->execute([$clinic_id]);
$doctor_list = $doctors->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-2xl mx-auto space-y-8">
    <header>
        <a href="appointments.php" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 font-bold text-sm transition-colors mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Appointments
        </a>
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Add New Appointment</h2>
        <p class="text-slate-500 mt-2">Schedule a new visit for a patient with an available doctor.</p>
    </header>

    <?php if ($error): ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 font-bold text-sm flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl border border-green-100 font-bold text-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        <!-- Patient Selection -->
        <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Select Patient <span class="text-red-500">*</span></label>
            <select name="patient_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold">
                <option value="">-- Choose Patient --</option>
                <?php foreach ($patient_list as $p): ?>
                    <option value="<?php echo $p['id']; ?>"><?php echo e($p['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Doctor Selection -->
        <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Assign Doctor <span class="text-red-500">*</span></label>
            <select name="doctor_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold">
                <option value="">-- Choose Doctor --</option>
                <?php foreach ($doctor_list as $d): ?>
                    <option value="<?php echo $d['id']; ?>">Dr. <?php echo e($d['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Date & Time -->
        <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Date & Time <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="date_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-semibold">
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                <i data-lucide="calendar-plus" class="w-5 h-5"></i>
                Confirm Appointment
            </button>
        </div>
    </form>
</div>

<?php require_once 'components/footer.php'; ?>
