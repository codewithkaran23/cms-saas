<?php
// pricing.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans | MedOS Patient Care</title>

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
                <a href="how-it-works.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">How it Works</a>
                <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-emerald-600">Pricing</a>
                <a href="contact.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="login.php" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">Sign In</a>
                <a href="login.php" class="px-6 py-2.5 emerald-gradient text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Page Header (Expansion Section) -->
    <header class="relative pt-56 pb-40 overflow-hidden bg-white">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full">
                <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] bg-emerald-50/50 rounded-full blur-[120px] animate-pulse"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-teal-50/50 rounded-full blur-[120px] animate-pulse" style="animation-delay: 3s;"></div>
            </div>
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#059669 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>
        </div>

        <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white border border-slate-100 rounded-full shadow-sm mb-12">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Pricing Plans</span>
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-slate-900 tracking-tightest mb-10 leading-[1.05]">
                Personalized medical care, <br>
                <span class="text-emerald-600">on your schedule.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto mb-20">
                Choose a plan that fits your needs and get direct access to professional medical care. Simple, secure, and designed around you.
            </p>
            
            <!-- Trust Boxes -->
            <div class="flex flex-wrap items-center justify-center gap-4">
                <!-- Privacy -->
                <div class="flex items-center gap-4 px-6 py-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-emerald-100 transition-all group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Privacy</p>
                        <p class="text-xs font-bold text-slate-900">100% Confidential</p>
                    </div>
                </div>

                <!-- Availability -->
                <div class="flex items-center gap-4 px-6 py-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-emerald-100 transition-all group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M12 5 9.04 7.96a2.17 2.17 0 0 0 0 3.08v0c.82.82 2.13.82 2.96 0"/><path d="m12 10 2.96 2.96a2.17 2.17 0 0 0 3.08 0v0c.82-.82.82-2.13 0-2.96L15 7"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Availability</p>
                        <p class="text-xs font-bold text-slate-900">Direct Access</p>
                    </div>
                </div>

                <!-- Support -->
                <div class="flex items-center gap-4 px-6 py-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-emerald-100 transition-all group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Support</p>
                        <p class="text-xs font-bold text-slate-900">Expert Guidance</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Pricing Cards Section -->
    <section class="py-24 bg-[#f8fafc] border-y border-slate-100 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    Pricing Plans
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 tracking-tightest mb-4 leading-tight">
                    Choose the right plan <br>
                    <span class="text-emerald-600">for your care needs.</span>
                </h2>
                <p class="text-slate-500 text-base font-medium max-w-xl mx-auto">Flexible plans designed to help patients connect with expert medical care anytime, anywhere.</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 items-stretch mb-12">
                <!-- Basic Plan -->
                <div class="group relative p-10 bg-white border border-slate-200 rounded-[2.5rem] hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 flex flex-col">
                    <div class="mb-8">
                        <div class="inline-flex items-center px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[9px] font-black text-slate-400 uppercase tracking-widest mb-6 uppercase">Essential Care</div>
                        <h4 class="text-xl font-bold text-slate-900 tracking-tight mb-2">Basic</h4>
                        <div class="flex items-baseline gap-1 mt-4">
                            <span class="text-4xl font-black text-slate-900 tracking-tightest">Free</span>
                        </div>
                        <p class="text-slate-500 text-sm mt-4 font-medium leading-relaxed">Get started with simple access to doctor consultations and appointment booking.</p>
                    </div>
                    
                    <ul class="space-y-4 mb-10 border-t border-slate-50 pt-8 flex-grow">
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Book Appointments
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Secure Messaging
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Basic Health Support
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Limited Chat Access
                        </li>
                    </ul>
                    
                    <a href="login.php" class="mt-auto block text-center w-full py-5 bg-slate-50 text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all border border-slate-100 hover:border-emerald-500">Get Started</a>
                </div>

                <!-- Premium Plan -->
                <div class="group relative p-10 bg-white border-2 border-emerald-500 rounded-[3rem] shadow-2xl shadow-emerald-900/10 lg:-translate-y-6 z-10 flex flex-col">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-emerald-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/30">Most Popular</div>
                    
                    <div class="mb-8">
                        <div class="inline-flex items-center px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-6">Complete Care</div>
                        <h4 class="text-xl font-bold text-slate-900 tracking-tight mb-2 text-emerald-600">Premium</h4>
                        <div class="flex items-baseline gap-1 mt-4">
                            <span class="text-4xl font-black text-slate-900 tracking-tightest">$19</span>
                            <span class="text-slate-400 font-bold text-sm tracking-tight">/month</span>
                        </div>
                        <p class="text-slate-500 text-sm mt-4 font-medium leading-relaxed">Ideal for patients who need regular communication and faster medical support.</p>
                    </div>
                    
                    <ul class="space-y-4 mb-10 border-t border-slate-50 pt-8 flex-grow">
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Unlimited Doctor Chat
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Priority Appointment Booking
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Prescription Updates
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Consultation History
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Health Reminders
                        </li>
                    </ul>
                    
                    <a href="login.php" class="mt-auto block text-center w-full py-5 emerald-gradient text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Start Free Trial</a>
                </div>

                <!-- VIP Care Plan -->
                <div class="group relative p-10 bg-white border border-slate-200 rounded-[2.5rem] hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 flex flex-col">
                    <div class="mb-8">
                        <div class="inline-flex items-center px-3 py-1 bg-slate-900 border border-slate-800 rounded-lg text-[9px] font-black text-white uppercase tracking-widest mb-6">Personalized Care</div>
                        <h4 class="text-xl font-bold text-slate-900 tracking-tight mb-2">VIP Care</h4>
                        <div class="flex items-baseline gap-1 mt-4">
                            <span class="text-4xl font-black text-slate-900 tracking-tightest">$49</span>
                            <span class="text-slate-400 font-bold text-sm tracking-tight">/month</span>
                        </div>
                        <p class="text-slate-500 text-sm mt-4 font-medium leading-relaxed">Personalized healthcare access with priority support and direct consultation.</p>
                    </div>
                    
                    <ul class="space-y-4 mb-10 border-t border-slate-50 pt-8 flex-grow">
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Instant Doctor Messaging
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Video Consultations
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            24/7 Priority Access
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Personalized Health Monitoring
                        </li>
                        <li class="flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </div>
                            Dedicated Support
                        </li>
                    </ul>
                    
                    <a href="contact.php" class="mt-auto block text-center w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10">Subscribe Now</a>
                </div>
            </div>

            <!-- Trust Note -->
            <div class="text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Private, secure, and confidential healthcare communication.
                </p>
            </div>
        </div>
    </section>

    <!-- Comparison Table (White Background) -->
    <section class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">Care Deep Dive</div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tightest mb-4">Detailed Comparison</h2>
                <p class="text-slate-500 font-medium text-base">Choose the plan that fits your care needs perfectly.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-6 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Feature Group</th>
                            <th class="py-6 px-6 text-[10px] font-black text-slate-900 uppercase tracking-widest">Basic</th>
                            <th class="py-6 px-6 text-[10px] font-black text-emerald-600 uppercase tracking-widest">Premium</th>
                            <th class="py-6 px-6 text-[10px] font-black text-slate-900 uppercase tracking-widest">VIP Care</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="bg-slate-50/30">
                            <td class="py-5 px-6 font-bold text-slate-900 text-sm tracking-tight" colspan="4">Consultation Features</td>
                        </tr>
                        <tr>
                            <td class="py-5 px-6 text-sm font-medium text-slate-500">Booking Access</td>
                            <td class="py-5 px-6 text-sm font-bold text-slate-700">Standard</td>
                            <td class="py-5 px-6 text-sm font-bold text-emerald-600">Priority</td>
                            <td class="py-5 px-6 text-sm font-bold text-slate-700">Instant</td>
                        </tr>
                        <tr>
                            <td class="py-5 px-6 text-sm font-medium text-slate-500">Messaging</td>
                            <td class="py-5 px-6 text-sm font-bold text-slate-700">Limited</td>
                            <td class="py-5 px-6 text-sm font-bold text-emerald-600">Unlimited</td>
                            <td class="py-5 px-6 text-sm font-bold text-slate-700">Direct 24/7</td>
                        </tr>
                        <tr>
                            <td class="py-5 px-6 text-sm font-medium text-slate-500">Secure Records</td>
                            <td class="py-5 px-6 text-sm text-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></td>
                            <td class="py-5 px-6 text-sm text-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></td>
                            <td class="py-5 px-6 text-sm text-emerald-500"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- FAQ Section (Soft Slate Background) -->
    <section class="py-24 bg-slate-50/50 border-y border-slate-100 relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 relative z-10" x-data="{ active: null }">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    FAQ
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tightest mb-4">Patient FAQ</h2>
                <p class="text-slate-500 font-medium text-base">Everything you need to know about your healthcare access.</p>
            </div>
            <div class="space-y-3">
                <!-- Q1 -->
                <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50" :class="active === 1 ? 'ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-7 py-5 text-left flex justify-between items-center group">
                        <span class="text-base font-semibold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">Is my medical data safe?</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500" :class="active === 1 ? 'bg-emerald-500 text-white rotate-45' : 'bg-slate-50 text-slate-400'">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <div class="px-7 pb-6 text-slate-500 text-sm font-medium leading-relaxed">
                            Yes, your personal and medical information is fully encrypted and securely stored. Your privacy and confidentiality are always protected.
                        </div>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50" :class="active === 2 ? 'ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-7 py-5 text-left flex justify-between items-center group">
                        <span class="text-base font-semibold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">How can I contact the doctor?</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500" :class="active === 2 ? 'bg-emerald-500 text-white rotate-45' : 'bg-slate-50 text-slate-400'">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <div class="px-7 pb-6 text-slate-500 text-sm font-medium leading-relaxed">
                            You can connect with the doctor through secure chat or by booking an appointment directly from the platform.
                        </div>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50" :class="active === 3 ? 'ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-7 py-5 text-left flex justify-between items-center group">
                        <span class="text-base font-semibold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">Can I book and manage appointments online?</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500" :class="active === 3 ? 'bg-emerald-500 text-white rotate-45' : 'bg-slate-50 text-slate-400'">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                    </button>
                    <div x-show="active === 3" x-collapse x-cloak>
                        <div class="px-7 pb-6 text-slate-500 text-sm font-medium leading-relaxed">
                            Yes, you can easily book, reschedule, or cancel appointments anytime through your account.
                        </div>
                    </div>
                </div>

                <!-- Q4 -->
                <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50" :class="active === 4 ? 'ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full px-7 py-5 text-left flex justify-between items-center group">
                        <span class="text-base font-semibold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">What benefits do I get with a paid plan?</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500" :class="active === 4 ? 'bg-emerald-500 text-white rotate-45' : 'bg-slate-50 text-slate-400'">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                    </button>
                    <div x-show="active === 4" x-collapse x-cloak>
                        <div class="px-7 pb-6 text-slate-500 text-sm font-medium leading-relaxed">
                            Paid plans offer enhanced access, including unlimited messaging, priority responses, appointment scheduling, and additional healthcare support features.
                        </div>
                    </div>
                </div>

                <!-- Q5 -->
                <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-slate-200/50" :class="active === 5 ? 'ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 5 ? null : 5)" class="w-full px-7 py-5 text-left flex justify-between items-center group">
                        <span class="text-base font-semibold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">What should I do in case of an emergency?</span>
                        <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-500" :class="active === 5 ? 'bg-emerald-500 text-white rotate-45' : 'bg-slate-50 text-slate-400'">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </div>
                    </button>
                    <div x-show="active === 5" x-collapse x-cloak>
                        <div class="px-7 pb-6 text-slate-500 text-sm font-medium leading-relaxed">
                            This platform is not intended for emergency situations. Please contact your local emergency services immediately for urgent medical help.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="bg-[#0b1120] rounded-[3rem] p-10 md:p-16 text-center relative overflow-hidden shadow-2xl border border-white/5">
                <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-emerald-500/20 rounded-full blur-[100px] pointer-events-none animate-pulse"></div>
                <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-emerald-600/10 rounded-full blur-[100px] pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tightest mb-6 leading-tight">
                        Experience care <br> without the wait.
                    </h2>
                    <p class="text-base text-slate-400 max-w-xl mx-auto mb-10 font-medium leading-relaxed">
                        Connect with expert medical care anytime, anywhere. Start your personalized health journey today.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
                        <a href="login.php" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-900 rounded-full font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-white/10 flex items-center justify-center gap-2 group">
                            Start Your Journey
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
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
