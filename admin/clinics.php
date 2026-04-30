<?php
// admin/clinics.php
require_once '../core/init.php';
Auth::protect('Super Admin');

$db = getDB();

// Handle Actions (Suspend/Delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    if ($action === 'suspend') {
        $stmt = $db->prepare("UPDATE clinics SET status = 'suspended' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action === 'activate') {
        $stmt = $db->prepare("UPDATE clinics SET status = 'active' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action === 'delete') {
        $stmt = $db->prepare("UPDATE clinics SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }
    redirect('admin/clinics.php');
}

$clinics = $db->query("SELECT * FROM clinics WHERE deleted_at IS NULL ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Clinics | Command Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#f8fafc] flex min-h-screen">

    <!-- Sidebar (Same as Dashboard) -->
    <aside class="w-72 bg-slate-900 text-white p-8 sticky top-0 h-screen flex flex-col shadow-2xl">
        <div class="mb-12">
            <h1 class="text-2xl font-black tracking-tighter flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg"></span>
                CMS <span class="text-blue-500">ADMIN</span>
            </h1>
        </div>
        <nav class="space-y-2 flex-1">
            <a href="index.php" class="flex items-center gap-4 py-4 px-6 text-slate-400 hover:bg-white/5 rounded-2xl font-bold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="clinics.php" class="flex items-center gap-4 py-4 px-6 bg-blue-600 rounded-2xl font-bold shadow-lg shadow-blue-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Clinics
            </a>
            <a href="#" class="flex items-center gap-4 py-4 px-6 text-slate-400 hover:bg-white/5 rounded-2xl font-bold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Billing
            </a>
        </nav>
        <div class="pt-10 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-4 py-4 px-6 text-red-400 hover:bg-red-500/10 rounded-2xl font-bold transition">
                Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 p-12 overflow-y-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Clinic <span class="text-blue-600">Database</span></h2>
                <p class="text-slate-500 font-medium">Manage every clinic on the platform.</p>
            </div>
            <a href="clinic-add.php" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black shadow-xl shadow-blue-600/20">+ Add New Clinic</a>
        </header>

        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Clinic Detail</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Subdomain</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em] text-right">Management</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($clinics as $c): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-600/10 text-blue-600 rounded-xl flex items-center justify-center font-black">
                                        <?php echo substr($c['name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900"><?php echo e($c['name']); ?></div>
                                        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Plan: Premium</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <div class="bg-slate-100 px-3 py-1 rounded-lg text-sm font-mono text-slate-600 inline-block">
                                    <?php echo e($c['subdomain']); ?>.cms.local
                                </div>
                            </td>
                            <td class="px-8 py-8">
                                <?php if ($c['status'] === 'active'): ?>
                                    <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-xs font-black uppercase tracking-widest">Active</span>
                                <?php else: ?>
                                    <span class="px-4 py-2 bg-red-100 text-red-700 rounded-full text-xs font-black uppercase tracking-widest"><?php echo e($c['status']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-8 text-right space-x-3">
                                <?php if ($c['status'] === 'active'): ?>
                                    <a href="?action=suspend&id=<?php echo $c['id']; ?>" class="text-yellow-600 font-bold text-sm hover:underline">Suspend</a>
                                <?php else: ?>
                                    <a href="?action=activate&id=<?php echo $c['id']; ?>" class="text-green-600 font-bold text-sm hover:underline">Activate</a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $c['id']; ?>" onclick="return confirm('Archive this clinic?')" class="text-red-400 font-bold text-sm hover:underline">Archive</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
