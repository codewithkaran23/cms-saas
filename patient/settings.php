<?php
// patient/settings.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$user_id = $_SESSION['user_id'];

// Fetch current user & profile data
$stmt = $db->prepare("
    SELECT u.*, p.picture_url, p.id as profile_id 
    FROM users u 
    LEFT JOIN patient_profiles p ON u.id = p.user_id 
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'] ?? $user_data['name'];
        $phone = $_POST['phone'] ?? $user_data['phone'];
        
        // 1. Update User Table
        $upd = $db->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        $upd->execute([$name, $phone, $user_id]);
        
        // 2. Handle Picture Upload
        $picture_url = $user_data['picture_url'];
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
            $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $filename = 'patient_' . $user_id . '_' . time() . '.' . $ext;
            
            $upload_dir = ROOT_PATH . '/uploads/patients/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            if (move_uploaded_file($_FILES['picture']['tmp_name'], $upload_dir . $filename)) {
                $picture_url = 'uploads/patients/' . $filename;
            }
        }

        // 3. Update Patient Profile Table (for picture)
        $upd_p = $db->prepare("UPDATE patient_profiles SET picture_url = ? WHERE user_id = ?");
        $upd_p->execute([$picture_url, $user_id]);
        
        $_SESSION['user_name'] = $name;
        $success = 'Profile updated successfully!';
        
        // Refresh data
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch();
    } 
    elseif (isset($_POST['update_password'])) {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        if (password_verify($current, $user_data['password_hash'])) {
            if ($new === $confirm) {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $upd = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $upd->execute([$hash, $user_id]);
                $success = 'Password changed successfully!';
            } else {
                $error = 'New passwords do not match.';
            }
        } else {
            $error = 'Current password is incorrect.';
        }
    }
}

$page_title = "My Profile Settings";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-5xl mx-auto py-6 animate-in fade-in duration-500">
    <header class="mb-10 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Profile <span class="text-teal-600">Settings</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Manage your account identity and security.</p>
        </div>
        <div class="hidden md:block">
            <div class="bg-teal-50 text-teal-700 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-teal-100">
                Patient Account Verified
            </div>
        </div>
    </header>

    <?php if ($success): ?>
        <div class="bg-emerald-600 text-white p-5 rounded-2xl mb-8 font-bold shadow-lg shadow-emerald-600/20 flex items-center gap-4 animate-in zoom-in duration-300">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-500 text-white p-5 rounded-2xl mb-8 font-bold shadow-lg shadow-red-500/20 flex items-center gap-4">
            <i data-lucide="alert-circle" class="w-6 h-6"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Profile Preview & Photo -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white p-8 rounded-[1.5rem] shadow-sm border border-slate-100 text-center space-y-6">
                <div class="relative inline-block group">
                    <div class="w-32 h-32 rounded-[2.5rem] overflow-hidden border-4 border-slate-50 shadow-inner bg-slate-50 mx-auto">
                        <?php if ($user_data['picture_url']): ?>
                            <img src="<?php echo base_url($user_data['picture_url']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-teal-50 text-teal-600">
                                <i data-lucide="user" class="w-12 h-12"></i>
                            </div>
                        <?php    endif; ?>
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-white p-2 rounded-xl shadow-lg border border-slate-100 text-teal-600">
                        <i data-lucide="camera" class="w-5 h-5"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900"><?php echo e($user_data['name']); ?></h3>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Patient</p>
                </div>
                <div class="pt-4 border-t border-slate-50">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Member Since</p>
                    <p class="text-sm font-bold text-slate-700"><?php echo date('M d, Y', strtotime($user_data['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Right: Forms -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="bg-white p-10 rounded-[1.5rem] shadow-sm border border-slate-100">
                <h4 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                    </div>
                    Personal Information
                </h4>
                <form method="POST" enctype="multipart/form-data" class="space-y-8">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Full Name</label>
                            <input type="text" name="name" value="<?php echo e($user_data['name']); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Phone Number</label>
                            <input type="text" name="phone" value="<?php echo e($user_data['phone'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Update Profile Photo</label>
                            <input type="file" name="picture" class="w-full text-xs text-slate-500 file:mr-6 file:py-4 file:px-8 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-all cursor-pointer">
                        </div>
                    </div>

                    <button type="submit" class="bg-teal-600 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-teal-600/30 hover:bg-teal-700 hover:-translate-y-1 transition-all flex items-center gap-3">
                        <i data-lucide="check" class="w-4 h-4"></i> Update My Profile
                    </button>
                </form>
            </div>

            <!-- Security Section -->
            <div class="bg-white p-10 rounded-[1.5rem] shadow-sm border border-slate-100">
                <h4 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    Security & Password
                </h4>
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="update_password" value="1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">New Password</label>
                            <input type="password" name="new_password" required class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Confirm Password</label>
                            <input type="password" name="confirm_password" required class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                        </div>
                    </div>
                    <button type="submit" class="bg-slate-900 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all flex items-center gap-3 mt-4">
                        <i data-lucide="key" class="w-4 h-4"></i> Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

<?php require_once 'components/footer.php'; ?>
