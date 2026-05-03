<?php
// patient/index.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

// Get next upcoming appointment
$upcoming_stmt = $db->prepare("
    SELECT a.*, d.name as doctor_name, COALESCE(dp.specialization, 'General Physician') as specialization
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    LEFT JOIN doctor_profiles dp ON d.id = dp.user_id
    WHERE a.patient_id = ? AND a.date_time > NOW() AND a.status IN ('pending', 'confirmed', 'in_progress')
    ORDER BY a.date_time ASC
    LIMIT 1
");
$upcoming_stmt->execute([$patient_id]);
$upcoming = $upcoming_stmt->fetch();

// Total appointments count
$total_stmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE patient_id = ?");
$total_stmt->execute([$patient_id]);
$total_appointments = $total_stmt->fetchColumn();

// Get recent documents
$doc_stmt = $db->prepare("SELECT * FROM patient_documents WHERE patient_id = ? ORDER BY created_at DESC LIMIT 3");
$doc_stmt->execute([$patient_id]);
$recent_docs = $doc_stmt->fetchAll();

// Get latest completed visit summary
$visit_stmt = $db->prepare("
    SELECT v.*, d.name as doctor_name 
    FROM visits v 
    JOIN users d ON v.doctor_id = d.id 
    WHERE v.patient_id = ? AND v.status = 'completed' 
    ORDER BY v.completed_at DESC 
    LIMIT 1
");
$visit_stmt->execute([$patient_id]);
$latest_visit = $visit_stmt->fetch();

$page_title = "My Health Dashboard";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-6xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                Good <?php echo (date('H') < 12) ? 'Morning' : ((date('H') < 17) ? 'Afternoon' : 'Evening'); ?>,
                <span class="text-teal-600"><?php echo explode(' ', $_SESSION['user_name'])[0]; ?></span>
            </h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Here's your health overview for today, <?php echo date('l, M d'); ?>.</p>
        </div>
        <a href="doctors.php" class="bg-teal-600 text-white px-7 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2 self-start">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i> Book Appointment
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Upcoming Appointment Card -->
            <?php if ($upcoming): ?>
                <div class="bg-teal-600 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-teal-600/30">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute right-8 top-8 bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                        Next Visit
                    </div>
                    <div class="relative z-10 mt-4">
                        <h3 class="text-4xl font-black tracking-tight"><?php echo date('h:i A', strtotime($upcoming['date_time'])); ?></h3>
                        <p class="text-teal-100 font-bold text-lg mt-1"><?php echo date('l, M d, Y', strtotime($upcoming['date_time'])); ?></p>
                        <div class="flex items-center gap-3 mt-6">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="font-black"><?php echo e($upcoming['doctor_name']); ?></p>
                                <p class="text-teal-200 text-xs font-bold"><?php echo e($upcoming['specialization']); ?></p>
                            </div>
                        </div>
                        <?php if ($upcoming['status'] === 'pending'): ?>
                        <div class="mt-6 bg-white/10 border border-white/20 px-5 py-3 rounded-xl inline-flex items-center gap-2 text-xs font-bold">
                            <i data-lucide="clock" class="w-4 h-4"></i> Awaiting doctor confirmation
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-[2.5rem] p-12 text-center border border-slate-100 shadow-sm">
                    <div class="w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="calendar-x" class="w-8 h-8 text-teal-300"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">No Upcoming Appointments</h3>
                    <p class="text-slate-400 text-sm font-medium mt-2 mb-8 max-w-xs mx-auto">Book a consultation to stay proactive about your health.</p>
                    <a href="doctors.php" class="inline-flex items-center gap-2 bg-teal-600 text-white px-7 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/20">
                        <i data-lucide="calendar-plus" class="w-4 h-4"></i> Book Appointment
                    </a>
                </div>
            <?php endif; ?>

            <!-- Latest Consultation Summary -->
            <?php if ($latest_visit): ?>
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden animate-in slide-in-from-bottom-4 duration-700">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-teal-600 shadow-sm">
                                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-900">Latest Consultation Summary</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Finalized on <?php echo date('M d, Y', strtotime($latest_visit['completed_at'])); ?></p>
                            </div>
                        </div>
                        <div class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100">
                            Completed
                        </div>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Diagnosis</p>
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-bold text-slate-700">
                                <?php echo e($latest_visit['diagnosis'] ?: 'General Observation'); ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Reported Symptoms</p>
                            <p class="text-sm font-medium text-slate-600 leading-relaxed italic">
                                "<?php echo e($latest_visit['symptoms'] ?: 'None recorded'); ?>"
                            </p>
                        </div>
                        <div class="md:col-span-2 pt-4 border-t border-slate-50">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Doctor's Notes & Advice</p>
                            <div class="text-sm font-medium text-slate-600 leading-relaxed whitespace-pre-line">
                                <?php echo e($latest_visit['notes'] ?: 'No additional notes provided.'); ?>
                            </div>
                            <div class="mt-6 flex items-center gap-3">
                                <div class="w-8 h-8 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center text-xs font-black">
                                    <?php echo strtoupper(substr($latest_visit['doctor_name'], 0, 1)); ?>
                                </div>
                                <p class="text-[11px] font-bold text-slate-500">Dr. <?php echo e($latest_visit['doctor_name']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i data-lucide="calendar-check" class="w-6 h-6"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Visits</p>
                    <h4 class="text-3xl font-black text-slate-900 mt-1"><?php echo $total_appointments; ?></h4>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Medical Records</p>
                    <h4 class="text-3xl font-black text-slate-900 mt-1"><?php echo count($recent_docs); ?></h4>
                </div>
            </div>
        </div>

        <!-- Side Column -->
        <div class="space-y-8">
            <!-- Quick Links -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl shadow-slate-900/20 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full blur-3xl"></div>
                <h4 class="text-sm font-black uppercase tracking-widest text-slate-300 mb-8">Quick Access</h4>
                <div class="space-y-3">
                    <a href="doctors.php" class="flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all group">
                        <div class="w-9 h-9 bg-teal-500/20 text-teal-400 rounded-xl flex items-center justify-center">
                            <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                        </div>
                        <span class="text-sm font-bold">Book Appointment</span>
                        <i data-lucide="chevron-right" class="w-4 h-4 ml-auto text-slate-500 group-hover:text-white transition-colors"></i>
                    </a>
                    <a href="records.php" class="flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all group">
                        <div class="w-9 h-9 bg-blue-500/20 text-blue-400 rounded-xl flex items-center justify-center">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                        </div>
                        <span class="text-sm font-bold">Medical Records</span>
                        <i data-lucide="chevron-right" class="w-4 h-4 ml-auto text-slate-500 group-hover:text-white transition-colors"></i>
                    </a>
                    <a href="settings.php" class="flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all group">
                        <div class="w-9 h-9 bg-slate-500/20 text-slate-400 rounded-xl flex items-center justify-center">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                        </div>
                        <span class="text-sm font-bold">My Profile</span>
                        <i data-lucide="chevron-right" class="w-4 h-4 ml-auto text-slate-500 group-hover:text-white transition-colors"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Docs -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-sm font-black text-slate-900">Recent Files</h4>
                    <a href="records.php" class="text-teal-600 font-black text-[10px] uppercase tracking-widest hover:text-teal-700 transition-colors">View All</a>
                </div>
                <div class="space-y-3">
                    <?php foreach($recent_docs as $doc): ?>
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-white hover:shadow-md transition-all">
                            <div class="w-10 h-10 bg-white text-teal-600 rounded-xl flex items-center justify-center border border-slate-100 shadow-sm shrink-0">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="text-xs font-black text-slate-900 truncate"><?php echo basename($doc['file_url']); ?></h6>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5"><?php echo date('M d, Y', strtotime($doc['created_at'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if(empty($recent_docs)): ?>
                        <div class="py-8 text-center">
                            <i data-lucide="folder-open" class="w-8 h-8 text-slate-200 mx-auto mb-2"></i>
                            <p class="text-slate-400 text-xs font-bold">No files uploaded yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
<?php require_once 'components/footer.php'; ?>
