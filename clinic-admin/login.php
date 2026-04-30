<?php
// clinic-admin/login.php
require_once '../core/init.php';

// Ensure we have a clinic context (if on localhost, need ?clinic=X)
if (!$clinic) {
    die("Clinic not identified. Please visit via the clinic's own URL.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Attempt login scoped to THIS clinic
    if (Auth::login($email, $password, $clinic['id'])) {
        redirect('clinic-admin/index.php');
    } else {
        $error = 'Invalid credentials for ' . $clinic['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Admin Login | <?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white p-10 rounded-3xl shadow-xl border border-gray-100">
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-primary mx-auto rounded-2xl mb-4 shadow-lg flex items-center justify-center text-white text-2xl font-black">
                <?php echo substr($clinic['name'], 0, 1); ?>
            </div>
            <h1 class="text-2xl font-bold text-gray-800"><?php echo e($clinic['name']); ?></h1>
            <p class="text-gray-500 text-sm">Staff Administration Login</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 text-sm"><?php echo e($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-gray-600 text-sm font-bold mb-2">Staff Email</label>
                <input type="email" name="email" required class="w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
            </div>
            <div>
                <label class="block text-gray-600 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-primary focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg hover:opacity-90 transition">
                Login to Panel
            </button>
        </form>
        
        <div class="mt-8 text-center">
            <a href="../" class="text-gray-400 text-sm hover:text-gray-600">← Back to Website</a>
        </div>
    </div>

</body>
</html>
