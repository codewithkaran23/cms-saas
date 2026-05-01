<?php
// clinic/patients.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Fetch all patients for THIS clinic
$stmt = $db->prepare("
    SELECT * FROM users 
    WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient')
    ORDER BY created_at DESC
");
$stmt->execute([$clinic_id]);
$patients = $stmt->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<header class="mb-10 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Patients Directory</h2>
        <p class="text-slate-500 mt-2">Manage all registered patients in your clinic.</p>
    </div>
    <button class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-md hover:bg-primary/90 transition flex items-center gap-2">
        <span>+</span> Add Patient
    </button>
</header>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider border-b border-slate-200">
                    <th class="p-5 font-bold">Name</th>
                    <th class="p-5 font-bold">Email</th>
                    <th class="p-5 font-bold">Registered On</th>
                    <th class="p-5 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($patients as $p): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-5 font-bold text-slate-700 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs text-slate-600 font-bold uppercase">
                                <?php echo substr($p['name'], 0, 1); ?>
                            </div>
                            <?php echo e($p['name']); ?>
                        </td>
                        <td class="p-5 text-slate-600"><?php echo e($p['email']); ?></td>
                        <td class="p-5 text-slate-500 text-sm"><?php echo date('M d, Y', strtotime($p['created_at'])); ?></td>
                        <td class="p-5 text-right">
                            <button class="text-sm text-primary font-bold hover:underline">View Profile</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($patients)): ?>
                    <tr>
                        <td colspan="4" class="p-10 text-center text-slate-500">
                            <div class="text-4xl mb-4">👥</div>
                            <p class="font-bold text-slate-700">No patients yet.</p>
                            <p class="text-sm mt-1">When patients register, they will appear here.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
