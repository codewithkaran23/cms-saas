<?php
// portal/index.php
require_once '../core/init.php';

// Ensure we have a clinic context
if (!$clinic) {
    die("Please access the portal through a clinic URL.");
}

$db = getDB();
$clinic_id = $clinic['id'];

// Fetch all active doctors for THIS clinic
$stmt = $db->prepare("
    SELECT u.*, dp.specialization, dp.biography 
    FROM users u 
    JOIN doctor_profiles dp ON u.id = dp.user_id 
    WHERE u.clinic_id = ? 
    AND u.role_id = (SELECT id FROM roles WHERE name = 'Doctor')
    AND u.deleted_at IS NULL
");
$stmt->execute([$clinic_id]);
$doctors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment | <?php echo e($clinic['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: <?php echo $clinic['primary_color']; ?>; }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
        .border-primary { border-color: var(--primary); }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Patient Portal Header -->
    <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-primary rounded shadow-sm"></div>
            <span class="text-xl font-bold text-gray-800"><?php echo e($clinic['name']); ?> <span class="font-normal text-gray-400">Portal</span></span>
        </div>
        <div class="flex items-center gap-6">
            <a href="<?php echo base_url('?clinic='.$clinic['subdomain']); ?>" class="text-sm font-medium text-gray-600 hover:text-primary">Home</a>
            <?php if (Auth::check()): ?>
                <a href="dashboard.php" class="text-sm font-medium text-gray-600 hover:text-primary">My Appointments</a>
                <a href="../admin/logout.php" class="bg-gray-100 px-4 py-2 rounded-lg text-sm font-bold text-gray-600">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md">Login / Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-16">
        <header class="text-center mb-16">
            <h1 class="text-4xl font-black text-gray-900 mb-4">Find Your <span class="text-primary">Specialist</span></h1>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">Select a doctor below to view their real-time availability and book your consultation in seconds.</p>
        </header>

        <!-- Doctors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($doctors)): ?>
                <div class="col-span-full text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-medium">No doctors are currently available for online booking at this clinic.</p>
                </div>
            <?php else: ?>
                <?php foreach ($doctors as $doc): ?>
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow group">
                        <div class="p-8 flex flex-col items-center text-center">
                            <div class="w-24 h-24 bg-gray-100 rounded-3xl mb-6 flex items-center justify-center text-gray-400 text-4xl font-black group-hover:bg-primary/5 transition-colors">
                                <?php echo substr($doc['name'], 0, 1); ?>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1"><?php echo e($doc['name']); ?></h3>
                            <p class="text-primary text-xs font-black uppercase tracking-widest mb-4"><?php echo e($doc['specialization'] ?? 'General Physician'); ?></p>
                            <p class="text-gray-500 text-sm line-clamp-3 mb-8">
                                <?php echo e($doc['biography'] ?: 'Expert healthcare services with a focus on patient comfort and comprehensive diagnosis.'); ?>
                            </p>
                            <a href="book.php?doctor_id=<?php echo $doc['id']; ?>" class="w-full bg-primary text-white font-bold py-4 rounded-2xl shadow-lg hover:opacity-90 transition transform hover:scale-[1.02]">
                                Book Consultation
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-10 text-center">
        <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> <?php echo e($clinic['name']); ?>. Powered by CMS SaaS.</p>
    </footer>

</body>
</html>
