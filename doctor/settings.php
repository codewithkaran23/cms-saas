<?php
// doctor/settings.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Fetch current practice settings
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
        $new_config['address'] = $_POST['address'] ?? '';
        
        $upd = $db->prepare("UPDATE clinics SET name = ?, primary_color = ?, config = ? WHERE id = ?");
        $upd->execute([$name, $primary_color, json_encode($new_config), $clinic_id]);
        
        $success = 'General settings updated successfully!';
        $clinic_data['name'] = $name;
        $clinic_data['primary_color'] = $primary_color;
        $config = $new_config;
    } 
    elseif ($tab === 'gateway') {
        $new_config = $config;
        $new_config['hero_title'] = $_POST['hero_title'] ?? '';
        $new_config['about_text'] = $_POST['about_text'] ?? '';
        
        $upd = $db->prepare("UPDATE clinics SET config = ? WHERE id = ?");
        $upd->execute([json_encode($new_config), $clinic_id]);
        
        $success = 'Portal gateway content updated!';
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
    <header class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Practice <span class="text-teal-600">Settings</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Configure your professional identity and operational rules.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">System Secure</span>
        </div>
    </header>

    <!-- Emerald Tabs Navigation -->
    <div class="flex items-center gap-3 mb-10 overflow-x-auto pb-4 custom-scrollbar">
        <?php 
        $tabs = [
            'general' => ['label' => 'Identity', 'icon' => 'building'],
            'gateway' => ['label' => 'Portal Gateway', 'icon' => 'layout'],
            'hours'   => ['label' => 'Operational Hours', 'icon' => 'clock'],
            'staff'   => ['label' => 'Staff Directory', 'icon' => 'users'],
        ];
        foreach ($tabs as $id => $data):
            $is_active = ($tab === $id);
        ?>
            <a href="?tab=<?php echo $id; ?>" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest transition-all whitespace-nowrap <?php echo $is_active ? 'bg-teal-600 text-white shadow-xl shadow-teal-600/20' : 'bg-white text-slate-400 border border-slate-100 hover:bg-slate-50'; ?>">
                <i data-lucide="<?php echo $data['icon']; ?>" class="w-4 h-4"></i>
                <?php echo $data['label']; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($success): ?>
        <div class="bg-emerald-500 text-white p-5 rounded-[1.5rem] mb-8 font-black shadow-xl shadow-emerald-500/10 flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500 text-xs uppercase tracking-widest">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center">
                <i data-lucide="check" class="w-5 h-5"></i>
            </div>
            <?php echo e($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="?tab=<?php echo $tab; ?>" class="space-y-8 pb-24">
        
        <?php if ($tab === 'general'): ?>
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                <h4 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="info" class="w-5 h-5"></i>
                    </div>
                    Practice Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Practice Name</label>
                        <input type="text" name="name" value="<?php echo e($clinic_data['name']); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Brand Color</label>
                        <div class="flex items-center gap-4">
                            <input type="color" name="primary_color" value="<?php echo e($clinic_data['primary_color']); ?>" class="w-16 h-12 bg-slate-50 border-none rounded-xl cursor-pointer">
                            <code class="bg-slate-50 px-4 py-3 rounded-xl text-slate-500 font-black text-[10px] uppercase tracking-widest border border-slate-100"><?php echo e($clinic_data['primary_color']); ?></code>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Contact Phone</label>
                        <input type="text" name="phone" value="<?php echo e($config['phone'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Inquiry Email</label>
                        <input type="email" name="email" value="<?php echo e($config['email'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                    </div>
                    <div class="space-y-3 md:col-span-2">
                        <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Practice Address</label>
                        <textarea name="address" rows="2" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700 leading-relaxed"><?php echo e($config['address'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

        <?php elseif ($tab === 'gateway'): ?>
            <div class="space-y-8">
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h4 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="layout" class="w-5 h-5"></i>
                        </div>
                        Gateway Customization
                    </h4>
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Main Heading</label>
                            <input type="text" name="hero_title" value="<?php echo e($config['hero_title'] ?? 'Welcome to our Practice'); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Welcome Text</label>
                            <textarea name="about_text" rows="4" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700 leading-relaxed"><?php echo e($config['about_text'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($tab === 'hours'): ?>
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                <h4 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    Weekly Operational Hours
                </h4>
                <div class="space-y-3">
                    <?php 
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $saved_hours = $config['working_hours'] ?? [];
                    foreach ($days as $day): 
                        $day_key = strtolower($day);
                        $start = $saved_hours[$day_key]['start'] ?? '09:00';
                        $end = $saved_hours[$day_key]['end'] ?? '17:00';
                        $closed = isset($saved_hours[$day_key]['closed']);
                    ?>
                        <div class="flex flex-col md:flex-row md:items-center gap-6 p-5 bg-slate-50 rounded-2xl border border-slate-100/50">
                            <span class="w-32 text-xs font-black text-slate-700 uppercase tracking-widest"><?php echo $day; ?></span>
                            <div class="flex-1 flex items-center gap-4">
                                <input type="time" name="hours[<?php echo $day_key; ?>][start]" value="<?php echo $start; ?>" class="bg-white border border-slate-200 px-4 py-2.5 rounded-xl text-xs font-black text-slate-600 outline-none focus:ring-2 focus:ring-teal-500/20">
                                <span class="text-slate-300 font-black text-[10px] tracking-widest">TO</span>
                                <input type="time" name="hours[<?php echo $day_key; ?>][end]" value="<?php echo $end; ?>" class="bg-white border border-slate-200 px-4 py-2.5 rounded-xl text-xs font-black text-slate-600 outline-none focus:ring-2 focus:ring-teal-500/20">
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="hours[<?php echo $day_key; ?>][closed]" <?php echo $closed ? 'checked' : ''; ?> class="w-5 h-5 rounded-lg border-slate-200 text-teal-600 focus:ring-teal-500/20">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-teal-600 transition-all">Closed</span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($tab === 'staff'): ?>
            <div class="bg-slate-900 p-12 rounded-[3rem] shadow-2xl shadow-slate-900/20 text-center relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/5 rounded-full blur-3xl opacity-50"></div>
                <div class="w-20 h-20 bg-white/10 text-white rounded-[1.5rem] flex items-center justify-center mx-auto mb-8 backdrop-blur-md">
                    <i data-lucide="users" class="w-10 h-10"></i>
                </div>
                <h4 class="text-2xl font-black text-white mb-4 tracking-tight">Staff & Doctor Directory</h4>
                <p class="text-slate-400 text-sm max-w-md mx-auto mb-10 font-medium leading-relaxed">Manage your medical team, assign roles, and configure secure access credentials in the central directory.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="doctors.php" class="bg-white text-slate-900 px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 transition-all">Browse Team</a>
                    <a href="doctor-add.php" class="bg-teal-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all">Add Staff Member</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($tab !== 'staff'): ?>
            <div class="flex justify-end pt-8">
                <button type="submit" class="bg-teal-600 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-teal-600/20 hover:bg-teal-700 hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                    <i data-lucide="save" class="w-5 h-5"></i> Save Settings
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>
<?php require_once 'components/footer.php'; ?>
