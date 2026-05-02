<?php
// patient/messages.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];
$clinic_id = $_SESSION['clinic_id'];

// Mock conversations for UI
$messages = [
    ['sender' => 'Dr. Smith', 'text' => 'Hello! Your blood report looks good. Please continue the current medication.', 'time' => '10:30 AM', 'is_me' => false],
    ['sender' => 'Me', 'text' => 'Thank you, Doctor. Should I book another visit next month?', 'time' => '10:45 AM', 'is_me' => true],
    ['sender' => 'Dr. Smith', 'text' => 'Yes, let\'s check again in 30 days.', 'time' => '11:00 AM', 'is_me' => false]
];

$page_title = "My Messages";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="h-[calc(100vh-140px)] flex flex-col animate-in fade-in duration-700">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 shrink-0">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Direct <span class="text-teal-600">Messaging</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Chat directly with your healthcare provider.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Doctor Online</span>
        </div>
    </header>

    <!-- Chat Area -->
    <div class="flex-1 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <!-- Chat Header -->
        <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-teal-600/20">
                    <i data-lucide="user" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-black text-slate-900">Dr. Clinic Support</h4>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Active Consultation</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="p-3 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                    <i data-lucide="phone" class="w-5 h-5"></i>
                </button>
                <button class="p-3 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                    <i data-lucide="video" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Messages Flow -->
        <div class="flex-1 p-10 overflow-y-auto space-y-8 custom-scrollbar">
            <?php foreach($messages as $msg): ?>
                <div class="flex <?php echo $msg['is_me'] ? 'justify-end' : 'justify-start'; ?>">
                    <div class="max-w-[70%] space-y-2">
                        <div class="flex items-center gap-2 <?php echo $msg['is_me'] ? 'flex-row-reverse' : ''; ?>">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest"><?php echo $msg['sender']; ?></p>
                            <span class="text-[9px] font-bold text-slate-300"><?php echo $msg['time']; ?></span>
                        </div>
                        <div class="p-5 rounded-[1.5rem] text-sm font-medium leading-relaxed <?php echo $msg['is_me'] ? 'bg-teal-600 text-white rounded-tr-none shadow-xl shadow-teal-600/10' : 'bg-slate-100 text-slate-700 rounded-tl-none'; ?>">
                            <?php echo e($msg['text']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Input Area -->
        <div class="p-8 border-t border-slate-50 bg-slate-50/30">
            <div class="relative flex items-center gap-4">
                <button class="p-4 text-slate-400 hover:text-teal-600 transition-all bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <i data-lucide="paperclip" class="w-5 h-5"></i>
                </button>
                <div class="relative flex-1">
                    <input type="text" placeholder="Type your message here..." class="w-full bg-white border border-slate-100 rounded-[1.5rem] px-8 py-4 text-sm font-bold focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all shadow-sm">
                    <button class="absolute right-3 top-1/2 -translate-y-1/2 bg-teal-600 text-white p-2.5 rounded-xl shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }
</style>

<?php require_once 'components/footer.php'; ?>
