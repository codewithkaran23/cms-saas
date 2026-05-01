<?php
// clinic/settings.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Fetch current clinic settings
$stmt = $db->prepare("SELECT * FROM clinics WHERE id = ?");
$stmt->execute([$clinic_id]);
$clinic_data = $stmt->fetch();
$config = json_decode($clinic_data['config'] ?? '{}', true);

$success = '';
$tab = $_GET['tab'] ?? 'general';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($tab === 'general') {
        $name = $_POST['name'] ?? $clinic_data['name'];
        $primary_color = $_POST['primary_color'] ?? $clinic_data['primary_color'];
        
        $new_config = $config;
        $new_config['phone'] = $_POST['phone'] ?? '';
        $new_config['email'] = $_POST['email'] ?? '';
        
        $upd = $db->prepare("UPDATE clinics SET name = ?, primary_color = ?, config = ? WHERE id = ?");
        $upd->execute([$name, $primary_color, json_encode($new_config), $clinic_id]);
        
        $success = 'General settings updated successfully!';
        $clinic_data['name'] = $name;
        $clinic_data['primary_color'] = $primary_color;
        $config = $new_config;
    } 
    elseif ($tab === 'website') {
        $new_config = $config;
        $new_config['hero_title'] = $_POST['hero_title'] ?? '';
        $new_config['about'] = $_POST['about'] ?? '';
        $new_config['services'] = [];
        
        if (isset($_POST['service_title'])) {
            foreach ($_POST['service_title'] as $key => $title) {
                if (!empty($title)) {
                    $new_config['services'][] = [
                        'title' => $title,
                        'desc' => $_POST['service_desc'][$key] ?? ''
                    ];
                }
            }
        }

        $upd = $db->prepare("UPDATE clinics SET config = ? WHERE id = ?");
        $upd->execute([json_encode($new_config), $clinic_id]);
        
        $success = 'Website content updated!';
        $config = $new_config;
    }
    elseif ($tab === 'hours') {
        $new_config = $config;
        $new_config['working_hours'] = $_POST['hours'] ?? [];
        
        $upd = $db->prepare("UPDATE clinics SET config = ? WHERE id = ?");
        $upd->execute([json_encode($new_config), $clinic_id]);
        
        $success = 'Working hours updated!';
        $config = $new_config;
    }
}

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-4xl animate-in fade-in duration-500">
    <header class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Clinic <span class="text-blue-600">Settings</span></h2>
        <p class="text-slate-500 text-xs font-medium mt-1">Manage your clinic identity, working hours, and subscription.</p>
    </header>

    <!-- Balanced Tabs Navigation -->
    <div class="flex items-center gap-2 mb-8 overflow-x-auto pb-2 custom-scrollbar">
        <?php 
        $tabs = [
            'general' => ['label' => 'General Info', 'icon' => 'business'],
            'website' => ['label' => 'Website Builder', 'icon' => 'auto_fix_high'],
            'hours'   => ['label' => 'Working Hours', 'icon' => 'schedule'],
            'staff'   => ['label' => 'Staff / Doctors', 'icon' => 'badge'],
            'billing' => ['label' => 'Subscription', 'icon' => 'payments'],
        ];
        foreach ($tabs as $id => $data):
            $is_active = ($tab === $id);
        ?>
            <a href="?tab=<?php echo $id; ?>" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs transition-all whitespace-nowrap <?php echo $is_active ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'; ?>">
                <span class="material-icons-round text-lg"><?php echo $data['icon']; ?></span>
                <?php echo $data['label']; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($success): ?>
        <div class="bg-green-600 text-white p-4 rounded-xl mb-6 font-bold shadow-lg shadow-green-600/20 flex items-center gap-3 animate-in fade-in slide-in-from-top-2 duration-400 text-sm">
            <span class="material-icons-round text-xl">check_circle</span>
            <?php echo e($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="?tab=<?php echo $tab; ?>" class="space-y-6 pb-20">
        
        <?php if ($tab === 'general'): ?>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                <h4 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="material-icons-round text-blue-500 text-xl">info</span> Clinic Identity
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest ml-1">Clinic Name</label>
                        <input type="text" name="name" value="<?php echo e($clinic_data['name']); ?>" class="w-full bg-slate-50 border border-slate-100 px-4 py-2.5 rounded-xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition font-bold text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest ml-1">Theme Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="primary_color" value="<?php echo e($clinic_data['primary_color']); ?>" class="w-16 h-10 bg-slate-50 border-none rounded-lg cursor-pointer">
                            <code class="bg-slate-50 px-3 py-2 rounded-lg text-slate-500 font-mono text-[10px]"><?php echo e($clinic_data['primary_color']); ?></code>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest ml-1">Clinic Phone</label>
                        <input type="text" name="phone" value="<?php echo e($config['phone'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-4 py-2.5 rounded-xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition font-bold text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest ml-1">Public Email</label>
                        <input type="email" name="email" value="<?php echo e($config['email'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-4 py-2.5 rounded-xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition font-bold text-sm">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest ml-1">Unique Subdomain</label>
                        <div class="flex items-center">
                            <input type="text" disabled value="<?php echo e($clinic_data['subdomain']); ?>" class="flex-1 bg-slate-100 border border-slate-200 px-4 py-2.5 rounded-l-xl font-bold text-slate-500 outline-none text-sm">
                            <span class="bg-slate-200 px-4 py-2.5 rounded-r-xl text-slate-600 font-bold text-sm">.medos.com</span>
                        </div>
                        <p class="text-[9px] text-slate-400 font-medium ml-1 mt-1">Your dedicated platform address. Contact support to change this.</p>
                    </div>
                </div>
            </div>

        <?php elseif ($tab === 'website'): ?>
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                    <h4 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="material-icons-round text-blue-500 text-xl">home</span> Homepage Hero
                    </h4>
                    <div class="space-y-2">
                        <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest ml-1">Hero Title</label>
                        <input type="text" name="hero_title" value="<?php echo e($config['hero_title'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-4 py-2.5 rounded-xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition font-bold text-sm">
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                    <h4 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="material-icons-round text-blue-500 text-xl">description</span> About Section
                    </h4>
                    <textarea name="about" rows="3" class="w-full bg-slate-50 border border-slate-100 px-4 py-2.5 rounded-xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition text-sm font-medium leading-relaxed"><?php echo e($config['about'] ?? ''); ?></textarea>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-icons-round text-blue-500 text-xl">medical_information</span> Services
                        </h4>
                        <button type="button" class="text-blue-600 font-bold text-xs hover:underline">+ Add New</button>
                    </div>
                    <div id="services-container" class="space-y-4">
                        <?php 
                        $existing_services = $config['services'] ?? [['title' => '', 'desc' => '']];
                        foreach ($existing_services as $s): 
                        ?>
                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div class="md:col-span-1 space-y-1">
                                    <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest">Service Name</label>
                                    <input type="text" name="service_title[]" value="<?php echo e($s['title']); ?>" class="w-full bg-white border border-slate-200 px-4 py-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-xs font-bold">
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <label class="block text-slate-400 text-[9px] font-black uppercase tracking-widest">Description</label>
                                    <input type="text" name="service_desc[]" value="<?php echo e($s['desc']); ?>" class="w-full bg-white border border-slate-200 px-4 py-2 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-xs">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        <?php elseif ($tab === 'hours'): ?>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                <h4 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="material-icons-round text-blue-500 text-xl">schedule</span> Weekly Hours
                </h4>
                <div class="space-y-2">
                    <?php 
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $saved_hours = $config['working_hours'] ?? [];
                    foreach ($days as $day): 
                        $day_key = strtolower($day);
                        $start = $saved_hours[$day_key]['start'] ?? '09:00';
                        $end = $saved_hours[$day_key]['end'] ?? '17:00';
                        $closed = isset($saved_hours[$day_key]['closed']);
                    ?>
                        <div class="flex items-center gap-4 p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                            <span class="w-24 text-xs font-bold text-slate-700"><?php echo $day; ?></span>
                            <div class="flex-1 flex items-center gap-2">
                                <input type="time" name="hours[<?php echo $day_key; ?>][start]" value="<?php echo $start; ?>" class="bg-white border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold">
                                <span class="text-slate-300 font-bold text-[9px]">TO</span>
                                <input type="time" name="hours[<?php echo $day_key; ?>][end]" value="<?php echo $end; ?>" class="bg-white border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-bold">
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="hours[<?php echo $day_key; ?>][closed]" <?php echo $closed ? 'checked' : ''; ?> class="w-4 h-4 rounded text-blue-600">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Closed</span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($tab === 'staff'): ?>
            <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-200 text-center">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-blue-100">
                    <span class="material-icons-round text-3xl">badge</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-2">Staff Management</h4>
                <p class="text-slate-500 text-sm max-w-md mx-auto mb-8 font-medium">To maintain security, all staff and doctors are managed in the central directory.</p>
                <div class="flex justify-center gap-3">
                    <a href="doctors.php" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs shadow-md">Browse Directory</a>
                    <a href="doctor-add.php" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold text-xs shadow-md">Add New Staff</a>
                </div>
            </div>

        <?php elseif ($tab === 'billing'): ?>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 relative overflow-hidden">
                <div class="absolute top-6 right-8">
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[9px] font-black uppercase tracking-widest">Active Plan</span>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-1">Platform Subscription</h4>
                <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-8">Manage your medOS tier</p>
                
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h5 class="text-2xl font-black text-slate-900 capitalize"><?php echo e($clinic_data['subscription_tier']); ?> Tier</h5>
                        <p class="text-slate-400 text-[10px] font-bold mt-1">Next renewal: June 15, 2026</p>
                    </div>
                    <button class="bg-white border border-slate-200 text-slate-900 px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm">Upgrade Plan</button>
                </div>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50/50">
                        <p class="text-slate-400 text-[8px] font-black uppercase tracking-widest mb-1">Patients</p>
                        <h6 class="text-lg font-black text-slate-900">Unlimited</h6>
                    </div>
                    <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50/50">
                        <p class="text-slate-400 text-[8px] font-black uppercase tracking-widest mb-1">Doctors</p>
                        <h6 class="text-lg font-black text-slate-900">5 / 10</h6>
                    </div>
                    <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50/50">
                        <p class="text-slate-400 text-[8px] font-black uppercase tracking-widest mb-1">Support</p>
                        <h6 class="text-lg font-black text-slate-900">Priority</h6>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($tab !== 'staff' && $tab !== 'billing'): ?>
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold text-base shadow-xl shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center gap-2">
                    <span class="material-icons-round">save</span> Save All Changes
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>
<?php require_once 'components/footer.php'; ?>
