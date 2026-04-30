<?php
// admin/clinic-add.php
require_once '../core/init.php';
Auth::protect('Super Admin');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $subdomain = strtolower($_POST['subdomain'] ?? '');
    $primary_color = $_POST['primary_color'] ?? '#3b82f6';
    $tier = $_POST['tier'] ?? 'basic';

    if (empty($name) || empty($subdomain)) {
        $error = 'All fields are required.';
    } else {
        $db = getDB();
        $check = $db->prepare("SELECT id FROM clinics WHERE subdomain = ?");
        $check->execute([$subdomain]);
        if ($check->fetch()) {
            $error = 'This subdomain is already taken.';
        } else {
            $stmt = $db->prepare("INSERT INTO clinics (name, subdomain, primary_color, subscription_tier, status) VALUES (?, ?, ?, ?, 'active')");
            $stmt->execute([$name, $subdomain, $primary_color, $tier]);
            $success = 'Clinic created successfully!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex">

    <aside class="w-64 bg-slate-900 min-h-screen text-white p-6 sticky top-0">
        <h1 class="text-2xl font-black mb-10 text-blue-500">CMS Admin</h1>
        <nav class="space-y-4">
            <a href="index.php" class="block py-2 px-4 hover:bg-slate-800 rounded-lg text-slate-400">Dashboard</a>
            <a href="clinics.php" class="block py-2 px-4 hover:bg-slate-800 rounded-lg text-slate-400">Clinics</a>
            <a href="logout.php" class="block py-2 px-4 text-red-400">Logout</a>
        </nav>
    </aside>

    <main class="flex-1 p-10">
        <div class="max-w-xl mx-auto">
            <a href="clinics.php" class="text-blue-600 text-sm font-bold mb-2 inline-block">← Back</a>
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Add Clinic</h2>

            <?php if ($error): ?><div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6"><?php echo e($error); ?></div><?php endif; ?>
            <?php if ($success): ?><div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6"><?php echo e($success); ?></div><?php endif; ?>

            <form method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" name="name" required class="w-full border border-gray-200 p-3 rounded-xl">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Subdomain</label>
                    <input type="text" name="subdomain" required class="w-full border border-gray-200 p-3 rounded-xl">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl">Create Clinic</button>
            </form>
        </div>
    </main>

</body>
</html>
