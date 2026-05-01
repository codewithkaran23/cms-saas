<?php
// clinic/doctor-add.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Doctor | <?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
    </style>
</head>
<body class="bg-gray-50 flex">

    <!-- Sidebar (Same) -->
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-6 sticky top-0 h-screen">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 bg-primary rounded"></div>
            <h1 class="text-xl font-bold text-gray-800">Clinic Admin</h1>
        </div>
        <nav class="space-y-4">
            <a href="<?php echo base_url('clinic/index.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Dashboard</a>
            <a href="<?php echo base_url('clinic/doctors.php'); ?>" class="block py-2 px-4 bg-primary text-white rounded-lg font-bold shadow-md">Doctors</a>
            <a href="<?php echo base_url('clinic/patients.php'); ?>" class="block py-2 px-4 text-gray-600 hover:bg-gray-50 rounded-lg">Patients</a>
            <div class="pt-10">
                <a href="<?php echo base_url('super-admin/logout.php'); ?>" class="block py-2 px-4 text-red-500 font-bold">Logout</a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-10">
        <div class="max-w-2xl mx-auto">
            <header class="mb-10">
                <a href="<?php echo base_url('clinic/doctors.php'); ?>" class="text-primary font-bold text-sm mb-2 inline-block">← Back to List</a>
                <h2 class="text-3xl font-bold text-gray-800">Add New Doctor</h2>
            </header>

            <?php if ($error): ?><div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6"><?php echo e($error); ?></div><?php endif; ?>
            <?php if ($success): ?><div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6"><?php echo e($success); ?></div><?php endif; ?>

            <form method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Doctor's Full Name</label>
                    <input type="text" name="name" placeholder="Dr. John Smith" required class="w-full border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Email Address</label>
                    <input type="email" name="email" required class="w-full border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Specialization</label>
                    <input type="text" name="specialization" placeholder="e.g. Cardiologist" class="w-full border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-sm text-gray-500">
                    <strong>Note:</strong> The default password will be <code>doctor123</code>. The doctor can change it upon their first login.
                </div>
                <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg hover:opacity-90 transition">
                    Register Doctor
                </button>
            </form>
        </div>
    </main>

</body>
</html>
