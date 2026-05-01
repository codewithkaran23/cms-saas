<?php
// clinic/patients.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Search Query
$search = $_GET['search'] ?? '';

// Fetch all patients for THIS clinic with search
$sql = "
    SELECT u.*, 
    (SELECT COUNT(*) FROM appointments WHERE patient_id = u.id) as total_appointments,
    (SELECT MAX(date_time) FROM appointments WHERE patient_id = u.id) as last_visit
    FROM users u 
    WHERE u.clinic_id = ? 
    AND u.role_id = (SELECT id FROM roles WHERE name = 'Patient')
    AND u.deleted_at IS NULL
";

$params = [$clinic_id];

if ($search) {
    $sql .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY u.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$patients = $stmt->fetchAll();

// Fetch Real Stats
$stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE clinic_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'Patient') AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$stmt->execute([$clinic_id]);
$new_this_month = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE clinic_id = ? AND status = 'completed'");
$stmt->execute([$clinic_id]);
$total_visits = $stmt->fetchColumn();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="flex flex-col gap-8">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Patients <span class="text-primary">Directory</span></h2>
            <p class="text-slate-500 mt-1">Manage and view medical history of your patients.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="patient-add.php" class="bg-primary text-white px-6 py-3.5 rounded-2xl font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform flex items-center gap-2">
                <span class="text-xl">+</span> Add New Patient
            </a>
        </div>
    </header>

    <!-- Stats Row (Optional but nice) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl">👥</div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Patients</p>
                <h3 class="text-2xl font-black text-slate-900"><?php echo count($patients); ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-2xl">📈</div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">New This Month</p>
                <h3 class="text-2xl font-black text-slate-900"><?php echo $new_this_month; ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl">📅</div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Visits</p>
                <h3 class="text-2xl font-black text-slate-900"><?php echo $total_visits; ?></h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center">
        <form class="relative flex-1 w-full">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
            <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search by name, email or phone..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-medium">
        </form>
        <div class="flex items-center gap-2">
            <button class="px-6 py-4 bg-slate-50 text-slate-600 rounded-2xl font-bold hover:bg-slate-100 transition">Filter</button>
            <button class="px-6 py-4 bg-slate-50 text-slate-600 rounded-2xl font-bold hover:bg-slate-100 transition">Export</button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 uppercase text-[10px] font-black tracking-[0.2em] border-b border-slate-100">
                        <th class="p-6">Patient Details</th>
                        <th class="p-6">Contact Info</th>
                        <th class="p-6">Total Visits</th>
                        <th class="p-6">Last Visit</th>
                        <th class="p-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($patients as $p): ?>
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-600 font-black text-lg border-2 border-white shadow-sm group-hover:scale-110 transition-transform">
                                        <?php echo substr($p['name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-lg leading-none mb-1"><?php echo e($p['name']); ?></p>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Patient ID: #<?php echo str_pad($p['id'], 5, '0', STR_PAD_LEFT); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-slate-700 flex items-center gap-2">
                                        <span class="opacity-50">📧</span> <?php echo e($p['email']); ?>
                                    </p>
                                    <p class="text-sm font-medium text-slate-500 flex items-center gap-2">
                                        <span class="opacity-50">📞</span> <?php echo e($p['phone'] ?: 'No phone'); ?>
                                    </p>
                                </div>
                            </td>
                            <td class="p-6">
                                <span class="px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-xs font-black">
                                    <?php echo $p['total_appointments']; ?> Visits
                                </span>
                            </td>
                            <td class="p-6">
                                <p class="text-sm font-bold text-slate-700"><?php echo $p['last_visit'] ? date('M d, Y', strtotime($p['last_visit'])) : 'No visits'; ?></p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight"><?php echo $p['last_visit'] ? date('h:i A', strtotime($p['last_visit'])) : '-'; ?></p>
                            </td>
                            <td class="p-6 text-right">
                                <a href="patient-profile.php?id=<?php echo $p['id']; ?>" class="inline-flex items-center justify-center w-10 h-10 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                                    <span class="text-lg">👁️</span>
                                </a>
                                <button class="inline-flex items-center justify-center w-10 h-10 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                    <span class="text-lg">✏️</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($patients)): ?>
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-5xl mx-auto mb-6">🔍</div>
                                <h3 class="text-2xl font-black text-slate-900">No patients found</h3>
                                <p class="text-slate-500 mt-2 max-w-xs mx-auto">Try adjusting your search or add a new patient to get started.</p>
                                <a href="patient-add.php" class="inline-block mt-8 text-primary font-bold hover:underline">+ Add Your First Patient</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
