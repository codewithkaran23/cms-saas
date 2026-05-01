<?php
// clinic/patient-profile.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$patient_id = $_GET['id'] ?? null;

if (!$patient_id) {
    redirect('clinic/patient-search.php');
}

// Fetch patient data
$stmt = $db->prepare("
    SELECT * FROM users 
    WHERE id = ? AND clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient')
");
$stmt->execute([$patient_id, $clinic_id]);
$patient = $stmt->fetch();

if (!$patient) {
    redirect('clinic/patients.php');
}

// Fetch visit history (from visits joined with appointments)
$stmt = $db->prepare("
    SELECT v.*, a.date_time, u.name as doctor_name 
    FROM visits v
    JOIN appointments a ON v.appointment_id = a.id
    JOIN users u ON a.doctor_id = u.id
    WHERE a.patient_id = ? AND a.clinic_id = ?
    ORDER BY a.date_time DESC
");
$stmt->execute([$patient_id, $clinic_id]);
$visits = $stmt->fetchAll();

// Fetch upcoming appointments
$stmt = $db->prepare("
    SELECT a.*, u.name as doctor_name 
    FROM appointments a
    JOIN users u ON a.doctor_id = u.id
    WHERE a.patient_id = ? AND a.clinic_id = ? 
    AND a.date_time > NOW()
    AND a.status != 'cancelled'
    ORDER BY a.date_time ASC
");
$stmt->execute([$patient_id, $clinic_id]);
$upcoming = $stmt->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="flex flex-col gap-8">
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="patients.php" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-primary hover:border-primary transition-all">←</a>
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Patient <span class="text-primary">Profile</span></h2>
                <p class="text-slate-500 mt-1">Viewing records for <?php echo e($patient['name']); ?></p>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-slate-200 px-6 py-3 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition">Edit Info</button>
            <button class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-primary/20 hover:opacity-90 transition">+ New Visit</button>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Patient Info Card -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-primary/10 to-primary/5 text-primary rounded-[2rem] flex items-center justify-center text-4xl font-black mx-auto mb-6 border-4 border-white shadow-xl">
                    <?php echo substr($patient['name'], 0, 1); ?>
                </div>
                <h3 class="text-2xl font-black text-slate-900"><?php echo e($patient['name']); ?></h3>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mt-1">Patient #<?php echo str_pad($patient['id'], 5, '0', STR_PAD_LEFT); ?></p>
                
                <div class="mt-8 pt-8 border-t border-slate-50 space-y-4 text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-sm font-bold uppercase tracking-tight">Email</span>
                        <span class="text-slate-700 font-medium"><?php echo e($patient['email']); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-sm font-bold uppercase tracking-tight">Phone</span>
                        <span class="text-slate-700 font-medium"><?php echo e($patient['phone'] ?: 'N/A'); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-sm font-bold uppercase tracking-tight">Registered</span>
                        <span class="text-slate-700 font-medium"><?php echo date('M d, Y', strtotime($patient['created_at'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <h4 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                    <span class="text-xl">📅</span> Upcoming
                </h4>
                <div class="space-y-4">
                    <?php foreach ($upcoming as $app): ?>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="font-bold text-slate-900"><?php echo date('M d, Y', strtotime($app['date_time'])); ?></p>
                            <p class="text-xs text-slate-500 font-medium mt-1">With Dr. <?php echo e($app['doctor_name']); ?></p>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-[10px] font-black uppercase tracking-wider text-primary"><?php echo date('h:i A', strtotime($app['date_time'])); ?></span>
                                <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded text-[10px] font-black uppercase tracking-wider"><?php echo e($app['status']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($upcoming)): ?>
                        <p class="text-center text-slate-400 text-sm py-4">No upcoming appointments</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Visit History / Medical Records -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-xl font-black text-slate-900">Visit History</h4>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest"><?php echo count($visits); ?> Total Visits</span>
                </div>

                <div class="space-y-6">
                    <?php foreach ($visits as $v): ?>
                        <div class="relative pl-8 before:absolute before:left-0 before:top-0 before:bottom-0 before:w-px before:bg-slate-100">
                            <div class="absolute left-[-4px] top-2 w-2 h-2 rounded-full bg-primary ring-4 ring-primary/10"></div>
                            <div class="p-6 bg-slate-50/50 rounded-3xl border border-slate-100 group hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                    <div>
                                        <p class="text-lg font-black text-slate-900"><?php echo date('F d, Y', strtotime($v['date_time'])); ?></p>
                                        <p class="text-xs font-bold text-primary uppercase tracking-widest">Attended by Dr. <?php echo e($v['doctor_name']); ?></p>
                                    </div>
                                    <button class="text-primary font-bold text-sm hover:underline">View Full Report ↗</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Symptoms</label>
                                        <p class="text-slate-600 text-sm leading-relaxed"><?php echo nl2br(e($v['symptoms'])); ?></p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Diagnosis</label>
                                        <p class="text-slate-600 text-sm leading-relaxed font-bold"><?php echo nl2br(e($v['diagnosis'])); ?></p>
                                    </div>
                                </div>
                                <div class="mt-6 pt-6 border-t border-slate-100">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Prescription / Notes</label>
                                    <div class="bg-white p-4 rounded-xl text-sm text-slate-600 italic">
                                        <?php echo nl2br(e($v['prescription_notes'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($visits)): ?>
                        <div class="text-center py-20">
                            <div class="text-6xl mb-4">📂</div>
                            <h5 class="text-xl font-bold text-slate-900">No medical history found</h5>
                            <p class="text-slate-500 mt-2">Visits will appear here after appointments are completed.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'components/footer.php'; ?>
