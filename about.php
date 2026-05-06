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
    <header class="relative pt-48 pb-32 overflow-hidden bg-white">
        <!-- Ambient Background Glows -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-[120px] opacity-60 animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-teal-50 rounded-full blur-[120px] opacity-60 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white border border-slate-100 rounded-full shadow-sm mb-12">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Our Story</span>
            </div>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 tracking-tightest mb-10 leading-[1.05]">
                We're on a mission to <br>
                <span class="text-emerald-600">modernize healthcare.</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto mb-20">
                MedOS was built with a single goal: to return the doctor's focus to the patient by eliminating the administrative static of legacy systems.
            </p>

            <!-- Core Value Boxes -->
            <div class="flex flex-wrap items-center justify-center gap-4">
                <!-- Value 1 -->
                <div class="flex items-center gap-4 px-6 py-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-emerald-100 transition-all group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Our Focus</p>
                        <p class="text-xs font-bold text-slate-900">Patient-First Care</p>
                    </div>
                </div>

                <!-- Value 2 -->
                <div class="flex items-center gap-4 px-6 py-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-emerald-100 transition-all group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Our Drive</p>
                        <p class="text-xs font-bold text-slate-900">Constant Innovation</p>
                    </div>
                </div>

                <!-- Value 3 -->
                <div class="flex items-center gap-4 px-6 py-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-emerald-100 transition-all group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Our Promise</p>
                        <p class="text-xs font-bold text-slate-900">Bank-Level Security</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- The Mission Section -->
    <section class="py-32 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-24 items-center">
            <!-- Visual Side -->
            <div class="relative group">
                <!-- 3D Mission Workflow Image -->
                <div class="relative w-full aspect-square rounded-[3rem] overflow-hidden">
                    <img src="about.png" alt="MedOS Mission Workflow" class="w-full h-full object-contain mix-blend-multiply">
                </div>

                <!-- Accent Glow -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-emerald-500/10 rounded-full blur-[120px] -z-10 "></div>
            </div>

            <!-- Content Side -->
            <div class="space-y-12">
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                        The Problem
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        Returning the focus <br>to healing.
                    </h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed">
                        Medical professionals spend up to 40% of their workday on administrative tasks. We believe that time should be spent with patients. MedOS automates the friction of clinical management, from scheduling to high-fidelity records, so doctors can be doctors again.
                    </p>
                </div>
                
                <div class="grid sm:grid-cols-2 gap-8 pt-8 border-t border-slate-100">
                    <div class="p-8 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="text-4xl lg:text-5xl font-black text-emerald-600 tracking-tightest mb-2">3,200+</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Clinics Powered</p>
                    </div>
                    <div class="p-8 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="text-4xl lg:text-5xl font-black text-emerald-600 tracking-tightest mb-2">10M+</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lives Impacted</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="py-32 bg-[#0b1120] relative overflow-hidden">
        <!-- High-Fidelity Ambient Glows -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-emerald-500/10 rounded-full blur-[150px] translate-x-1/4 -translate-y-1/4 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[800px] h-[800px] bg-emerald-600/5 rounded-full blur-[150px] -translate-x-1/4 translate-y-1/4 animate-pulse" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-24">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-8">
                    The Emerald Standard
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tightest mb-6 leading-tight">
                    The principles that drive the <br>
                    <span class="text-emerald-500">Emerald Standard.</span>
                </h2>
                <p class="text-slate-400 text-base md:text-lg font-medium max-w-xl mx-auto leading-relaxed">
                    We're committed to building tools that prioritize doctors and their patients above all else.
                </p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="group relative p-10 bg-white/[0.03] border border-white/10 rounded-[2.5rem] hover:bg-white/[0.06] hover:border-emerald-500/30 transition-all duration-700 overflow-hidden shadow-2xl">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all"></div>
                    
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-[1.25rem] flex items-center justify-center mb-8 shadow-inner group-hover:scale-110 transition-transform duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        </div>
                        <h5 class="text-xl font-bold text-white mb-4">Patient-First Thinking</h5>
                        <p class="text-slate-400 text-base leading-relaxed font-medium">Every feature we build is designed to improve the patient experience. If it doesn't help the patient, it doesn't belong in MedOS.</p>
                    </div>
                </div>

                <!-- Value 2 -->
                <div class="group relative p-10 bg-white/[0.03] border border-white/10 rounded-[2.5rem] hover:bg-white/[0.06] hover:border-emerald-500/30 transition-all duration-700 overflow-hidden shadow-2xl">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all"></div>

                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-[1.25rem] flex items-center justify-center mb-8 shadow-inner group-hover:scale-110 transition-transform duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m13 2-2 10h9L11 22l2-10H4l9-10z"/></svg>
                        </div>
                        <h5 class="text-xl font-bold text-white mb-4">Zero-Friction Design</h5>
                        <p class="text-slate-400 text-base leading-relaxed font-medium">We obsess over clicks. We minimize steps. We ensure that clinical documentation is fast, intuitive, and high-fidelity.</p>
                    </div>
                </div>

                <!-- Value 3 -->
                <div class="group relative p-10 bg-white/[0.03] border border-white/10 rounded-[2.5rem] hover:bg-white/[0.06] hover:border-emerald-500/30 transition-all duration-700 overflow-hidden shadow-2xl">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all"></div>

                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-[1.25rem] flex items-center justify-center mb-8 shadow-inner group-hover:scale-110 transition-transform duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <h5 class="text-xl font-bold text-white mb-4">Absolute Privacy</h5>
                        <p class="text-slate-400 text-base leading-relaxed font-medium">Healthcare data is sacred. We use industry-leading encryption and decentralized architectures to protect patient privacy at all costs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Standards -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center gap-3 px-6 py-3 bg-emerald-50 rounded-full text-emerald-700 text-[10px] font-black uppercase tracking-widest mb-10 border border-emerald-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Bank-Level Security Standards
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tightest mb-8 leading-tight">
                Your clinical data, protected <br>by the <span class="text-emerald-600">Emerald Vault.</span>
            </h2>
            <p class="text-lg text-slate-500 font-medium leading-relaxed mb-16 max-w-2xl mx-auto">
                We understand the responsibility of managing health data. That's why we built MedOS on a cloud-native architecture with 256-bit AES encryption at rest and in transit.
            </p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="p-8 bg-slate-50 border border-slate-100 rounded-3xl group hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                    <h6 class="font-black text-slate-900 text-[10px] uppercase tracking-widest mb-3 text-emerald-600">HIPAA</h6>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Compliant</p>
                </div>
                <div class="p-8 bg-slate-50 border border-slate-100 rounded-3xl group hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                    <h6 class="font-black text-slate-900 text-[10px] uppercase tracking-widest mb-3 text-emerald-600">GDPR</h6>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Compliant</p>
                </div>
                <div class="p-8 bg-slate-50 border border-slate-100 rounded-3xl group hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                    <h6 class="font-black text-slate-900 text-[10px] uppercase tracking-widest mb-3 text-emerald-600">AES-256</h6>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Encrypted</p>
                </div>
                <div class="p-8 bg-slate-50 border border-slate-100 rounded-3xl group hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
                    <h6 class="font-black text-slate-900 text-[10px] uppercase tracking-widest mb-3 text-emerald-600">SLA</h6>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">99.9% Uptime</p>
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
