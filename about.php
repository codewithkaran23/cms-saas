<?php
// about.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | MedOS Modern Healthcare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        emerald: { 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b' }
                    }
                }
            }
        }
    </script>
    <style>
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .emerald-gradient { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .text-gradient { background: linear-gradient(135deg, #064e3b 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-[#fcfdfd] text-slate-600 font-sans">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-[100] border-b border-slate-100/50 glass">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 emerald-gradient text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i data-lucide="heart-pulse" class="w-6 h-6"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase text-slate-900">MED<span class="text-emerald-600">OS</span></span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="about.php" class="text-xs font-black uppercase tracking-widest text-emerald-600">About Us</a>
                <a href="how-it-works.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">How it Works</a>
                <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="contact.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Contact</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="login.php" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">Sign In</a>
                <a href="login.php" class="px-6 py-2.5 emerald-gradient text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <header class="pt-48 pb-24 bg-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter mb-8 leading-[1.1]">We're on a mission to <br><span class="text-gradient">modernize healthcare.</span></h1>
            <p class="text-xl text-slate-500 font-medium leading-relaxed">MedOS was built with a single goal: to return the doctor's focus to the patient by eliminating the administrative static of legacy systems.</p>
        </div>
    </header>

    <!-- The Mission Section -->
    <section class="py-32">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-20 items-center">
            <div class="relative">
                <div class="w-full aspect-square bg-emerald-50 rounded-[4rem] relative overflow-hidden">
                    <div class="absolute inset-10 bg-white rounded-[3rem] shadow-2xl shadow-emerald-900/10 flex items-center justify-center">
                         <i data-lucide="shield-check" class="w-48 h-48 text-emerald-100"></i>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 bg-white p-10 rounded-[2.5rem] shadow-2xl border border-slate-100 max-w-xs">
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-3">Our Core Promise</p>
                    <p class="text-base font-bold text-slate-700 leading-relaxed italic">"Security isn't a feature; it's the foundation of everything we build."</p>
                </div>
            </div>
            
            <div class="space-y-12">
                <div class="space-y-6">
                    <h3 class="text-4xl font-black text-slate-900 tracking-tight">Returning the focus to healing.</h3>
                    <p class="text-lg text-slate-500 leading-relaxed">Medical professionals spend up to 40% of their workday on administrative tasks. We believe that time should be spent with patients. MedOS automates the friction of clinical management, from scheduling to high-fidelity records, so doctors can be doctors again.</p>
                </div>
                
                <div class="grid grid-cols-2 gap-10">
                    <div class="p-8 bg-slate-50 rounded-[2rem]">
                        <h4 class="text-5xl font-black text-emerald-600 mb-2">3,200+</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Clinics Powered</p>
                    </div>
                    <div class="p-8 bg-slate-50 rounded-[2rem]">
                        <h4 class="text-5xl font-black text-emerald-600 mb-2">10M+</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lives Impacted</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="py-32 bg-slate-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-24">
                <h2 class="text-4xl font-black text-white tracking-tight mb-4">Our Core <span class="text-emerald-500">Values</span></h2>
                <p class="text-slate-400 font-bold text-sm uppercase tracking-widest">The principles that drive the Emerald Standard.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <div class="space-y-6">
                    <div class="w-14 h-14 bg-white/10 text-emerald-500 rounded-2xl flex items-center justify-center"><i data-lucide="heart" class="w-7 h-7"></i></div>
                    <h5 class="text-xl font-black text-white">Patient-First Thinking</h5>
                    <p class="text-slate-400 text-sm leading-relaxed">Every feature we build is designed to improve the patient experience. If it doesn't help the patient, it doesn't belong in MedOS.</p>
                </div>
                <div class="space-y-6">
                    <div class="w-14 h-14 bg-white/10 text-emerald-500 rounded-2xl flex items-center justify-center"><i data-lucide="zap" class="w-7 h-7"></i></div>
                    <h5 class="text-xl font-black text-white">Zero-Friction Design</h5>
                    <p class="text-slate-400 text-sm leading-relaxed">We obsess over clicks. We minimize steps. We ensure that clinical documentation is fast, intuitive, and high-fidelity.</p>
                </div>
                <div class="space-y-6">
                    <div class="w-14 h-14 bg-white/10 text-emerald-500 rounded-2xl flex items-center justify-center"><i data-lucide="shield-check" class="w-7 h-7"></i></div>
                    <h5 class="text-xl font-black text-white">Absolute Privacy</h5>
                    <p class="text-slate-400 text-sm leading-relaxed">Healthcare data is sacred. We use industry-leading encryption and decentralized architectures to protect patient privacy at all costs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Standards -->
    <section class="py-32 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-emerald-50 rounded-full text-emerald-700 text-xs font-black uppercase tracking-widest mb-10">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Bank-Level Security Standards
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-8">Your clinical data, <br>protected by the <span class="text-emerald-600">Emerald Vault.</span></h2>
            <p class="text-lg text-slate-500 font-medium leading-relaxed mb-16">We understand the responsibility of managing health data. That's why we built MedOS on a cloud-native architecture with 256-bit AES encryption at rest and in transit.</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="p-6 border border-slate-100 rounded-3xl">
                    <h6 class="font-black text-slate-900 text-xs uppercase tracking-widest mb-2">HIPAA</h6>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Compliant</p>
                </div>
                <div class="p-6 border border-slate-100 rounded-3xl">
                    <h6 class="font-black text-slate-900 text-xs uppercase tracking-widest mb-2">GDPR</h6>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Compliant</p>
                </div>
                <div class="p-6 border border-slate-100 rounded-3xl">
                    <h6 class="font-black text-slate-900 text-xs uppercase tracking-widest mb-2">AES-256</h6>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Encrypted</p>
                </div>
                <div class="p-6 border border-slate-100 rounded-3xl">
                    <h6 class="font-black text-slate-900 text-xs uppercase tracking-widest mb-2">SLA</h6>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">99.9% Uptime</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">© 2026 MedOS Clinical Systems. Built for the Next Generation of Healthcare.</p>
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>
