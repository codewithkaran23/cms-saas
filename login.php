<?php
// login.php
require_once 'core/init.php';

// Redirect if already logged in
if (Auth::check()) {
    if (Auth::hasRole('Doctor')) redirect('doctor/index.php');
    if (Auth::hasRole('Patient')) redirect('patient/index.php');
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (Auth::login($email, $password)) {
        // Force password reset if flag is set
        if (Auth::shouldReset()) {
            redirect('change-password.php');
        }

        // Successful login - role-based redirection
        if (Auth::hasRole('Doctor')) redirect('doctor/index.php');
        if (Auth::hasRole('Patient')) redirect('patient/index.php');
        redirect('index.php');
    } else {
        $error = 'Invalid email or password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Portal Login | <?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f8fafc] flex items-center justify-center min-h-screen p-6 relative overflow-hidden">
    
    <!-- Background Accents -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-teal-50 rounded-full blur-3xl opacity-50 -z-10 translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-3xl opacity-50 -z-10 -translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-md w-full">
        <div class="bg-white p-12 rounded-[3rem] shadow-2xl shadow-teal-900/5 border border-slate-100 relative overflow-hidden backdrop-blur-sm bg-white/90">
            
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-teal-600 mx-auto rounded-3xl mb-6 shadow-xl shadow-teal-600/20 flex items-center justify-center text-white text-3xl font-black">
                    +
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Portal Login</h1>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-3">Practice Management System</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 p-5 rounded-2xl mb-8 text-xs font-black uppercase tracking-widest flex items-center gap-4 animate-in fade-in zoom-in duration-300">
                    <div class="w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center">!</div>
                    <?php echo e($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-8">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                    <input type="email" name="email" required placeholder="your@email.com" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-5 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all font-bold text-slate-700">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Secure Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-5 rounded-2xl focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all font-bold text-slate-700">
                </div>
                
                <button type="submit" class="w-full bg-teal-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-teal-600/20 hover:bg-teal-700 hover:-translate-y-1 transition-all duration-300 uppercase tracking-widest text-[11px]">
                    Sign In to Portal
                </button>
            </form>
            
            <div class="mt-12 text-center pt-8 border-t border-slate-50">
                <a href="index.php" class="text-slate-400 text-[10px] font-black uppercase tracking-widest hover:text-teal-600 transition-all flex items-center justify-center gap-3 group">
                    <span class="text-lg group-hover:-translate-x-1 transition-transform">←</span> Back to Practice Gateway
                </a>
            </div>
        </div>

        <p class="text-center mt-8 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">
            © <?php echo date('Y'); ?> <?php echo e($clinic['name']); ?> • Secure Access
        </p>
    </div>

</body>
</html>
