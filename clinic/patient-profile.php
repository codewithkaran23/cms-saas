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

// Fetch visit history
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

<div class="space-y-6 animate-in fade-in duration-500">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="patients.php" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 transition-all shadow-sm">
                <span class="material-icons-round">arrow_back</span>
            </a>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Patient <span class="text-blue-600">Profile</span></h2>
        </div>
        <div class="flex gap-2">
            <button class="bg-white border border-slate-200 px-5 py-2.5 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                <span class="material-icons-round text-base">edit</span> Edit
            </button>
            <button class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center gap-2">
                <span class="material-icons-round text-base">add</span> New Visit
            </button>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Info Column -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 text-center relative overflow-hidden">
                <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl font-black mx-auto mb-4 border border-blue-100 shadow-inner">
                    <?php echo substr($patient['name'], 0, 1); ?>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-1"><?php echo e($patient['name']); ?></h3>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">ID #<?php echo str_pad($patient['id'], 5, '0', STR_PAD_LEFT); ?></p>
                
                <div class="mt-6 pt-6 border-t border-slate-100 space-y-4 text-left">
                    <div class="flex items-center gap-3">
                        <span class="material-icons-round text-slate-300 text-lg">email</span>
                        <span class="text-sm font-semibold text-slate-700 truncate"><?php echo e($patient['email']); ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-icons-round text-slate-300 text-lg">call</span>
                        <span class="text-sm font-semibold text-slate-700"><?php echo e($patient['phone'] ?: 'N/A'); ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="material-icons-round text-slate-300 text-lg">calendar_today</span>
                        <span class="text-sm font-semibold text-slate-700">Joined <?php echo date('M Y', strtotime($patient['created_at'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Compact Upcoming -->
            <div class="bg-slate-900 p-6 rounded-3xl text-white shadow-xl shadow-slate-900/20">
                <h4 class="text-sm font-bold mb-4 flex items-center justify-between uppercase tracking-widest text-slate-400">
                    Upcoming <span class="material-icons-round text-blue-500 text-lg">event</span>
                </h4>
                <div class="space-y-3">
                    <?php foreach (array_slice($upcoming, 0, 2) as $app): ?>
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                            <p class="text-sm font-bold"><?php echo date('M d, Y', strtotime($app['date_time'])); ?></p>
                            <p class="text-[10px] text-slate-400 mt-1">Dr. <?php echo e($app['doctor_name']); ?></p>
                            <div class="mt-3 flex items-center justify-between text-[9px] font-black uppercase tracking-widest text-blue-400">
                                <span class="flex items-center gap-1"><span class="material-icons-round text-xs">schedule</span> <?php echo date('h:i A', strtotime($app['date_time'])); ?></span>
                                <span class="bg-blue-500/10 px-2 py-0.5 rounded-full">Active</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($upcoming)): ?>
                        <p class="text-slate-500 text-[10px] font-bold text-center py-4 uppercase tracking-widest">No scheduled visits</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Records Column -->
        <div class="lg:col-span-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                <h4 class="text-lg font-bold text-slate-900 mb-8 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
                    Visit History
                </h4>

                <div class="space-y-8 relative before:absolute before:left-3.5 before:top-1 before:bottom-1 before:w-px before:bg-slate-100">
                    <?php foreach ($visits as $v): ?>
                        <div class="relative pl-10 group">
                            <div class="absolute left-0 top-1 w-7 h-7 rounded-lg bg-white border-2 border-slate-100 flex items-center justify-center text-slate-300 group-hover:border-blue-600 group-hover:text-blue-600 transition-all z-10">
                                <span class="material-icons-round text-sm">medical_services</span>
                            </div>
                            
                            <div class="p-6 bg-slate-50/50 rounded-2xl border border-transparent hover:border-blue-100 hover:bg-white hover:shadow-lg hover:shadow-slate-200/50 transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-lg font-bold text-slate-900"><?php echo date('M d, Y', strtotime($v['date_time'])); ?></p>
                                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mt-0.5">Dr. <?php echo e($v['doctor_name']); ?></p>
                                    </div>
                                    <button class="bg-white px-3 py-1.5 rounded-lg text-blue-600 font-bold text-[9px] uppercase tracking-widest border border-slate-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        View Report
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                            <span class="material-icons-round text-sm">assignment</span> Symptoms
                                        </label>
                                        <p class="text-slate-600 text-xs leading-relaxed"><?php echo nl2br(e($v['symptoms'])); ?></p>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                            <span class="material-icons-round text-sm">biotech</span> Diagnosis
                                        </label>
                                        <p class="text-slate-900 font-bold text-xs leading-relaxed"><?php echo nl2br(e($v['diagnosis'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'components/footer.php'; ?>
