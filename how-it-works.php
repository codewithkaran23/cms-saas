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

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        emerald: { 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b' }
                    },
                    letterSpacing: {
                        tightest: '-.04em',
                        tighter: '-.03em',
                    }
                }
            }
        }
    </script>
    <style>
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .emerald-gradient { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .text-gradient { background: linear-gradient(135deg, #064e3b 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#fcfdfd] text-slate-600 font-sans selection:bg-emerald-100 selection:text-emerald-900">

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
                <a href="index.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Home</a>
                <a href="about.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">About Us</a>
                <a href="how-it-works.php" class="text-xs font-black uppercase tracking-widest text-emerald-600">How it Works</a>
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
    <header class="relative pt-[8rem] pb-20 overflow-hidden bg-white">
        <!-- Ambient Background Glows -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-[120px] opacity-60 animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-teal-50 rounded-full blur-[120px] opacity-60 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center">
            <div class="text-left relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-10">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    AVERAGE SETUP TIME: UNDER 30 MINUTES
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 tracking-tightest leading-[1.1] mb-8">
                    Clinical Management <br>
                    <span class="text-gradient">That Runs Itself.</span>
                </h1>
                <p class="text-lg text-slate-500 font-medium leading-relaxed mb-10 max-w-xl">
                    Experience a zero-friction migration. MedOS is designed to integrate seamlessly into your clinic's daily workflow without the learning curve.
                </p>

                <div class="flex items-center gap-6">
                    <div class="flex -space-x-3">
                        <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=100&h=100" alt="Doctor" class="w-11 h-11 rounded-full border-2 border-white shadow-lg object-cover">
                        <img src="https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&q=80&w=100&h=100" alt="Doctor" class="w-11 h-11 rounded-full border-2 border-white shadow-lg object-cover">
                        <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&q=80&w=100&h=100" alt="Doctor" class="w-11 h-11 rounded-full border-2 border-white shadow-lg object-cover">
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Join 3,200+ Professionals</p>
                </div>
            </div>

            <div class="relative">
                <!-- Subtle Ambient Glow -->
                <div class="absolute -inset-20 bg-emerald-500/5 blur-[120px] rounded-full -z-10 opacity-50"></div>
                <div class="mix-blend-multiply">
                    <img src="Adobe Express - file.png" alt="MedOS Clinical Workflow" class="w-full h-auto" style="mask-image: linear-gradient(to top right, transparent 0%, black 25%); -webkit-mask-image: linear-gradient(to top right, transparent 0%, black 25%);">
                </div>
            </div>
        </div>
    </header>

    <!-- Roadmap Introduction -->
    <section class="py-16 bg-[#f8fafc] border-y border-slate-100/50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">The Implementation Roadmap</p>
            <h2 class="text-3xl font-black text-slate-900 tracking-tightest">4 Steps to Clinical Excellence</h2>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="py-32 relative overflow-hidden bg-white">
        <!-- Subtle Connecting Line -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-px h-full bg-slate-100 hidden lg:block"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-x-24 gap-y-32">
                
                <!-- Step 01 -->
                <div class="flex gap-10 group">
                    <span class="text-6xl font-black text-slate-100 group-hover:text-emerald-500 transition-all duration-500 tracking-tightest leading-none">01</span>
                    <div class="space-y-6">
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Easy Registration</h3>
                        <p class="text-lg text-slate-500 font-medium leading-relaxed">Create your personal health profile and connect with your doctor in minutes. Our simple, secure setup guide makes it effortless to get started.</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100/50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Personal Profile</span>
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100/50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Secure Access</span>
                        </div>
                    </div>
                </div>

                <!-- Step 02 -->
                <div class="flex gap-10 group lg:translate-y-16">
                    <span class="text-6xl font-black text-slate-100 group-hover:text-emerald-500 transition-all duration-500 tracking-tightest leading-none">02</span>
                    <div class="space-y-6">
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Direct Booking</h3>
                        <p class="text-lg text-slate-500 font-medium leading-relaxed">Schedule consultations and manage your appointments with ease. No more waiting rooms—just expert care when you need it.</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100/50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Instant Booking</span>
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100/50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Reminders</span>
                        </div>
                    </div>
                </div>

                <!-- Step 03 -->
                <div class="flex gap-10 group">
                    <span class="text-6xl font-black text-slate-100 group-hover:text-emerald-500 transition-all duration-500 tracking-tightest leading-none">03</span>
                    <div class="space-y-6">
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Seamless Consultations</h3>
                        <p class="text-lg text-slate-500 font-medium leading-relaxed">Connect through secure messaging or video. Get prescriptions and medical advice instantly, without the hassle of administrative static.</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100/50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Secure Chat</span>
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100/50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">E-Prescriptions</span>
                        </div>
                    </div>
                </div>

                <!-- Step 04 -->
                <div class="flex gap-10 group lg:translate-y-16">
                    <span class="text-6xl font-black text-slate-100 group-hover:text-emerald-500 transition-all duration-500 tracking-tightest leading-none">04</span>
                    <div class="space-y-6">
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Health Management</h3>
                        <p class="text-lg text-slate-500 font-medium leading-relaxed">Track your progress, manage medications, and view your history in one unified dashboard. Complete healthcare in your pocket.</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100/50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Health Tracking</span>
                            <span class="px-3 py-1 bg-emerald-50 border border-emerald-100/50 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest">Medication Logs</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-32 bg-[#fcfdfd] relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 relative z-10" x-data="{ active: null }">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    FAQ
                </div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tightest mb-4">Common Questions</h2>
                <p class="text-slate-500 font-medium text-lg">Everything you need to know about MedOS.</p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50" :class="active === 1 ? 'ring-2 ring-emerald-500/20 border-emerald-500/30 shadow-2xl shadow-emerald-900/5' : ''">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-8 py-7 text-left flex justify-between items-center group">
                        <span class="text-lg font-semibold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">How long does the initial setup take?</span>
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                             <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform duration-500" :class="active === 1 ? 'rotate-45 text-emerald-600' : ''"></i>
                        </div>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <div class="px-8 pb-8 text-slate-500 text-base font-medium leading-relaxed pt-2">
                            Most clinics are fully operational within 30 minutes. Our automated setup wizard guides you through branding, doctor profiles, and scheduling configuration without requiring technical help.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50" :class="active === 2 ? 'ring-2 ring-emerald-500/20 border-emerald-500/30 shadow-2xl shadow-emerald-900/5' : ''">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-8 py-7 text-left flex justify-between items-center group">
                        <span class="text-lg font-semibold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">Is my patient data secure?</span>
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                             <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform duration-500" :class="active === 2 ? 'rotate-45 text-emerald-600' : ''"></i>
                        </div>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <div class="px-8 pb-8 text-slate-500 text-base font-medium leading-relaxed pt-2">
                            Security is our priority. MedOS uses HIPAA-compliant data encryption and is hosted on enterprise-grade AWS infrastructure with automated daily backups and 99.9% uptime.
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50" :class="active === 3 ? 'ring-2 ring-emerald-500/20 border-emerald-500/30 shadow-2xl shadow-emerald-900/5' : ''">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-8 py-7 text-left flex justify-between items-center group">
                        <span class="text-lg font-semibold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">Can I migrate from my current software?</span>
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                             <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform duration-500" :class="active === 3 ? 'rotate-45 text-emerald-600' : ''"></i>
                        </div>
                    </button>
                    <div x-show="active === 3" x-collapse x-cloak>
                        <div class="px-8 pb-8 text-slate-500 text-base font-medium leading-relaxed pt-2">
                            Yes! We provide bulk import tools for patient records and history. Our team can also assist with custom data migrations to ensure you don't lose any clinical data during the transition.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="bg-[#0b1120] rounded-[3.5rem] p-10 md:p-20 text-center relative overflow-hidden shadow-2xl border border-white/5">
                <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-emerald-500/20 rounded-full blur-[100px] pointer-events-none animate-pulse"></div>
                <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-emerald-600/10 rounded-full blur-[100px] pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-8">
                        Get Started Today
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tightest mb-8 leading-[1.1]">
                        Ready to transform <br> your practice?
                    </h2>
                    
                    <p class="text-lg text-slate-400 max-w-xl mx-auto mb-12 font-medium leading-relaxed">
                        Join over 3,200 medical professionals who run their practices without stress. Set up in under an hour.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
                        <a href="login.php" class="w-full sm:w-auto px-10 py-5 bg-white text-slate-900 rounded-full font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-white/10 flex items-center justify-center gap-2 group">
                            Launch Your Practice
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                        <a href="contact.php" class="w-full sm:w-auto px-10 py-5 border border-white/20 text-white rounded-full font-black text-xs uppercase tracking-widest hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            Talk to Our Team
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-12 gap-16 mb-24">
            <!-- Brand Column -->
            <div class="md:col-span-4">
                <a href="index.php" class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 emerald-gradient text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M12 5 9.04 7.96a2.17 2.17 0 0 0 0 3.08v0c.82.82 2.13.82 2.96 0"/><path d="m12 10 2.96 2.96a2.17 2.17 0 0 0 3.08 0v0c.82-.82.82-2.13 0-2.96L15 7"/></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tighter uppercase text-slate-900">MED<span class="text-emerald-600">OS</span></span>
                </a>
                <p class="text-sm text-slate-500 font-medium leading-relaxed max-w-sm mb-10">
                    The OS for modern practices. High-fidelity clinical management built for the next generation of healthcare delivery.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-emerald-500 hover:text-white hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 border border-slate-100/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-emerald-500 hover:text-white hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 border border-slate-100/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-emerald-500 hover:text-white hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 border border-slate-100/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                </div>
            </div>

            <!-- Links Columns -->
            <div class="md:col-span-2">
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-8">Product</h4>
                <ul class="space-y-4">
                    <li><a href="how-it-works.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">How it Works</a></li>
                    <li><a href="pricing.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Pricing Plans</a></li>
                    <li><a href="login.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Staff Portal</a></li>
                    <li><a href="login.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Patient Portal</a></li>
                </ul>
            </div>

            <div class="md:col-span-2">
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-8">Company</h4>
                <ul class="space-y-4">
                    <li><a href="about.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Our Mission</a></li>
                    <li><a href="contact.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Contact Sales</a></li>
                    <li><a href="#" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Security Standards</a></li>
                    <li><a href="#" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="md:col-span-4">
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-8">Stay Updated</h4>
                <p class="text-xs text-slate-500 font-bold mb-6">Join our newsletter for clinical management tips.</p>
                <div class="relative group">
                    <input type="email" placeholder="Enter your email" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all">
                    <button class="absolute right-2 top-2 bottom-2 px-4 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all">Join</button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 pt-12 border-t border-slate-50 flex flex-col md:row justify-between items-center gap-6">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">
                © 2026 MedOS Clinical Systems. All rights reserved. Built with excellence.
            </p>
        </div>
    </footer>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>

</html>
