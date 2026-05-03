<?php
// doctor/staff-add.php
require_once '../core/init.php';
Auth::protect('Doctor');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = generate_random_password(); // Auto-generate secure password
    $role_name = $_POST['role_name'] ?? 'Receptionist';
    
    $clinic_id = $_SESSION['clinic_id'];
    $db = getDB();

    // 1. Check if email exists in this clinic
    $check = $db->prepare("SELECT id FROM users WHERE email = ? AND clinic_id = ?");
    $check->execute([$email, $clinic_id]);
    
    if ($check->fetch()) {
        $error = 'Email already registered for this clinic.';
    } else {
        try {
            // 2. Get Role ID
            $role_stmt = $db->prepare("SELECT id FROM roles WHERE name = ?");
            $role_stmt->execute([$role_name]);
            $role_id = $role_stmt->fetchColumn();

            if (!$role_id) throw new Exception("Invalid role selected.");

            // 3. Create User
            $stmt = $db->prepare("INSERT INTO users (clinic_id, role_id, name, email, password_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$clinic_id, $role_id, $name, $email, password_hash($password, PASSWORD_DEFAULT)]);

            // Send Credentials Email
            send_credentials_email($email, $name, $password, $role_name);

            $success = "Member $name added successfully! Credentials sent to email.";
        } catch (Exception $e) {
            $error = "Error adding member: " . $e->getMessage();
        }
    }
}

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-2xl mx-auto space-y-8 animate-in fade-in duration-700">
    <!-- Header Area -->
    <header>
        <a href="doctors.php" class="inline-flex items-center gap-2 text-slate-500 hover:text-teal-600 font-bold text-sm transition-colors mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Team
        </a>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Add Team <span class="text-teal-600">Member</span></h2>
        <p class="text-slate-500 text-sm font-medium mt-1">Register a new staff member or receptionist to your practice.</p>
    </header>

    <?php if ($error): ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 font-bold text-sm flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-100 font-bold text-sm flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo e($success); ?>
        </div>
    <?php endif; ?>

    <!-- Form Area -->
    <form method="POST" class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-6">
        <div class="space-y-2">
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
            <input type="text" name="name" placeholder="John Doe" required 
                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-bold text-sm focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 outline-none transition-all">
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
            <input type="email" name="email" placeholder="staff@example.com" required 
                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-bold text-sm focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 outline-none transition-all">
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Assigned Role</label>
            <select name="role_name" required 
                    class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-bold text-sm focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 outline-none transition-all">
                <option value="Receptionist">Receptionist</option>
                <option value="Receptionist">Support Staff</option>
            </select>
        </div>

        <div class="bg-teal-50/50 p-6 rounded-2xl border border-teal-100/50">
            <div class="flex gap-3">
                <i data-lucide="shield-check" class="w-5 h-5 text-teal-600 shrink-0"></i>
                <div>
                    <h5 class="text-[10px] font-black text-teal-700 uppercase tracking-widest">Secure Access</h5>
                    <p class="text-xs text-teal-600/80 font-medium mt-1">A secure, random password will be generated and sent to the team member's email address automatically.</p>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-teal-600 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center justify-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Register Team Member
        </button>
    </form>
</div>

<?php require_once 'components/footer.php'; ?>
