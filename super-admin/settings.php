<?php
// admin/settings.php
require_once '../core/init.php';
Auth::protect('Super Admin');

$db = getDB();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingsToSave = [
        'smtp_host' => $_POST['smtp_host'] ?? '',
        'smtp_port' => $_POST['smtp_port'] ?? '',
        'daily_backup_enabled' => isset($_POST['daily_backup_enabled']) ? '1' : '0'
    ];

    $stmt = $db->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    foreach ($settingsToSave as $key => $val) {
        $stmt->execute([$key, $val]);
    }
    
    $success = 'System settings updated successfully!';
}

// Fetch current settings
$current_settings = [];
$settings_query = $db->query("SELECT setting_key, setting_value FROM system_settings");
while ($row = $settings_query->fetch()) {
    $current_settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Settings | MedOS Platform</title>
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
            <a href="plans.php" class="flex items-center gap-3 py-3 px-4 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                SaaS Billing
            </a>
            <a href="settings.php" class="flex items-center gap-3 py-3 px-4 bg-teal-50 text-primary rounded-lg font-bold border border-teal-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
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

    <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
        <header class="mb-10">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">System Settings</h2>
            <p class="text-slate-500 text-sm mt-1">Configure global platform parameters (SMTP, Backups, Security).</p>
        </header>

        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-8 font-semibold flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-green-500"></span> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6 max-w-4xl">
            <!-- Settings Block 1 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200">
                <h4 class="text-lg font-bold text-slate-900 mb-6">1. SMTP Email Configuration</h4>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">SMTP Host</label>
                        <input type="text" name="smtp_host" value="<?php echo e($current_settings['smtp_host'] ?? ''); ?>" placeholder="smtp.gmail.com" class="w-full bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">SMTP Port</label>
                        <input type="text" name="smtp_port" value="<?php echo e($current_settings['smtp_port'] ?? ''); ?>" placeholder="587" class="w-full bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-lg text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                </div>
            </div>

            <!-- Settings Block 2 -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200">
                <h4 class="text-lg font-bold text-slate-900 mb-6">2. Automated Backups</h4>
                <div class="flex items-center justify-between p-5 bg-slate-50 border border-slate-100 rounded-lg">
                    <div>
                        <h5 class="font-bold text-slate-900 text-sm">Daily Database Backup</h5>
                        <p class="text-xs text-slate-500 mt-0.5">Automatically backup clinic data every 24 hours.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="daily_backup_enabled" value="1" class="sr-only peer" <?php echo (isset($current_settings['daily_backup_enabled']) && $current_settings['daily_backup_enabled'] === '1') ? 'checked' : ''; ?>>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>
            </div>

            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-bold text-sm shadow-md shadow-primary/20 hover:bg-teal-800 transition-colors">Save Platform Config</button>
        </form>
    </main>

</body>
</html>
