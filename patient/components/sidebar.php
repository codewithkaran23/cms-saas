<?php
// patient/components/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
$current_view = $_GET['view'] ?? '';

// Helper for Inline SVGs to prevent flickering
function get_lucide_svg($name, $class = "w-4 h-4 opacity-70") {
    $icons = [
        'layout-dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>',
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>',
        'file-text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
        'message-square' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        'log-out' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
    ];
    return str_replace('<svg ', '<svg class="' . $class . '" ', $icons[$name] ?? '');
}

$nav_items = [
    'My Health' => ['icon' => 'layout-dashboard', 'url' => 'index.php'],
    'Bookings' => ['icon' => 'calendar', 'url' => 'appointments.php'],
    'Medical Records' => ['icon' => 'file-text', 'url' => 'records.php'],
    'Messages' => ['icon' => 'message-square', 'url' => 'messages.php'],
    'My Profile' => ['icon' => 'user', 'url' => 'settings.php']
];

function is_active($data, $current_page, $current_view) {
    $parsed = parse_url($data['url']);
    $page = basename($parsed['path'] ?? '');
    if ($page !== $current_page) return false;
    
    if (isset($data['view'])) {
        return $current_view === $data['view'];
    }
    return true;
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
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Patient Portal</p>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto custom-scrollbar">
        <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Patient Menu</p>
        <?php foreach ($nav_items as $label => $data): ?>
            <?php 
                $active = is_active($data, $current_page, $current_view);
                $item_class = $active 
                    ? "bg-teal-50/60 text-teal-700 shadow-sm border border-teal-100/50" 
                    : "text-slate-600 hover:bg-slate-50 hover:text-slate-900 border border-transparent";
            ?>
            
            <a href="<?php echo base_url('patient/' . $data['url']); ?>" class="flex items-center justify-between px-4 py-3 rounded-2xl text-[13px] font-bold transition-all <?php echo $item_class; ?>">
                <div class="flex items-center gap-3.5 flex-1">
                    <span class="<?php echo $active ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-600'; ?>">
                        <?php echo get_lucide_svg($data['icon'], "w-5 h-5"); ?>
                    </span>
                    <?php echo $label; ?>
                </div>
            </a>
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
