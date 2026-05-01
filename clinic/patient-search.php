<?php
// clinic/patient-search.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$search = $_GET['search'] ?? '';
$results = [];

// Handle removal from recent list
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_recent_id'])) {
    $remove_id = (int)$_POST['remove_recent_id'];
    if (!isset($_SESSION['hidden_recent_patients'])) {
        $_SESSION['hidden_recent_patients'] = [];
    }
    if (!in_array($remove_id, $_SESSION['hidden_recent_patients'])) {
        $_SESSION['hidden_recent_patients'][] = $remove_id;
    }
    // Return early or redirect to avoid form resubmission
    header("Location: patient-search.php" . ($search ? "?search=".urlencode($search) : ""));
    exit;
}

if ($search) {
    $stmt = $db->prepare("
        SELECT * FROM users 
        WHERE clinic_id = ? 
        AND role_id = (SELECT id FROM roles WHERE name = 'Patient')
        AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)
        AND deleted_at IS NULL
    ");
    $stmt->execute([$clinic_id, "%$search%", "%$search%", "%$search%"]);
    $results = $stmt->fetchAll();
}

// Fetch 4 most recent patients for quick access, excluding hidden ones
$hidden_ids = $_SESSION['hidden_recent_patients'] ?? [];
$exclude_sql = "";
$exclude_params = [$clinic_id];

if (!empty($hidden_ids)) {
    $placeholders = implode(',', array_fill(0, count($hidden_ids), '?'));
    $exclude_sql = " AND id NOT IN ($placeholders) ";
    $exclude_params = array_merge($exclude_params, $hidden_ids);
}

$stmt = $db->prepare("
    SELECT * FROM users 
    WHERE clinic_id = ? 
    AND role_id = (SELECT id FROM roles WHERE name = 'Patient')
    AND deleted_at IS NULL
    $exclude_sql
    ORDER BY created_at DESC LIMIT 4
");
$stmt->execute($exclude_params);
$recent_patients = $stmt->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-4xl mx-auto py-8 animate-in fade-in duration-500">
    <!-- Compact Header -->
    <header class="text-center mb-10">
        <div class="inline-block px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-widest mb-3 border border-blue-100">
            Intelligent Search
        </div>
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Find <span class="text-blue-600">Patient Profile</span></h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Access comprehensive medical records instantly.</p>
    </header>

    <!-- Balanced Omni-Search Container -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 mb-10">
        <form method="GET" class="relative">
            <div class="bg-slate-50/80 rounded-2xl p-2 flex items-center border border-slate-100 focus-within:border-blue-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                <div class="w-10 h-10 flex items-center justify-center text-blue-500 ml-2">
                    <span class="material-icons-round text-2xl">search</span>
                </div>
                <input type="text" name="search" value="<?php echo e($search); ?>" autofocus placeholder="Search by name, email or mobile..." class="flex-1 bg-transparent border-none py-3 text-lg font-bold text-slate-800 placeholder:text-slate-300 outline-none">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold text-base shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all mr-1">
                    Search
                </button>
            </div>
        </form>

        <?php if (!$search): ?>
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recently Registered</h4>
                    <a href="patients.php" class="text-blue-600 text-[9px] font-black uppercase tracking-widest hover:underline">View All</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <?php foreach ($recent_patients as $rp): ?>
                        <div class="relative group">
                            <a href="patient-profile.php?id=<?php echo $rp['id']; ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 pr-10">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-sm">
                                    <?php echo substr($rp['name'], 0, 1); ?>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <p class="font-bold text-slate-900 text-xs leading-none mb-1 group-hover:text-blue-600 transition-colors truncate"><?php echo e($rp['name']); ?></p>
                                    <p class="text-[10px] text-slate-400 font-medium truncate"><?php echo e($rp['email']); ?></p>
                                </div>
                            </a>
                            <!-- Remove Button -->
                            <form method="POST" class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <input type="hidden" name="remove_recent_id" value="<?php echo $rp['id']; ?>">
                                <button type="submit" class="w-7 h-7 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Remove from recent">
                                    <span class="material-icons-round text-lg">close</span>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (empty($recent_patients)): ?>
                    <p class="text-[10px] text-slate-400 font-medium text-center py-4">No recent activity to show.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($search): ?>
            <div class="mt-8 space-y-3">
                <div class="flex items-center gap-4 px-2">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Search Results (<?php echo count($results); ?>)</h4>
                    <div class="flex-1 h-px bg-slate-50"></div>
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <?php foreach ($results as $p): ?>
                        <a href="patient-profile.php?id=<?php echo $p['id']; ?>" class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-transparent hover:border-blue-200 hover:bg-white hover:shadow-md transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-lg font-black text-blue-600 border border-slate-100 shadow-sm group-hover:scale-105 transition-transform">
                                    <?php echo substr($p['name'], 0, 1); ?>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-slate-900 leading-tight group-hover:text-blue-600 transition-colors"><?php echo e($p['name']); ?></p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5"><?php echo e($p['email']); ?> • <?php echo e($p['phone'] ?: 'N/A'); ?></p>
                                </div>
                            </div>
                            <span class="material-icons-round text-slate-200 group-hover:text-blue-600 transition-all text-xl pr-2">chevron_right</span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php if (empty($results)): ?>
                    <div class="text-center py-10 opacity-50">
                        <span class="material-icons-round text-4xl">search_off</span>
                        <p class="text-xs font-bold mt-2">No matching records</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Balanced Quick Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="patient-add.php" class="bg-blue-600 p-8 rounded-3xl text-white flex flex-col items-center text-center hover:scale-[1.02] transition-all shadow-xl shadow-blue-600/20 group">
            <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-white/20 transition-all">
                <span class="material-icons-round text-3xl">person_add</span>
            </div>
            <span class="font-bold text-xl">Add New Patient</span>
            <p class="text-blue-100/70 mt-1 text-sm font-medium">Create a digital medical record</p>
        </a>
        <a href="patients.php" class="bg-slate-900 p-8 rounded-3xl text-white flex flex-col items-center text-center hover:scale-[1.02] transition-all shadow-xl shadow-slate-900/20 group">
            <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-white/20 transition-all">
                <span class="material-icons-round text-3xl">group</span>
            </div>
            <span class="font-bold text-xl">Browse Directory</span>
            <p class="text-slate-400 mt-1 text-sm font-medium">Manage all clinic patient records</p>
        </a>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
