<?php
// pricing.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans | MedOS Transparency</title>
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

    <!-- Page Header -->
    <header class="pt-48 pb-24 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter mb-8 leading-[1.1]">Simple, <span class="text-gradient">transparent pricing.</span></h1>
            <p class="text-xl text-slate-500 font-medium leading-relaxed">Start free. Scale as your clinic grows. No hidden fees, no implementation charges—ever.</p>
        </div>
    </header>

    <!-- Pricing Cards -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-8 mb-32">
                <!-- Starter Plan -->
                <div class="p-10 rounded-[3rem] border border-slate-100 bg-white group hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-500">
                    <div class="mb-10">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Starter</h4>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-black text-slate-900 tracking-tight">$0</span>
                            <span class="text-slate-400 font-bold text-sm">/month</span>
                        </div>
                        <p class="text-slate-500 text-sm mt-4 font-medium">Perfect for solo practitioners starting their digital journey.</p>
                    </div>
                    <ul class="space-y-4 mb-12">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> 1 Doctor Portal</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Up to 50 Patients</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Clinical Records</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Appointment Scheduling</li>
                    </ul>
                    <a href="login.php" class="block text-center w-full py-5 bg-slate-50 text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100">Get Started Free</a>
                </div>

                <!-- Practice Plan -->
                <div class="p-10 rounded-[3rem] border-2 border-emerald-500 bg-white relative shadow-2xl shadow-emerald-900/10 scale-105 z-10">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-emerald-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest">Most Popular</div>
                    <div class="mb-10">
                        <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-4">Practice</h4>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-black text-slate-900 tracking-tight">$49</span>
                            <span class="text-slate-400 font-bold text-sm">/month</span>
                        </div>
                        <p class="text-slate-500 text-sm mt-4 font-medium">For growing clinics with multiple doctors and staff.</p>
                    </div>
                    <ul class="space-y-4 mb-12">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Unlimited Doctors</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Unlimited Patients</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Automated Billing</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Patient Portal Access</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Priority Support</li>
                    </ul>
                    <a href="login.php" class="block text-center w-full py-5 emerald-gradient text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 transition-all">Start 14-Day Trial</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="p-10 rounded-[3rem] border border-slate-100 bg-white group hover:shadow-2xl hover:shadow-emerald-900/5 transition-all duration-500">
                    <div class="mb-10">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Enterprise</h4>
                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-black text-slate-900 tracking-tight">Custom</span>
                        </div>
                        <p class="text-slate-500 text-sm mt-4 font-medium">For hospitals and multi-branch clinical networks.</p>
                    </div>
                    <ul class="space-y-4 mb-12">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Multi-Branch Sync</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Dedicated Manager</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Custom API Access</li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700"><i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> 24/7 Phone Support</li>
                    </ul>
                    <a href="contact.php" class="block text-center w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all">Contact Sales</a>
                </div>
            </div>

            <!-- Comparison Table -->
            <div class="mt-32">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-4">Compare <span class="text-emerald-600">Features</span></h2>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-widest">Choose the plan that fits your practice perfectly.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="py-8 px-6 text-xs font-black text-slate-400 uppercase tracking-widest">Feature</th>
                                <th class="py-8 px-6 text-xs font-black text-slate-900 uppercase tracking-widest">Starter</th>
                                <th class="py-8 px-6 text-xs font-black text-emerald-600 uppercase tracking-widest">Practice</th>
                                <th class="py-8 px-6 text-xs font-black text-slate-900 uppercase tracking-widest">Enterprise</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <!-- Section: Core -->
                            <tr>
                                <td class="py-6 px-6 font-black text-slate-900 text-sm tracking-tight" colspan="4">Core Practice Management</td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Doctors Included</td>
                                <td class="py-6 px-6 text-sm font-bold text-slate-700">1</td>
                                <td class="py-6 px-6 text-sm font-bold text-emerald-600">Unlimited</td>
                                <td class="py-6 px-6 text-sm font-bold text-slate-700">Unlimited</td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Patient Records</td>
                                <td class="py-6 px-6 text-sm font-bold text-slate-700">Up to 50</td>
                                <td class="py-6 px-6 text-sm font-bold text-emerald-600">Unlimited</td>
                                <td class="py-6 px-6 text-sm font-bold text-slate-700">Unlimited</td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Appointment Scheduling</td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            </tr>
                            <!-- Section: Advanced -->
                            <tr>
                                <td class="py-10 px-6 font-black text-slate-900 text-sm tracking-tight" colspan="4">Advanced Capabilities</td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Patient Portal</td>
                                <td class="py-6 px-6 text-sm text-slate-200"><i data-lucide="minus" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Automated Billing & Invoicing</td>
                                <td class="py-6 px-6 text-sm text-slate-200"><i data-lucide="minus" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Integrated HD Telehealth</td>
                                <td class="py-6 px-6 text-sm text-slate-200"><i data-lucide="minus" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">E-Prescription Engine</td>
                                <td class="py-6 px-6 text-sm text-slate-200"><i data-lucide="minus" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Clinical Data Analytics</td>
                                <td class="py-6 px-6 text-sm text-slate-200"><i data-lucide="minus" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Custom Multi-Branch Sync</td>
                                <td class="py-6 px-6 text-sm text-slate-200"><i data-lucide="minus" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-slate-200"><i data-lucide="minus" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            </tr>
                            <!-- Section: Support -->
                            <tr>
                                <td class="py-10 px-6 font-black text-slate-900 text-sm tracking-tight" colspan="4">Support & Security</td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Bank-Level Encryption</td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                                <td class="py-6 px-6 text-sm text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            </tr>
                            <tr>
                                <td class="py-6 px-6 text-sm font-medium text-slate-500">Support Level</td>
                                <td class="py-6 px-6 text-sm font-bold text-slate-700">Email</td>
                                <td class="py-6 px-6 text-sm font-bold text-emerald-600">Priority Chat</td>
                                <td class="py-6 px-6 text-sm font-bold text-slate-700">24/7 Dedicated</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">© 2026 MedOS Clinical Systems. Transparent Healthcare Excellence.</p>
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>
