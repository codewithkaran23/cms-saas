<?php
// login.php
require_once 'core/init.php';

if (Auth::check()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ? AND u.clinic_id IS NOT NULL AND u.deleted_at IS NULL");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role_name'];
            $_SESSION['clinic_id'] = $user['clinic_id'];
            
            // Redirect to the SaaS front page as requested
            redirect('index.php');
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - MedOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { primary: '#0f766e', accent: '#14d1c0' }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-teal-50 via-slate-100 to-teal-100/50 min-h-screen flex flex-col font-sans">
    
    <nav class="w-full z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 text-2xl font-black tracking-tighter uppercase text-slate-900">
                <div class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center text-lg">+</div>
                MED<span class="text-primary">OS</span>
            </a>
            <div class="hidden lg:flex items-center gap-10 text-sm font-semibold uppercase tracking-widest text-slate-500">
                <a href="index.php" class="hover:text-primary transition">Back to Home</a>
            </div>
        </div>
    </nav>

    <div class="flex-1 flex items-center justify-center p-6 relative">
        <div class="absolute inset-0 z-0 bg-[url('https://images.pexels.com/photos/7088483/pexels-photo-7088483.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1')] bg-cover bg-center opacity-10"></div>
        <div class="absolute inset-0 bg-white/60 backdrop-blur-sm z-0"></div>

        <div class="max-w-md w-full bg-white p-10 rounded-3xl border border-slate-200 shadow-xl relative z-10">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black text-slate-900 mb-2">Welcome Back</h1>
                <p class="text-slate-500 font-medium">Sign in to manage your clinic.</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100 flex items-center gap-2">
                    <span class="text-xl">⚠️</span> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-slate-700 text-sm font-bold mb-2 uppercase tracking-wide">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                </div>
                <div>
                    <label class="block text-slate-700 text-sm font-bold mb-2 uppercase tracking-wide">Password</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                </div>
                
                <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-teal-800 shadow-md shadow-primary/20 transition mt-4">Sign In</button>
            </form>
            
            <div class="mt-8 text-center text-sm font-semibold text-slate-500">
                Don't have an account? <a href="signup.php" class="text-primary hover:underline">Sign Up</a>
            </div>
        </div>
    </div>

</body>
</html>
