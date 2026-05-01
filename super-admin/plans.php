<?php
// admin/plans.php
require_once '../core/init.php';
Auth::protect('Super Admin');

$db = getDB();

$plans_query = $db->query("SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY price ASC");
$plans = $plans_query->fetchAll();

// Calculate actual Total MRR
$mrr_query = $db->query("
    SELECT SUM(sp.price) 
    FROM clinics c 
    LEFT JOIN subscription_plans sp ON c.subscription_tier = sp.tier_name 
    WHERE c.deleted_at IS NULL AND c.status = 'active'
");
$total_mrr = $mrr_query->fetchColumn() ?: 0;

$active_subs = $db->query("SELECT COUNT(*) FROM clinics WHERE deleted_at IS NULL AND status = 'active'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Management | MedOS Platform</title>
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
            <a href="clinics.php" class="flex items-center gap-3 py-3 px-4 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Tenant Clinics
            </a>
            <a href="plans.php" class="flex items-center gap-3 py-3 px-4 bg-teal-50 text-primary rounded-lg font-bold border border-teal-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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

    <main class="flex-1 p-8 lg:p-12 overflow-y-auto bg-slate-50">
        <header class="mb-10">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">SaaS Plans & Billing</h2>
            <p class="text-slate-500 text-sm mt-1">Manage pricing tiers and track platform revenue.</p>
        </header>

        <!-- Revenue Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-primary p-8 rounded-xl text-white shadow-md shadow-primary/20 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <p class="text-teal-100 text-[10px] font-bold uppercase tracking-widest mb-1 relative z-10">Total MRR</p>
                <h3 class="text-5xl font-black mb-1 relative z-10">$<?php echo number_format($total_mrr, 2); ?></h3>
                <span class="text-teal-200 text-xs font-semibold relative z-10">Calculated from <?php echo $active_subs; ?> active clinics</span>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach ($plans as $p): ?>
                <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200 flex flex-col hover:border-slate-300 hover:shadow-md transition-all">
                    <h4 class="text-sm font-bold text-slate-400 mb-1 uppercase tracking-wider"><?php echo e(ucfirst($p['tier_name'])); ?></h4>
                    <div class="text-4xl font-black text-slate-900 mb-6">$<?php echo number_format($p['price'], 0); ?><span class="text-xs text-slate-400 font-bold ml-1">/ mo</span></div>
                    
                    <ul class="text-slate-600 text-sm space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span><?php echo e($p['features']); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Branded Subdomain</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Multi-role Access</span>
                        </li>
                    </ul>
                    <button class="w-full bg-slate-50 border border-slate-200 text-slate-700 font-bold py-3 rounded-lg hover:bg-slate-100 hover:text-slate-900 transition text-sm shadow-sm">Edit Plan Features</button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
