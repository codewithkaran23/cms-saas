<?php
// clinic/patient-history.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$patient_id = $_GET['id'] ?? null;

if (!$patient_id) {
    redirect('clinic/patients.php');
}

// Fetch Patient Info
$stmt = $db->prepare("
    SELECT u.name, pp.id_no, pp.picture_url 
    FROM users u 
    JOIN patient_profiles pp ON u.id = pp.user_id 
    WHERE u.id = ? AND u.clinic_id = ?
");
$stmt->execute([$patient_id, $clinic_id]);
$patient = $stmt->fetch();

if (!$patient) {
    redirect('clinic/patients.php');
}

// Fetch Visit History
$stmt = $db->prepare("
    SELECT v.*, a.date_time, u.name as doctor_name 
    FROM visits v
    JOIN appointments a ON v.appointment_id = a.id
    JOIN users u ON a.doctor_id = u.id
    WHERE a.patient_id = ? AND a.clinic_id = ?
    ORDER BY a.date_time DESC
");
$stmt->execute([$patient_id, $clinic_id]);
$history = $stmt->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header Area -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[1.5rem] bg-teal-50 text-teal-600 flex items-center justify-center font-black text-xl border border-teal-100 shadow-inner overflow-hidden">
                <?php if ($patient['picture_url']): ?>
                    <img src="<?php echo base_url($patient['picture_url']); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <?php echo substr($patient['name'], 0, 1); ?>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight"><?php echo e($patient['name']); ?></h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest rounded-md border border-slate-200">Patient ID</span>
                    <p class="text-xs font-bold text-slate-400 tracking-wider"><?php echo e($patient['id_no']); ?></p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="patients.php" class="h-12 px-6 rounded-2xl bg-slate-50 text-slate-600 font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all flex items-center gap-3 border border-slate-100">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Return to List
            </a>
            <button class="h-12 px-8 rounded-2xl bg-orange-500 text-white font-black text-[10px] uppercase tracking-widest shadow-xl shadow-orange-500/20 hover:bg-orange-600 transition-all flex items-center gap-3">
                <i data-lucide="plus" class="w-4 h-4"></i> New Document
            </button>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main History List -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-3">
                    <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse shadow-lg shadow-orange-500/50"></span>
                    Clinical Timeline (<?php echo count($history); ?>)
                </h4>
            </div>

            <?php if (empty($history)): ?>
                <div class="bg-white p-20 rounded-[3rem] border border-dashed border-slate-200 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6">
                        <i data-lucide="folder-open" class="w-10 h-10"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-400">No medical encounters archived yet.</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($history as $h): ?>
                        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:scale-[1.01] transition-all group relative overflow-hidden">
                            <div class="flex flex-col md:flex-row justify-between gap-6 mb-8">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-inner">
                                        <i data-lucide="stethoscope" class="w-7 h-7"></i>
                                    </div>
                                    <div>
                                        <p class="text-lg font-black text-slate-800 tracking-tight"><?php echo date('F j, Y', strtotime($h['date_time'])); ?></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <i data-lucide="user" class="w-3 h-3 text-teal-600"></i>
                                            <p class="text-[10px] font-black text-teal-600 uppercase tracking-widest">Dr. <?php echo e($h['doctor_name']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-teal-50 hover:text-teal-600 transition-all flex items-center justify-center border border-slate-100">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </button>
                                    <button class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-orange-50 hover:text-orange-500 transition-all flex items-center justify-center border border-slate-100">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-slate-50">
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span> Symptoms
                                    </label>
                                    <p class="text-sm text-slate-600 font-medium leading-relaxed"><?php echo nl2br(e($h['symptoms'])); ?></p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span> Diagnosis
                                    </label>
                                    <p class="text-sm font-black text-slate-800 leading-relaxed"><?php echo nl2br(e($h['diagnosis'])); ?></p>
                                </div>
                                <?php if ($h['prescription_notes']): ?>
                                    <div class="md:col-span-2 p-6 bg-teal-50/50 rounded-3xl border border-teal-100/50 flex gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-teal-600 shadow-sm flex-shrink-0">
                                            <i data-lucide="pill" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <label class="text-[9px] font-black text-teal-600 uppercase tracking-widest block mb-1">Prescription & Clinical Advice</label>
                                            <p class="text-sm text-slate-700 font-medium italic"><?php echo nl2br(e($h['prescription_notes'])); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar Summary -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-slate-900 p-10 rounded-[3rem] text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
                <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-10 flex items-center justify-between">
                    Health Summary <i data-lucide="bar-chart-2" class="w-4 h-4 text-teal-500"></i>
                </h4>
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-white/5">
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Visits</span>
                        <span class="text-2xl font-black text-white"><?php echo count($history); ?></span>
                    </div>
                    <div class="flex items-center justify-between pb-4 border-b border-white/5">
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Last Encounter</span>
                        <span class="text-sm font-black text-white"><?php echo !empty($history) ? date('M d, Y', strtotime($history[0]['date_time'])) : 'N/A'; ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Lead Doctor</span>
                        <span class="text-sm font-black text-white"><?php echo !empty($history) ? e($history[0]['doctor_name']) : 'N/A'; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Patient Actions</h4>
                <div class="space-y-3">
                    <a href="patient-profile.php?id=<?php echo $patient_id; ?>" class="flex items-center justify-between p-5 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-all group border border-transparent hover:border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-teal-600 shadow-sm">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 uppercase tracking-widest">Full Profile</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="patient-edit.php?id=<?php echo $patient_id; ?>" class="flex items-center justify-between p-5 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-all group border border-transparent hover:border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-500 shadow-sm">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 uppercase tracking-widest">Edit Records</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
