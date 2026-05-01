<?php
// signup.php
require_once 'core/init.php';

$step = $_GET['step'] ?? 1;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Capture Data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $clinic_name = $_POST['clinic_name'];
    $subdomain = strtolower($_POST['subdomain']);

    $db = getDB();

    // 2. Validate Subdomain
    $check = $db->prepare("SELECT id FROM clinics WHERE subdomain = ?");
    $check->execute([$subdomain]);
    if ($check->fetch()) {
        $error = "This subdomain is already taken.";
    } else {
        try {
            $db->beginTransaction();

            // 3. Create Clinic (Default Config)
            $config = json_encode([
                'hero_title' => 'Welcome to ' . $clinic_name,
                'about' => 'We are dedicated to providing excellent healthcare.',
                'services' => [
                    ['title' => 'General Consultation', 'desc' => 'Primary health services.'],
                    ['title' => 'Emergency', 'desc' => 'Immediate care.']
                ]
            ]);
            $stmt = $db->prepare("INSERT INTO clinics (name, subdomain, primary_color, config, status) VALUES (?, ?, '#3b82f6', ?, 'pending')");
            $stmt->execute([$clinic_name, $subdomain, $config]);
            $clinic_id = $db->lastInsertId();

            // 4. Create User (Clinic Admin)
            $role_stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Clinic Admin'");
            $role_stmt->execute();
            $role_id = $role_stmt->fetchColumn();

            $user_stmt = $db->prepare("INSERT INTO users (clinic_id, role_id, name, email, password_hash) VALUES (?, ?, ?, ?, ?)");
            $user_stmt->execute([$clinic_id, $role_id, $name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $user_id = $db->lastInsertId();

            $db->commit();

            // 5. AUTO-LOGIN
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = 'Clinic Admin';
            $_SESSION['clinic_id'] = $clinic_id;

            // Redirect to the SaaS front page
            redirect('index.php');

        } catch (Exception $e) {
            $db->rollBack();
            $error = "System error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | MedOS</title>
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

    <div class="flex-1 flex items-center justify-center p-6 relative py-12">
        <div class="absolute inset-0 z-0 bg-[url('https://images.pexels.com/photos/7088483/pexels-photo-7088483.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1')] bg-cover bg-center opacity-10"></div>
        <div class="absolute inset-0 bg-white/60 backdrop-blur-sm z-0"></div>

        <div class="max-w-md w-full bg-white p-10 rounded-3xl border border-slate-200 shadow-xl relative z-10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-slate-900 mb-2">Create Your <span class="text-primary">Clinic.</span></h1>
                <p class="text-slate-500 font-medium">Join 500+ doctors growing their practice.</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100 flex items-center gap-2">
                    <span class="text-xl">⚠️</span> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <input type="text" name="name" required placeholder="Full Name" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                </div>
                <div>
                    <input type="email" name="email" required placeholder="Email Address" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                </div>
                <div>
                    <input type="password" name="password" required placeholder="Password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                </div>
                
                <div class="pt-4 mt-4 border-t border-slate-100 space-y-4">
                    <input type="text" name="clinic_name" required placeholder="Clinic Name" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                    <div class="flex items-center gap-2 bg-slate-50 px-6 py-4 rounded-xl border border-slate-200">
                        <input type="text" name="subdomain" required placeholder="subdomain" class="bg-transparent border-none outline-none text-right font-bold text-primary flex-1">
                        <span class="text-slate-400 font-bold">.cms.local</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-teal-800 shadow-md shadow-primary/20 transition mt-6">
                    Create Account & Proceed
                </button>
            </form>
            
            <div class="mt-8 text-center text-sm font-semibold text-slate-500">
                Already have an account? <a href="login.php" class="text-primary hover:underline">Sign In</a>
            </div>
        </div>
    </div>

</body>
</html>
