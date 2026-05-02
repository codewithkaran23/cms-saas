<?php
// how-it-works.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How it Works | MedOS Service Flow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        .step-number-gradient { background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#fcfdfd] text-slate-600 font-sans selection:bg-emerald-100 selection:text-emerald-900">

    <!-- Navigation (Exact from index.php) -->
    <nav class="fixed top-0 w-full z-[100] border-b border-slate-100/50 glass">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 emerald-gradient text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i data-lucide="heart-pulse" class="w-6 h-6"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase text-slate-900">MED<span class="text-emerald-600">OS</span></span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="about.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">About Us</a>
                <a href="how-it-works.php" class="text-xs font-black uppercase tracking-widest text-emerald-600 transition-colors">How it Works</a>
                <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="contact.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="login.php" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">Sign In</a>
                <a href="login.php" class="px-6 py-2.5 emerald-gradient text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-48 pb-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
            <div class="text-left">
                <!-- Badge (Exact from index.php) -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full text-emerald-700 text-[10px] font-black uppercase tracking-widest mb-10">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    ⚡ AVERAGE SETUP TIME: UNDER 30 MINUTES
                </div>
                
                <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-[1.1] mb-8">
                    Clinical Management <br>
                    <span class="text-gradient">That Runs Itself.</span>
                </h1>
                <p class="text-lg text-slate-500 font-medium leading-relaxed mb-10">
                    Experience a zero-friction migration. MedOS is designed to integrate seamlessly into your clinic's daily workflow without the learning curve.
                </p>

                <div class="flex items-center gap-6">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-200"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-300"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-400"></div>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Join 3,200+ Practices</p>
                </div>
            </div>

            <div class="relative">
                <div class="absolute inset-0 bg-emerald-500/10 blur-[100px] rounded-full -z-10"></div>
                <div class="p-3 bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl scale-110">
                    <img src="medos_hero.png" alt="MedOS Clinical Dashboard" class="w-full h-auto rounded-3xl">
                </div>
            </div>
        </div>
    </header>


    <!-- Intro Section -->
    <section class="py-12 bg-white/50 border-y border-slate-100/50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">The Implementation Roadmap</p>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter">4 Steps to Clinical Excellence</h2>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-emerald-50/30 rounded-full blur-3xl -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-x-20 gap-y-32">
                
                <!-- Step 01 -->
                <div class="flex gap-8 group">
                    <span class="text-6xl font-black step-number-gradient opacity-20 group-hover:opacity-100 transition-opacity">01</span>
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Instant Onboarding</h3>
                        <p class="text-slate-500 leading-relaxed">Register your practice and customize your clinic's public profile in minutes. Our self-serve wizard handles the heavy lifting.</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Clinic Branding</span>
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Public Web-site</span>
                        </div>
                    </div>
                </div>

                <!-- Step 02 -->
                <div class="flex gap-8 group">
                    <span class="text-6xl font-black step-number-gradient opacity-20 group-hover:opacity-100 transition-opacity">02</span>
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Smart Queue Sync</h3>
                        <p class="text-slate-500 leading-relaxed">Configure your OP schedule and activate the Digital Token System. Sync waitlists with TV displays and patient SMS notifications automatically.</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Live Token TV</span>
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">SMS Alerts</span>
                        </div>
                    </div>
                </div>

                <!-- Step 03 -->
                <div class="flex gap-8 group">
                    <span class="text-6xl font-black step-number-gradient opacity-20 group-hover:opacity-100 transition-opacity">03</span>
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Digital Care Delivery</h3>
                        <p class="text-slate-500 leading-relaxed">Chart vitals and generate intelligent E-Prescriptions in under 60 seconds. Our clinical database assists with accurate dosages and history tracking.</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">60s Prescriptions</span>
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Patient 360</span>
                        </div>
                    </div>
                </div>

                <!-- Step 04 -->
                <div class="flex gap-8 group">
                    <span class="text-6xl font-black step-number-gradient opacity-20 group-hover:opacity-100 transition-opacity">04</span>
                    <div class="space-y-4">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Integrated ecosystem</h3>
                        <p class="text-slate-500 leading-relaxed">Close the care loop with automated lab orders, pharmacy sync, and professional billing. A unified hub for your entire clinic operation.</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Lab/Pharmacy Sync</span>
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Billing ERP</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-slate-50/50">
        <div class="max-w-4xl mx-auto px-6" x-data="{ active: null }">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-4">Common Questions</h2>
                <p class="text-slate-500 font-bold text-sm uppercase tracking-widest">Everything you need to know about MedOS.</p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden transition-all" :class="active === 1 ? 'shadow-xl shadow-emerald-900/5 border-emerald-200' : ''">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                        <span class="text-base font-black text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">How long does the initial setup take?</span>
                        <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform" :class="active === 1 ? 'rotate-45' : ''"></i>
                    </button>
                    <div x-show="active === 1" x-cloak class="px-8 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4">
                        Most clinics are fully operational within 30 minutes. Our automated setup wizard guides you through branding, doctor profiles, and scheduling configuration without requiring technical help.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden transition-all" :class="active === 2 ? 'shadow-xl shadow-emerald-900/5 border-emerald-200' : ''">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                        <span class="text-base font-black text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">Is my patient data secure?</span>
                        <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform" :class="active === 2 ? 'rotate-45' : ''"></i>
                    </button>
                    <div x-show="active === 2" x-cloak class="px-8 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4">
                        Security is our priority. MedOS uses HIPAA-compliant data encryption and is hosted on enterprise-grade AWS infrastructure with automated daily backups and 99.9% uptime.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden transition-all" :class="active === 3 ? 'shadow-xl shadow-emerald-900/5 border-emerald-200' : ''">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                        <span class="text-base font-black text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">Can I migrate from my current software?</span>
                        <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform" :class="active === 3 ? 'rotate-45' : ''"></i>
                    </button>
                    <div x-show="active === 3" x-cloak class="px-8 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4">
                        Yes! We provide bulk import tools for patient records and history. Our team can also assist with custom data migrations to ensure you don't lose any clinical data during the transition.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-slate-900 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute inset-0 bg-emerald-500/10 blur-[120px] rounded-full -z-10"></div>
                
                <h2 class="text-4xl md:text-7xl font-black text-white tracking-tighter mb-8 relative z-10 leading-[0.9]">
                    Ready to transform your practice?
                </h2>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 relative z-10">
                    <a href="login.php" class="w-full sm:w-auto px-12 py-6 emerald-gradient text-white rounded-[2.5rem] font-black text-sm uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Launch Your Practice</a>
                    <a href="contact.php" class="w-full sm:w-auto px-12 py-6 border border-white/20 text-white rounded-[2.5rem] font-black text-sm uppercase tracking-widest hover:bg-white/5 transition-all">Talk to Our Team</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (Exact from index.php) -->
    <footer class="bg-white border-t border-slate-100 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12 mb-20 text-left">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 emerald-gradient text-white rounded-lg flex items-center justify-center">
                        <i data-lucide="heart-pulse" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xl font-black tracking-tighter uppercase text-slate-900">MED<span class="text-emerald-600">OS</span></span>
                </div>
                <p class="text-xs text-slate-500 font-bold leading-relaxed">The OS for modern practices. High-fidelity clinical management built for the next generation of healthcare.</p>
            </div>
            
            <div>
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Product</h4>
                <ul class="space-y-4">
                    <li><a href="how-it-works.php" class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">How it Works</a></li>
                    <li><a href="pricing.php" class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Pricing Tiers</a></li>
                    <li><a href="login.php" class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Staff Portal</a></li>
                    <li><a href="login.php" class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Patient Portal</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Company</h4>
                <ul class="space-y-4">
                    <li><a href="about.php" class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Our Mission</a></li>
                    <li><a href="contact.php" class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Contact Sales</a></li>
                    <li><a href="#" class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Security Standards</a></li>
                    <li><a href="#" class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Privacy Policy</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Connect</h4>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                    <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100"><i data-lucide="linkedin" class="w-5 h-5"></i></a>
                    <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100"><i data-lucide="mail" class="w-5 h-5"></i></a>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 pt-10 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">© 2026 MedOS Clinical Systems. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
