<?php
// clinic/doctor-add.php
require_once '../core/init.php';
Auth::protect('Doctor');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? 'doctor123'; // Default password
    $specialization = $_POST['specialization'] ?? '';
    
    $clinic_id = $_SESSION['clinic_id'];
    $db = getDB();

    // 1. Check if email exists in this clinic
    $check = $db->prepare("SELECT id FROM users WHERE email = ? AND clinic_id = ?");
    $check->execute([$email, $clinic_id]);
    
    if ($check->fetch()) {
        $error = 'Email already registered for this clinic.';
    } else {
        try {
            $db->beginTransaction();

            // 2. Get Doctor Role ID
            $role_stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Doctor'");
            $role_stmt->execute();
            $role_id = $role_stmt->fetchColumn();

            // 3. Create User
            $stmt = $db->prepare("INSERT INTO users (clinic_id, role_id, name, email, password_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$clinic_id, $role_id, $name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $user_id = $db->lastInsertId();

            // 4. Create Doctor Profile
            $profile_stmt = $db->prepare("INSERT INTO doctor_profiles (user_id, clinic_id, specialization) VALUES (?, ?, ?)");
            $profile_stmt->execute([$user_id, $clinic_id, $specialization]);

            $db->commit();
            $success = "Doctor $name added successfully!";
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Error adding doctor: " . $e->getMessage();
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
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Add New <span class="text-teal-600">Doctor</span></h2>
        <p class="text-slate-500 text-sm font-medium mt-1">Register a new medical professional to your practice.</p>
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
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Doctor's Full Name</label>
            <input type="text" name="name" placeholder="Dr. John Smith" required 
                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-bold text-sm focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 outline-none transition-all">
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
            <input type="email" name="email" placeholder="doctor@example.com" required 
                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-bold text-sm focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 outline-none transition-all">
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Specialization</label>
            <input type="text" name="specialization" placeholder="e.g. Cardiologist" 
                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 font-bold text-sm focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 outline-none transition-all">
        </div>

        <div class="bg-teal-50/50 p-6 rounded-2xl border border-teal-100/50">
            <div class="flex gap-3">
                <i data-lucide="info" class="w-5 h-5 text-teal-600 shrink-0"></i>
                <div>
                    <h5 class="text-[10px] font-black text-teal-700 uppercase tracking-widest">Automatic Credentialing</h5>
                    <p class="text-xs text-teal-600/80 font-medium mt-1">The default password will be <code class="bg-teal-100/50 px-1.5 py-0.5 rounded font-black text-teal-700">doctor123</code>. The doctor will be prompted to change this upon their first login.</p>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-teal-600 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center justify-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Register Medical Professional
        </button>
    </form>
</div>

<?php require_once 'components/footer.php'; ?>
