<?php
// doctor/components/sidebar.php
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
        'file-plus' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="12" x2="12" y1="18" y2="12"/><line x1="9" x2="15" y1="15" y2="15"/></svg>',
    ];
    return str_replace('<svg ', '<svg class="' . $class . '" ', $icons[$name] ?? '');
}

$nav_items = [
    'Dashboard' => ['url' => 'index.php', 'icon' => 'layout-dashboard'],
    'Medical Team' => ['url' => 'doctors.php', 'icon' => 'stethoscope'],
    'Patients' => [
        'url' => 'patients.php',
        'icon' => 'users',
        'sub_items' => [
            'add' => ['label' => 'Add Patient', 'url' => 'patient-add.php', 'icon' => 'plus-circle'],
            'list' => ['label' => 'Patient List', 'url' => 'patients.php', 'icon' => 'list'],
            'add-doc' => ['label' => 'Add Document', 'url' => 'patient-document-add.php', 'icon' => 'file-plus'],
            'doc-list' => ['label' => 'Document List', 'url' => 'records.php', 'icon' => 'folder'],
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
    'Medical Records' => ['url' => 'records.php', 'icon' => 'folder'],
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

<aside class="w-72 bg-white border-r border-slate-200 min-h-screen flex flex-col sticky top-0 h-screen z-50 text-slate-600 shadow-sm overflow-x-hidden no-flicker">
    <!-- Brand Area -->
    <div class="p-8 flex flex-col gap-1 border-b border-slate-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-teal-500/20" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);">
                <i data-lucide="heart-pulse" class="w-6 h-6"></i>
            </div>
            <div class="overflow-hidden">
                <h1 class="text-xl font-black text-slate-900 tracking-tighter leading-none">MED<span class="text-teal-600">OS</span></h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Practice Manager</p>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto custom-scrollbar">
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Main Menu</p>
        <?php foreach ($nav_items as $label => $data): ?>
            <?php 
                $has_sub = isset($data['sub_items']);
                $active = is_active($data, $current_page, $current_view);
                $primary_color = '#0d9488'; // Emerald
                
                $item_class = $active && !$has_sub 
                    ? "bg-teal-50/60 text-teal-700 shadow-sm border border-teal-100/50" 
                    : "text-slate-600 hover:bg-slate-50 hover:text-slate-900 border border-transparent";
            ?>
            
            <div class="nav-group" x-data="{ open: <?php echo $active ? 'true' : 'false'; ?> }">
                <div class="flex items-center justify-between px-4 py-3 rounded-2xl text-[13px] font-bold transition-all <?php echo $item_class; ?>">
                    
                    <a href="<?php echo base_url('doctor/' . $data['url']); ?>" class="flex items-center gap-3.5 flex-1">
                        <span class="<?php echo $active && !$has_sub ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-600'; ?>">
                            <?php echo get_lucide_svg($data['icon'], "w-5 h-5"); ?>
                        </span>
                        <?php echo $label; ?>
                    </a>

                    <?php if ($has_sub): ?>
                        <div class="cursor-pointer transition-transform opacity-40 hover:opacity-100" @click="open = !open" :class="open ? 'rotate-180' : ''">
                            <?php echo get_lucide_svg('chevron-down', "w-4 h-4"); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($has_sub): ?>
                    <div x-show="open" x-collapse x-cloak class="mt-1 ml-4 border-l-2 border-slate-100/80 space-y-1">
                        <?php foreach ($data['sub_items'] as $id => $sub): 
                            $sub_active = false;
                            $parsed_url = parse_url($sub['url']);
                            $sub_page = basename($parsed_url['path'] ?? '');
                            parse_str($parsed_url['query'] ?? '', $sub_query);
                            
                            if ($sub_page === $current_page) {
                                if (isset($sub_query['view'])) {
                                    $sub_active = ($current_view === $sub_query['view']);
                                } else {
                                    $sub_active = ($current_view === '');
                                }
                            }
                        ?>
                            <a href="<?php echo base_url('doctor/' . $sub['url']); ?>" 
                               class="flex items-center gap-3 px-8 py-2.5 text-[12px] font-bold transition-colors <?php echo $sub_active ? 'text-teal-600' : 'text-slate-400 hover:text-slate-600'; ?>">
                                <span class="w-1.5 h-1.5 rounded-full <?php echo $sub_active ? 'bg-teal-500' : 'bg-slate-200'; ?>"></span>
                                <?php echo $sub['label']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </nav>

    <!-- Footer Area -->
    <div class="p-6">
        <a href="<?php echo base_url('logout.php'); ?>" class="flex items-center justify-center gap-3 px-4 py-3.5 rounded-2xl text-[13px] font-bold text-red-500 bg-red-50 hover:bg-red-100 transition-all border border-red-100/50">
            <?php echo get_lucide_svg('log-out', "w-5 h-5"); ?>
            Sign Out
        </a>
    </div>
</aside>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }
[x-cloak] { display: none !important; }
</style>

<div class="flex-1 flex flex-col min-w-0 bg-[#f8fafc]">
    <?php require_once 'topbar.php'; ?>
    <main class="flex-1 p-10 overflow-y-auto">
