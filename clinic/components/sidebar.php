<?php
// clinic/components/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);

$nav_items = [
    'index.php' => ['label' => 'Dashboard', 'icon' => '📊'],
    'appointments.php' => ['label' => 'Appointments', 'icon' => '📅'],
    'patients.php' => ['label' => 'Patients', 'icon' => '👥'],
    'settings.php' => ['label' => 'Settings', 'icon' => '⚙️'],
];
?>

<!-- Premium Sidebar -->
<aside class="w-72 bg-white border-r border-slate-200 min-h-screen flex flex-col sticky top-0 h-screen shadow-sm z-50">
    <!-- Brand Area -->
    <div class="p-8 border-b border-slate-100 flex items-center gap-4">
        <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-xl shadow-inner border border-primary/20">
            🏥
        </div>
        <div>
            <h1 class="text-lg font-bold text-slate-900 leading-tight truncate w-40"><?php echo e($clinic['name']); ?></h1>
            <p class="text-xs font-semibold text-primary uppercase tracking-widest mt-1">Admin Panel</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-6 py-8 space-y-2 overflow-y-auto">
        <?php foreach ($nav_items as $url => $data): ?>
            <?php 
                $is_active = ($current_page === $url); 
                $active_classes = $is_active 
                    ? 'bg-primary text-white shadow-md shadow-primary/20' 
                    : 'text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors';
            ?>
            <a href="<?php echo base_url('clinic/' . $url); ?>" class="flex items-center gap-4 px-4 py-3.5 rounded-xl font-medium <?php echo $active_classes; ?>">
                <span class="text-xl"><?php echo $data['icon']; ?></span>
                <?php echo $data['label']; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- User Profile & Logout -->
    <div class="p-6 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center text-slate-600 font-bold uppercase border-2 border-white shadow-sm">
                    <?php echo substr($_SESSION['user_name'], 0, 1); ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-slate-900 truncate"><?php echo e($_SESSION['user_name']); ?></p>
                    <p class="text-xs text-slate-500 truncate"><?php echo e($_SESSION['user_email'] ?? 'Admin'); ?></p>
                </div>
            </div>
        </div>
        <a href="<?php echo base_url('super-admin/logout.php'); ?>" class="mt-4 flex items-center justify-center w-full py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 hover:border-red-100 hover:text-red-600 transition shadow-sm">
            Log Out
        </a>
    </div>
</aside>

<!-- Main Content Wrapper Start -->
<main class="flex-1 p-8 lg:p-12 overflow-x-hidden">
