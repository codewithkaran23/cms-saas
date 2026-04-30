<?php
// admin/login.php
require_once '../core/init.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Attempt Super Admin login (clinic_id is NULL)
    if (Auth::login($email, $password)) {
        redirect('admin/index.php');
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-slate-900 p-10 rounded-3xl border border-slate-800 shadow-2xl">
        <h1 class="text-3xl font-black text-white text-center mb-8">CMS <span class="text-blue-500">Admin</span></h1>
        
        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-xl mb-6 text-sm">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-slate-400 text-sm mb-2">Email Address</label>
                <input type="email" name="email" required class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl">
            </div>
            <div>
                <label class="block text-slate-400 text-sm mb-2">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-xl">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition">Sign In</button>
        </form>
    </div>

</body>
</html>
