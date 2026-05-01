<?php
// clinic/patient-search.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$search = $_GET['search'] ?? '';
$results = [];

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

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-4xl mx-auto">
    <header class="text-center mb-12">
        <h2 class="text-4xl font-black text-slate-900 tracking-tight">Find <span class="text-primary">Patient Profile</span></h2>
        <p class="text-slate-500 mt-2 text-lg">Search by name, email or phone number to access medical records.</p>
    </header>

    <div class="bg-white p-10 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100">
        <form method="GET" class="relative group">
            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-2xl group-focus-within:scale-110 transition-transform">🔍</span>
            <input type="text" name="search" value="<?php echo e($search); ?>" autofocus placeholder="Start typing name or email..." class="w-full pl-16 pr-6 py-6 bg-slate-50 border-none rounded-[2rem] focus:ring-4 focus:ring-primary/10 outline-none transition font-bold text-xl">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 bg-primary text-white px-8 py-3 rounded-2xl font-black shadow-lg shadow-primary/20 hover:scale-105 transition">Search</button>
        </form>

        <?php if ($search): ?>
            <div class="mt-12 space-y-6">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Search Results (<?php echo count($results); ?>)</h4>
                
                <?php foreach ($results as $p): ?>
                    <a href="patient-profile.php?id=<?php echo $p['id']; ?>" class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl border border-transparent hover:border-primary hover:bg-white hover:shadow-xl transition-all group">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-2xl font-black text-primary shadow-sm group-hover:scale-110 transition-transform">
                                <?php echo substr($p['name'], 0, 1); ?>
                            </div>
                            <div>
                                <p class="text-xl font-black text-slate-900 leading-tight"><?php echo e($p['name']); ?></p>
                                <p class="text-sm font-bold text-slate-400 mt-1"><?php echo e($p['email']); ?> • <?php echo e($p['phone'] ?: 'No phone'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-primary font-black text-sm uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">View Profile →</span>
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-300 group-hover:text-primary shadow-sm">
                                👁️
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php if (empty($results)): ?>
                    <div class="text-center py-10">
                        <div class="text-5xl mb-4">📭</div>
                        <p class="text-slate-500 font-bold">No patients found matching "<?php echo e($search); ?>"</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Actions -->
    <div class="mt-12 grid grid-cols-2 gap-6">
        <a href="patient-add.php" class="bg-blue-600 p-8 rounded-[2.5rem] text-white flex flex-col items-center text-center hover:scale-105 transition-transform shadow-xl shadow-blue-600/20">
            <span class="text-3xl mb-4">👤+</span>
            <span class="font-black text-lg">Add New Patient</span>
        </a>
        <a href="patients.php" class="bg-slate-900 p-8 rounded-[2.5rem] text-white flex flex-col items-center text-center hover:scale-105 transition-transform shadow-xl shadow-slate-900/20">
            <span class="text-3xl mb-4">📋</span>
            <span class="font-black text-lg">Browse Directory</span>
        </a>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
