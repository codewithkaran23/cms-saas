<?php
// clinic/components/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
$current_view = $_GET['view'] ?? '';

// Helper for Inline SVGs to prevent flickering
function get_lucide_svg($name, $class = "w-4 h-4 opacity-70") {
    $icons = [
        'layout-dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>',
        'stethoscope' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6h2a6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/></svg>',
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>',
        'folder' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/></svg>',
        'file-text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
        'bar-chart' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>',
        'settings' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.72V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
        'log-out' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
        'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>',
        'list' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        'clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>',
        'calendar-check' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>',
        'calendar-clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h5"/><path d="M17.5 17.5 16 16.3V14"/><circle cx="16" cy="16" r="6"/></svg>',
        'plus-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>',
    ];
    return str_replace('<svg ', '<svg class="' . $class . '" ', $icons[$name] ?? '');
}

$nav_items = [
    'Dashboard' => ['url' => 'index.php', 'icon' => 'layout-dashboard'],
    'Doctors' => ['url' => 'doctors.php', 'icon' => 'stethoscope'],
    'Patients' => [
        'url' => 'patients.php',
        'icon' => 'users',
        'sub_items' => [
            'list' => ['label' => 'Patient List', 'url' => 'patients.php', 'icon' => 'list'],
            'profile' => ['label' => 'Profile', 'url' => '#', 'icon' => 'user'],
            'history' => ['label' => 'Visit History', 'url' => '#', 'icon' => 'clock'],
            'search' => ['label' => 'Search', 'url' => '#', 'icon' => 'search'],
        ]
    ],
    'Appointments' => [
        'url' => 'appointments.php?view=all',
        'icon' => 'calendar',
        'sub_items' => [
            'all' => ['label' => 'All Appointments', 'url' => 'appointments.php?view=all', 'icon' => 'list'],
            'today' => ['label' => 'Today', 'url' => 'appointments.php?view=today', 'icon' => 'calendar-check'],
            'upcoming' => ['label' => 'Upcoming', 'url' => 'appointments.php?view=upcoming', 'icon' => 'calendar-clock'],
            'add' => ['label' => 'Add Appointment', 'url' => 'appointment-add.php', 'icon' => 'plus-circle'],
        ]
    ],
    'Records' => ['url' => 'records.php', 'icon' => 'folder'],
    'Billing' => ['url' => 'billing.php', 'icon' => 'file-text'],
    'Analytics' => ['url' => 'analytics.php', 'icon' => 'bar-chart'],
];

$other_items = [
    'Settings' => ['url' => 'settings.php', 'icon' => 'settings'],
];

function is_active($item, $current_page, $current_view) {
    if (isset($item['sub_items'])) {
        foreach ($item['sub_items'] as $sub) {
            if (strpos($sub['url'], $current_page) !== false) {
                if (strpos($sub['url'], 'view=') !== false) {
                    if (strpos($sub['url'], "view=$current_view") !== false) return true;
                } else {
                    return true;
                }
            }
        }
    }
    return ($item['url'] === $current_page);
}
?>

<script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<aside class="w-72 bg-[#1e293b] min-h-screen flex flex-col sticky top-0 h-screen z-50 text-slate-300 shadow-xl overflow-x-hidden no-flicker">
    <!-- Brand Area -->
    <div class="p-8 flex items-center gap-4 border-b border-slate-800/50">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white shadow-lg" style="background-color: <?php echo $clinic['primary_color']; ?>;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
        </div>
        <div class="overflow-hidden">
            <h1 class="text-lg font-bold text-white tracking-tight truncate leading-tight"><?php echo e($clinic['name']); ?></h1>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Admin Dashboard</p>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <?php foreach ($nav_items as $label => $data): ?>
            <?php 
                $has_sub = isset($data['sub_items']);
                $active = is_active($data, $current_page, $current_view);
                $active_style = $active && !$has_sub ? "background-color: {$clinic['primary_color']};" : "";
            ?>
            
            <div class="nav-group" x-data="{ open: <?php echo $active ? 'true' : 'false'; ?> }">
                <div class="flex items-center justify-between px-4 py-2.5 rounded-lg text-xs font-bold transition-all <?php echo $active && !$has_sub ? 'text-white shadow-md' : 'hover:text-white'; ?>"
                     style="<?php echo $active_style; ?>">
                    
                    <a href="<?php echo base_url('clinic/' . $data['url']); ?>" class="flex items-center gap-4 flex-1">
                        <?php echo get_lucide_svg($data['icon']); ?>
                        <?php echo $label; ?>
                    </a>

                    <?php if ($has_sub): ?>
                        <div class="cursor-pointer transition-transform" @click="open = !open" :class="open ? 'rotate-180' : ''">
                            <?php echo get_lucide_svg('chevron-down'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($has_sub): ?>
                    <div x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
                        <?php foreach ($data['sub_items'] as $id => $sub): 
                            $sub_active = (strpos($sub['url'], $current_page) !== false && (strpos($sub['url'], "view=$current_view") !== false || ($current_view === '' && $id === 'list' && $current_page === 'patients.php')));
                        ?>
                            <a href="<?php echo base_url('clinic/' . $sub['url']); ?>" 
                               class="flex items-center gap-3 px-12 py-2 text-xs font-semibold rounded-lg transition-colors <?php echo $sub_active ? 'bg-blue-600/20 text-blue-400' : 'text-slate-500 hover:text-slate-300'; ?>">
                                <?php echo isset($sub['icon']) ? get_lucide_svg($sub['icon'], "w-3.5 h-3.5 opacity-60") : ''; ?>
                                <?php echo $sub['label']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </nav>

    <!-- Others Section -->
    <div class="px-8 py-4 border-t border-slate-800/50">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Others</p>
        <div class="space-y-1 -mx-4">
            <?php foreach ($other_items as $label => $data): ?>
                <?php 
                    $active = is_active($data, $current_page, $current_view);
                    $active_style = $active ? "background-color: {$clinic['primary_color']};" : "";
                ?>
                <a href="<?php echo base_url('clinic/' . $data['url']); ?>" 
                   class="flex items-center gap-4 px-4 py-2.5 rounded-lg text-xs font-bold transition-all <?php echo $active ? 'text-white shadow-md' : 'hover:text-white'; ?>"
                   style="<?php echo $active_style; ?>">
                    <?php echo get_lucide_svg($data['icon']); ?>
                    <?php echo $label; ?>
                </a>
            <?php endforeach; ?>
            
            <a href="<?php echo base_url('super-admin/logout.php'); ?>" class="flex items-center gap-4 px-4 py-2.5 rounded-lg text-xs font-bold text-slate-400 hover:text-red-400 transition-colors">
                <?php echo get_lucide_svg('log-out'); ?>
                Logout
            </a>
        </div>
    </div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

<div class="flex-1 flex flex-col min-w-0 bg-[#f8fafc]">
    <?php require_once 'topbar.php'; ?>
    <main class="flex-1 p-8 overflow-y-auto">
