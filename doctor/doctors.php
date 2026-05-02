<?php
// clinic/doctors.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Fetch all staff (Doctors & Receptionists) for THIS clinic
$stmt = $db->prepare("
    SELECT u.*, r.name as role_name, dp.specialization 
    FROM users u 
    JOIN roles r ON u.role_id = r.id
    LEFT JOIN doctor_profiles dp ON u.id = dp.user_id 
    WHERE u.clinic_id = ? 
    AND r.name IN ('Doctor', 'Receptionist')
    AND u.deleted_at IS NULL
");
$stmt->execute([$clinic_id]);
$doctors = $stmt->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Header Area -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Our <span class="text-teal-600">Medical Team</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Manage and view all healthcare professionals in your practice.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="staff-add.php" class="bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-2xl font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4"></i> Add New Member
            </a>
            <a href="doctor-add.php" class="bg-teal-600 text-white px-6 py-3 rounded-2xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Add New Doctor
            </a>
        </div>
    </header>

    <!-- Doctors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php foreach ($doctors as $doc): ?>
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col items-center text-center group hover:shadow-md transition-all">
                <div class="relative mb-6">
                    <div class="w-24 h-24 bg-teal-50 text-teal-600 rounded-3xl flex items-center justify-center text-3xl font-black border border-teal-100/50 group-hover:scale-110 transition-transform duration-300">
                        <?php echo substr($doc['name'], 0, 1); ?>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full"></div>
                </div>
                
                <h4 class="text-xl font-black text-slate-900"><?php echo e($doc['name']); ?></h4>
                <p class="text-teal-600 text-[10px] font-black uppercase tracking-widest mt-1 mb-4"><?php echo e($doc['role_name'] === 'Doctor' ? ($doc['specialization'] ?? 'General Physician') : $doc['role_name']); ?></p>
                
                <p class="text-slate-400 text-xs font-medium mb-8"><?php echo e($doc['email']); ?></p>
                
                <div class="flex gap-3 w-full mt-auto">
                    <button class="flex-1 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-teal-50 hover:text-teal-600 transition-all border border-transparent hover:border-teal-100/50">Schedule</button>
                    <button class="flex-1 py-3 bg-slate-50 text-slate-600 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-teal-50 hover:text-teal-600 transition-all border border-transparent hover:border-teal-100/50">Edit Profile</button>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($doctors)): ?>
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="users" class="w-10 h-10"></i>
                </div>
                <h5 class="text-slate-900 font-bold">No doctors found</h5>
                <p class="text-slate-400 text-sm mt-1">Start by adding your first medical professional.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
