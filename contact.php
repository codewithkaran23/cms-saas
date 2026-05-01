<?php require_once 'core/init.php'; ?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | MedOS</title>
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
</head>
<body class="bg-gradient-to-br from-teal-50 via-slate-100 to-teal-100/50 bg-fixed text-slate-600 font-sans selection:bg-accent selection:text-slate-900 overflow-x-hidden min-h-screen flex flex-col">

    <!-- Navigation -->
    <nav class="absolute top-0 w-full z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 text-2xl font-black tracking-tighter uppercase text-slate-900">
                <div class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center text-lg">+</div>
                MED<span class="text-primary">OS</span>
            </a>
            <div class="hidden lg:flex items-center gap-10 text-sm font-semibold uppercase tracking-widest text-slate-500">
                <a href="about.php" class="hover:text-primary transition">About Us</a>
                <a href="services.php" class="hover:text-primary transition">Services</a>
                <a href="contact.php" class="text-primary transition">Contact</a>
                <a href="index.php#pricing" class="hover:text-primary transition">Pricing</a>
                <?php if (Auth::check()): ?>
                    <a href="clinic/index.php" class="text-primary border border-primary px-8 py-2.5 rounded-full hover:bg-primary hover:text-white transition shadow-sm">Dashboard</a>
                    <a href="logout.php" class="text-slate-500 hover:text-red-500 transition font-bold">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-primary border border-primary px-8 py-2.5 rounded-full hover:bg-primary hover:text-white transition shadow-sm">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="pt-40 pb-24 flex-1">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-bold uppercase tracking-[0.2em] text-sm mb-4 block">Get in Touch</span>
                <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight">We're Here to Help.</h1>
            </div>

            <div class="grid lg:grid-cols-2 gap-16">
                <!-- Form Area -->
                <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-xl relative z-10">
                    <form class="space-y-6" onsubmit="event.preventDefault(); alert('Thank you for contacting us! We will be in touch shortly.');">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-slate-700 text-sm font-bold mb-2 uppercase tracking-wide">First Name</label>
                                <input type="text" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-bold mb-2 uppercase tracking-wide">Last Name</label>
                                <input type="text" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2 uppercase tracking-wide">Work Email</label>
                            <input type="email" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2 uppercase tracking-wide">Message</label>
                            <textarea required rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 px-6 py-4 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-teal-800 shadow-md shadow-primary/20 transition mt-2">Send Message</button>
                    </form>
                </div>

                <!-- Info Area -->
                <div class="flex flex-col justify-center space-y-12">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Enterprise Sales</h3>
                        <p class="text-slate-500 mb-6">Looking to deploy MedOS across multiple branches or a hospital network? Talk to our enterprise specialists.</p>
                        <a href="mailto:sales@medos.com" class="font-bold text-primary hover:underline flex items-center gap-2">sales@medos.com <span>→</span></a>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Customer Support</h3>
                        <p class="text-slate-500 mb-6">Already using MedOS and need help? Our technical support team is available 24/7 to assist you.</p>
                        <a href="mailto:support@medos.com" class="font-bold text-primary hover:underline flex items-center gap-2">support@medos.com <span>→</span></a>
                    </div>
                    <div class="pt-8 border-t border-slate-200">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-teal-50 text-primary rounded-full flex items-center justify-center text-xl shrink-0">📍</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Headquarters</h4>
                                <p class="text-slate-500 text-sm">123 Software Ave, Tech District<br>San Francisco, CA 94107</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
                        <a href="index.php#features" class="hover:text-primary transition">Features</a>
                        <a href="index.php#pricing" class="hover:text-primary transition">Pricing</a>
                    </div>
                    <div class="flex flex-col gap-4">
                        <span class="text-white">Company</span>
                        <a href="about.php" class="hover:text-primary transition">About Us</a>
                        <a href="contact.php" class="text-primary transition">Contact</a>
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
