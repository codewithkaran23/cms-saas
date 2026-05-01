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

<div class="max-w-5xl">
    <header class="mb-10">
        <h2 class="text-4xl font-black text-slate-900 tracking-tight">Clinic <span class="text-primary">Settings</span></h2>
        <p class="text-slate-500 mt-2">Manage your clinic identity, working hours, and subscription.</p>
    </header>

    <!-- Tabs Navigation -->
    <div class="flex items-center gap-2 mb-10 overflow-x-auto pb-2">
        <?php 
        $tabs = [
            'general' => ['label' => 'General Info', 'icon' => '🏢'],
            'website' => ['label' => 'Website Builder', 'icon' => '🎨'],
            'hours'   => ['label' => 'Working Hours', 'icon' => '🕒'],
            'staff'   => ['label' => 'Staff / Doctors', 'icon' => '👨‍⚕️'],
            'billing' => ['label' => 'Subscription', 'icon' => '💳'],
        ];
        foreach ($tabs as $id => $data):
            $is_active = ($tab === $id);
        ?>
            <a href="?tab=<?php echo $id; ?>" class="flex items-center gap-3 px-6 py-3.5 rounded-2xl font-bold transition-all whitespace-nowrap <?php echo $is_active ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-105' : 'bg-white text-slate-600 border border-slate-100 hover:bg-slate-50'; ?>">
                <span><?php echo $data['icon']; ?></span>
                <?php echo $data['label']; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($success): ?>
        <div class="bg-green-600 text-white p-6 rounded-[2rem] mb-8 font-bold shadow-xl shadow-green-600/20 flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
            <span class="text-2xl">✅</span>
            <?php echo e($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="?tab=<?php echo $tab; ?>" class="space-y-8 pb-20">
        
        <?php if ($tab === 'general'): ?>
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                <h4 class="text-xl font-black text-slate-900 mb-8">Clinic Identity</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Clinic Name</label>
                        <input type="text" name="name" value="<?php echo e($clinic_data['name']); ?>" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-bold text-lg">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Theme Color</label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="primary_color" value="<?php echo e($clinic_data['primary_color']); ?>" class="w-20 h-14 bg-slate-50 border-none rounded-2xl cursor-pointer">
                            <code class="bg-slate-50 px-4 py-3 rounded-xl text-slate-500 font-mono text-sm"><?php echo e($clinic_data['primary_color']); ?></code>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Clinic Phone</label>
                        <input type="text" name="phone" value="<?php echo e($config['phone'] ?? ''); ?>" placeholder="+1 (555) 000-0000" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-bold text-lg">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Public Email</label>
                        <input type="email" name="email" value="<?php echo e($config['email'] ?? ''); ?>" placeholder="contact@clinic.com" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-bold text-lg">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Subdomain</label>
                        <div class="flex items-center">
                            <input type="text" disabled value="<?php echo e($clinic_data['subdomain']); ?>" class="flex-1 bg-slate-100 border-none px-6 py-4 rounded-l-2xl font-bold text-slate-500 outline-none">
                            <span class="bg-slate-200 px-6 py-4 rounded-r-2xl text-slate-600 font-bold">.curalite.com</span>
                        </div>
                        <p class="text-[10px] text-slate-400 italic ml-1">Contact support to change your subdomain.</p>
                    </div>
                </div>
            </div>

        <?php elseif ($tab === 'website'): ?>
            <!-- Website Builder Section -->
            <div class="space-y-8">
                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                    <h4 class="text-xl font-black text-slate-900 mb-8">Homepage Hero</h4>
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Hero Title</label>
                        <input type="text" name="hero_title" value="<?php echo e($config['hero_title'] ?? ''); ?>" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-black text-2xl tracking-tight">
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                    <h4 class="text-xl font-black text-slate-900 mb-8">About Section</h4>
                    <textarea name="about" rows="4" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition leading-relaxed text-lg"><?php echo e($config['about'] ?? ''); ?></textarea>
                </div>

                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                    <h4 class="text-xl font-black text-slate-900 mb-8">Services Provided</h4>
                    <div id="services-container" class="space-y-6">
                        <?php 
                        $existing_services = $config['services'] ?? [['title' => '', 'desc' => '']];
                        foreach ($existing_services as $s): 
                        ?>
                            <div class="p-8 bg-slate-50 rounded-[2rem] grid grid-cols-1 md:grid-cols-3 gap-8 items-end relative group">
                                <div class="md:col-span-1 space-y-2">
                                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest">Service Name</label>
                                    <input type="text" name="service_title[]" value="<?php echo e($s['title']); ?>" class="w-full bg-white border-none px-5 py-3 rounded-xl outline-none focus:ring-2 focus:ring-primary font-bold">
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest">Description</label>
                                    <input type="text" name="service_desc[]" value="<?php echo e($s['desc']); ?>" class="w-full bg-white border-none px-5 py-3 rounded-xl outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        <?php elseif ($tab === 'hours'): ?>
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                <h4 class="text-xl font-black text-slate-900 mb-8">Opening Hours</h4>
                <div class="space-y-4">
                    <?php 
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $saved_hours = $config['working_hours'] ?? [];
                    foreach ($days as $day): 
                        $day_key = strtolower($day);
                        $start = $saved_hours[$day_key]['start'] ?? '09:00';
                        $end = $saved_hours[$day_key]['end'] ?? '17:00';
                        $closed = isset($saved_hours[$day_key]['closed']);
                    ?>
                        <div class="flex flex-col md:flex-row md:items-center gap-6 p-6 bg-slate-50 rounded-3xl transition-all hover:bg-slate-100/50">
                            <div class="w-32">
                                <span class="font-black text-slate-900"><?php echo $day; ?></span>
                            </div>
                            <div class="flex-1 flex items-center gap-4">
                                <input type="time" name="hours[<?php echo $day_key; ?>][start]" value="<?php echo $start; ?>" class="bg-white border-none px-4 py-2 rounded-xl focus:ring-2 focus:ring-primary outline-none font-bold">
                                <span class="text-slate-400">to</span>
                                <input type="time" name="hours[<?php echo $day_key; ?>][end]" value="<?php echo $end; ?>" class="bg-white border-none px-4 py-2 rounded-xl focus:ring-2 focus:ring-primary outline-none font-bold">
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="hours[<?php echo $day_key; ?>][closed]" <?php echo $closed ? 'checked' : ''; ?> class="w-5 h-5 rounded-md text-primary focus:ring-primary">
                                <span class="text-sm font-bold text-slate-600 uppercase tracking-tight">Closed</span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($tab === 'staff'): ?>
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-8">
                    <h4 class="text-xl font-black text-slate-900">Manage Staff Members</h4>
                    <a href="doctor-add.php" class="text-primary font-bold hover:underline">+ Add Doctor</a>
                </div>
                <p class="text-slate-500 mb-10">You can manage all your doctors and staff members from the dedicated Doctors page.</p>
                <a href="doctors.php" class="inline-block bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:scale-105 transition-transform">
                    Go to Doctors Management →
                </a>
            </div>

        <?php elseif ($tab === 'billing'): ?>
            <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 right-0 p-10">
                    <span class="px-6 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-black uppercase tracking-widest">Active Plan</span>
                </div>
                <h4 class="text-xl font-black text-slate-900 mb-2">Current Subscription</h4>
                <p class="text-slate-500 mb-8 uppercase tracking-widest font-bold text-xs">Manage your billing and tier limits.</p>
                
                <div class="bg-slate-50 p-8 rounded-[2rem] border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <h5 class="text-3xl font-black text-slate-900 mb-1 capitalize"><?php echo e($clinic_data['subscription_tier']); ?> Plan</h5>
                        <p class="text-slate-500 font-medium">Billed annually • Next renewal June 2026</p>
                    </div>
                    <button class="bg-white border border-slate-200 text-slate-900 px-8 py-4 rounded-2xl font-black shadow-sm hover:bg-slate-50 transition">Upgrade Tier</button>
                </div>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 border border-slate-100 rounded-3xl">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Patients Limit</p>
                        <h6 class="text-2xl font-black text-slate-900">Unlimited</h6>
                    </div>
                    <div class="p-6 border border-slate-100 rounded-3xl">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Doctor Slots</p>
                        <h6 class="text-2xl font-black text-slate-900">5 / 10 Used</h6>
                    </div>
                    <div class="p-6 border border-slate-100 rounded-3xl">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Support</p>
                        <h6 class="text-2xl font-black text-slate-900">Priority 24/7</h6>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($tab !== 'staff' && $tab !== 'billing'): ?>
            <button type="submit" class="fixed bottom-10 right-10 bg-primary text-white px-12 py-5 rounded-2xl font-black text-xl shadow-2xl shadow-primary/40 hover:scale-105 transition-transform z-[60]">
                Save Changes
            </button>
        <?php endif; ?>
    </form>
</div>
<?php require_once 'components/footer.php'; ?>
