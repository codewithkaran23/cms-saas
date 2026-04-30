<?php
// admin/index.php
require_once '../core/init.php';
Auth::protect('Super Admin');

$db = getDB();

// Advanced Metrics
$total_clinics = $db->query("SELECT COUNT(*) FROM clinics WHERE deleted_at IS NULL")->fetchColumn();
$active_clinics = $db->query("SELECT COUNT(*) FROM clinics WHERE deleted_at IS NULL AND status = 'active'")->fetchColumn();
$total_users = $db->query("SELECT COUNT(*) FROM users WHERE deleted_at IS NULL")->fetchColumn();
$recent_clinics = $db->query("SELECT * FROM clinics WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Monthly Growth (Mock data for now, would be SQL in production)
$growth = [65, 78, 90, 115, 142, 180];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Command Center | CMS SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#f8fafc] flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-slate-900 text-white p-8 sticky top-0 h-screen flex flex-col shadow-2xl">
        <div class="mb-12">
            <h1 class="text-2xl font-black tracking-tighter flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-600 rounded-lg"></span>
                CMS <span class="text-blue-500">ADMIN</span>
            </h1>
        </div>
        <nav class="space-y-2 flex-1">
            <a href="index.php" class="flex items-center gap-4 py-4 px-6 bg-blue-600 rounded-2xl font-bold shadow-lg shadow-blue-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="clinics.php" class="flex items-center gap-4 py-4 px-6 text-slate-400 hover:bg-white/5 rounded-2xl font-bold transition">
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

    <!-- Main Content -->
    <main class="flex-1 p-12 overflow-y-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Platform <span class="text-blue-600">Overview</span></h2>
                <p class="text-slate-500 font-medium">Monitoring clinic performance and platform growth.</p>
            </div>
            <div class="flex gap-4">
                <button class="bg-white border border-slate-200 px-6 py-3 rounded-xl font-bold text-slate-600 shadow-sm">Download Report</button>
                <a href="clinic-add.php" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-black shadow-xl shadow-blue-600/20">+ Add Clinic</a>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 -mr-8 -mt-8 rounded-full"></div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Total Clinics</p>
                <h3 class="text-5xl font-black text-slate-900 mb-2"><?php echo $total_clinics; ?></h3>
                <span class="text-green-500 font-bold text-sm">↑ 12% Growth</span>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Active Sessions</p>
                <h3 class="text-5xl font-black text-slate-900 mb-2"><?php echo $active_clinics; ?></h3>
                <span class="text-slate-400 font-bold text-sm">Real-time usage</span>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Platform Users</p>
                <h3 class="text-5xl font-black text-slate-900 mb-2"><?php echo $total_users; ?></h3>
                <span class="text-blue-500 font-bold text-sm">Doctors & Staff</span>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 bg-slate-900">
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Platform Revenue</p>
                <h3 class="text-5xl font-black text-white mb-2">$<?php echo $active_clinics * 49; ?></h3>
                <span class="text-blue-400 font-bold text-sm">Estimated MRR</span>
            </div>
        </div>

        <!-- Recent Activity & Chart -->
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-10">
                    <h4 class="text-xl font-bold">New Clinic Onboarding</h4>
                    <a href="clinics.php" class="text-blue-600 font-bold text-sm">View All Clinics</a>
                </div>
                <div class="space-y-6">
                    <?php foreach ($recent_clinics as $c): ?>
                        <div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl group hover:bg-blue-50 transition">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center font-black text-blue-600 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition">
                                    <?php echo substr($c['name'], 0, 1); ?>
                                </div>
                                <div>
                                    <h5 class="font-bold text-lg text-slate-900"><?php echo e($c['name']); ?></h5>
                                    <p class="text-slate-400 text-sm"><?php echo e($c['subdomain']); ?>.cms.local</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-10">
                                <div class="text-right">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Signed Up</p>
                                    <p class="font-bold text-slate-700"><?php echo date('M d, Y', strtotime($c['created_at'])); ?></p>
                                </div>
                                <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-widest">
                                    <?php echo e($c['status']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="bg-blue-600 p-10 rounded-[3rem] shadow-2xl shadow-blue-600/30 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="text-2xl font-black mb-8">Platform Health</h4>
                    <div class="space-y-8">
                        <div>
                            <div class="flex justify-between text-sm font-bold uppercase tracking-widest mb-2 opacity-60">
                                <span>Server Uptime</span>
                                <span>99.9%</span>
                            </div>
                            <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                                <div class="bg-white h-full w-[99.9%]"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm font-bold uppercase tracking-widest mb-2 opacity-60">
                                <span>Database Sync</span>
                                <span>Optimized</span>
                            </div>
                            <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                                <div class="bg-white h-full w-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Abstract Design Decor -->
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>
        </div>
    </main>

</body>
</html>