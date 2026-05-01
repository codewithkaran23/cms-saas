<?php
// index.php
require_once 'core/init.php';

if ($clinic) {
    $active_theme = $clinic['active_theme'] ?? 'modern';
    require_once "templates/{$active_theme}/index.php";
    exit;
}
?>
<!-- SaaS Landing Page -> Platform Version (Light Theme) -->
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedOS | The Ultimate Practice OS</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: { sans: ['Poppins', 'sans-serif'] },
                        colors: { primary: '#0f766e', accent: '#14d1c0' }
                    }
                }
            }
        </script>
        <style>
            .img-hover-zoom { overflow: hidden; }
            .img-hover-zoom img { transition: transform .8s ease; }
            .img-hover-zoom:hover img { transform: scale(1.1); }
        </style>
    </head>
    <body class="bg-gradient-to-br from-teal-50 via-slate-100 to-teal-100/50 bg-fixed text-slate-600 font-sans selection:bg-accent selection:text-slate-900 overflow-x-hidden min-h-screen">

        <!-- Navigation -->
        <nav class="absolute top-0 w-full z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md shadow-sm">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-2 text-2xl font-black tracking-tighter uppercase text-slate-900">
                    <div class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center text-lg">+</div>
                    MED<span class="text-primary">OS</span>
                </div>
                <div class="hidden lg:flex items-center gap-10 text-sm font-semibold uppercase tracking-widest text-slate-500">
                    <a href="about.php" class="hover:text-primary transition">About Us</a>
                    <a href="services.php" class="hover:text-primary transition">Services</a>
                    <a href="contact.php" class="hover:text-primary transition">Contact</a>
                    <a href="#pricing" class="hover:text-primary transition">Pricing</a>
                    <?php if (Auth::check()): ?>
                        <a href="clinic/index.php" class="text-primary border border-primary px-8 py-2.5 rounded-full hover:bg-primary hover:text-white transition shadow-sm">Dashboard</a>
                        <a href="logout.php" class="text-slate-500 hover:text-red-500 transition font-bold">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="text-primary border border-primary px-8 py-2.5 rounded-full hover:bg-primary hover:text-white transition shadow-sm">Sign In</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <!-- Light Hero Section -->
        <section class="relative pt-40 pb-48 lg:pt-56 lg:pb-64 overflow-hidden bg-transparent">
            <div class="absolute inset-0 z-0">
                <img src="https://images.pexels.com/photos/7088483/pexels-photo-7088483.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-full h-full object-cover" alt="Medical Platform">
                <div class="absolute inset-0 bg-primary opacity-40 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-white/80"></div>
            </div>
        
            <div class="max-w-7xl mx-auto px-6 relative z-10 grid lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-3 px-5 py-2 bg-teal-50 border border-teal-100 rounded-full text-primary text-xs font-bold uppercase tracking-[0.2em] mb-8">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        SaaS For Medical Practices
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold mb-8 tracking-tight leading-[1.1] text-slate-900">
                        Build Your Clinic's <br> <span class="text-primary font-black">Digital Future.</span>
                    </h1>
                    <p class="text-lg text-slate-500 mb-10 max-w-xl leading-relaxed">
                        MedOS provides an instant branded website, intelligent EMR, and QR-based patient check-ins. The complete operating system to scale your medical practice.
                    </p>
                    <div class="flex flex-wrap gap-6">
                        <a href="signup.php" class="bg-primary text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-teal-800 transition shadow-lg shadow-primary/20">Sign Up</a>
                        <a href="#about" class="flex items-center gap-3 text-slate-600 font-semibold hover:text-primary transition">
                            <div class="w-12 h-12 rounded-full border border-slate-300 flex items-center justify-center text-slate-400">↓</div>
                            Explore Features
                        </a>
                    </div>
                </div>

                <!-- Hero Visual / Dashboard Mockup -->
                <div class="lg:col-span-5 hidden md:block relative">
                    <div class="absolute inset-0 bg-primary/5 blur-3xl rounded-full"></div>
                    
                    <div class="relative z-10 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-2xl overflow-hidden transform hover:-translate-y-2 transition duration-500">
                        <!-- Mac OS style window dots -->
                        <div class="flex gap-2 mb-8">
                            <div class="w-3.5 h-3.5 rounded-full bg-slate-200"></div>
                            <div class="w-3.5 h-3.5 rounded-full bg-slate-200"></div>
                            <div class="w-3.5 h-3.5 rounded-full bg-slate-200"></div>
                        </div>

                        <!-- Mockup Header -->
                        <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center font-black text-xl">+</div>
                                <div>
                                    <div class="h-3 w-32 bg-slate-200 rounded-full mb-2"></div>
                                    <div class="h-2 w-20 bg-slate-100 rounded-full"></div>
                                </div>
                            </div>
                            <div class="h-8 px-4 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-[0.2em] rounded-full flex items-center justify-center border border-green-100">Live Status</div>
                        </div>

                        <!-- Mockup Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                <div class="h-2 w-16 bg-slate-200 rounded-full mb-4"></div>
                                <div class="text-3xl font-black text-slate-800">1,284</div>
                                <div class="text-xs text-green-500 font-bold mt-2 flex items-center gap-1">↑ 12% this week</div>
                            </div>
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                <div class="h-2 w-16 bg-slate-200 rounded-full mb-4"></div>
                                <div class="text-3xl font-black text-slate-800">42</div>
                                <div class="text-xs text-primary font-bold mt-2 flex items-center gap-1">📅 Appointments</div>
                            </div>
                        </div>

                        <!-- Mockup Chart -->
                        <div class="h-28 w-full bg-gradient-to-t from-primary/5 to-transparent border-b-2 border-primary rounded-lg flex items-end px-3 gap-3">
                            <div class="w-full bg-primary/40 rounded-t-md h-[40%] hover:h-[45%] transition-all duration-300 cursor-pointer"></div>
                            <div class="w-full bg-primary/60 rounded-t-md h-[70%] hover:h-[75%] transition-all duration-300 cursor-pointer"></div>
                            <div class="w-full bg-primary rounded-t-md h-[90%] hover:h-[95%] transition-all duration-300 cursor-pointer shadow-[0_0_15px_rgba(15,118,110,0.5)]"></div>
                            <div class="w-full bg-primary/80 rounded-t-md h-[60%] hover:h-[65%] transition-all duration-300 cursor-pointer"></div>
                            <div class="w-full bg-primary rounded-t-md h-[100%] hover:h-[100%] transition-all duration-300 cursor-pointer shadow-[0_0_15px_rgba(15,118,110,0.5)]"></div>
                        </div>
                        
                        <!-- Floating Card Overlap -->
                        <div class="absolute -bottom-2 -right-2 bg-white p-5 rounded-2xl shadow-2xl border border-slate-100 flex items-center gap-4 transform scale-90 animate-bounce" style="animation-duration: 3s;">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl font-black">✓</div>
                            <div>
                                <div class="text-sm font-black text-slate-800 tracking-tight">Website Deployed</div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Just now</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Action Overlap (Light Theme) -->
        <div class="relative z-20 max-w-7xl mx-auto px-6 -mt-16 mb-32">
            <div class="grid lg:grid-cols-3 gap-0 rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                <div class="bg-primary p-8 text-white transform hover:-translate-y-2 hover:scale-[1.02] transition duration-300 z-10 relative">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl mb-4 shadow-inner">🌐</div>
                    <h3 class="text-xl font-bold mb-3">Instant Website</h3>
                    <p class="text-teal-50 font-medium mb-4 text-sm leading-relaxed">Visual drag-and-drop builder. Your professional clinic site is live instantly upon registration.</p>
                </div>
                <div class="bg-white p-8 text-slate-800 border-y border-r border-slate-200 relative z-0 transform hover:-translate-y-2 hover:scale-[1.02] transition duration-300">
                    <div class="w-12 h-12 bg-teal-50 text-primary rounded-full flex items-center justify-center text-xl mb-4 border border-teal-100">📂</div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Smart EMR</h3>
                    <p class="text-slate-500 font-medium mb-4 text-sm leading-relaxed">Paperless practice management with structured symptoms, diagnoses, and digital prescriptions.</p>
                </div>
                <div class="bg-white p-8 text-slate-800 border-y border-r border-slate-200 relative z-0 transform hover:-translate-y-2 hover:scale-[1.02] transition duration-300">
                    <div class="w-12 h-12 bg-teal-50 text-primary rounded-full flex items-center justify-center text-xl mb-4 border border-teal-100">📱</div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">QR Patient IDs</h3>
                    <p class="text-slate-500 font-medium mb-4 text-sm leading-relaxed">Digital health cards for patients. Scan to check-in for lightning-speed visits and record retrieval.</p>
                </div>
            </div>
        </div>

        <!-- Photo-Rich About Section -->
        <section id="about" class="py-24 relative overflow-hidden bg-transparent border-y border-slate-200/50">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative">
                    <div class="absolute -inset-4 bg-teal-100 blur-3xl rounded-full z-0 opacity-50 pointer-events-none"></div>
                    <div class="relative z-10 flex gap-6">
                        <div class="w-1/2 mt-12 img-hover-zoom rounded-3xl shadow-xl border-4 border-white">
                            <img src="https://images.pexels.com/photos/3845126/pexels-photo-3845126.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-full h-full object-cover" alt="Medical Software">
                        </div>
                        <div class="w-1/2 img-hover-zoom rounded-3xl shadow-xl border-4 border-white mb-12">
                            <img src="https://images.pexels.com/photos/6129437/pexels-photo-6129437.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-full h-full object-cover" alt="Doctor Team">
                        </div>
                    </div>
                    <!-- Floating Badge -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-2xl border border-slate-100 shadow-2xl z-20 text-center w-48 hover:scale-110 transition duration-500 cursor-pointer">
                        <div class="text-4xl font-black text-primary mb-1">500+</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Clinics Powered</div>
                    </div>
                </div>
                <div>
                    <span class="text-primary font-bold uppercase tracking-[0.2em] text-sm mb-4 block">The MedOS Advantage</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-8 leading-tight tracking-tight">
                        Built for Doctors, <br>Designed for Growth.
                    </h2>
                    <p class="text-lg text-slate-500 mb-8 leading-relaxed">
                        MedOS replaces the fragmented tools you currently use. From patient acquisition through your auto-generated website, to appointment scheduling and secure medical records, everything happens in one unified platform.
                    </p>
                    <div class="space-y-4 mb-10">
                        <div class="flex items-center gap-4 bg-white border border-slate-200 p-4 rounded-2xl shadow-sm hover:border-primary/50 transition transform hover:translate-x-2">
                            <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center text-primary text-xl">✓</div>
                            <div>
                                <h4 class="font-bold text-slate-900">Zero Setup Time</h4>
                                <p class="text-sm text-slate-500">Databases and domains provisioned instantly.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 bg-white border border-slate-200 p-4 rounded-2xl shadow-sm hover:border-primary/50 transition transform hover:translate-x-2">
                            <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center text-primary text-xl">✓</div>
                            <div>
                                <h4 class="font-bold text-slate-900">Bank-Level Security</h4>
                                <p class="text-sm text-slate-500">HIPAA compliant patient data encryption.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Photo-Rich Alternating Features -->
        <section id="features" class="py-24 bg-white/40 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-6 space-y-32">
                <!-- Feature 1 -->
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="transform hover:scale-[1.02] transition duration-500">
                        <span class="text-primary font-bold uppercase tracking-widest text-sm mb-2 block">01. Visual Editor</span>
                        <h3 class="text-3xl font-bold text-slate-900 mb-4 tracking-tight">Your Clinic, Beautifully Branded Online.</h3>
                        <p class="text-slate-500 mb-6 leading-relaxed">
                            Say goodbye to expensive web agencies. MedOS generates a premium medical website for your clinic the second you sign up. Click on any text or image to change it live.
                        </p>
                        <a href="signup.php" class="text-primary font-bold hover:text-teal-800 transition flex items-center gap-2 group">Start Designing <span class="group-hover:translate-x-2 transition">→</span></a>
                    </div>
                    <div class="relative img-hover-zoom rounded-[2rem] border-4 border-slate-100 shadow-2xl h-[320px]">
                        <img src="https://images.pexels.com/photos/1181675/pexels-photo-1181675.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-full h-full object-cover" alt="Coding/Website">
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="order-2 lg:order-1 relative img-hover-zoom rounded-[2rem] border-4 border-slate-100 shadow-2xl h-[320px]">
                        <img src="https://images.pexels.com/photos/40568/medical-appointment-doctor-healthcare-40568.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-full h-full object-cover" alt="Doctor with iPad">
                    </div>
                    <div class="order-1 lg:order-2 transform hover:scale-[1.02] transition duration-500">
                        <span class="text-primary font-bold uppercase tracking-widest text-sm mb-2 block">02. Paperless Clinic</span>
                        <h3 class="text-3xl font-bold text-slate-900 mb-4 tracking-tight">Intelligent Electronic Medical Records.</h3>
                        <p class="text-slate-500 mb-6 leading-relaxed">
                            Type less, treat more. Our EMR system comes pre-loaded with ICD codes, intelligent symptom tracking, and one-click PDF e-prescription generation.
                        </p>
                        <a href="signup.php" class="text-primary font-bold hover:text-teal-800 transition flex items-center gap-2 group">Explore EMR <span class="group-hover:translate-x-2 transition">→</span></a>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="transform hover:scale-[1.02] transition duration-500">
                        <span class="text-primary font-bold uppercase tracking-widest text-sm mb-2 block">03. Patient Experience</span>
                        <h3 class="text-3xl font-bold text-slate-900 mb-4 tracking-tight">Digital Health Cards & Fast Check-ins.</h3>
                        <p class="text-slate-500 mb-6 leading-relaxed">
                            Every patient receives a unique digital QR card. When they walk into your clinic, a quick scan immediately pulls up their medical history and books them in.
                        </p>
                        <a href="signup.php" class="text-primary font-bold hover:text-teal-800 transition flex items-center gap-2 group">See How It Works <span class="group-hover:translate-x-2 transition">→</span></a>
                    </div>
                    <div class="relative img-hover-zoom rounded-[2rem] border-4 border-slate-100 shadow-2xl h-[320px]">
                        <img src="https://images.pexels.com/photos/7014493/pexels-photo-7014493.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" class="w-full h-full object-cover" alt="Phone scanning">
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-24 bg-transparent border-y border-slate-200/50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block">Testimonials</span>
                    <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Trusted by Doctors Worldwide</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm relative hover:-translate-y-2 hover:shadow-xl transition duration-500 cursor-pointer group">
                        <div class="text-primary text-5xl font-serif absolute top-4 right-8 opacity-10 group-hover:opacity-20 transition">"</div>
                        <p class="text-slate-600 italic mb-8 relative z-10 leading-relaxed pt-2">"MedOS completely transformed my cardiology clinic. The instant website gave me a professional presence immediately, and the EMR is lightning fast."</p>
                        <div class="flex items-center gap-4">
                            <img src="https://images.pexels.com/photos/5215024/pexels-photo-5215024.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&dpr=1" class="w-14 h-14 rounded-full object-cover border-2 border-primary" alt="Doctor">
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Dr. Sarah Jenkins</h4>
                                <p class="text-slate-500 text-xs">Cardiologist, NY</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm relative hover:-translate-y-2 hover:shadow-xl transition duration-500 cursor-pointer group">
                        <div class="text-primary text-5xl font-serif absolute top-4 right-8 opacity-10 group-hover:opacity-20 transition">"</div>
                        <p class="text-slate-600 italic mb-8 relative z-10 leading-relaxed pt-2">"The QR patient cards are a game-changer. My front desk is no longer crowded, and patients love the modern, tech-forward experience."</p>
                        <div class="flex items-center gap-4">
                            <img src="https://images.pexels.com/photos/5327585/pexels-photo-5327585.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&dpr=1" class="w-14 h-14 rounded-full object-cover border-2 border-primary" alt="Doctor">
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Dr. Michael Chen</h4>
                                <p class="text-slate-500 text-xs">Neurologist, CA</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm relative hover:-translate-y-2 hover:shadow-xl transition duration-500 cursor-pointer group">
                        <div class="text-primary text-5xl font-serif absolute top-4 right-8 opacity-10 group-hover:opacity-20 transition">"</div>
                        <p class="text-slate-600 italic mb-8 relative z-10 leading-relaxed pt-2">"I run three different pediatric branches. The enterprise tier allows me to manage all locations, staff, and billing from one centralized dashboard."</p>
                        <div class="flex items-center gap-4">
                            <img src="https://images.pexels.com/photos/8460157/pexels-photo-8460157.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&dpr=1" class="w-14 h-14 rounded-full object-cover border-2 border-primary" alt="Doctor">
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">Dr. Emily Carter</h4>
                                <p class="text-slate-500 text-xs">Pediatrician, TX</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-32 bg-white/40 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20 max-w-2xl mx-auto">
                    <span class="text-primary font-bold uppercase tracking-[0.2em] text-sm mb-4 block">Transparent Pricing</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight">Scale Your Practice</h2>
                </div>
            
                <div class="grid md:grid-cols-3 gap-8 items-center">
                    <!-- Tier 1 -->
                    <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:border-primary/30 hover:shadow-lg transition duration-500">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Starter</h3>
                        <p class="text-slate-500 text-sm mb-8">Perfect for single doctors.</p>
                        <div class="text-5xl font-black text-primary mb-8">$49<span class="text-lg text-slate-400 font-bold">/mo</span></div>
                        <ul class="space-y-4 mb-10 text-slate-600 font-medium text-sm">
                            <li class="flex items-center gap-3"><span class="text-primary font-bold">✓</span> Visual Website Builder</li>
                            <li class="flex items-center gap-3"><span class="text-primary font-bold">✓</span> 100 Patient Records</li>
                            <li class="flex items-center gap-3"><span class="text-primary font-bold">✓</span> Standard Booking System</li>
                        </ul>
                        <a href="<?php echo Auth::check() ? 'checkout.php?plan=starter' : 'signup.php'; ?>" class="block w-full py-4 bg-slate-50 text-slate-700 border border-slate-200 text-center rounded-xl font-bold hover:bg-slate-100 transition shadow-sm">Start Free</a>
                    </div>
                
                    <!-- Tier 2 -->
                    <div class="bg-primary p-12 rounded-3xl shadow-2xl shadow-primary/30 transform md:-translate-y-4 relative hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-0 right-8 -translate-y-1/2 bg-white text-primary text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-full border border-slate-200 shadow-md">Popular</div>
                        <h3 class="text-xl font-bold text-white mb-2">Professional</h3>
                        <p class="text-teal-100 text-sm mb-8">For growing clinics.</p>
                        <div class="text-6xl font-black text-white mb-8">$99<span class="text-xl text-teal-200 font-bold">/mo</span></div>
                        <ul class="space-y-4 mb-10 text-white font-medium text-sm">
                            <li class="flex items-center gap-3"><span class="font-bold">✓</span> Everything in Starter</li>
                            <li class="flex items-center gap-3"><span class="font-bold">✓</span> Unlimited Patients</li>
                            <li class="flex items-center gap-3"><span class="font-bold">✓</span> Smart e-Prescriptions</li>
                            <li class="flex items-center gap-3"><span class="font-bold">✓</span> QR Patient IDs</li>
                            <li class="flex items-center gap-3"><span class="font-bold">✓</span> WhatsApp Reminders</li>
                        </ul>
                        <a href="<?php echo Auth::check() ? 'checkout.php?plan=professional' : 'signup.php'; ?>" class="block w-full py-4 bg-white text-primary text-center rounded-xl font-bold shadow-lg hover:bg-slate-50 transition">Get Professional</a>
                    </div>

                    <!-- Tier 3 -->
                    <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm hover:border-primary/30 hover:shadow-lg transition duration-500">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Enterprise</h3>
                        <p class="text-slate-500 text-sm mb-8">For multi-branch hospitals.</p>
                        <div class="text-5xl font-black text-primary mb-8">$199<span class="text-lg text-slate-400 font-bold">/mo</span></div>
                        <ul class="space-y-4 mb-10 text-slate-600 font-medium text-sm">
                            <li class="flex items-center gap-3"><span class="text-primary font-bold">✓</span> Multiple Clinic Branches</li>
                            <li class="flex items-center gap-3"><span class="text-primary font-bold">✓</span> Custom Domain Mapping</li>
                            <li class="flex items-center gap-3"><span class="text-primary font-bold">✓</span> Telemedicine / Video Conf</li>
                            <li class="flex items-center gap-3"><span class="text-primary font-bold">✓</span> Dedicated Account Manager</li>
                        </ul>
                        <a href="<?php echo Auth::check() ? 'checkout.php?plan=enterprise' : 'signup.php'; ?>" class="block w-full py-4 bg-slate-50 text-slate-700 border border-slate-200 text-center rounded-xl font-bold hover:bg-slate-100 transition shadow-sm">Contact Sales</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer (Dark for Contrast) -->
        <footer class="bg-slate-900 pt-24 pb-12 text-slate-400">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center md:items-start gap-12 mb-16 text-center md:text-left">
                    <div class="max-w-xs">
                        <div class="flex items-center justify-center md:justify-start gap-2 text-2xl font-black tracking-tighter uppercase mb-6 text-white">
                            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white text-lg">+</div>
                            MED<span class="text-primary">OS</span>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">Elevating medical practices through intelligent digital infrastructure.</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-12 text-sm font-semibold uppercase tracking-widest text-slate-500">
                        <div class="flex flex-col gap-4">
                            <span class="text-white">Platform</span>
                            <a href="#features" class="hover:text-primary transition">Features</a>
                            <a href="#pricing" class="hover:text-primary transition">Pricing</a>
                        </div>
                        <div class="flex flex-col gap-4">
                            <span class="text-white">Company</span>
                            <a href="#" class="hover:text-primary transition">About Us</a>
                            <a href="#" class="hover:text-primary transition">Contact</a>
                        </div>
                        <div class="flex flex-col gap-4">
                            <span class="text-white">Legal</span>
                            <a href="#" class="hover:text-primary transition">Privacy</a>
                            <a href="#" class="hover:text-primary transition">Terms</a>
                        </div>
                    </div>
                </div>
                <div class="pt-8 border-t border-slate-800 text-center flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">© <?php echo date('Y'); ?> MedOS SaaS Platform. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </body>
    </html>