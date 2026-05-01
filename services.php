<?php require_once 'core/init.php'; ?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | MedOS</title>
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
                <a href="about.php" class="hover:text-primary transition">About Us</a>
                <a href="services.php" class="text-primary transition">Services</a>
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
            <div class="text-center mb-20">
                <span class="text-primary font-bold uppercase tracking-[0.2em] text-sm mb-4 block">Our Solutions</span>
                <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight">Everything You Need.</h1>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Service 1 -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-500">
                    <div class="w-16 h-16 bg-teal-50 text-primary rounded-full flex items-center justify-center text-3xl mb-6 border border-teal-100">🌐</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Instant Websites</h3>
                    <p class="text-slate-500 leading-relaxed">
                        A beautiful, highly-converting website generated instantly. Drag and drop visual editor lets you customize everything in real-time.
                    </p>
                </div>
                <!-- Service 2 -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-500">
                    <div class="w-16 h-16 bg-teal-50 text-primary rounded-full flex items-center justify-center text-3xl mb-6 border border-teal-100">📅</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Patient Portal</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Give your patients the ability to book their own appointments 24/7. Automatic SMS and email reminders reduce no-shows.
                    </p>
                </div>
                <!-- Service 3 -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-500">
                    <div class="w-16 h-16 bg-teal-50 text-primary rounded-full flex items-center justify-center text-3xl mb-6 border border-teal-100">📂</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Smart EMR</h3>
                    <p class="text-slate-500 leading-relaxed">
                        HIPAA-compliant patient records. Pre-loaded with ICD-10 codes, intelligent symptom tracking, and one-click e-prescriptions.
                    </p>
                </div>
                <!-- Service 4 -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-500">
                    <div class="w-16 h-16 bg-teal-50 text-primary rounded-full flex items-center justify-center text-3xl mb-6 border border-teal-100">📱</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">QR Check-ins</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Every patient gets a unique QR health card. Scan it at the front desk to instantly pull up their file and mark them as arrived.
                    </p>
                </div>
                <!-- Service 5 -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-500">
                    <div class="w-16 h-16 bg-teal-50 text-primary rounded-full flex items-center justify-center text-3xl mb-6 border border-teal-100">💳</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Automated Billing</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Generate invoices, track insurance claims, and accept online payments instantly. Reduce administrative overhead dramatically.
                    </p>
                </div>
                <!-- Service 6 -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-500">
                    <div class="w-16 h-16 bg-teal-50 text-primary rounded-full flex items-center justify-center text-3xl mb-6 border border-teal-100">📊</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Practice Analytics</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Detailed reporting on clinic performance, revenue, patient retention, and doctor efficiency in a centralized dashboard.
                    </p>
                </div>
                <!-- Service 7 -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-500">
                    <div class="w-16 h-16 bg-teal-50 text-primary rounded-full flex items-center justify-center text-3xl mb-6 border border-teal-100">💬</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">WhatsApp Booking</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Send automated appointment reminders and let patients book, reschedule, or cancel directly via WhatsApp.
                    </p>
                </div>
                <!-- Service 8 -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-2 transition duration-500">
                    <div class="w-16 h-16 bg-teal-50 text-primary rounded-full flex items-center justify-center text-3xl mb-6 border border-teal-100">🎥</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Telemedicine</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Built-in secure video conferencing for remote consultations. Automatically generate meeting links for paid appointments.
                    </p>
                </div>
            </div>
            
            <div class="mt-20 text-center">
                <a href="<?php echo Auth::check() ? 'checkout.php' : 'signup.php'; ?>" class="inline-block bg-primary text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-teal-800 transition shadow-lg shadow-primary/20">Get Started Today</a>
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
                        <a href="about.php" class="hover:text-primary transition">About Us</a>
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
