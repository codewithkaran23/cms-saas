<?php
$hero_img = "medical_hero_dark_1777542852205.png";
$doc1 = "https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?q=80&w=400&auto=format&fit=crop";
$doc2 = "https://images.unsplash.com/photo-1594824432258-f9a46a51d957?q=80&w=400&auto=format&fit=crop";
$doc3 = "https://images.unsplash.com/photo-1622253692010-333f2da6031d?q=80&w=400&auto=format&fit=crop";
$about_img = "https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=800&auto=format&fit=crop";

$config = json_decode($clinic['config'] ?? '{}', true);
    $services = $config['services'] ?? [
        ['title' => 'Cardiology', 'desc' => 'Advanced heart health monitoring and comprehensive diagnostics.'],
        ['title' => 'Neurology', 'desc' => 'Expert care for neurological disorders and brain health.'],
        ['title' => 'Orthopedics', 'desc' => 'Specialized treatments for bone, joint, and muscle conditions.'],
        ['title' => 'Pediatrics', 'desc' => 'Compassionate, specialized medical care for children and infants.'],
        ['title' => 'Dental Care', 'desc' => 'State-of-the-art dental procedures and oral hygiene maintenance.'],
        ['title' => 'Laboratory', 'desc' => 'Fast, accurate diagnostic testing with cutting-edge equipment.']
    ];
    $about = $config['about'] ?? "We are dedicated to providing the highest quality healthcare services to our community. With state-of-the-art facilities and a team of globally recognized specialists, we ensure that your health is in the best hands.";
    $hero_title = $config['hero_title'] ?? "Expert Medical Care You Can Trust.";

    $is_editor = (Auth::check() && Auth::hasRole('Clinic Admin') && $_SESSION['clinic_id'] == $clinic['id']);
    ?>
    <!DOCTYPE html>
    <html lang="en"class="scroll-smooth">
    <head>
        <meta charset="UTF-8">
        <title><?php echo e($clinic['name']); ?> | Premium Medical Center</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            :root { --primary: <?php echo $clinic['primary_color']; ?>; }
            .bg-primary { background-color: var(--primary); }
            .text-primary { color: var(--primary); }
            .border-primary { border-color: var(--primary); }
            .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
            [contenteditable="true"]:focus { outline: 2px dashed var(--primary); outline-offset: 8px; background: rgba(255, 255, 255, 0.05); border-radius: 4px; }
        </style>
    </head>
    <body class="bg-[#070b14] text-slate-300 overflow-x-hidden selection:bg-blue-500 selection:text-white">

        <?php if ($is_editor): ?>
                <div class="fixed top-0 left-0 w-full bg-white/10 backdrop-blur-xl text-white py-3 px-6 z-[100] flex justify-between items-center border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse shadow-lg shadow-green-500/50"></span>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-green-400">Live Editor Active</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <button id="save-visual-changes" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-full font-bold text-sm transition">Publish Website</button>
                        <a href="clinic/index.php" class="text-xs font-bold text-slate-400 hover:text-white transition">Exit to Dashboard</a>
                    </div>
                </div>
                <div class="h-14"></div>
        <?php endif; ?>

        <!-- Navigation -->
        <nav class="absolute top-0 w-full z-50 border-b border-white/10 <?php echo $is_editor ? 'mt-14' : ''; ?>">
            <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-primary/30"><?php echo substr($clinic['name'], 0, 1); ?></div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tighter uppercase text-white leading-none"><?php echo e($clinic['name']); ?></h1>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-primary">Medical Center</span>
                    </div>
                </div>
                <div class="hidden lg:flex items-center gap-8 text-sm font-bold uppercase tracking-widest text-slate-300">
                    <a href="#about" class="hover:text-white transition">About</a>
                    <a href="#services" class="hover:text-white transition">Departments</a>
                    <a href="#doctors" class="hover:text-white transition">Doctors</a>
                    <a href="patient/" class="bg-primary text-white px-8 py-3.5 rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-transform">Book Appointment</a>
                </div>
            </div>
        </nav>

        <!-- 1. Hero Section -->
        <section class="relative pt-40 pb-32 lg:pt-64 lg:pb-48 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="<?php echo $hero_img; ?>" class="w-full h-full object-cover opacity-20" alt="Clinic Facility">
                <div class="absolute inset-0 bg-gradient-to-r from-[#070b14] via-[#070b14]/80 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#070b14] via-transparent to-transparent"></div>
            </div>
        
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-full mb-8">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-white">Accepting New Patients</span>
                    </div>
                    <h2 id="hero-title" class="text-5xl lg:text-7xl font-extrabold mb-8 tracking-tight text-white leading-[1.1]" <?php echo $is_editor ? 'contenteditable="true"' : ''; ?>>
                        <?php echo e($hero_title); ?>
                    </h2>
                    <p class="text-xl text-slate-400 mb-10 leading-relaxed font-medium max-w-xl">
                        Bringing together world-class specialists, advanced technology, and compassionate care for you and your family.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="patient/" class="bg-primary text-white px-10 py-4 rounded-full font-bold text-lg shadow-xl shadow-primary/30 hover:bg-white hover:text-primary transition-colors">Our Services</a>
                        <a href="#about" class="glass px-10 py-4 rounded-full font-bold text-lg text-white hover:bg-white/10 transition">Learn More</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Action Overlap -->
        <div class="relative z-20 max-w-7xl mx-auto px-6 -mt-24 mb-32">
            <div class="grid lg:grid-cols-3 gap-6">
                <div class="bg-primary p-10 rounded-3xl text-white shadow-2xl shadow-primary/20 transform hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-3xl mb-6">🚑</div>
                    <h3 class="text-2xl font-bold mb-4">Emergency Cases</h3>
                    <p class="text-white/80 font-medium mb-6">Our emergency department is open 24/7. Ready to handle critical medical situations instantly.</p>
                    <a href="#" class="font-bold uppercase tracking-widest text-sm hover:underline">Contact Us →</a>
                </div>
                <div class="glass bg-[#111827] p-10 rounded-3xl text-white shadow-2xl transform hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-blue-500/10 text-blue-400 rounded-2xl flex items-center justify-center text-3xl mb-6">📅</div>
                    <h3 class="text-2xl font-bold mb-4">Doctors Timetable</h3>
                    <p class="text-slate-400 font-medium mb-6">Schedule your visit perfectly. View the working hours of our specialized physicians.</p>
                    <a href="#" class="text-blue-400 font-bold uppercase tracking-widest text-sm hover:underline">View Schedule →</a>
                </div>
                <div class="glass bg-[#111827] p-10 rounded-3xl text-white shadow-2xl transform hover:-translate-y-2 transition duration-300">
                    <div class="w-14 h-14 bg-green-500/10 text-green-400 rounded-2xl flex items-center justify-center text-3xl mb-6">🕒</div>
                    <h3 class="text-2xl font-bold mb-4">Opening Hours</h3>
                    <ul class="space-y-3 text-slate-400 font-medium w-full">
                        <li class="flex justify-between border-b border-white/5 pb-2"><span>Mon - Fri</span> <span class="text-white">8:00 - 20:00</span></li>
                        <li class="flex justify-between border-b border-white/5 pb-2"><span>Saturday</span> <span class="text-white">9:00 - 18:00</span></li>
                        <li class="flex justify-between"><span>Sunday</span> <span class="text-primary font-bold">Emergency Only</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 2. About Section -->
        <section id="about" class="py-24 relative">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative">
                    <div class="absolute -inset-4 bg-primary/20 blur-3xl rounded-full z-0"></div>
                    <img src="<?php echo $about_img; ?>" class="rounded-[3rem] relative z-10 border border-white/10 shadow-2xl" alt="Medical Team">
                    <div class="absolute -bottom-10 -right-10 glass bg-[#070b14]/90 p-8 rounded-3xl z-20 border border-white/10 shadow-2xl">
                        <div class="text-5xl font-black text-primary mb-2">25+</div>
                        <div class="text-sm font-bold uppercase tracking-widest text-slate-300">Years of<br>Excellence</div>
                    </div>
                </div>
                <div>
                    <span class="text-primary font-black uppercase tracking-[0.2em] text-sm mb-4 block">About Our Clinic</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-8 leading-tight tracking-tight">
                        Your Health is Our <br>Top Priority.
                    </h2>
                    <p id="about-text" class="text-lg text-slate-400 mb-10 leading-relaxed" <?php echo $is_editor ? 'contenteditable="true"' : ''; ?>>
                        <?php echo e($about); ?>
                    </p>
                    <div class="grid grid-cols-2 gap-6 mb-10">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">✓</div>
                            <span class="font-bold text-white">Modern Technology</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">✓</div>
                            <span class="font-bold text-white">Expert Doctors</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">✓</div>
                            <span class="font-bold text-white">24/7 Support</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">✓</div>
                            <span class="font-bold text-white">Affordable Care</span>
                        </div>
                    </div>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/f6/Signature_placeholder.svg" class="h-16 invert opacity-50" alt="Signature">
                </div>
            </div>
        </section>

        <!-- 3. Departments / Services -->
        <section id="services" class="py-32 bg-[#0a101d] border-y border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-20 max-w-2xl mx-auto">
                    <span class="text-primary font-black uppercase tracking-[0.2em] text-sm mb-4 block">Departments</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight">Our Medical Services</h2>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($services as $index => $s): ?>
                            <div class="glass bg-[#070b14] p-10 rounded-[2.5rem] hover:-translate-y-2 hover:bg-white/[0.02] transition-all duration-300 group border border-white/5">
                                <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mb-8 text-3xl group-hover:bg-primary group-hover:text-white transition-colors">
                                    ⚕️
                                </div>
                                <h3 class="service-title text-2xl font-bold text-white mb-4" data-index="<?php echo $index; ?>" <?php echo $is_editor ? 'contenteditable="true"' : ''; ?>><?php echo e($s['title']); ?></h3>
                                <p class="service-desc text-slate-400 leading-relaxed mb-8" data-index="<?php echo $index; ?>" <?php echo $is_editor ? 'contenteditable="true"' : ''; ?>><?php echo e($s['desc']); ?></p>
                                <a href="patient/" class="text-primary font-bold uppercase tracking-widest text-xs flex items-center gap-2 group-hover:gap-4 transition-all">Read More <span>→</span></a>
                            </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- 4. Stats Section -->
        <section class="py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-primary/5"></div>
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center divide-x divide-white/10">
                    <div>
                        <div class="text-5xl font-black text-white mb-2">850<span class="text-primary">+</span></div>
                        <div class="text-sm font-bold uppercase tracking-widest text-slate-400">Happy Patients</div>
                    </div>
                    <div>
                        <div class="text-5xl font-black text-white mb-2">15<span class="text-primary">+</span></div>
                        <div class="text-sm font-bold uppercase tracking-widest text-slate-400">Expert Doctors</div>
                    </div>
                    <div>
                        <div class="text-5xl font-black text-white mb-2">34<span class="text-primary">+</span></div>
                        <div class="text-sm font-bold uppercase tracking-widest text-slate-400">Clinic Rooms</div>
                    </div>
                    <div>
                        <div class="text-5xl font-black text-white mb-2">10<span class="text-primary">+</span></div>
                        <div class="text-sm font-bold uppercase tracking-widest text-slate-400">Awards Won</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Doctors Section -->
        <section id="doctors" class="py-32 bg-[#0a101d] border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-end mb-20">
                    <div class="max-w-2xl">
                        <span class="text-primary font-black uppercase tracking-[0.2em] text-sm mb-4 block">Our Experts</span>
                        <h2 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight">Meet Our Specialists</h2>
                    </div>
                    <a href="#" class="hidden md:inline-block glass px-8 py-3 rounded-full font-bold text-white hover:bg-white/10 transition">View All Doctors</a>
                </div>
            
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Doctor 1 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-[2.5rem] mb-6 aspect-[4/5] bg-slate-800">
                            <img src="<?php echo $doc1; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700 opacity-80 group-hover:opacity-100" alt="Dr. Sarah">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#070b14] to-transparent opacity-80"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4 class="text-2xl font-bold text-white mb-1">Dr. Sarah Jenkins</h4>
                                <p class="text-primary font-bold text-sm uppercase tracking-widest">Cardiologist</p>
                            </div>
                        </div>
                    </div>
                    <!-- Doctor 2 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-[2.5rem] mb-6 aspect-[4/5] bg-slate-800">
                            <img src="<?php echo $doc2; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700 opacity-80 group-hover:opacity-100" alt="Dr. Michael">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#070b14] to-transparent opacity-80"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4 class="text-2xl font-bold text-white mb-1">Dr. Michael Chen</h4>
                                <p class="text-primary font-bold text-sm uppercase tracking-widest">Neurologist</p>
                            </div>
                        </div>
                    </div>
                    <!-- Doctor 3 -->
                    <div class="group">
                        <div class="relative overflow-hidden rounded-[2.5rem] mb-6 aspect-[4/5] bg-slate-800">
                            <img src="<?php echo $doc3; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700 opacity-80 group-hover:opacity-100" alt="Dr. Emily">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#070b14] to-transparent opacity-80"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <h4 class="text-2xl font-bold text-white mb-1">Dr. Emily Carter</h4>
                                <p class="text-primary font-bold text-sm uppercase tracking-widest">Pediatrician</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. Extended Footer -->
        <footer class="bg-[#05080f] pt-24 pb-12 border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-16">
                    <!-- Brand Info -->
                    <div class="lg:col-span-1">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white font-black text-xl"><?php echo substr($clinic['name'], 0, 1); ?></div>
                            <span class="text-xl font-black tracking-tighter uppercase text-white"><?php echo e($clinic['name']); ?></span>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed mb-8">Providing world-class medical care with advanced technology and a patient-first approach.</p>
                    </div>
                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-sm">Departments</h4>
                        <ul class="space-y-4 text-slate-400 text-sm font-medium">
                            <li><a href="#" class="hover:text-primary transition">Cardiology</a></li>
                            <li><a href="#" class="hover:text-primary transition">Neurology</a></li>
                            <li><a href="#" class="hover:text-primary transition">Orthopedics</a></li>
                        </ul>
                    </div>
                    <!-- Contact -->
                    <div>
                        <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-sm">Contact Us</h4>
                        <ul class="space-y-4 text-slate-400 text-sm font-medium">
                            <li class="flex items-start gap-3"><span class="text-primary mt-1">📍</span> 123 Health Ave, NY</li>
                            <li class="flex items-center gap-3"><span class="text-primary">📞</span> +1 (555) 123-4567</li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

        <?php if ($is_editor): ?>
            <script>
                document.getElementById('save-visual-changes').addEventListener('click', async function() {
                    const btn = this;
                    btn.innerText = 'Publishing...';
                    btn.disabled = true;
                    const services = [];
                    document.querySelectorAll('.service-title').forEach((el, i) => {
                        const descEl = document.querySelectorAll('.service-desc')[i];
                        services.push({ title: el.innerText, desc: descEl.innerText });
                    });
                    const data = {
                        hero_title: document.getElementById('hero-title').innerText,
                        about: document.getElementById('about-text').innerText,
                        services: services
                    };
                    const response = await fetch('api/save-website.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    if (response.ok) {
                        const result = await response.json();
                        if (result.status === 'pending') {
                            btn.innerText = 'Redirecting to Checkout...';
                            setTimeout(() => { window.location.href = 'checkout.php'; }, 1000);
                        } else {
                            btn.innerText = 'Published Successfully! 🚀';
                            setTimeout(() => { window.location.href = 'clinic/index.php'; }, 1500);
                        }
                    }
                });
            </script>
        <?php endif; ?>

    </body>
    </html>
