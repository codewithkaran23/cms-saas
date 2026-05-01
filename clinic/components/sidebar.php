<?php
// clinic/components/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);

$nav_items = [
    'index.php' => ['label' => 'Dashboard', 'icon' => 'grid_view'],
    'doctors.php' => ['label' => 'Doctors', 'icon' => 'medical_services'],
    'patients.php' => ['label' => 'Patients', 'icon' => 'group'],
    'appointments.php' => ['label' => 'Appointments', 'icon' => 'calendar_month'],
    'records.php' => ['label' => 'Records', 'icon' => 'folder_open'],
    'billing.php' => ['label' => 'Billing & Invoices', 'icon' => 'receipt_long'],
    'analytics.php' => ['label' => 'Analytics', 'icon' => 'bar_chart'],
];

$other_items = [
    'settings.php' => ['label' => 'Settings', 'icon' => 'settings'],
];
?>

<!-- Material Icons for the Sidebar -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- Dark Professional Sidebar -->
<aside class="w-72 bg-[#1e293b] min-h-screen flex flex-col sticky top-0 h-screen z-50 text-slate-300">
    <!-- Brand Area -->
    <div class="p-8 flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
            <span class="material-icons-round">medical_information</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Med<span class="text-blue-500">OS</span></h1>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Clinic Management</p>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="flex-1 px-4 py-4 space-y-1">
        <?php foreach ($nav_items as $url => $data): ?>
            <?php 
                $is_active = ($current_page === $url); 
                $active_classes = $is_active 
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' 
                    : 'hover:bg-slate-800 hover:text-white transition-all';
            ?>
            <a href="<?php echo base_url('clinic/' . $url); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold <?php echo $active_classes; ?>">
                <span class="material-icons-round text-xl"><?php echo $data['icon']; ?></span>
                <?php echo $data['label']; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Others Section -->
    <div class="px-8 py-4">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Others</p>
        <div class="space-y-1 -mx-4">
            <?php foreach ($other_items as $url => $data): ?>
                <?php 
                    $is_active = ($current_page === $url); 
                    $active_classes = $is_active 
                        ? 'bg-blue-600 text-white shadow-lg' 
                        : 'hover:bg-slate-800 hover:text-white transition-all';
                ?>
                <a href="<?php echo base_url('clinic/' . $url); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold <?php echo $active_classes; ?>">
                    <span class="material-icons-round text-xl"><?php echo $data['icon']; ?></span>
                    <?php echo $data['label']; ?>
                </a>
            <?php endforeach; ?>
            
            <a href="<?php echo base_url('super-admin/logout.php'); ?>" class="flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold text-slate-400 hover:text-red-400 transition-colors">
                <span class="material-icons-round text-xl">logout</span>
                Logout
            </a>
        </div>
    </div>
</aside>

<!-- Main Content Wrapper -->
<div class="flex-1 flex flex-col min-w-0 bg-[#f8fafc]">
    <!-- Topbar Component will be here -->
    <?php require_once 'topbar.php'; ?>
    
    <main class="flex-1 p-8 overflow-y-auto">
