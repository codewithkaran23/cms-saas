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
            <a href="index.php" class="flex items-center gap-3">
                <div
                    class="w-10 h-10 emerald-gradient text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i data-lucide="heart-pulse" class="w-6 h-6"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase text-slate-900">MED<span
                        class="text-emerald-600">OS</span></span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="index.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Home</a>
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

    <section class="py-32 bg-[#0b1120] text-white relative overflow-hidden">
        <!-- Premium Mesh Gradient Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[60%] h-[60%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute -bottom-[20%] -right-[10%] w-[60%] h-[60%] bg-emerald-600/5 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-24 items-center relative z-10">

            <!-- LEFT CONTENT -->
            <div class="space-y-12">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-[10px] font-black text-emerald-400 uppercase tracking-widest">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        System Performance
                    </div>
                    <h2 class="text-5xl md:text-6xl font-black leading-[1.05] tracking-tighter">
                        Your clinic isn’t slow. <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-600">Your software is.</span>
                    </h2>
                    <p class="text-slate-400 text-lg leading-relaxed max-w-xl font-medium">
                        MedOS is built to solve the administrative friction that slows down modern healthcare. We return 2+ hours of your day back to clinical care.
                    </p>
                </div>

                <!-- FEATURES -->
                <div class="space-y-4">
                    <!-- FEATURE 1 -->
                    <div class="group p-6 rounded-3xl border border-white/5 hover:bg-white/[0.03] hover:border-white/10 transition-all duration-500 flex gap-6 items-start">
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i data-lucide="zap" class="w-7 h-7"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-1 tracking-tight">Documentation at the speed of care</h5>
                            <p class="text-slate-400 text-sm leading-relaxed font-medium">
                                Stop spending hours on notes. Our smart templates learn your style and finish clinical documentation before the patient leaves the room.
                            </p>
                        </div>
                    </div>

                    <!-- FEATURE 2 -->
                    <div class="group p-6 rounded-3xl border border-white/5 hover:bg-white/[0.03] hover:border-white/10 transition-all duration-500 flex gap-6 items-start">
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i data-lucide="database" class="w-7 h-7"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-1 tracking-tight">Data that moves with you</h5>
                            <p class="text-slate-400 text-sm leading-relaxed font-medium">
                                No more spinning wheels. Access complete patient history, lab reports, and vitals in under 200ms on any device, anywhere.
                            </p>
                        </div>
                    </div>

                    <!-- FEATURE 3 -->
                    <div class="group p-6 rounded-3xl border border-white/5 hover:bg-white/[0.03] hover:border-white/10 transition-all duration-500 flex gap-6 items-start">
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i data-lucide="layout-grid" class="w-7 h-7"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-1 tracking-tight">Chaos-free patient journeys</h5>
                            <p class="text-slate-400 text-sm leading-relaxed font-medium">
                                A unified hub for scheduling and queue management that keeps your staff synchronized and your waiting rooms empty.
                            </p>
                        </div>
                    </div>

                    <!-- FEATURE 4 -->
                    <div class="group p-6 rounded-3xl border border-white/5 hover:bg-white/[0.03] hover:border-white/10 transition-all duration-500 flex gap-6 items-start">
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i data-lucide="credit-card" class="w-7 h-7"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-1 tracking-tight">Clinical-First revenue engine</h5>
                            <p class="text-slate-400 text-sm leading-relaxed font-medium">
                                Billing is no longer an afterthought. Generate professional invoices and track revenue directly from your clinical workspace.
                            </p>
                        </div>
                    </div>
                </div>

            </div>


            <!-- RIGHT SIDE (REAL VALUE VISUAL) -->
            <div class="relative">
                <div class="relative z-10 p-5 bg-white/5 rounded-[4rem] border border-white/10 backdrop-blur-sm shadow-[0_0_50px_-12px_rgba(16,185,129,0.3)] overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1581056771107-24ca5f033842?q=80&w=2070&auto=format&fit=crop" 
                         alt="Doctor using MedOS" 
                         class="w-full h-auto rounded-[3rem] opacity-90 transition-all duration-700 group-hover:scale-105 group-hover:opacity-100">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b1120] via-transparent to-transparent opacity-60"></div>

                    <!-- Floating Interface Badge -->
                    <div class="absolute bottom-10 left-10 right-10 p-8 bg-white/[0.03] backdrop-blur-2xl border border-white/10 rounded-[2.5rem] shadow-2xl animate-float">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 border border-emerald-500/30">
                                <i data-lucide="zap" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <div class="inline-flex items-center gap-2 px-2 py-0.5 bg-emerald-500/20 rounded text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-2">Performance Boost</div>
                                <p class="text-2xl font-black text-white tracking-tighter">Zero Latency Dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Abstract Glows -->
                <div class="absolute -top-10 -right-10 w-96 h-96 bg-emerald-500/20 blur-[120px] rounded-full animate-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-96 h-96 bg-emerald-600/10 blur-[120px] rounded-full animate-pulse" style="animation-delay: 1.5s;"></div>
            </div>


        </div>
    </section>
    <section class="py-32 bg-[#f8fafc] relative overflow-hidden">
        <!-- Modern Background Accents -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-100/30 rounded-full blur-[100px] -z-10 animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-emerald-50/50 rounded-full blur-[100px] -z-10 animate-pulse" style="animation-delay: 2s;"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <!-- HEADER -->
            <div class="text-center mb-28">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    Professional Suite
                </div>
                <h2 class="text-5xl md:text-6xl font-black text-slate-900 tracking-tightest mb-8 leading-tight">
                    One system. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400">Every part of your clinic.</span>
                </h2>
                <p class="text-slate-500 text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                    From the first booking to the final payment — MedOS handles everything for your whole team in one place.
                </p>
            </div>            <!-- GRID -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

                <!-- APPOINTMENT SYSTEM -->
                <div class="group p-10 rounded-[3rem] bg-white border border-slate-100/50 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.08)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-10 shadow-sm border border-emerald-100 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-500/20 transition-all duration-500">
                        <i data-lucide="calendar-check" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Online Bookings</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10 font-medium">
                        Patients can book online. We send automatic reminders to their phone so they don't forget.
                    </p>
                    <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Saves 4hrs / Day</span>
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- CLINICAL RECORDS -->
                <div class="group p-10 rounded-[3rem] bg-white border border-slate-100/50 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.08)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-10 shadow-sm border border-emerald-100 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-500/20 transition-all duration-500">
                        <i data-lucide="file-text" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Patient Records</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10 font-medium">
                        See patient history and reports instantly. Write your notes faster with easy templates.
                    </p>
                    <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Safe Storage</span>
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- E-PRESCRIPTIONS -->
                <div class="group p-10 rounded-[3rem] bg-white border border-slate-100/50 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.08)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-10 shadow-sm border border-emerald-100 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-500/20 transition-all duration-500">
                        <i data-lucide="stethoscope" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Digital Prescriptions</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10 font-medium">
                        Create and send prescriptions in seconds. They are clear, safe, and easy for patients to get on their phones.
                    </p>
                    <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Fast & Easy</span>
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- SMART QUEUE -->
                <div class="group p-10 rounded-[3rem] bg-white border border-slate-100/50 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.08)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-10 shadow-sm border border-emerald-100 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-500/20 transition-all duration-500">
                        <i data-lucide="users" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Live Patient Queue</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10 font-medium">
                        Track patients live and show their status on a TV. No more waiting room mess.
                    </p>
                    <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Real-time Sync</span>
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- TELEHEALTH -->
                <div class="group p-10 rounded-[3rem] bg-white border border-slate-100/50 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.08)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-10 shadow-sm border border-emerald-100 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-500/20 transition-all duration-500">
                        <i data-lucide="video" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Online Video Calls</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10 font-medium">
                        Talk to patients over high-quality video inside the system. No need for extra apps.
                    </p>
                    <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Easy Video</span>
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>

                <!-- BILLING -->
                <div class="group p-10 rounded-[3rem] bg-white border border-slate-100/50 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.08)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-10 shadow-sm border border-emerald-100 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-500/20 transition-all duration-500">
                        <i data-lucide="credit-card" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Easy Billing</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10 font-medium">
                        Create simple bills and track payments instantly. Manage your money without extra software.
                    </p>
                    <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Money Tracking</span>
                        </div>
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

    <!-- Customer Stories Section -->
    <section class="py-20 bg-white relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 border border-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6">
                    Customer Stories
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 tracking-tightest mb-6 leading-[1.1]">
                    Trusted by Clinics <br> Across the Globe
                </h2>
                <p class="text-lg text-slate-500 max-w-xl mx-auto font-medium">From solo practitioners to multi-branch networks — here’s what our customers say.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Testimonial 1 -->
                <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.05)] transition-all duration-500 flex flex-col justify-between group">
                    <div>
                        <div class="flex gap-1 mb-8">
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                        </div>
                        <p class="text-slate-600 text-base font-medium leading-relaxed italic mb-8">
                            "MedOS has completely transformed how we manage our clinic. We went from manual paper records to a fully automated system in just 45 minutes."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-50">
                        <div class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center font-black text-[10px] shadow-lg shadow-sky-500/20 group-hover:scale-110 transition-transform">RK</div>
                        <div>
                            <h4 class="font-black text-slate-900 text-xs tracking-tight">Dr. Rajesh Kumar</h4>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Medical Director, City Clinic</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.05)] transition-all duration-500 flex flex-col justify-between group">
                    <div>
                        <div class="flex gap-1 mb-8">
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                        </div>
                        <p class="text-slate-600 text-base font-medium leading-relaxed italic mb-8">
                            "The multi-branch sync is incredibly powerful. I can track all three of my clinics from one dashboard in real-time. It's a lifesaver."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-50">
                        <div class="w-10 h-10 bg-violet-500 text-white rounded-full flex items-center justify-center font-black text-[10px] shadow-lg shadow-violet-500/20 group-hover:scale-110 transition-transform">SM</div>
                        <div>
                            <h4 class="font-black text-slate-900 text-xs tracking-tight">Sarah Miller</h4>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Ops Manager, HealthFirst</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.05)] transition-all duration-500 flex flex-col justify-between group">
                    <div>
                        <div class="flex gap-1 mb-8">
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                        </div>
                        <p class="text-slate-600 text-base font-medium leading-relaxed italic mb-8">
                            "Billing used to be our biggest headache. With MedOS, we've reduced our errors to zero. It's the best investment we've made."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-50">
                        <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center font-black text-[10px] shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform">AB</div>
                        <div>
                            <h4 class="font-black text-slate-900 text-xs tracking-tight">Amit Bhardwaj</h4>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Owner, LifeLine Diagnostics</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-32 bg-[#fcfdfd] relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-600/5 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/4"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    Transparent Pricing
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 tracking-tightest mb-6 leading-[1.1]">
                    Simple plans for <br>
                    <span class="text-emerald-600">modern practices.</span>
                </h2>
                <p class="text-lg text-slate-500 max-w-xl mx-auto font-medium">Start free. Scale as your clinic grows. No hidden fees, no implementation charges—ever.</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <!-- Starter Plan -->
                <div class="group p-12 rounded-[2rem] bg-white border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.05)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="mb-12">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Starter</span>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-6xl font-black text-slate-900 tracking-tighter">$0</span>
                            <span class="text-slate-400 font-bold text-sm">/month</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-6 mb-12">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            1 Doctor Portal
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Up to 50 Patients
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Clinical Records
                        </li>
                    </ul>

                    <a href="login.php" class="block text-center w-full py-5 bg-slate-50 text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all">Get Started Free</a>
                </div>

                <!-- Practice Plan -->
                <div class="group p-12 rounded-[2rem] bg-white border-2 border-emerald-500 shadow-[0_40px_100px_rgba(16,185,129,0.15)] -translate-y-4 relative overflow-hidden scale-105 z-20">
                    <div class="absolute top-0 right-0 px-6 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-bl-3xl">Most Popular</div>
                    
                    <!-- Internal Glow -->
                    <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-emerald-500/5 rounded-full blur-[80px] pointer-events-none"></div>

                    <div class="mb-12 relative z-10">
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Practice</span>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-6xl font-black text-slate-900 tracking-tighter">$49</span>
                            <span class="text-slate-400 font-bold text-sm">/month</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-6 mb-12 relative z-10">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Unlimited Doctors
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Unlimited Patients
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Automated Billing
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Patient Portal Access
                        </li>
                    </ul>

                    <a href="login.php" class="block text-center w-full py-6 emerald-gradient text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-500/30 hover:scale-105 active:scale-95 transition-all relative z-10">Start 14-Day Trial</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="group p-12 rounded-[2rem] bg-white border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.05)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="mb-12">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Enterprise</span>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-5xl font-black text-slate-900 tracking-tighter">Custom</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-6 mb-12">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Multi-Branch Sync
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Dedicated Manager
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Custom API Access
                        </li>
                    </ul>

                    <a href="contact.php" class="block text-center w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-32 bg-white relative overflow-hidden" x-data="{ active: null }">
        <!-- Background Accents -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-50/50 rounded-full blur-[120px] -z-10 animate-pulse"></div>
        
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <div class="text-center mb-24">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    Help Center
                </div>
                <h2 class="text-5xl font-black text-slate-900 tracking-tightest mb-6 leading-tight">
                    Got questions? <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400">We've got answers.</span>
                </h2>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Everything you need to know about MedOS.</p>
            </div>

            <div class="space-y-4">
                <!-- Q1 -->
                <div class="group border border-slate-100 rounded-[2rem] overflow-hidden transition-all duration-500"
                    :class="active === 1 ? 'bg-[#f8fafc] border-emerald-500/30 shadow-[0_0_40px_-10px_rgba(16,185,129,0.2)]' : 'bg-white hover:bg-slate-50/50'">
                    <button @click="active = active === 1 ? null : 1"
                        class="w-full p-8 flex items-center justify-between text-left transition-all">
                        <span class="text-lg font-black text-slate-900 tracking-tight">Is MedOS compliant with healthcare data laws?</span>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shadow-sm"
                             :class="active === 1 ? 'bg-emerald-500 text-white rotate-45 shadow-emerald-500/40' : 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100'">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </div>
                    </button>
                    <div x-show="active === 1" x-collapse
                        class="p-8 pt-0 text-slate-500 text-base leading-relaxed border-t border-slate-50 font-medium">
                        Absolutely. MedOS utilizes bank-level AES-256 encryption and follows international healthcare
                        data protection standards (including HIPAA/GDPR compatibility). Your patient data is siloed and
                        protected at the highest tier.
                    </div>
                </div>

                <!-- Q2 -->
                <div class="group border border-slate-100 rounded-[2rem] overflow-hidden transition-all duration-500"
                    :class="active === 2 ? 'bg-[#f8fafc] border-emerald-500/30 shadow-[0_0_40px_-10px_rgba(16,185,129,0.2)]' : 'bg-white hover:bg-slate-50/50'">
                    <button @click="active = active === 2 ? null : 2"
                        class="w-full p-8 flex items-center justify-between text-left transition-all">
                        <span class="text-lg font-black text-slate-900 tracking-tight">How long does it take to migrate our current data?</span>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shadow-sm"
                             :class="active === 2 ? 'bg-emerald-500 text-white rotate-45 shadow-emerald-500/40' : 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100'">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </div>
                    </button>
                    <div x-show="active === 2" x-collapse
                        class="p-8 pt-0 text-slate-500 text-base leading-relaxed border-t border-slate-50 font-medium">
                        Our Intelligent Importer can handle thousands of records in minutes. Most clinics are fully
                        operational on MedOS in under an hour. We provide a migration guarantee to ensure zero data
                        loss.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="group border border-slate-100 rounded-[2rem] overflow-hidden transition-all duration-500"
                    :class="active === 3 ? 'bg-[#f8fafc] border-emerald-500/30 shadow-[0_0_40px_-10px_rgba(16,185,129,0.2)]' : 'bg-white hover:bg-slate-50/50'">
                    <button @click="active = active === 3 ? null : 3"
                        class="w-full p-8 flex items-center justify-between text-left transition-all">
                        <span class="text-lg font-black text-slate-900 tracking-tight">Do you offer support for multi-branch clinics?</span>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shadow-sm"
                             :class="active === 3 ? 'bg-emerald-500 text-white rotate-45 shadow-emerald-500/40' : 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100'">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </div>
                    </button>
                    <div x-show="active === 3" x-collapse
                        class="p-8 pt-0 text-slate-500 text-base leading-relaxed border-t border-slate-50 font-medium">
                        Yes, our Enterprise plan includes specialized multi-branch sync, centralized reporting, and a
                        dedicated account manager to handle complex clinical networks.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-white relative overflow-hidden">
        <!-- Professional Emerald Background Glows -->
        <div class="absolute top-1/2 left-0 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2 -z-10 animate-pulse"></div>
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-emerald-500/5 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/3 -z-10 animate-pulse" style="animation-delay: 1.5s;"></div>
        
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="bg-[#0b1120] rounded-[2.5rem] p-10 md:p-20 text-center relative overflow-hidden shadow-2xl border border-white/5">
                <!-- Emerald Internal Glows -->
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
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-5 mb-16">
                        <a href="login.php" class="w-full sm:w-auto px-10 py-5 bg-white text-slate-900 rounded-full font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-white/10 flex items-center justify-center gap-2 group">
                            Start Your Free Trial
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="contact.php" class="w-full sm:w-auto px-10 py-5 border border-white/20 text-white rounded-full font-black text-xs uppercase tracking-widest hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            Talk to Our Team
                        </a>
                    </div>
                    
                    <!-- Trust Bar (Compact) -->
                    <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4 pt-10 border-t border-white/5">
                        <div class="flex items-center gap-2 text-slate-500 text-[9px] font-black uppercase tracking-widest">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500"></i>
                            No credit card
                        </div>
                        <div class="flex items-center gap-2 text-slate-500 text-[9px] font-black uppercase tracking-widest">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500"></i>
                            Free forever (≤2 docs)
                        </div>
                        <div class="flex items-center gap-2 text-slate-500 text-[9px] font-black uppercase tracking-widest">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500"></i>
                            Cancel anytime
                        </div>
                        <div class="flex items-center gap-2 text-slate-500 text-[9px] font-black uppercase tracking-widest">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500"></i>
                            Enterprise Security
                        </div>
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
                        <i data-lucide="heart-pulse" class="w-6 h-6"></i>
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