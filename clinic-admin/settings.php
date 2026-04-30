<?php
// clinic-admin/settings.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Fetch current clinic settings
$stmt = $db->prepare("SELECT * FROM clinics WHERE id = ?");
$stmt->execute([$clinic_id]);
$clinic_data = $stmt->fetch();
$config = json_decode($clinic_data['config'] ?? '{}', true);

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? $clinic_data['name'];
    $primary_color = $_POST['primary_color'] ?? $clinic_data['primary_color'];
    
    // Website Content
    $new_config = [
        'hero_title' => $_POST['hero_title'] ?? '',
        'about' => $_POST['about'] ?? '',
        'services' => []
    ];
    
    // Handle dynamic services
    if (isset($_POST['service_title'])) {
        foreach ($_POST['service_title'] as $key => $title) {
            if (!empty($title)) {
                $new_config['services'][] = [
                    'title' => $title,
                    'desc' => $_POST['service_desc'][$key] ?? ''
                ];
            }
        }
    }

    $upd = $db->prepare("UPDATE clinics SET name = ?, primary_color = ?, config = ? WHERE id = ?");
    $upd->execute([$name, $primary_color, json_encode($new_config), $clinic_id]);
    
    $success = 'Website updated successfully! Your changes are live on your subdomain.';
    $clinic_data['name'] = $name;
    $clinic_data['primary_color'] = $primary_color;
    $config = $new_config;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Website Builder | <?php echo e($clinic_data['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic_data['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
    </style>
</head>
<body class="bg-slate-50 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen p-6 sticky top-0 h-screen">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 bg-primary rounded shadow-lg shadow-primary/20"></div>
            <h1 class="text-xl font-bold text-gray-800">Clinic Admin</h1>
        </div>
        <nav class="space-y-4">
            <a href="index.php" class="block py-2 px-4 text-gray-500 hover:bg-gray-50 rounded-lg font-medium transition">Dashboard</a>
            <a href="doctors.php" class="block py-2 px-4 text-gray-500 hover:bg-gray-50 rounded-lg font-medium transition">Doctors</a>
            <a href="appointments.php" class="block py-2 px-4 text-gray-500 hover:bg-gray-50 rounded-lg font-medium transition">Appointments</a>
            <a href="settings.php" class="block py-2 px-4 bg-primary text-white rounded-lg font-bold shadow-xl shadow-primary/20">Website Builder</a>
            <div class="pt-10">
                <a href="../admin/logout.php" class="block py-2 px-4 text-red-500 font-bold">Logout</a>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-10 max-w-5xl">
        <header class="mb-10 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-black text-gray-900">Customise Your <span class="text-primary">Website</span></h2>
                <p class="text-gray-500">Edit the content that patients see on your subdomain.</p>
            </div>
            <a href="<?php echo base_url('?clinic='.$clinic_data['subdomain']); ?>" target="_blank" class="bg-white border border-gray-200 px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-50 transition">
                View Live Site ↗
            </a>
        </header>

        <?php if ($success): ?>
            <div class="bg-green-600 text-white p-6 rounded-2xl mb-8 font-bold shadow-xl shadow-green-600/20">
                <?php echo e($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-8 pb-20">
            
            <!-- Basic Identity -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h4 class="text-xl font-bold mb-8">1. Brand Identity</h4>
                <div class="grid grid-cols-2 gap-10">
                    <div>
                        <label class="block text-gray-400 text-xs font-bold uppercase mb-3">Clinic Name</label>
                        <input type="text" name="name" value="<?php echo e($clinic_data['name']); ?>" class="w-full bg-gray-50 border-none px-5 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs font-bold uppercase mb-3">Theme Color</label>
                        <input type="color" name="primary_color" value="<?php echo e($clinic_data['primary_color']); ?>" class="w-full h-14 bg-gray-50 border-none rounded-2xl cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Hero Section -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h4 class="text-xl font-bold mb-8">2. Homepage Hero</h4>
                <div>
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-3">Hero Title (Main Heading)</label>
                    <input type="text" name="hero_title" value="<?php echo e($config['hero_title'] ?? ''); ?>" placeholder="e.g. Your Health, Our Global Commitment." class="w-full bg-gray-50 border-none px-5 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition font-black text-xl">
                </div>
            </div>

            <!-- About Section -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h4 class="text-xl font-bold mb-8">3. About Section</h4>
                <textarea name="about" rows="4" class="w-full bg-gray-50 border-none px-5 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none transition leading-relaxed text-lg"><?php echo e($config['about'] ?? ''); ?></textarea>
            </div>

            <!-- Services Section -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h4 class="text-xl font-bold">4. Services Provided</h4>
                </div>
                <div id="services-container" class="space-y-6">
                    <?php 
                    $existing_services = $config['services'] ?? [['title' => '', 'desc' => '']];
                    foreach ($existing_services as $s): 
                    ?>
                        <div class="p-6 bg-gray-50 rounded-3xl grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                            <div class="md:col-span-1">
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-3">Service Name</label>
                                <input type="text" name="service_title[]" value="<?php echo e($s['title']); ?>" class="w-full bg-white border-none px-4 py-3 rounded-xl outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-3">Description</label>
                                <input type="text" name="service_desc[]" value="<?php echo e($s['desc']); ?>" class="w-full bg-white border-none px-4 py-3 rounded-xl outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="fixed bottom-10 right-10 bg-primary text-white px-12 py-5 rounded-2xl font-black text-xl shadow-2xl shadow-primary/40 hover:scale-105 transition-transform">
                Publish Changes Live
            </button>
        </form>
    </main>

</body>
</html>
