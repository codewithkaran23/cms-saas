<?php
// patient/session.php — Live Consultation View (Agora Powered)
require_once '../core/init.php';
Auth::protect('Patient');

$agora_config = require_once '../config/agora.php';
$db = getDB();
$patient_id = $_SESSION['user_id'];
$apt_id = (int)($_GET['id'] ?? 0);

if (!$apt_id) {
    header('Location: appointments.php');
    exit;
}

// Fetch Appointment
$stmt = $db->prepare("
    SELECT a.*, d.name as doctor_name 
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    WHERE a.id = ? AND a.patient_id = ?
");
$stmt->execute([$apt_id, $patient_id]);
$apt = $stmt->fetch();

if (!$apt) {
    header('Location: appointments.php');
    exit;
}

$page_title = "Live Consultation";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<!-- Agora SDK -->
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.18.2.js"></script>
<script src="../assets/js/agora-handler.js"></script>

<div class="p-6 max-w-[1600px] mx-auto" x-data="{
    aptId: <?php echo $apt_id; ?>,
    agoraAppId: '<?php echo $agora_config['app_id']; ?>',
    agoraToken: '<?php echo $agora_config['token'] ?? ''; ?>',
    status: '<?php echo $apt['status']; ?>',
    agora: null,
    callJoined: false,
    isMuted: false,
    isVideoOff: false,
    isReady: false,
    visitData: null,
    
    init() {
        this.fetchLiveUpdate();
        setInterval(() => this.fetchLiveUpdate(), 3000);
        
        if(this.status === 'in_progress') {
            this.initAgora();
        }
    },

    fetchLiveUpdate() {
        fetch(`../api/consultation.php?action=fetch&appointment_id=${this.aptId}`)
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    this.visitData = data;
                    if(data.status && data.status !== this.status) {
                        this.status = data.status;
                        if(this.status === 'in_progress' && !this.agora) {
                            this.initAgora();
                        }
                    }
                }
            });
    },

    initAgora() {
        if(!this.agoraAppId) return;
        const testingChannel = '<?php echo $agora_config['testing_channel'] ?? ''; ?>';
        const channel = (testingChannel && testingChannel !== '') ? testingChannel : 'medos-session-' + this.aptId;

        const self = this; // Capture Alpine context for callbacks

        // Handler Signature: (appId, channel, token, uid, onJoin, onReady)
        this.agora = new AgoraHandler(
            this.agoraAppId, 
            channel, 
            this.agoraToken, 
            2, 
            () => { self.callJoined = true; }, // onJoin - remote video arrived
            () => { self.isReady = true; }    // onReady - local tracks published
        );
    },

    async toggleMic() {
        if(!this.agora || !this.isReady) return;
        this.isMuted = await this.agora.toggleAudio();
    },

    async toggleVideo() {
        if(!this.agora || !this.isReady) return;
        this.isVideoOff = await this.agora.toggleVideo();
    }
}">
    <!-- Header Card -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-teal-50 rounded-[1.5rem] flex items-center justify-center text-teal-600 shadow-inner">
                <i data-lucide="video" class="w-8 h-8"></i>
            </div>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h2 class="text-2xl font-black text-slate-800">Telehealth Hub</h2>
                    <template x-if="status === 'in_progress'">
                        <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full flex items-center gap-1.5 shadow-lg shadow-emerald-500/20">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                            Live Now
                        </span>
                    </template>
                    <template x-if="status !== 'in_progress' && status !== 'completed'">
                        <span class="px-3 py-1 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-amber-500/20">
                            Waiting Room
                        </span>
                    </template>
                </div>
                <p class="text-slate-400 text-sm font-medium">Session ID: <span class="text-slate-600 font-bold">#CMS-<?php echo str_pad($apt_id, 5, '0', STR_PAD_LEFT); ?></span></p>
            </div>
        </div>

        <div class="flex items-center gap-4 p-2 pr-6 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="w-12 h-12 bg-white rounded-full p-1 shadow-sm overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($apt['doctor_name']); ?>&background=0D9488&color=fff" class="w-full h-full object-cover rounded-full">
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Attending Physician</p>
                <p class="text-sm font-bold text-slate-800">Dr. <?php echo e($apt['doctor_name']); ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Cinematic Video Area -->
        <div class="lg:col-span-8">
            <div class="bg-slate-950 rounded-[3.5rem] aspect-video relative overflow-hidden shadow-2xl border-[6px] border-white group">
                
                <!-- STATE: Waiting Room / Live -->
                <template x-if="status !== 'completed'">
                    <div class="w-full h-full">
                        <!-- Remote Content -->
                        <div id="remote-video" class="absolute inset-0 w-full h-full bg-slate-950 z-0">
                            <!-- Secure Shield Overlay -->
                            <div x-show="!callJoined" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/95 z-20 transition-all duration-1000 backdrop-blur-xl">
                                <div class="relative mb-10 group/icon">
                                    <div class="w-40 h-40 bg-teal-500/5 rounded-full flex items-center justify-center animate-pulse border border-teal-500/10">
                                        <i data-lucide="shield-check" class="w-20 h-20 text-teal-500/50"></i>
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-20 h-20 bg-white rounded-[2rem] shadow-2xl flex items-center justify-center text-teal-600 transform group-hover/icon:scale-110 transition-all duration-500">
                                            <i data-lucide="video" class="w-10 h-10"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <h3 class="text-3xl font-black text-white mb-3 tracking-tight">Private Consultation</h3>
                                <p class="text-slate-400 text-center max-w-sm px-6 text-sm font-medium leading-relaxed">
                                    Your secure clinical session is ready. 
                                    <template x-if="status !== 'in_progress'">
                                        <span class="block mt-2 text-amber-500/80 font-black uppercase text-[10px] tracking-widest">Waiting for doctor to join...</span>
                                    </template>
                                </p>

                                <template x-if="status === 'in_progress'">
                                    <button @click="agora.join()" class="mt-10 bg-teal-600 hover:bg-teal-500 text-white px-14 py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest transition-all shadow-2xl shadow-teal-500/40 transform hover:-translate-y-1 active:translate-y-0 flex items-center gap-4">
                                        <i data-lucide="play" class="w-5 h-5 fill-current"></i>
                                        Join Consultation
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Local Mirror -->
                        <div id="local-video" class="absolute bottom-10 right-10 w-56 aspect-video bg-slate-900 rounded-[2rem] border-4 border-white/10 shadow-2xl overflow-hidden z-30 transition-all duration-500 hover:scale-105 group-hover:bottom-12"></div>
                    </div>
                </template>

                <!-- Solid UI Layer -->
                <div class="absolute inset-0 pointer-events-none z-40">
                    <!-- HUD: Status Display -->
                    <div class="absolute top-10 left-10 flex flex-col gap-3 pointer-events-auto">
                        <div id="agora-debug" class="bg-black/90 text-[10px] font-black text-teal-400 px-5 py-2.5 rounded-full border border-teal-500/30 uppercase tracking-widest shadow-2xl">
                            Engine: Ready
                        </div>
                    </div>

                    <!-- Floating Control Bar (Now correctly hidden until join) -->
                    <div x-show="callJoined" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-3 px-6 py-3 bg-slate-900/90 backdrop-blur-2xl rounded-[2rem] border border-white/10 shadow-2xl pointer-events-auto">
                        <button @click="toggleMic()" 
                            :disabled="!isReady || !callJoined"
                            :class="isMuted ? 'bg-red-500 text-white' : (isReady && callJoined ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-white/5 text-white/30 cursor-not-allowed')" 
                            class="w-12 h-12 rounded-xl flex items-center justify-center transition-all relative">
                            <i x-show="!isMuted" data-lucide="mic" class="w-5 h-5 absolute"></i>
                            <i x-show="isMuted" data-lucide="mic-off" class="w-5 h-5 absolute text-white"></i>
                        </button>
                        
                        <button @click="toggleVideo()" 
                            :disabled="!isReady || !callJoined"
                            :class="isVideoOff ? 'bg-red-500 text-white' : (isReady && callJoined ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-white/5 text-white/30 cursor-not-allowed')" 
                            class="w-12 h-12 rounded-xl flex items-center justify-center transition-all relative">
                            <i x-show="!isVideoOff" data-lucide="video" class="w-5 h-5 absolute"></i>
                            <i x-show="isVideoOff" data-lucide="video-off" class="w-5 h-5 absolute text-white"></i>
                        </button>
                        
                        <div class="w-px h-6 bg-white/10 mx-1"></div>
                        
                        <button @click="if(confirm('End this consultation?')) { if(agora) agora.leave(); window.location.href='appointments.php'; }" class="bg-red-600 hover:bg-red-700 text-white px-6 h-12 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-red-600/30 flex items-center gap-2">
                            <i data-lucide="phone-off" class="w-4 h-4"></i>
                            End
                        </button>
                    </div>
                </div>

                <!-- STATE: Completed -->
                <template x-if="status === 'completed'">
                    <div class="w-full h-full flex flex-col items-center justify-center bg-white p-12 text-center animate-in fade-in zoom-in duration-700">
                        <div class="w-28 h-28 bg-teal-50 text-teal-600 rounded-[2.5rem] flex items-center justify-center mb-8 shadow-inner">
                            <i data-lucide="check-circle-2" class="w-14 h-14"></i>
                        </div>
                        <h3 class="text-5xl font-black text-slate-900 mb-4 tracking-tighter">Session Concluded</h3>
                        <p class="text-slate-500 max-w-md mb-12 text-lg font-medium leading-relaxed">Thank you for choosing MedOS. Your digital health records and prescriptions are now ready for review.</p>
                        
                        <div class="flex flex-wrap gap-4 justify-center">
                            <a href="#" class="bg-slate-900 hover:bg-slate-800 text-white px-12 py-6 rounded-[2rem] font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-slate-900/20 flex items-center gap-3">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                View Prescription
                            </a>
                            <a href="appointments.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-12 py-6 rounded-[2rem] font-black text-xs uppercase tracking-widest transition-all flex items-center gap-3">
                                <i data-lucide="home" class="w-5 h-5"></i>
                                Dashboard
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Advice Area -->
            <div class="mt-8 bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="stethoscope" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Doctor's Live Observations</h4>
                </div>
                <div class="bg-slate-50 rounded-[2rem] p-8 min-h-[200px] border border-slate-100 transition-all group-hover:bg-slate-100/50">
                    <p class="text-slate-700 font-bold italic text-lg leading-relaxed whitespace-pre-line" x-text="visitData?.notes || 'The doctor will update your session notes in real-time here.'">
                        The doctor will update your session notes in real-time here.
                    </p>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-all duration-700"></div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Health Status Card -->
            <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                        <i data-lucide="activity" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-lg font-black text-slate-900">Diagnosis Feed</h4>
                </div>
                
                <div class="space-y-8">
                    <div class="p-6 bg-emerald-50/50 rounded-[2rem] border border-emerald-100">
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            Latest Finding
                        </p>
                        <p class="text-lg font-black text-emerald-900 leading-tight" x-text="visitData?.diagnosis || 'Pending...'"></p>
                    </div>
                    
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Your Reported Symptoms</p>
                        <p class="text-sm font-bold text-slate-600 leading-relaxed italic" x-text="visitData?.symptoms ? '“' + visitData.symptoms + '”' : 'No symptoms provided.'"></p>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="bg-indigo-600 rounded-[3rem] p-10 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="lock" class="w-7 h-7 text-white"></i>
                    </div>
                    <h4 class="text-lg font-black mb-4 tracking-tight">End-to-End Private</h4>
                    <p class="text-indigo-100 text-sm font-medium leading-relaxed">This consultation is strictly confidential. All audio, video, and medical records are secured under HIPAA-grade encryption.</p>
                </div>
                <div class="absolute -right-4 -bottom-4 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-all duration-1000"></div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
