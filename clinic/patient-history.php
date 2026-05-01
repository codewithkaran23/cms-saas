<?php
// clinic/patient-history.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Fetch all visits for the clinic
$stmt = $db->prepare("
    SELECT v.*, a.date_time, p.name as patient_name, d.name as doctor_name 
    FROM visits v
    JOIN appointments a ON v.appointment_id = a.id
    JOIN users p ON a.patient_id = p.id
    JOIN users d ON a.doctor_id = d.id
    WHERE a.clinic_id = ?
    ORDER BY a.date_time DESC
");
$stmt->execute([$clinic_id]);
$visits = $stmt->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="flex flex-col gap-8">
    <header class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Visit <span class="text-primary">History</span></h2>
            <p class="text-slate-500 mt-1">Complete log of all patient consultations and medical reports.</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="bg-white border border-slate-200 px-6 py-3 rounded-2xl font-bold text-slate-600 hover:bg-slate-50 transition">Export CSV</button>
        </div>
    </header>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 uppercase text-[10px] font-black tracking-[0.2em] border-b border-slate-100">
                        <th class="p-8">Date & Time</th>
                        <th class="p-8">Patient</th>
                        <th class="p-8">Doctor</th>
                        <th class="p-8">Diagnosis Summary</th>
                        <th class="p-8 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($visits as $v): ?>
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="p-8">
                                <p class="font-black text-slate-900"><?php echo date('M d, Y', strtotime($v['date_time'])); ?></p>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-tight"><?php echo date('h:i A', strtotime($v['date_time'])); ?></p>
                            </td>
                            <td class="p-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-black text-xs">
                                        <?php echo substr($v['patient_name'], 0, 1); ?>
                                    </div>
                                    <span class="font-bold text-slate-700"><?php echo e($v['patient_name']); ?></span>
                                </div>
                            </td>
                            <td class="p-8">
                                <p class="text-sm font-bold text-slate-600">Dr. <?php echo e($v['doctor_name']); ?></p>
                            </td>
                            <td class="p-8">
                                <p class="text-sm text-slate-500 line-clamp-1 max-w-xs"><?php echo e($v['diagnosis'] ?: 'No diagnosis recorded'); ?></p>
                            </td>
                            <td class="p-8 text-right">
                                <a href="patient-profile.php?id=<?php echo $v['id']; ?>" class="inline-flex items-center gap-2 text-primary font-black text-xs uppercase tracking-widest hover:underline">
                                    Full Report ↗
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($visits)): ?>
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="text-6xl mb-4">📂</div>
                                <h3 class="text-2xl font-black text-slate-900">No visit records found</h3>
                                <p class="text-slate-500 mt-2">Visits will be logged here after appointments are marked as completed.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
