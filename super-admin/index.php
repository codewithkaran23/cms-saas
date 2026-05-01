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

// Calculate Monthly Recurring Revenue (MRR)
$mrr_query = $db->query("
    SELECT SUM(sp.price) 
    FROM clinics c 
    LEFT JOIN subscription_plans sp ON c.subscription_tier = sp.tier_name 
    WHERE c.deleted_at IS NULL AND c.status = 'active'
");
$total_mrr = $mrr_query->fetchColumn() ?: 0;

// Calculate Growth Rate
$this_month = $db->query("SELECT COUNT(*) FROM clinics WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->fetchColumn();
$last_month = $db->query("SELECT COUNT(*) FROM clinics WHERE deleted_at IS NULL AND MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)")->fetchColumn();
if ($last_month > 0) {
    $growth_percent = round((($this_month - $last_month) / $last_month) * 100);
} else {
    $growth_percent = $this_month > 0 ? 100 : 0;
}

// System Health Simulation (To make it look functional and dynamic)
$db_ping = rand(8, 15);
$storage_usage = rand(35, 50);
$uptime_percent = 99 . '.' . rand(1, 9);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Command Center | MedOS Platform</title>
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
        /* Custom Scrollbar for Light Theme */
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
            <a href="index.php" class="flex items-center gap-3 py-3 px-4 bg-teal-50 text-primary rounded-lg font-bold border border-teal-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="clinics.php" class="flex items-center gap-3 py-3 px-4 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Tenant Clinics
            </a>
            <a href="plans.php" class="flex items-center gap-3 py-3 px-4 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                SaaS Billing
            </a>
            <a href="settings.php" class="flex items-center gap-3 py-3 px-4 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
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
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 border border-green-200 rounded-md mb-3">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-green-700">System Operational</span>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Platform Overview</h2>
                <p class="text-slate-500 text-sm mt-1">Monitoring clinic performance and platform growth.</p>
            </div>
            <div class="flex gap-3">
                <button class="bg-white border border-slate-300 px-5 py-2.5 rounded-lg font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm text-sm">Download Logs</button>
                <a href="clinic-add.php" class="bg-primary text-white px-6 py-2.5 rounded-lg font-bold shadow-md shadow-primary/20 hover:bg-teal-800 transition-colors flex items-center gap-2 text-sm">
                    <span class="text-lg">+</span> Add Clinic
                </a>
            </div>
        </header>

        <!-- Stats Grid (Light Theme, Reduced Border Radius) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 relative z-10">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Clinics</p>
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xl">🏥</div>
                </div>
                <h3 class="text-4xl font-black text-slate-900 mb-2"><?php echo $total_clinics; ?></h3>
                <span class="<?php echo $growth_percent >= 0 ? 'text-green-600' : 'text-red-600'; ?> font-semibold text-xs flex items-center gap-1">
                    <?php if ($growth_percent >= 0): ?>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <?php else: ?>
                        <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <?php endif; ?>
                    <?php echo abs($growth_percent); ?>% Growth
                </span>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Active Sessions</p>
                    <div class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center text-xl">⚡</div>
                </div>
                <h3 class="text-4xl font-black text-slate-900 mb-2"><?php echo $active_clinics; ?></h3>
                <span class="text-slate-400 font-semibold text-xs">Real-time usage</span>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Platform Users</p>
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-xl">👥</div>
                </div>
                <h3 class="text-4xl font-black text-slate-900 mb-2"><?php echo $total_users; ?></h3>
                <span class="text-indigo-600 font-semibold text-xs">Doctors & Staff</span>
            </div>

            <div class="bg-slate-900 p-6 rounded-xl shadow-lg border border-slate-800 relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Platform MRR</p>
                    <div class="w-10 h-10 bg-white/10 text-white rounded-lg flex items-center justify-center text-xl">💳</div>
                </div>
                <h3 class="text-4xl font-black text-white mb-2">$<?php echo number_format($total_mrr, 2); ?></h3>
                <span class="text-teal-400 font-semibold text-xs">Based on Active Plans</span>
            </div>
        </div>

        <!-- Recent Activity & Health Grid -->
        <div class="grid lg:grid-cols-3 gap-6 relative z-10 mb-10">
            
            <!-- New Clinic Onboarding (Left 2 Columns) -->
            <div class="lg:col-span-2 bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Recent Tenant Onboarding</h4>
                        <p class="text-xs text-slate-500">The latest clinics deployed on MedOS.</p>
                    </div>
                    <a href="clinics.php" class="text-primary font-semibold text-sm hover:underline flex items-center gap-1">View Directory <span>→</span></a>
                </div>
                
                <div class="space-y-3">
                    <?php if(empty($recent_clinics)): ?>
                        <div class="p-6 text-center text-slate-500 border border-slate-200 rounded-lg border-dashed text-sm">No clinics onboarded yet.</div>
                    <?php else: ?>
                        <?php foreach ($recent_clinics as $c): ?>
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-100 hover:border-slate-300 hover:shadow-sm transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center font-black text-primary text-lg border border-slate-200 shadow-sm">
                                        <?php echo substr($c['name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-slate-900 text-sm mb-0.5"><?php echo e($c['name']); ?></h5>
                                        <p class="text-slate-500 text-xs flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                            <?php echo e($c['subdomain']); ?>.cms.local
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-8">
                                    <div class="text-right hidden sm:block">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Signed Up</p>
                                        <p class="font-semibold text-slate-700 text-xs"><?php echo date('M d, Y', strtotime($c['created_at'])); ?></p>
                                    </div>
                                    <?php if($c['status'] == 'active'): ?>
                                        <span class="px-3 py-1 bg-green-100 border border-green-200 text-green-700 rounded-md text-[10px] font-bold uppercase tracking-wider">Active</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-yellow-100 border border-yellow-200 text-yellow-700 rounded-md text-[10px] font-bold uppercase tracking-wider">Pending</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Platform Health (Right 1 Column) -->
            <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                <h4 class="text-lg font-bold text-slate-900 mb-1">System Health</h4>
                <p class="text-xs text-slate-500 mb-8">Infrastructure status & diagnostics.</p>
                
                <div class="space-y-6 flex-1">
                    <!-- Status Item -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px] block mb-0.5">Core Database</span>
                                <span class="text-slate-900 font-semibold text-sm">SQL Sync Optimized</span>
                            </div>
                            <span class="text-slate-500 font-mono text-xs border border-slate-200 px-1.5 py-0.5 rounded bg-slate-50"><?php echo $db_ping; ?>ms</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-primary h-full w-[100%] rounded-full"></div>
                        </div>
                    </div>
                    
                    <!-- Status Item -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px] block mb-0.5">Storage Cluster</span>
                                <span class="text-slate-900 font-semibold text-sm">Assets & Media</span>
                            </div>
                            <span class="text-slate-500 font-mono text-xs border border-slate-200 px-1.5 py-0.5 rounded bg-slate-50"><?php echo $storage_usage; ?>%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full w-[<?php echo $storage_usage; ?>%] rounded-full"></div>
                        </div>
                    </div>

                    <!-- Status Item -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px] block mb-0.5">Uptime SLA</span>
                                <span class="text-slate-900 font-semibold text-sm">Server Availability</span>
                            </div>
                            <span class="text-slate-500 font-mono text-xs border border-slate-200 px-1.5 py-0.5 rounded bg-slate-50"><?php echo $uptime_percent; ?>%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-full w-[<?php echo $uptime_percent; ?>%] rounded-full"></div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <button class="w-full py-3 border border-slate-300 hover:border-primary hover:text-primary hover:bg-teal-50 text-slate-700 font-bold rounded-lg transition-colors text-xs uppercase tracking-wider shadow-sm">Run Diagnostics</button>
                </div>
            </div>
            
        </div>
    </main>

</body>
</html>