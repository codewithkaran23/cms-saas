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
    } elseif ($action === 'impersonate') {
        if (Auth::impersonate($id)) {
            redirect('clinic/index.php');
        }
    } elseif ($action === 'delete') {
        $stmt = $db->prepare("UPDATE clinics SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }
    redirect('super-admin/clinics.php');
}

$clinics = $db->query("SELECT * FROM clinics WHERE deleted_at IS NULL ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Clinics | MedOS Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { 
                        primary: '#0f766e', /* teal-700 */
                        accent: '#14d1c0' 
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="flex min-h-screen bg-slate-50 text-slate-600 font-sans selection:bg-accent selection:text-slate-900 overflow-hidden">

    <!-- Crisp Light Sidebar -->
    <aside class="w-72 bg-white border-r border-slate-200 flex flex-col h-screen sticky top-0 relative z-20 shadow-sm">
        
        <div class="p-6 relative z-10 border-b border-slate-100">
            <h1 class="text-2xl font-black tracking-tighter uppercase flex items-center gap-3 text-slate-900">
                <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center text-xl shadow-md shadow-primary/20">+</div>
                MED<span class="text-primary">OS</span>
            </h1>
            <p class="text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold mt-2 pl-1">Super Admin Console</p>
        </div>
        
        <nav class="space-y-1 p-4 flex-1 relative z-10">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 pl-4">Main Menu</p>
            <a href="index.php" class="flex items-center gap-3 py-3 px-4 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="clinics.php" class="flex items-center gap-3 py-3 px-4 bg-teal-50 text-primary rounded-lg font-bold border border-teal-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Tenant Clinics
            </a>
            <a href="plans.php" class="flex items-center gap-3 py-3 px-4 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                SaaS Billing
            </a>
            <a href="settings.php" class="flex items-center gap-3 py-3 px-4 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                System Config
            </a>
        </nav>
        
        <div class="p-6 border-t border-slate-100 relative z-10">
            <a href="logout.php" class="flex items-center justify-center gap-2 py-3 w-full bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg font-bold border border-red-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-8 lg:p-12 h-screen overflow-y-auto relative bg-slate-50">
        
        <!-- Header -->
        <header class="flex justify-between items-end mb-10 relative z-10">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Clinic Database</h2>
                <p class="text-slate-500 text-sm mt-1">Manage every tenant clinic on the platform.</p>
            </div>
            <a href="clinic-add.php" class="bg-primary text-white px-6 py-2.5 rounded-lg font-bold shadow-md shadow-primary/20 hover:bg-teal-800 transition-colors flex items-center gap-2 text-sm">
                <span class="text-lg">+</span> Add Clinic
            </a>
        </header>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-wider">Clinic Detail</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-wider">Subdomain</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-wider text-right">Management</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($clinics)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500 text-sm">No clinics found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($clinics as $c): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-black text-lg border border-blue-100">
                                            <?php echo substr($c['name'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm"><?php echo e($c['name']); ?></div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Plan: Premium</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="bg-slate-100 border border-slate-200 px-2 py-1 rounded text-xs font-mono text-slate-600 inline-block">
                                        <?php echo e($c['subdomain']); ?>.cms.local
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <?php if ($c['status'] === 'active'): ?>
                                        <span class="px-3 py-1 bg-green-100 border border-green-200 text-green-700 rounded-md text-[10px] font-bold uppercase tracking-wider">Active</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-yellow-100 border border-yellow-200 text-yellow-700 rounded-md text-[10px] font-bold uppercase tracking-wider"><?php echo e($c['status']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-right space-x-2">
                                    <a href="?action=impersonate&id=<?php echo $c['id']; ?>" class="text-primary font-semibold text-xs bg-teal-50 border border-teal-100 px-3 py-1.5 rounded-lg hover:bg-primary hover:text-white transition inline-block">Login as Clinic</a>
                                    
                                    <?php if ($c['status'] === 'active'): ?>
                                        <a href="?action=suspend&id=<?php echo $c['id']; ?>" class="text-yellow-600 font-semibold text-xs border border-transparent hover:border-yellow-200 px-2 py-1 rounded transition inline-block">Suspend</a>
                                    <?php else: ?>
                                        <a href="?action=activate&id=<?php echo $c['id']; ?>" class="text-green-600 font-semibold text-xs border border-transparent hover:border-green-200 px-2 py-1 rounded transition inline-block">Activate</a>
                                    <?php endif; ?>
                                    
                                    <a href="?action=delete&id=<?php echo $c['id']; ?>" onclick="return confirm('Archive this clinic?')" class="text-red-500 font-semibold text-xs border border-transparent hover:border-red-200 px-2 py-1 rounded transition inline-block">Archive</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
