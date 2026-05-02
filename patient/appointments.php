<?php
// patient/appointments.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];
$clinic_id = $_SESSION['clinic_id'];

// Handle new booking (simplified)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book') {
    $doctor_id = $_POST['doctor_id'];
    $date_time = $_POST['date_time'];
    $reason = $_POST['reason'] ?? 'General Consultation';
    
    $stmt = $db->prepare("INSERT INTO appointments (clinic_id, patient_id, doctor_id, date_time, status, created_at) VALUES (?, ?, ?, ?, 'booked', NOW())");
    $stmt->execute([$clinic_id, $patient_id, $doctor_id, $date_time]);
    
    redirect('patient/appointments.php?success=1');
}

// Fetch appointments
$stmt = $db->prepare("
    SELECT a.*, d.name as doctor_name 
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    WHERE a.patient_id = ? 
    ORDER BY a.date_time DESC
");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();

// Fetch doctors for booking
$doc_stmt = $db->prepare("SELECT id, name FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Doctor')");
$doc_stmt->execute([$clinic_id]);
$doctors = $doc_stmt->fetchAll();

$page_title = "My Appointments";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Your <span class="text-teal-600">Appointments</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Manage your health schedule and view visit history.</p>
        </div>
        <button onclick="document.getElementById('bookingModal').classList.remove('hidden')" class="bg-teal-600 text-white px-8 py-3.5 rounded-2xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Book New Visit
        </button>
    </header>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-6 rounded-[2rem] flex items-center gap-4 animate-in slide-in-from-top-4">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="font-black text-sm uppercase tracking-widest">Success!</p>
                <p class="text-xs font-medium">Your appointment has been successfully scheduled.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Appointments Timeline -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 p-10 shadow-sm">
        <h4 class="text-lg font-black text-slate-900 tracking-tight mb-10">Visit History & Schedule</h4>
        
        <div class="space-y-8 relative before:absolute before:left-[1.65rem] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-50">
            <?php foreach($appointments as $app): 
                $is_past = strtotime($app['date_time']) < time();
                $status_color = 'bg-slate-100 text-slate-400';
                if ($app['status'] === 'booked') $status_color = 'bg-teal-50 text-teal-600 border border-teal-100/50';
                if ($app['status'] === 'completed') $status_color = 'bg-emerald-50 text-emerald-600 border border-emerald-100/50';
                if ($app['status'] === 'cancelled') $status_color = 'bg-red-50 text-red-400 border border-red-100/50';
            ?>
                <div class="relative pl-14 group">
                    <div class="absolute left-0 top-1.5 w-[3.5rem] h-[3.5rem] bg-white rounded-2xl border-4 border-[#f8fafc] shadow-sm flex items-center justify-center z-10 transition-transform group-hover:scale-110">
                        <div class="w-full h-full rounded-xl bg-slate-50 flex flex-col items-center justify-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase leading-none"><?php echo date('M', strtotime($app['date_time'])); ?></span>
                            <span class="text-lg font-black text-slate-900 leading-none mt-0.5"><?php echo date('d', strtotime($app['date_time'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="p-8 bg-slate-50/50 rounded-[2rem] border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:bg-white hover:shadow-xl hover:shadow-slate-200/20 transition-all">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-teal-600 border border-slate-100 shadow-sm">
                                <i data-lucide="user-check" class="w-7 h-7"></i>
                            </div>
                            <div>
                                <h5 class="text-lg font-black text-slate-900 tracking-tight">Consultation with Dr. <?php echo e($app['doctor_name']); ?></h5>
                                <div class="flex items-center gap-4 mt-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3 h-3"></i> <?php echo date('h:i A', strtotime($app['date_time'])); ?>
                                    </p>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i> Main Clinic
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo $status_color; ?>">
                                <?php echo str_replace('_', ' ', $app['status']); ?>
                            </span>
                            <button class="w-10 h-10 rounded-xl bg-white text-slate-400 flex items-center justify-center border border-slate-100 hover:text-slate-600 transition-all">
                                <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if(empty($appointments)): ?>
                <div class="text-center py-20 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
                    <i data-lucide="calendar" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                    <p class="text-slate-400 font-bold">No appointments found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6 backdrop-blur-md bg-slate-900/40">
    <div class="bg-white rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl animate-in zoom-in-95 duration-300">
        <div class="p-10 bg-teal-600 text-white relative">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
            <h3 class="text-2xl font-black tracking-tight relative z-10">New Appointment</h3>
            <p class="text-teal-50 text-sm font-medium mt-1 relative z-10">Select your preferred doctor and time.</p>
            <button onclick="document.getElementById('bookingModal').classList.add('hidden')" class="absolute top-8 right-8 text-white/50 hover:text-white transition-all">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <form method="POST" class="p-10 space-y-6">
            <input type="hidden" name="action" value="book">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Select Doctor</label>
                <select name="doctor_id" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all">
                    <?php foreach($doctors as $doc): ?>
                        <option value="<?php echo $doc['id']; ?>">Dr. <?php echo e($doc['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Date & Time</label>
                <input type="datetime-local" name="date_time" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Reason for Visit (Optional)</label>
                <textarea name="reason" placeholder="Briefly describe your concern..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all h-32 resize-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-teal-600 text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all mt-4">Confirm Appointment</button>
        </form>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
