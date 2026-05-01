<?php
// clinic/patient-add.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($password)) $errors[] = "Temporary password is required.";

    // Check if email exists for this clinic
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND clinic_id = ?");
    $stmt->execute([$email, $clinic_id]);
    if ($stmt->fetch()) {
        $errors[] = "A patient with this email already exists in your clinic.";
    }

    if (empty($errors)) {
        $role_stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Patient'");
        $role_stmt->execute();
        $role_id = $role_stmt->fetchColumn();

        $stmt = $db->prepare("
            INSERT INTO users (clinic_id, role_id, name, email, phone, password_hash) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $clinic_id, 
            $role_id, 
            $name, 
            $email, 
            $phone, 
            password_hash($password, PASSWORD_DEFAULT)
        ]);

        $success = "Patient added successfully!";
        // Redirect after 2 seconds
        header("Refresh:2; url=patients.php");
    }
}

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-3xl mx-auto">
    <header class="mb-10">
        <a href="patients.php" class="text-primary font-bold hover:underline flex items-center gap-2 mb-4">
            <span>←</span> Back to Patients
        </a>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Add New <span class="text-primary">Patient</span></h2>
        <p class="text-slate-500 mt-1">Register a new patient to your clinic management system.</p>
    </header>

    <?php if ($success): ?>
        <div class="bg-green-600 text-white p-6 rounded-2xl mb-8 font-bold shadow-xl shadow-green-600/20 animate-bounce">
            ✅ <?php echo $success; ?> Redirecting...
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-2xl mb-8">
            <p class="font-black uppercase text-xs tracking-widest mb-2">Please fix the following:</p>
            <ul class="list-disc list-inside text-sm font-bold">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo $e; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                <input type="text" name="name" required placeholder="John Doe" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-medium">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                <input type="text" name="phone" placeholder="+1 (555) 000-0000" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-medium">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
            <input type="email" name="email" required placeholder="patient@example.com" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-medium">
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Temporary Password</label>
            <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-medium">
            <p class="text-[10px] text-slate-400 font-bold italic ml-1">The patient can change this after their first login.</p>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-primary text-white py-5 rounded-2xl font-black text-lg shadow-xl shadow-primary/30 hover:scale-[1.01] transition-transform">
                Create Patient Account
            </button>
        </div>
    </form>
</div>

<?php require_once 'components/footer.php'; ?>
