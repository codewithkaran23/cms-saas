<?php
// patient/book.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$user_id = $_SESSION['user_id'];
$doctor_id = $_GET['doctor_id'] ?? null;

if (!$doctor_id) {
    header("Location: index.php");
    exit;
}

// Fetch doctor details
$stmt = $db->prepare("
    SELECT u.*, dp.specialization 
    FROM users u 
    JOIN doctor_profiles dp ON u.id = dp.user_id 
    WHERE u.id = ? AND u.clinic_id = ?
");
$stmt->execute([$doctor_id, $_SESSION['clinic_id']]);
$doctor = $stmt->fetch();

if (!$doctor) {
    header("Location: index.php");
    exit;
}

// Fetch current user data (for pre-filling if needed)
$user_stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user_data = $user_stmt->fetch();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $date_time = "$date $time";

    // 1. Logic Validation: Prevent past bookings
    if (strtotime($date_time) < time()) {
        $error = 'You cannot book an appointment in the past.';
    } else {
        try {
            // 2. Logic Validation: Check for Double-Booking
            $check_stmt = $db->prepare("
                SELECT COUNT(*) 
                FROM appointments 
                WHERE doctor_id = ? AND date_time = ? AND status != 'cancelled'
            ");
            $check_stmt->execute([$doctor_id, $date_time]);
            if ($check_stmt->fetchColumn() > 0) {
                $error = 'The selected time slot is already booked. Please choose another time.';
            } else {
                $db->beginTransaction();

                // 3. Create Appointment (Status is 'pending' for doctor approval)
                $app_stmt = $db->prepare("
                    INSERT INTO appointments (clinic_id, patient_id, doctor_id, date_time, status) 
                    VALUES (?, ?, ?, ?, 'pending')
                ");
                $app_stmt->execute([$_SESSION['clinic_id'], $user_id, $doctor_id, $date_time]);

                $db->commit();
                $success = "Your appointment with Dr. " . $doctor['name'] . " has been requested!";
            }
        } catch (Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            $error = "Booking failed: " . $e->getMessage();
        }
    }
}

$page_title = "Book Appointment";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-4xl mx-auto py-8 animate-in fade-in duration-700">
    <!-- Header -->
    <header class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Confirm <span class="text-teal-600">Booking</span></h2>
        <p class="text-slate-500 text-sm font-medium mt-1">Select your preferred date and time for consultation.</p>
    </header>

    <?php if ($success): ?>
        <div class="bg-white p-16 rounded-[3rem] border border-slate-100 shadow-2xl shadow-teal-500/10 text-center space-y-6 animate-in zoom-in duration-500">
            <div class="w-24 h-24 bg-emerald-500 text-white rounded-[2rem] flex items-center justify-center mx-auto shadow-xl shadow-emerald-500/20">
                <i data-lucide="check" class="w-12 h-12"></i>
            </div>
            <div>
                <h3 class="text-3xl font-black text-slate-900 italic">Request Sent!</h3>
                <p class="text-slate-500 font-medium mt-2">Dr. <?php echo e($doctor['name']); ?> will review and confirm your session shortly.</p>
            </div>
            <div class="pt-6">
                <a href="appointments.php" class="bg-slate-900 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/20 hover:bg-slate-800 transition-all inline-block">
                    View My Bookings
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Doctor Sidebar Info -->
            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm space-y-6 text-center">
                    <div class="w-24 h-24 rounded-[2rem] bg-teal-50 text-teal-600 flex items-center justify-center mx-auto text-3xl font-black italic shadow-inner">
                        <?php echo substr($doctor['name'], 0, 1); ?>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-900 italic">Dr. <?php echo e($doctor['name']); ?></h4>
                        <p class="text-teal-600 text-[10px] font-black uppercase tracking-widest mt-1"><?php echo e($doctor['specialization']); ?></p>
                    </div>
                    <div class="pt-6 border-t border-slate-50 space-y-4">
                        <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                            <i data-lucide="map-pin" class="w-4 h-4 text-slate-300"></i>
                            <span>Main Clinical Center</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                            <i data-lucide="clock" class="w-4 h-4 text-slate-300"></i>
                            <span>30-45 Mins Session</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <form method="POST" class="bg-white p-10 rounded-[2rem] border border-slate-100 shadow-sm space-y-10">
                    <?php if ($error): ?>
                        <div class="bg-red-50 border border-red-100 text-red-600 p-6 rounded-2xl flex items-center gap-4 text-xs font-black italic">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-6">
                        <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="w-6 h-6 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center italic">01</span>
                            Select Appointment Schedule
                        </h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Preferred Date</label>
                                <div class="relative">
                                    <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all font-bold text-slate-700 text-sm">
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Preferred Time</label>
                                <select name="time" required class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all font-bold text-slate-700 text-sm appearance-none cursor-pointer">
                                    <option value="">Select a slot</option>
                                    <optgroup label="Morning">
                                        <option value="09:00:00">09:00 AM</option>
                                        <option value="10:00:00">10:00 AM</option>
                                        <option value="11:00:00">11:00 AM</option>
                                    </optgroup>
                                    <optgroup label="Afternoon">
                                        <option value="14:00:00">02:00 PM</option>
                                        <option value="15:00:00">03:00 PM</option>
                                        <option value="16:00:00">04:00 PM</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50">
                        <button type="submit" class="w-full bg-teal-600 text-white py-6 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-teal-600/30 hover:bg-teal-700 hover:-translate-y-1 transition-all flex items-center justify-center gap-4">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            Request Appointment
                        </button>
                        <p class="text-[9px] text-slate-400 font-bold text-center mt-6 uppercase tracking-widest">By confirming, you agree to the clinical terms and conditions.</p>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'components/footer.php'; ?>
