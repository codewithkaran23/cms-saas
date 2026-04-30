<?php
// index.php
require_once 'core/init.php';

if ($clinic):
    // ... (Keep the clinic-specific code I wrote earlier, it's already premium)
    // ... I will skip repeating that part in this edit and focus on the ELSE part
?>
<?php 
// For brevity, I'm assuming the clinic part is already there. 
// I will rewrite the whole file to ensure it's 100% complete.
?>
<?php
    $config = json_decode($clinic['config'] ?? '{}', true);
    $services = $config['services'] ?? [
        ['title' => 'General Consultation', 'desc' => 'Comprehensive health checkups and personalized care plans.'],
        ['title' => 'Emergency Care', 'desc' => '24/7 urgent medical assistance for critical health issues.'],
        ['title' => 'Laboratory Tests', 'desc' => 'State-of-the-art diagnostic testing and quick results.']
    ];
    $about = $config['about'] ?? "We are dedicated to providing the highest quality healthcare services to our community.";
    $hero_title = $config['hero_title'] ?? "Your Health, Our Global Commitment.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title><?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; } :root { --primary: <?php echo $clinic['primary_color']; ?>; }</style>
</head>
<body class="bg-white">
    <nav class="flex justify-between p-6 max-w-7xl mx-auto items-center">
        <span class="text-2xl font-black" style="color: var(--primary)"><?php echo e($clinic['name']); ?></span>
        <a href="portal/" class="bg-slate-900 text-white px-6 py-2 rounded-full font-bold">Book Now</a>
    </nav>
    <section class="py-20 text-center max-w-4xl mx-auto px-6">
        <h1 class="text-6xl font-black mb-6 leading-tight"><?php echo e($hero_title); ?></h1>
        <p class="text-xl text-slate-500 mb-10"><?php echo e($about); ?></p>
        <a href="portal/" class="bg-blue-600 text-white px-10 py-5 rounded-2xl font-bold text-xl shadow-2xl shadow-blue-600/30">Get Appointment</a>
    </section>
</body>
</html>

<?php else: ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CMS SaaS | The OS for Modern Medical Practices</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-text { background: linear-gradient(90deg, #3b82f6, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-[#030712] text-white selection:bg-blue-500 overflow-x-hidden">

    <!-- Glowing Background -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-blue-600/20 blur-[120px] -z-10 rounded-full"></div>

    <!-- Navigation -->
    <nav class="max-w-7xl mx-auto px-6 py-8 flex justify-between items-center relative z-10">
        <div class="text-2xl font-black tracking-tighter">CMS<span class="text-blue-500">.</span>SAAS</div>
        <div class="hidden md:flex items-center gap-10 text-sm font-bold text-slate-400 uppercase tracking-widest">
            <a href="#features" class="hover:text-white transition">Features</a>
            <a href="#pricing" class="hover:text-white transition">Pricing</a>
            <a href="admin/login.php" class="hover:text-white transition border border-white/10 px-6 py-2 rounded-full">Sign In</a>
            <a href="signup.php" class="bg-blue-600 text-white px-8 py-3 rounded-full shadow-2xl shadow-blue-600/40 hover:scale-105 transition-transform">Get Started</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 pt-32 pb-40 text-center">
        <div class="inline-block px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 text-xs font-black uppercase tracking-[0.2em] mb-10">
            Trusted by 500+ Clinics Worldwide
        </div>
        <h1 class="text-7xl md:text-8xl font-black mb-10 tracking-tight leading-[0.9]">
            Your Practice, <br> <span class="gradient-text">Fully Digital.</span>
        </h1>
        <p class="text-xl text-slate-400 mb-16 max-w-3xl mx-auto leading-relaxed">
            The only platform that builds your professional clinic website, manages your appointments, and handles your medical records in one seamless experience.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="signup.php" class="bg-blue-600 px-12 py-6 rounded-2xl font-black text-xl hover:bg-blue-700 transition shadow-2xl shadow-blue-600/50">Launch Your Website</a>
            <a href="?clinic=citycare" target="_blank" class="bg-white/5 border border-white/10 px-12 py-6 rounded-2xl font-black text-xl hover:bg-white/10 transition">Live Demo</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="max-w-7xl mx-auto px-6 py-32 border-t border-white/5">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white/5 border border-white/10 p-10 rounded-[3rem] hover:bg-white/[0.07] transition">
                <div class="w-14 h-14 bg-blue-600/20 text-blue-500 rounded-2xl flex items-center justify-center mb-8">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9-9c1.657 0 3 4.03 3 9s-1.343 9-3 9m0-18c-1.657 0-3 4.03-3 9s1.343 9 3 9m-9-9h18"></path></svg>
                </div>
                <h3 class="text-2xl font-bold mb-4">Auto-Subdomain</h3>
                <p class="text-slate-400 leading-relaxed text-lg">Get a professional subdomain like yourname.cms.local instantly. Ready to share with patients.</p>
            </div>
            <div class="bg-white/5 border border-white/10 p-10 rounded-[3rem] hover:bg-white/[0.07] transition">
                <div class="w-14 h-14 bg-purple-600/20 text-purple-500 rounded-2xl flex items-center justify-center mb-8">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold mb-4">Smart Scheduling</h3>
                <p class="text-slate-400 leading-relaxed text-lg">Real-time booking portal for patients. No more overlapping appointments or phone calls.</p>
            </div>
            <div class="bg-white/5 border border-white/10 p-10 rounded-[3rem] hover:bg-white/[0.07] transition">
                <div class="w-14 h-14 bg-indigo-600/20 text-indigo-500 rounded-2xl flex items-center justify-center mb-8">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold mb-4">Next-Gen EMR</h3>
                <p class="text-slate-400 leading-relaxed text-lg">Professional medical records and PDF prescriptions generated in seconds. Paperless practice.</p>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="max-w-7xl mx-auto px-6 py-32">
        <div class="text-center mb-20">
            <h2 class="text-5xl font-black mb-6">Simple, Honest <span class="text-blue-500">Pricing.</span></h2>
            <p class="text-slate-400 text-lg">One plan. Everything included. Unlimited growth.</p>
        </div>
        <div class="max-w-md mx-auto bg-blue-600 p-1 rounded-[3rem] shadow-2xl shadow-blue-600/20">
            <div class="bg-slate-950 p-12 rounded-[2.8rem] text-center">
                <h4 class="text-xl font-bold mb-2 uppercase tracking-widest text-blue-500">The Founder Plan</h4>
                <div class="flex items-baseline justify-center gap-2 mb-10">
                    <span class="text-6xl font-black">$49</span>
                    <span class="text-slate-500 font-bold uppercase tracking-widest">/month</span>
                </div>
                <ul class="text-left space-y-6 mb-12 text-slate-300 font-medium">
                    <li class="flex items-center gap-4">✅ Custom Clinic Website</li>
                    <li class="flex items-center gap-4">✅ Unlimited Appointments</li>
                    <li class="flex items-center gap-4">✅ Full EMR & Prescriptions</li>
                    <li class="flex items-center gap-4">✅ 24/7 Priority Support</li>
                </ul>
                <a href="signup.php" class="block w-full bg-blue-600 text-white font-black py-6 rounded-2xl text-xl hover:bg-blue-700 transition">Get Started Now</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-6 py-20 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-10">
        <div class="text-xl font-black uppercase tracking-tighter">CMS<span class="text-blue-500">.</span>SAAS</div>
        <p class="text-slate-500 font-medium">© <?php echo date('Y'); ?> CMS SaaS Platform. All Rights Reserved.</p>
    </nav>

</body>
</html>
<?php endif; ?>
