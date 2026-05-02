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

<div class="space-y-6 animate-in fade-in duration-500">
    <!-- Header Area -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100">
                <?php if ($patient['picture_url']): ?>
                    <img src="<?php echo base_url($patient['picture_url']); ?>" class="w-full h-full rounded-xl object-cover">
                <?php else: ?>
                    <?php echo substr($patient['name'], 0, 1); ?>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800"><?php echo e($patient['name']); ?></h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Medical History • ID: <?php echo e($patient['id_no']); ?></p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="patients.php" class="bg-slate-100 text-slate-600 px-4 py-2 rounded-lg font-bold text-xs hover:bg-slate-200 transition-all flex items-center gap-2">
                <span class="material-icons-round text-sm">arrow_back</span> List
            </a>
            <button class="bg-orange-500 text-white px-4 py-2 rounded-lg font-bold text-xs shadow-md shadow-orange-500/20 hover:bg-orange-600 transition-all flex items-center gap-2">
                <span class="material-icons-round text-sm">add</span> New Document
            </button>
        </div>
    </header>

    <!-- Visit Timeline -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Main History List -->
        <div class="lg:col-span-8 space-y-4">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 px-2">
                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></span>
                Past Encounters (<?php echo count($history); ?>)
            </h4>

            <?php if (empty($history)): ?>
                <div class="bg-white p-12 rounded-3xl border border-dashed border-slate-300 text-center opacity-60">
                    <span class="material-icons-round text-5xl mb-3">folder_open</span>
                    <p class="text-sm font-bold">No medical records found for this patient.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($history as $h): ?>
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all group">
                            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                                        <span class="material-icons-round">medical_services</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800"><?php echo date('F j, Y', strtotime($h['date_time'])); ?></p>
                                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-0.5">Dr. <?php echo e($h['doctor_name']); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button class="text-slate-400 hover:text-blue-600 transition-all"><span class="material-icons-round text-lg">print</span></button>
                                    <button class="text-slate-400 hover:text-orange-500 transition-all"><span class="material-icons-round text-lg">edit</span></button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-50">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Symptoms</label>
                                    <p class="text-xs text-slate-600 leading-relaxed"><?php echo nl2br(e($h['symptoms'])); ?></p>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Diagnosis</label>
                                    <p class="text-xs font-bold text-slate-800 leading-relaxed"><?php echo nl2br(e($h['diagnosis'])); ?></p>
                                </div>
                                <?php if ($h['prescription_notes']): ?>
                                    <div class="md:col-span-2 space-y-1.5 bg-blue-50/50 p-4 rounded-xl border border-blue-100/50">
                                        <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest block">Prescription & Notes</label>
                                        <p class="text-xs text-slate-700 italic"><?php echo nl2br(e($h['prescription_notes'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar Summary -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-slate-900 p-8 rounded-3xl text-white shadow-xl">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center justify-between">
                    Health Summary <span class="material-icons-round text-blue-500">insights</span>
                </h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-xs">Total Visits</span>
                        <span class="font-bold"><?php echo count($history); ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-xs">Last Visit</span>
                        <span class="font-bold"><?php echo !empty($history) ? date('M d, Y', strtotime($history[0]['date_time'])) : 'N/A'; ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-xs">Primary Doctor</span>
                        <span class="font-bold"><?php echo !empty($history) ? e($history[0]['doctor_name']) : 'N/A'; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Patient Actions</h4>
                <div class="grid grid-cols-1 gap-2">
                    <a href="patient-profile.php?id=<?php echo $patient_id; ?>" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="material-icons-round text-blue-500">person</span>
                            <span class="text-xs font-bold text-slate-700">View Full Profile</span>
                        </div>
                        <span class="material-icons-round text-slate-300 group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </a>
                    <a href="patient-edit.php?id=<?php echo $patient_id; ?>" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="material-icons-round text-green-500">edit</span>
                            <span class="text-xs font-bold text-slate-700">Edit Records</span>
                        </div>
                        <span class="material-icons-round text-slate-300 group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
