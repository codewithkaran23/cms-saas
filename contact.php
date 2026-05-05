<?php
// contact.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | MedOS Partnership</title>
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
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#fcfdfd] text-slate-600 font-sans" x-data="{ tab: 'demo' }">

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
                <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="contact.php" class="text-xs font-black uppercase tracking-widest text-emerald-600">Contact</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="login.php" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">Sign In</a>
                <a href="login.php" class="px-6 py-2.5 emerald-gradient text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <header class="pt-48 pb-24 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-6xl md:text-8xl font-black text-slate-900 tracking-tighter mb-8 leading-tight">Let's build your <span class="text-gradient">modern practice.</span></h1>
            <p class="text-xl text-slate-500 font-medium leading-relaxed">Whether you're looking for a personalized demo or need technical support, our team is ready to assist you.</p>
        </div>
    </header>

    <!-- Contact Form & Info -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-3 gap-20">
            
            <!-- Left: Info -->
            <div class="space-y-12">
                <div>
                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-6">Our Headquarters</h4>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="map-pin" class="w-5 h-5"></i></div>
                            <p class="text-sm font-bold text-slate-700 leading-relaxed">123 Clinical Plaza, Innovation District<br>New York, NY 10001, USA</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="mail" class="w-5 h-5"></i></div>
                            <p class="text-sm font-bold text-slate-700 leading-relaxed">hello@medos-clinical.com<br>support@medos-clinical.com</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0"><i data-lucide="phone" class="w-5 h-5"></i></div>
                            <p class="text-sm font-bold text-slate-700 leading-relaxed">+1 (800) MED-OS-01<br>Mon-Fri, 9am - 6pm EST</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-6">Support Tiers</h4>
                    <div class="space-y-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <h6 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-1">Standard Support</h6>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">24-hour response time</p>
                        </div>
                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                            <h6 class="text-xs font-black text-emerald-700 uppercase tracking-widest mb-1">Priority Support</h6>
                            <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-widest">2-hour response time (Practice Plan)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="lg:col-span-2">
                <!-- Tab Switcher -->
                <div class="flex p-1 bg-slate-100 rounded-[2.5rem] mb-12">
                    <button @click="tab = 'demo'" :class="tab === 'demo' ? 'bg-white text-slate-900 shadow-xl' : 'text-slate-400'" 
                            class="flex-1 py-5 rounded-[2.5rem] text-xs font-black uppercase tracking-widest transition-all">Book a Demo</button>
                    <button @click="tab = 'support'" :class="tab === 'support' ? 'bg-white text-slate-900 shadow-xl' : 'text-slate-400'" 
                            class="flex-1 py-5 rounded-[2.5rem] text-xs font-black uppercase tracking-widest transition-all">Technical Support</button>
                </div>

                <div class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-2xl shadow-emerald-900/5">
                    <!-- Demo Form -->
                    <form x-show="tab === 'demo'" x-cloak class="space-y-8 animate-in fade-in duration-500">
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                                <input type="text" placeholder="Dr. John Smith" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Work Email</label>
                                <input type="email" placeholder="john@clinic.com" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Clinic Size</label>
                                <select class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 outline-none transition-all">
                                    <option>Solo Practitioner</option>
                                    <option>2-5 Doctors</option>
                                    <option>5-20 Doctors</option>
                                    <option>Large Hospital / Multi-branch</option>
                                </select>
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Specialization</label>
                                <input type="text" placeholder="e.g. Cardiology" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">How can we help you?</label>
                            <textarea rows="4" placeholder="Tell us about your practice's needs..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all"></textarea>
                        </div>
                        <button type="submit" class="w-full py-6 emerald-gradient text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-[1.02] transition-all">Schedule Demo Call</button>
                    </form>

                    <!-- Support Form -->
                    <form x-show="tab === 'support'" x-cloak class="space-y-8 animate-in fade-in duration-500">
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">User ID / Email</label>
                                <input type="text" placeholder="your@email.com" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Issue Category</label>
                                <select class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 outline-none transition-all">
                                    <option>Login / Access Issue</option>
                                    <option>Clinical Data Query</option>
                                    <option>Billing / Invoice Help</option>
                                    <option>General Bug Report</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Describe the Issue</label>
                            <textarea rows="6" placeholder="Please provide details about the problem..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all"></textarea>
                        </div>
                        <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/20 hover:scale-[1.02] transition-all">Submit Support Ticket</button>
                    </form>
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

    <script>lucide.createIcons();</script>
</body>
</html>
