<?php require_once 'core/init.php'; ?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | MedOS</title>
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
<body class="bg-gradient-to-br from-teal-50 via-slate-100 to-teal-100/50 bg-fixed text-slate-600 font-sans selection:bg-accent selection:text-slate-900 overflow-x-hidden min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="absolute top-0 w-full z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 text-2xl font-black tracking-tighter uppercase text-slate-900">
                <div class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center text-lg">+</div>
                MED<span class="text-primary">OS</span>
            </a>
            <div class="hidden lg:flex items-center gap-10 text-sm font-semibold uppercase tracking-widest text-slate-500">
                <a href="about.php" class="text-primary transition">About Us</a>
                <a href="services.php" class="hover:text-primary transition">Services</a>
                <a href="contact.php" class="hover:text-primary transition">Contact</a>
                <a href="index.php#pricing" class="hover:text-primary transition">Pricing</a>
                <?php if (Auth::check()): ?>
                    <a href="clinic/index.php" class="text-primary border border-primary px-8 py-2.5 rounded-full hover:bg-primary hover:text-white transition shadow-sm">Dashboard</a>
                    <a href="logout.php" class="text-slate-500 hover:text-red-500 transition font-bold">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-primary border border-primary px-8 py-2.5 rounded-full hover:bg-primary hover:text-white transition shadow-sm">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="pt-40 pb-24 flex-1">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-bold uppercase tracking-[0.2em] text-sm mb-4 block">Our Story</span>
                <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight">Revolutionizing Practice Management.</h1>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="rounded-[2rem] overflow-hidden border-4 border-white shadow-2xl">
                    <img src="https://images.pexels.com/photos/7088483/pexels-photo-7088483.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-full h-full object-cover" alt="MedOS Office">
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Built by doctors, for doctors.</h2>
                    <p class="text-lg text-slate-500 mb-6 leading-relaxed">
                        We started MedOS because we were frustrated with the fragmented software available to medical clinics. Doctors had to use one service for their website, another for patient records, and a third for appointment scheduling.
                    </p>
                    <p class="text-lg text-slate-500 mb-8 leading-relaxed">
                        Our mission is to provide an all-in-one operating system that deploys instantly, looks beautiful, and strictly adheres to medical compliance standards.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center">
                            <div class="text-4xl font-black text-primary mb-2">5k+</div>
                            <div class="text-sm font-bold uppercase text-slate-400 tracking-widest">Clinics</div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center">
                            <div class="text-4xl font-black text-primary mb-2">1M+</div>
                            <div class="text-sm font-bold uppercase text-slate-400 tracking-widest">Patients</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 pt-24 pb-12 text-slate-400">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center md:items-start gap-12 mb-16 text-center md:text-left">
                <div class="max-w-xs">
                    <div class="flex items-center justify-center md:justify-start gap-2 text-2xl font-black tracking-tighter uppercase mb-6 text-white">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white text-lg">+</div>
                        MED<span class="text-primary">OS</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Elevating medical practices through intelligent digital infrastructure.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-12 text-sm font-semibold uppercase tracking-widest text-slate-500">
                    <div class="flex flex-col gap-4">
                        <span class="text-white">Platform</span>
                        <a href="index.php#features" class="hover:text-primary transition">Features</a>
                        <a href="index.php#pricing" class="hover:text-primary transition">Pricing</a>
                    </div>
                    <div class="flex flex-col gap-4">
                        <span class="text-white">Company</span>
                        <a href="about.php" class="text-primary transition">About Us</a>
                        <a href="contact.php" class="hover:text-primary transition">Contact</a>
                    </div>
                    <div class="flex flex-col gap-4">
                        <span class="text-white">Legal</span>
                        <a href="#" class="hover:text-primary transition">Privacy</a>
                        <a href="#" class="hover:text-primary transition">Terms</a>
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-800 text-center flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">© <?php echo date('Y'); ?> MedOS SaaS Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
