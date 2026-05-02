<?php
// index.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedOS | The Operating System for Modern Medical Practices</title>
    
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
                    }
                }
            }
        }
    </script>
    
    <style>
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .emerald-gradient { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .text-gradient { background: linear-gradient(135deg, #064e3b 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#fcfdfd] text-slate-600 font-sans selection:bg-emerald-100 selection:text-emerald-900">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-[100] border-b border-slate-100/50 glass">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 emerald-gradient text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i data-lucide="heart-pulse" class="w-6 h-6"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase text-slate-900">MED<span
                        class="text-emerald-600">OS</span></span>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="about.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">About
                    Us</a>
                <a href="how-it-works.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">How
                    it Works</a>
                <a href="pricing.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="contact.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="login.php"
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">Sign
                    In</a>
                <a href="login.php"
                    class="px-6 py-2.5 emerald-gradient text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Get
                    Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-32 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-3xl opacity-60">
            </div>
            <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-teal-50 rounded-full blur-3xl opacity-60">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
            <div class="text-left">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-full text-emerald-700 text-[10px] font-black uppercase tracking-widest mb-8">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    The Operating System for Modern Healthcare
                </div>

                <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-[1.1] mb-8">
                    Modern software for your clinic. <br>
                    <span class="text-gradient">Better care for your patients.</span>
                </h1>

                <p class="text-xl text-slate-500 font-medium mb-12">
                    Stop using paper and old, slow software. MedOS is a fast and easy way to manage your entire clinic in one place.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 mb-10">
                    <a href="login.php"
                        class="w-full sm:w-auto px-10 py-5 emerald-gradient text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/30 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                        Launch Your Practice <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="how-it-works.php"
                        class="w-full sm:w-auto px-10 py-5 bg-white border border-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all flex items-center justify-center gap-3">
                        See How it Works
                    </a>
                </div>

                <!-- Floating Trust Badges Inline -->
                <div class="flex flex-wrap gap-3">
                    <div
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-100 rounded-xl shadow-sm">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">AES-256
                            Secure</span>
                    </div>
                    <div
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-100 rounded-xl shadow-sm">
                        <i data-lucide="clock" class="w-4 h-4 text-emerald-500"></i>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Real-time
                            Sync</span>
                    </div>
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="relative">
                <div class="absolute inset-0 bg-emerald-500/10 blur-[100px] rounded-full -z-10 animate-pulse"></div>
                <div class="p-3 bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl scale-110">
                    <img src="medos_hero.png" alt="MedOS Clinical Dashboard" class="w-full h-auto rounded-3xl">
                </div>

                <!-- Mini Float Stats -->
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-3xl shadow-2xl border border-slate-50 animate-float z-20"
                    style="animation-delay: 1s;">
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Clinic Performance</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter">+42% Efficiency</p>
                </div>
            </div>
        </div>

        <!-- Trusted By Logos -->

        <!-- Clinical Impact: Trust by the Numbers -->
        <div class="pt-24 border-t border-slate-100/50">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-12">
                <!-- Stat 1 -->
                <div class="text-center px-6">
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">3,200+</p>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Active Practices</p>
                </div>
                <!-- Stat 2 -->
                <div class="text-center px-6 border-l border-slate-100">
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">1.2M+</p>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Patients Managed</p>
                </div>
                <!-- Stat 3 -->
                <div class="text-center px-6 border-l border-slate-100">
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">99.9%</p>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Clinical Uptime</p>
                </div>
                <!-- Stat 4 -->
                <div class="text-center px-6 border-l border-slate-100">
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">AES-256</p>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Security Standard</p>
                </div>
            </div>
        </div>
        </div>
    </section>

    <section class="py-32 bg-slate-900 text-white relative overflow-hidden">

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center">

            <!-- LEFT CONTENT -->
            <div class="space-y-10">

                <h2 class="text-5xl font-black leading-tight">
                    Your clinic isn’t slow. <br>
                    <span class="text-emerald-500">Your software is.</span>
                </h2>

                <p class="text-slate-400 text-lg leading-relaxed max-w-xl">
                    MedOS is built to solve the administrative friction that slows down modern healthcare. We return 2+ hours of your day back to clinical care.
                </p>

                <!-- FEATURES -->
                <div class="space-y-6">

                    <!-- FEATURE 1 -->
                    <div class="flex gap-5 items-start group">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center mt-1 group-hover:bg-emerald-500 group-hover:text-white transition-all shrink-0">
                            <i data-lucide="zap" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-2">Documentation at the speed of care</h5>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                Stop spending hours on notes. Our smart templates learn your style and finish clinical documentation before the patient leaves the room.
                            </p>
                        </div>
                    </div>

                    <!-- FEATURE 2 -->
                    <div class="flex gap-5 items-start group">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center mt-1 group-hover:bg-emerald-500 group-hover:text-white transition-all shrink-0">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-2">Data that moves with you</h5>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                No more spinning wheels. Access complete patient history, lab reports, and vitals in under 200ms on any device, anywhere.
                            </p>
                        </div>
                    </div>

                    <!-- FEATURE 3 -->
                    <div class="flex gap-5 items-start group">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center mt-1 group-hover:bg-emerald-500 group-hover:text-white transition-all shrink-0">
                            <i data-lucide="layout-grid" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-2">Chaos-free patient journeys</h5>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                A unified hub for scheduling and queue management that keeps your staff synchronized and your waiting rooms empty.
                            </p>
                        </div>
                    </div>
                    <!-- FEATURE 4 -->
                    <div class="flex gap-5 items-start group">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center mt-1 group-hover:bg-emerald-500 group-hover:text-white transition-all shrink-0">
                            <i data-lucide="credit-card" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-2">Clinical-First revenue engine</h5>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                Billing is no longer an afterthought. Generate professional invoices and track revenue directly from your clinical workspace.
                            </p>
                        </div>
                    </div>

                </div>

            </div>


            <!-- RIGHT SIDE (REAL VALUE VISUAL) -->
            <div class="relative">
                <div class="relative z-10 p-4 bg-white/5 rounded-[3rem] border border-white/10 backdrop-blur-sm shadow-2xl overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1581056771107-24ca5f033842?q=80&w=2070&auto=format&fit=crop" 
                         alt="Doctor using MedOS" 
                         class="w-full h-auto rounded-[2.5rem] opacity-90 group-hover:opacity-100 transition-opacity duration-500 shadow-2xl">
                    
                    <!-- Floating Interface Badge -->
                    <div class="absolute bottom-8 left-8 right-8 p-6 bg-slate-900/90 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl animate-float">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-500">
                                <i data-lucide="zap" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Performance Boost</p>
                                <p class="text-lg font-black text-white tracking-tight">Zero Latency Dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Abstract Glow Behind Image -->
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-emerald-500/20 blur-[100px] rounded-full animate-pulse"></div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-emerald-500/10 blur-[100px] rounded-full animate-pulse" style="animation-delay: 2s;"></div>
            </div>


        </div>
      <!-- Detailed Features -->
    <section class="py-32 bg-[#fcfdfd] relative overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-emerald-50/50 rounded-full blur-3xl -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-6">
            <!-- HEADER -->
            <div class="text-center mb-24">
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em] mb-4">Everything your clinic needs</p>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-6">
                    One system. <span class="text-gradient">Every part of your clinic.</span>
                </h2>
                <p class="text-slate-500 text-lg max-w-2xl mx-auto font-medium">
                    From the first booking to the final payment — MedOS handles everything for your whole team in one place.
                </p>
            </div>

            <!-- GRID -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- APPOINTMENT SYSTEM -->
                <div class="p-10 rounded-[3rem] border border-slate-100 bg-white/50 backdrop-blur-sm group hover:border-emerald-200 hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-500">
                    <div class="w-16 h-16 emerald-gradient text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-500/20 transition-all">
                        <i data-lucide="calendar-check" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Online Bookings</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 font-medium">
                        Patients can book online. We send automatic reminders to their phone so they don't forget.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Saves 4hrs / Day</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- CLINICAL RECORDS -->
                <div class="p-10 rounded-[3rem] border border-slate-100 bg-white/50 backdrop-blur-sm group hover:border-emerald-200 hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-500">
                    <div class="w-16 h-16 emerald-gradient text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-500/20 transition-all">
                        <i data-lucide="file-text" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Patient Records</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 font-medium">
                        See patient history and reports instantly. Write your notes faster with easy templates.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Safe Storage</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- E-PRESCRIPTIONS -->
                <div class="p-10 rounded-[3rem] border border-slate-100 bg-white/50 backdrop-blur-sm group hover:border-emerald-200 hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-500">
                    <div class="w-16 h-16 emerald-gradient text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-500/20 transition-all">
                        <i data-lucide="stethoscope" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Digital Prescriptions</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 font-medium">
                        Create and send prescriptions in seconds. They are clear, safe, and easy for patients to get on their phones.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Fast & Easy</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- SMART QUEUE -->
                <div class="p-10 rounded-[3rem] border border-slate-100 bg-white/50 backdrop-blur-sm group hover:border-emerald-200 hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-500">
                    <div class="w-16 h-16 emerald-gradient text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-500/20 transition-all">
                        <i data-lucide="users" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Live Patient Queue</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 font-medium">
                        Track patients live and show their status on a TV. No more waiting room mess.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Real-time Sync</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- TELEHEALTH -->
                <div class="p-10 rounded-[3rem] border border-slate-100 bg-white/50 backdrop-blur-sm group hover:border-emerald-200 hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-500">
                    <div class="w-16 h-16 emerald-gradient text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-500/20 transition-all">
                        <i data-lucide="video" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Online Video Calls</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 font-medium">
                        Talk to patients over high-quality video inside the system. No need for extra apps.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Easy Video</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- BILLING -->
                <div class="p-10 rounded-[3rem] border border-slate-100 bg-white/50 backdrop-blur-sm group hover:border-emerald-200 hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-500">
                    <div class="w-16 h-16 emerald-gradient text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-emerald-500/20 transition-all">
                        <i data-lucide="credit-card" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Easy Billing</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 font-medium">
                        Create simple bills and track payments instantly. Manage your money without extra software.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Money Tracking</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

            </div>

            <!-- BOTTOM TRUST LINE -->
            <p class="text-center text-[10px] font-black text-slate-400 mt-20 uppercase tracking-[0.3em]">
                Built for real clinics — not confusing hospital systems
            </p>

        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-32 bg-white" x-data="{ active: null }">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-4">Got questions? <span
                        class="text-emerald-600">We've got answers.</span></h2>
                <p class="text-slate-500 font-bold text-sm uppercase tracking-widest">Everything you need to know about
                    MedOS.</p>
            </div>

            <div class="space-y-4">
                <!-- Q1 -->
                <div class="border border-slate-100 rounded-3xl overflow-hidden transition-all"
                    :class="active === 1 ? 'shadow-xl shadow-emerald-900/5 border-emerald-200' : ''">
                    <button @click="active = active === 1 ? null : 1"
                        class="w-full p-8 flex items-center justify-between text-left hover:bg-slate-50 transition-all">
                        <span class="font-black text-slate-900 tracking-tight">Is MedOS compliant with healthcare data
                            laws?</span>
                        <i data-lucide="plus" class="w-5 h-5 transition-transform"
                            :class="active === 1 ? 'rotate-45' : ''"></i>
                    </button>
                    <div x-show="active === 1" x-cloak
                        class="p-8 pt-0 text-slate-500 text-sm leading-relaxed border-t border-slate-50">
                        Absolutely. MedOS utilizes bank-level AES-256 encryption and follows international healthcare
                        data protection standards (including HIPAA/GDPR compatibility). Your patient data is siloed and
                        protected at the highest tier.
                    </div>
                </div>
                <!-- Q2 -->
                <div class="border border-slate-100 rounded-3xl overflow-hidden transition-all"
                    :class="active === 2 ? 'shadow-xl shadow-emerald-900/5 border-emerald-200' : ''">
                    <button @click="active = active === 2 ? null : 2"
                        class="w-full p-8 flex items-center justify-between text-left hover:bg-slate-50 transition-all">
                        <span class="font-black text-slate-900 tracking-tight">How long does it take to migrate our
                            current data?</span>
                        <i data-lucide="plus" class="w-5 h-5 transition-transform"
                            :class="active === 2 ? 'rotate-45' : ''"></i>
                    </button>
                    <div x-show="active === 2" x-cloak
                        class="p-8 pt-0 text-slate-500 text-sm leading-relaxed border-t border-slate-50">
                        Our Intelligent Importer can handle thousands of records in minutes. Most clinics are fully
                        operational on MedOS in under an hour. We provide a migration guarantee to ensure zero data
                        loss.
                    </div>
                </div>
                <!-- Q3 -->
                <div class="border border-slate-100 rounded-3xl overflow-hidden transition-all"
                    :class="active === 3 ? 'shadow-xl shadow-emerald-900/5 border-emerald-200' : ''">
                    <button @click="active = active === 3 ? null : 3"
                        class="w-full p-8 flex items-center justify-between text-left hover:bg-slate-50 transition-all">
                        <span class="font-black text-slate-900 tracking-tight">Do you offer support for multi-branch
                            clinics?</span>
                        <i data-lucide="plus" class="w-5 h-5 transition-transform"
                            :class="active === 3 ? 'rotate-45' : ''"></i>
                    </button>
                    <div x-show="active === 3" x-cloak
                        class="p-8 pt-0 text-slate-500 text-sm leading-relaxed border-t border-slate-50">
                        Yes, our Enterprise plan includes specialized multi-branch sync, centralized reporting, and a
                        dedicated account manager to handle complex clinical networks.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-32 bg-slate-900 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-teal-500/10 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2">
        </div>

        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-5xl md:text-8xl font-black text-white tracking-tighter mb-8 leading-[0.9]">Ready to
                transform your practice?</h2>
            <p class="text-xl text-slate-400 mb-12 font-medium">Join over 3,200 medical professionals who run their
                practices without stress. Set up in under an hour.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="login.php"
                    class="w-full sm:w-auto px-12 py-6 bg-white text-slate-900 rounded-[2.5rem] font-black text-sm uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-white/10">Start
                    Your Free Trial</a>
                <a href="contact.php"
                    class="w-full sm:w-auto px-12 py-6 border border-white/20 text-white rounded-[2.5rem] font-black text-sm uppercase tracking-widest hover:bg-white/5 transition-all">Talk
                    to Our Team</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12 mb-20 text-left">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 emerald-gradient text-white rounded-lg flex items-center justify-center">
                        <i data-lucide="heart-pulse" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xl font-black tracking-tighter uppercase text-slate-900">MED<span
                            class="text-emerald-600">OS</span></span>
                </div>
                <p class="text-xs text-slate-500 font-bold leading-relaxed">The OS for modern practices. High-fidelity
                    clinical management built for the next generation of healthcare.</p>
            </div>

            <div>
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Product</h4>
                <ul class="space-y-4">
                    <li><a href="how-it-works.php"
                            class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">How
                            it Works</a></li>
                    <li><a href="pricing.php"
                            class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Pricing
                            Tiers</a></li>
                    <li><a href="login.php"
                            class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Staff
                            Portal</a></li>
                    <li><a href="login.php"
                            class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Patient
                            Portal</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Company</h4>
                <ul class="space-y-4">
                    <li><a href="about.php"
                            class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Our
                            Mission</a></li>
                    <li><a href="contact.php"
                            class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Contact
                            Sales</a></li>
                    <li><a href="#"
                            class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Security
                            Standards</a></li>
                    <li><a href="#"
                            class="text-[11px] font-black text-slate-600 hover:text-emerald-600 transition-colors uppercase tracking-widest">Privacy
                            Policy</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Connect</h4>
                <div class="flex gap-4">
                    <a href="#"
                        class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100"><i
                            data-lucide="twitter" class="w-5 h-5"></i></a>
                    <a href="#"
                        class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100"><i
                            data-lucide="linkedin" class="w-5 h-5"></i></a>
                    <a href="#"
                        class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100"><i
                            data-lucide="mail" class="w-5 h-5"></i></a>
                </div>
            </div>
        </div>

        <div
            class="max-w-7xl mx-auto px-6 pt-10 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">© 2026 MedOS Clinical Systems.
                All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>