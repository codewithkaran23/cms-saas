<?php
// patient/settings.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$user_id = $_SESSION['user_id'];

// Fetch current patient data
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'] ?? $user_data['name'];
        $phone = $_POST['phone'] ?? $user_data['phone'];
        
        $upd = $db->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        $upd->execute([$name, $phone, $user_id]);
        
        $_SESSION['user_name'] = $name;
        $success = 'Profile updated successfully!';
        $user_data['name'] = $name;
        $user_data['phone'] = $phone;
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

<div class="max-w-4xl animate-in fade-in duration-500">
    <header class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">My <span class="text-teal-600">Profile</span></h2>
        <p class="text-slate-500 text-sm font-medium mt-1">Manage your personal information and security settings.</p>
    </header>

    <?php if ($success): ?>
        <div class="bg-emerald-500 text-white p-5 rounded-[1.5rem] mb-8 font-black shadow-xl shadow-emerald-500/10 flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500 text-xs uppercase tracking-widest">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center">
                <i data-lucide="check" class="w-5 h-5"></i>
            </div>
            <?php echo e($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-500 text-white p-5 rounded-[1.5rem] mb-8 font-black shadow-xl shadow-red-500/10 flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500 text-xs uppercase tracking-widest">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
            </div>
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h4 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                Personal Details
            </h4>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="update_profile" value="1">
                <div class="space-y-3">
                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="name" value="<?php echo e($user_data['name']); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                </div>
                <div class="space-y-3">
                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Email Address</label>
                    <input type="email" disabled value="<?php echo e($user_data['email']); ?>" class="w-full bg-slate-100 border border-slate-100 px-6 py-4 rounded-2xl font-bold text-sm text-slate-400 outline-none cursor-not-allowed">
                    <p class="text-[9px] text-slate-400 font-medium ml-1">Contact support to change your registered email.</p>
                </div>
                <div class="space-y-3">
                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Phone Number</label>
                    <input type="text" name="phone" value="<?php echo e($user_data['phone'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                </div>
                <button type="submit" class="w-full bg-teal-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center justify-center gap-3">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                </button>
            </form>
        </div>

        <!-- Security -->
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h4 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                Security
            </h4>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="update_password" value="1">
                <div class="space-y-3">
                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Current Password</label>
                    <input type="password" name="current_password" required class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                </div>
                <div class="space-y-3">
                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">New Password</label>
                    <input type="password" name="new_password" required class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                </div>
                <div class="space-y-3">
                    <label class="block text-slate-400 text-[10px] font-black uppercase tracking-widest ml-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" required class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition font-bold text-sm text-slate-700">
                </div>
                <button type="submit" class="w-full bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all flex items-center justify-center gap-3">
                    <i data-lucide="key" class="w-4 h-4"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
