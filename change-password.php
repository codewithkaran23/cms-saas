<?php
// change-password.php
require_once 'core/init.php';

// Must be logged in
if (!Auth::check()) {
    redirect('login.php');
}

// If they don't need a reset, send them to their dashboard
if (!Auth::shouldReset()) {
    if (Auth::hasRole('Doctor')) redirect('doctor/index.php');
    if (Auth::hasRole('Patient')) redirect('patient/index.php');
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($new_password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $db = getDB();
        $user_id = $_SESSION['user_id'];
        
        $stmt = $db->prepare("UPDATE users SET password_hash = ?, require_reset = 0 WHERE id = ?");
        if ($stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $user_id])) {
            $_SESSION['require_reset'] = 0;
            $success = 'Password updated successfully! Redirecting...';
            
            // Redirect after success
            if (Auth::hasRole('Doctor')) header("Refresh:2; url=doctor/index.php");
            elseif (Auth::hasRole('Patient')) header("Refresh:2; url=patient/index.php");
            else header("Refresh:2; url=index.php");
        } else {
            $error = 'Failed to update password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password | MedOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
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
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-3xl opacity-50 -z-10 translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-teal-50 rounded-full blur-3xl opacity-50 -z-10 -translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-md w-full">
        <div class="bg-white p-12 rounded-[3rem] shadow-2xl shadow-teal-900/5 border border-slate-100 relative overflow-hidden backdrop-blur-sm bg-white/90">
            
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-emerald-600 mx-auto rounded-2xl mb-6 shadow-xl shadow-emerald-600/20 flex items-center justify-center text-white">
                    <i data-lucide="shield-lock" class="w-8 h-8"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Security Update</h1>
                <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mt-3">Please set your permanent password</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 p-5 rounded-2xl mb-8 text-xs font-bold flex items-center gap-4">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <?php echo e($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 p-5 rounded-2xl mb-8 text-xs font-bold flex items-center gap-4">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                    <?php echo e($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                    <input type="password" name="new_password" required placeholder="Min. 8 characters" 
                           class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" required placeholder="Repeat password" 
                           class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700">
                </div>
                
                <button type="submit" class="w-full bg-emerald-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-600/20 hover:bg-emerald-700 transition-all duration-300 uppercase tracking-widest text-[11px] mt-4 flex items-center justify-center gap-2">
                    Update Password <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
