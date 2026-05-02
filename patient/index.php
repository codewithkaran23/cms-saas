<?php
// patient/index.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

// Get upcoming appointment
$upcoming_stmt = $db->prepare("
    SELECT a.*, d.name as doctor_name 
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    WHERE a.patient_id = ? AND a.date_time > NOW() AND a.status = 'booked'
    ORDER BY a.date_time ASC
    LIMIT 1
");
$upcoming_stmt->execute([$patient_id]);
$upcoming = $upcoming_stmt->fetch();

// Get recent documents
$doc_stmt = $db->prepare("
    SELECT * FROM patient_documents 
    WHERE patient_id = ? 
    ORDER BY created_at DESC 
    LIMIT 3
");
$doc_stmt->execute([$patient_id]);
$recent_docs = $doc_stmt->fetchAll();

$page_title = "My Health Dashboard";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Hero Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Welcome Back, <span class="text-teal-600"><?php echo explode(' ', $_SESSION['user_name'])[0]; ?></span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Track your health progress and manage your upcoming visits.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="appointments.php" class="bg-teal-600 text-white px-8 py-3.5 rounded-2xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Book New Appointment
            </a>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Upcoming Appointment Alert -->
            <?php if ($upcoming): ?>
                <div class="bg-teal-600 rounded-[2.5rem] p-10 text-white relative overflow-hidden group shadow-2xl shadow-teal-600/20">
                    <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="px-3 py-1 rounded-full bg-white/20 text-[10px] font-black uppercase tracking-widest backdrop-blur-md">Upcoming Visit</span>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                            <div>
                                <h3 class="text-4xl font-black tracking-tighter mb-2"><?php echo date('h:i A', strtotime($upcoming['date_time'])); ?></h3>
                                <p class="text-teal-50 font-bold text-lg"><?php echo date('l, M d, Y', strtotime($upcoming['date_time'])); ?></p>
                                <div class="flex items-center gap-2 mt-6">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </div>
                                    <p class="font-bold text-teal-50">Dr. <?php echo e($upcoming['doctor_name']); ?></p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3">
                                <button class="bg-white text-teal-600 px-8 py-3.5 rounded-2xl font-bold text-xs shadow-lg hover:bg-teal-50 transition-all">Reschedule</button>
                                <button class="bg-teal-700/50 text-white border border-white/10 px-8 py-3.5 rounded-2xl font-bold text-xs backdrop-blur-md hover:bg-teal-700 transition-all">Cancel Appointment</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-[2.5rem] p-12 text-center border border-slate-100 shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="calendar-x" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">No Upcoming Appointments</h3>
                    <p class="text-slate-400 text-sm font-medium mt-2 mb-8 mx-auto max-w-xs">You don't have any scheduled visits. Book one now to stay on top of your health.</p>
                    <a href="appointments.php" class="inline-flex items-center gap-2 bg-teal-600 text-white px-8 py-3.5 rounded-2xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all">
                        Schedule a Visit
                    </a>
                </div>
            <?php endif; ?>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i data-lucide="file-text" class="w-7 h-7"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Medical Records</p>
                    <h4 class="text-3xl font-black text-slate-900 mt-1"><?php echo count($recent_docs); ?> <span class="text-sm font-medium text-slate-400 ml-1">New Files</span></h4>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                    <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i data-lucide="message-square" class="w-7 h-7"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unread Messages</p>
                    <h4 class="text-3xl font-black text-slate-900 mt-1">0</h4>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="space-y-8">
            <!-- Practice Info Card -->
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-xl shadow-slate-900/20 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                <h4 class="text-lg font-black tracking-tight mb-6 relative z-10">Your Practice</h4>
                <div class="space-y-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md">
                            <i data-lucide="map-pin" class="w-6 h-6 text-teal-400"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Location</p>
                            <p class="text-sm font-bold mt-0.5">Emerald City Medical Center</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md">
                            <i data-lucide="phone" class="w-6 h-6 text-teal-400"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact</p>
                            <p class="text-sm font-bold mt-0.5">+1 (555) 012-3456</p>
                        </div>
                    </div>
                </div>
                <button class="w-full mt-10 py-4 bg-white/10 hover:bg-white/20 border border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all backdrop-blur-md">Emergency Support</button>
            </div>

            <!-- Recent Documents -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-lg font-black text-slate-900 tracking-tight">Recent Files</h4>
                    <a href="records.php" class="text-teal-600 font-black text-[10px] uppercase tracking-widest hover:underline">View All</a>
                </div>
                <div class="space-y-4">
                    <?php foreach($recent_docs as $doc): ?>
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all group">
                            <div class="w-10 h-10 bg-white text-teal-600 rounded-xl flex items-center justify-center border border-slate-100 shadow-sm">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="text-xs font-black text-slate-900 truncate"><?php echo basename($doc['file_url']); ?></h6>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5"><?php echo date('M d, Y', strtotime($doc['created_at'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if(empty($recent_docs)): ?>
                        <p class="text-center py-6 text-slate-400 text-xs font-medium">No files uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
