<?php
// patient/doctors.php — Multi-Step Booking Wizard
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$patient_id = $_SESSION['user_id'];

$success = '';
$error = '';

// Handle Booking Submission (Step 3 confirm)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_doctor_id'])) {
    $doctor_id = (int) $_POST['book_doctor_id'];
    $date      = $_POST['date'] ?? '';
    $time      = $_POST['time'] ?? '';
    $reason    = trim($_POST['reason'] ?? '');
    $date_time = "$date $time";

    if (empty($date) || empty($time)) {
        $error = 'Please select both a date and a time slot.';
    } elseif (strtotime($date_time) < time()) {
        $error = 'You cannot book an appointment in the past.';
    } else {
        // Double-booking check
        $check = $db->prepare("
            SELECT COUNT(*) FROM appointments 
            WHERE doctor_id = ? AND date_time = ? AND status NOT IN ('cancelled')
        ");
        $check->execute([$doctor_id, $date_time]);

        if ($check->fetchColumn() > 0) {
            $error = 'That time slot is already booked. Please choose a different time.';
        } else {
            $ins = $db->prepare("
                INSERT INTO appointments (clinic_id, patient_id, doctor_id, date_time, reason, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'pending', NOW())
            ");
            $ins->execute([$clinic_id, $patient_id, $doctor_id, $date_time, $reason]);
            $success = 'Your appointment has been submitted! The doctor will confirm shortly.';
        }
    }
}

// Fetch all doctors
$stmt = $db->prepare("
    SELECT u.id, u.name, u.email, dp.specialization, dp.biography 
    FROM users u 
    LEFT JOIN doctor_profiles dp ON u.id = dp.user_id 
    WHERE u.clinic_id = ? AND u.role_id = (SELECT id FROM roles WHERE name = 'Doctor')
    ORDER BY u.name ASC
");
$stmt->execute([$clinic_id]);
$doctors = $stmt->fetchAll();

$page_title = "Book an Appointment";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<style>
    .wizard-step { display: none; }
    .wizard-step.active { display: block; animation: fadeUp 0.4s ease; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .doctor-card { cursor: pointer; transition: all 0.3s ease; }
    .doctor-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
    .doctor-card.selected { border-color: #0d9488 !important; box-shadow: 0 0 0 3px rgba(13,148,136,0.15), 0 20px 40px rgba(13,148,136,0.1) !important; }
    .doctor-card.selected .doc-avatar { background: #0d9488 !important; color: #fff !important; }
    .doctor-card.selected .doc-check { display: flex !important; }
    .step-dot.done { background: #0d9488 !important; color: #fff !important; }
    .step-dot.active-step { background: #0d9488 !important; color: #fff !important; box-shadow: 0 0 0 4px rgba(13,148,136,0.2); }
    .step-line.done { background: #0d9488 !important; }
    .time-slot { cursor: pointer; transition: all 0.2s ease; }
    .time-slot:hover { border-color: #0d9488; background: #f0fdfa; }
    .time-slot.selected { background: #0d9488 !important; color: #fff !important; border-color: #0d9488 !important; }
</style>

<div class="max-w-4xl mx-auto py-8 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Book an Appointment</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Follow the steps below to schedule your visit.</p>
        </div>
        <a href="appointments.php" class="text-slate-500 hover:text-slate-900 text-xs font-black uppercase tracking-widest flex items-center gap-2 transition-all border border-slate-200 px-5 py-3 rounded-xl hover:bg-slate-50">
            <i data-lucide="clock" class="w-4 h-4"></i> My Bookings
        </a>
    </div>

    <!-- Success Banner -->
    <?php if ($success): ?>
        <div class="bg-emerald-600 text-white p-8 rounded-[2rem] shadow-xl shadow-emerald-600/30 flex items-center gap-6">
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
                <i data-lucide="check-circle" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="font-black text-lg">Appointment Requested!</p>
                <p class="text-emerald-100 text-sm font-medium mt-1"><?php echo $success; ?></p>
            </div>
            <a href="appointments.php" class="ml-auto bg-white text-emerald-600 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 transition-all shrink-0">
                View Schedule
            </a>
        </div>
    <?php endif; ?>

    <!-- Error Banner -->
    <?php if ($error): ?>
        <div class="bg-red-50 border border-red-100 text-red-600 p-6 rounded-2xl flex items-center gap-4">
            <i data-lucide="alert-circle" class="w-6 h-6 shrink-0"></i>
            <p class="font-bold text-sm"><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <!-- Stepper -->
    <div class="flex items-center gap-0 bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm" id="stepper">
        <div class="flex items-center gap-3 flex-1">
            <div class="step-dot active-step w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center font-black text-sm shrink-0" id="dot-1">1</div>
            <p class="text-xs font-black text-slate-600 uppercase tracking-wider hidden sm:block" id="label-1">Choose Doctor</p>
        </div>
        <div class="step-line h-0.5 w-12 bg-slate-200 shrink-0" id="line-1"></div>
        <div class="flex items-center gap-3 flex-1">
            <div class="step-dot w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center font-black text-sm shrink-0" id="dot-2">2</div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-wider hidden sm:block" id="label-2">Date & Details</p>
        </div>
        <div class="step-line h-0.5 w-12 bg-slate-200 shrink-0" id="line-2"></div>
        <div class="flex items-center gap-3 flex-1">
            <div class="step-dot w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center font-black text-sm shrink-0" id="dot-3">3</div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-wider hidden sm:block" id="label-3">Confirm</p>
        </div>
    </div>

    <!-- ===================== STEP 1: Choose Doctor ===================== -->
    <div class="wizard-step active" id="step-1">
        <?php if (empty($doctors)): ?>
            <div class="py-20 text-center bg-white border border-dashed border-slate-200 rounded-[2rem]">
                <i data-lucide="user-x" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                <p class="text-slate-400 font-bold text-sm">No doctors registered in this clinic.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($doctors as $doc): ?>
                    <div class="doctor-card bg-white rounded-[2rem] border-2 border-slate-100 p-8 relative"
                         data-id="<?php echo $doc['id']; ?>"
                         data-name="<?php echo e($doc['name']); ?>"
                         data-spec="<?php echo e($doc['specialization'] ?: 'General Physician'); ?>"
                         data-bio="<?php echo e($doc['biography'] ?: 'Experienced specialist providing personalized clinical care.'); ?>"
                         onclick="selectDoctor(this)">
                        
                        <!-- Checkmark -->
                        <div class="doc-check hidden absolute top-6 right-6 w-8 h-8 bg-teal-600 text-white rounded-full items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </div>

                        <div class="flex items-start gap-5">
                            <div class="doc-avatar w-16 h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-2xl font-black shrink-0 transition-all">
                                <?php echo strtoupper(substr($doc['name'], 0, 1)); ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-lg font-black text-slate-900"><?php echo e($doc['name']); ?></h4>
                                <p class="text-teal-600 text-[10px] font-black uppercase tracking-[0.15em] mt-0.5">
                                    <?php echo e($doc['specialization'] ?: 'General Physician'); ?>
                                </p>
                                <p class="text-slate-400 text-xs font-medium leading-relaxed mt-3 line-clamp-2">
                                    <?php echo e($doc['biography'] ?: 'Experienced specialist providing personalized clinical care.'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-end mt-8">
                <button id="btn-to-step2" disabled onclick="goToStep(2)"
                    class="bg-teal-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-3 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-teal-600">
                    Next: Pick Date & Time <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- ===================== STEP 2: Date & Details Form ===================== -->
    <div class="wizard-step" id="step-2">
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <!-- Selected Doctor Mini Card -->
            <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex items-center gap-4">
                <div class="w-10 h-10 bg-teal-600 text-white rounded-xl flex items-center justify-center font-black text-sm" id="mini-avatar">D</div>
                <div>
                    <p class="text-sm font-black text-slate-900" id="mini-name">Doctor Name</p>
                    <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest" id="mini-spec">Specialization</p>
                </div>
                <button onclick="goToStep(1)" class="ml-auto text-xs font-black text-slate-400 hover:text-teal-600 uppercase tracking-widest transition-colors">Change</button>
            </div>

            <!-- Form -->
            <div class="p-8 space-y-8">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Preferred Date</label>
                    <input type="date" id="inp-date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                        class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all">
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Choose a Time Slot</label>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3" id="time-slots">
                        <div class="time-slot border border-slate-200 rounded-xl py-3 text-center text-xs font-bold text-slate-600" data-time="09:00:00" onclick="selectTime(this)">9:00 AM</div>
                        <div class="time-slot border border-slate-200 rounded-xl py-3 text-center text-xs font-bold text-slate-600" data-time="10:00:00" onclick="selectTime(this)">10:00 AM</div>
                        <div class="time-slot border border-slate-200 rounded-xl py-3 text-center text-xs font-bold text-slate-600" data-time="11:00:00" onclick="selectTime(this)">11:00 AM</div>
                        <div class="time-slot border border-slate-200 rounded-xl py-3 text-center text-xs font-bold text-slate-600" data-time="12:00:00" onclick="selectTime(this)">12:00 PM</div>
                        <div class="time-slot border border-slate-200 rounded-xl py-3 text-center text-xs font-bold text-slate-600" data-time="14:00:00" onclick="selectTime(this)">2:00 PM</div>
                        <div class="time-slot border border-slate-200 rounded-xl py-3 text-center text-xs font-bold text-slate-600" data-time="15:00:00" onclick="selectTime(this)">3:00 PM</div>
                        <div class="time-slot border border-slate-200 rounded-xl py-3 text-center text-xs font-bold text-slate-600" data-time="16:00:00" onclick="selectTime(this)">4:00 PM</div>
                        <div class="time-slot border border-slate-200 rounded-xl py-3 text-center text-xs font-bold text-slate-600" data-time="17:00:00" onclick="selectTime(this)">5:00 PM</div>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Reason for Visit <span class="text-slate-300">(Optional)</span></label>
                    <textarea id="inp-reason" rows="3" placeholder="Briefly describe your symptoms or reason for the visit..."
                        class="w-full bg-slate-50 border border-slate-200 px-5 py-4 rounded-2xl text-sm font-medium text-slate-700 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all resize-none"></textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-8">
            <button onclick="goToStep(1)" class="text-slate-500 hover:text-slate-900 font-black text-xs uppercase tracking-widest flex items-center gap-2 transition-all px-6 py-4 rounded-2xl border border-slate-200 hover:bg-slate-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
            </button>
            <button id="btn-to-step3" onclick="goToStep(3)"
                class="bg-teal-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-3 disabled:opacity-40 disabled:cursor-not-allowed"
                disabled>
                Next: Review & Confirm <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- ===================== STEP 3: Review & Confirm ===================== -->
    <div class="wizard-step" id="step-3">
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="bg-slate-900 text-white px-8 py-6 flex items-center gap-4">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="font-black text-sm">Appointment Summary</h4>
                    <p class="text-slate-400 text-xs font-medium">Please review your appointment details before confirming.</p>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <!-- Doctor -->
                <div class="flex items-center gap-5 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-14 h-14 bg-teal-600 text-white rounded-2xl flex items-center justify-center text-xl font-black shrink-0" id="rev-avatar">D</div>
                    <div>
                        <p class="text-base font-black text-slate-900" id="rev-name">Doctor Name</p>
                        <p class="text-teal-600 text-[10px] font-black uppercase tracking-widest" id="rev-spec">Specialization</p>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Date</p>
                        <p class="text-sm font-black text-slate-900" id="rev-date">—</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Time</p>
                        <p class="text-sm font-black text-slate-900" id="rev-time">—</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                        <p class="text-sm font-black text-amber-600">Pending Approval</p>
                    </div>
                </div>

                <!-- Reason -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100" id="rev-reason-wrap" style="display:none;">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Reason for Visit</p>
                    <p class="text-sm font-medium text-slate-700" id="rev-reason">—</p>
                </div>
            </div>
        </div>

        <!-- Hidden form for actual submission -->
        <form method="POST" id="booking-form" class="mt-8 flex items-center justify-between">
            <input type="hidden" name="book_doctor_id" id="form-doctor-id">
            <input type="hidden" name="date" id="form-date">
            <input type="hidden" name="time" id="form-time">
            <input type="hidden" name="reason" id="form-reason">

            <button type="button" onclick="goToStep(2)" class="text-slate-500 hover:text-slate-900 font-black text-xs uppercase tracking-widest flex items-center gap-2 transition-all px-6 py-4 rounded-2xl border border-slate-200 hover:bg-slate-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
            </button>
            <button type="submit" class="bg-emerald-600 text-white px-12 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/30 hover:bg-emerald-700 transition-all flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i> Confirm Appointment
            </button>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });

let selectedDoctor = null;
let selectedTime = null;

function selectDoctor(el) {
    document.querySelectorAll('.doctor-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    selectedDoctor = {
        id: el.dataset.id,
        name: el.dataset.name,
        spec: el.dataset.spec,
        bio: el.dataset.bio,
        initial: el.dataset.name.charAt(0).toUpperCase()
    };
    document.getElementById('btn-to-step2').disabled = false;
    lucide.createIcons();
}

function selectTime(el) {
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    selectedTime = el.dataset.time;
    validateStep2();
}

function validateStep2() {
    const date = document.getElementById('inp-date').value;
    document.getElementById('btn-to-step3').disabled = !(date && selectedTime);
}

document.getElementById('inp-date').addEventListener('change', validateStep2);

function goToStep(n) {
    // Validate before moving forward
    if (n === 2 && !selectedDoctor) return;
    if (n === 3) {
        const date = document.getElementById('inp-date').value;
        if (!date || !selectedTime) return;
    }

    // Update steps visibility
    document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));
    document.getElementById('step-' + n).classList.add('active');

    // Update stepper dots
    for (let i = 1; i <= 3; i++) {
        const dot = document.getElementById('dot-' + i);
        const label = document.getElementById('label-' + i);
        dot.classList.remove('done', 'active-step');
        label.classList.remove('text-slate-600');
        label.classList.add('text-slate-400');

        if (i < n) {
            dot.classList.add('done');
            dot.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        } else {
            dot.innerHTML = i;
            if (i === n) {
                dot.classList.add('active-step');
                label.classList.remove('text-slate-400');
                label.classList.add('text-slate-600');
            }
        }
    }
    // Lines
    document.getElementById('line-1').classList.toggle('done', n > 1);
    document.getElementById('line-2').classList.toggle('done', n > 2);

    // Populate Step 2 mini card
    if (n === 2 && selectedDoctor) {
        document.getElementById('mini-avatar').textContent = selectedDoctor.initial;
        document.getElementById('mini-name').textContent = selectedDoctor.name;
        document.getElementById('mini-spec').textContent = selectedDoctor.spec;
    }

    // Populate Step 3 review
    if (n === 3 && selectedDoctor) {
        const dateVal = document.getElementById('inp-date').value;
        const reason = document.getElementById('inp-reason').value.trim();

        document.getElementById('rev-avatar').textContent = selectedDoctor.initial;
        document.getElementById('rev-name').textContent = selectedDoctor.name;
        document.getElementById('rev-spec').textContent = selectedDoctor.spec;

        // Format date nicely
        const d = new Date(dateVal + 'T00:00:00');
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('rev-date').textContent = d.toLocaleDateString('en-US', opts);

        // Format time
        const [h, m] = selectedTime.split(':');
        const hour = parseInt(h);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const h12 = hour % 12 || 12;
        document.getElementById('rev-time').textContent = h12 + ':' + m + ' ' + ampm;

        // Reason
        const reasonWrap = document.getElementById('rev-reason-wrap');
        if (reason) {
            reasonWrap.style.display = 'block';
            document.getElementById('rev-reason').textContent = reason;
        } else {
            reasonWrap.style.display = 'none';
        }

        // Populate hidden form
        document.getElementById('form-doctor-id').value = selectedDoctor.id;
        document.getElementById('form-date').value = dateVal;
        document.getElementById('form-time').value = selectedTime;
        document.getElementById('form-reason').value = reason;
    }

    // Re-render icons
    lucide.createIcons();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php require_once 'components/footer.php'; ?>
