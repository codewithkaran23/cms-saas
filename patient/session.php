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

if (!$apt || ($apt['status'] !== 'in_progress' && $apt['status'] !== 'completed')) {
    header('Location: appointments.php?error=not_live');
    exit;
}

$page_title = "Live Consultation";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<!-- Agora SDK -->
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.18.2.js"></script>
<script src="../assets/js/agora-handler.js"></script>

<div class="space-y-0" x-data="{
    aptId: <?php echo $apt_id; ?>,
    agoraAppId: '<?php echo $agora_config['app_id']; ?>',
    agoraToken: '<?php echo $agora_config['token'] ?? ''; ?>',
    status: '<?php echo $apt['status']; ?>',
    symptoms: '',
    diagnosis: '',
    notes: '',
    agora: null,
    callJoined: false,
    
    init() {
        this.fetchLiveUpdate();
        // Poll for updates every 3 seconds
        setInterval(() => {
            if(this.status === 'in_progress') {
                this.fetchLiveUpdate();
            }
        }, 3000);

        if(this.status === 'in_progress') {
            this.$nextTick(() => this.initAgora());
        }
    },

    initAgora() {
        if(!this.agoraAppId || this.agoraAppId === 'YOUR_AGORA_APP_ID_HERE') {
            console.warn('Agora App ID not set.');
            return;
        }
        
        // Match Doctor's room naming exactly
        const testingChannel = '<?php echo $agora_config['testing_channel'] ?? ''; ?>';
        const channel = (testingChannel && testingChannel !== '' && testingChannel !== 'ENTER_THE_CHANNEL_NAME_YOU_USED_HERE') 
            ? testingChannel 
            : 'medos-session-' + this.aptId;

        this.agora = new AgoraHandler(this.agoraAppId, channel, this.agoraToken, 2);
        // Removed auto-join to satisfy autoplay policies
    },

    fetchLiveUpdate() {
        fetch(`../api/consultation.php?action=fetch&appointment_id=${this.aptId}`)
            .then(r => r.json())
            .then(data => {
                if(data.id) {
                    this.symptoms = data.symptoms || 'Waiting for doctor...';
                    this.diagnosis = data.diagnosis || 'Waiting for doctor...';
                    this.notes = data.notes || 'Waiting for doctor...';
                    this.status = data.status;
                    
                    if(this.status === 'completed') {
                        if(this.agora) this.agora.leave();
                        window.location.href = 'index.php?success=consultation_completed';
                    }
                }
            });
    }
}">

    <!-- Top Bar -->
    <div class="bg-slate-900 text-white -mx-8 -mt-8 px-8 py-5 flex items-center justify-between mb-8 rounded-b-[2rem]">
        <div class="flex items-center gap-4">
            <a href="appointments.php" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                    <h3 class="font-black text-sm uppercase tracking-widest">Live Consultation</h3>
                </div>
                <p class="text-slate-400 text-xs font-medium mt-0.5">With Dr. <?php echo e($apt['doctor_name']); ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20">
            <span class="text-[10px] font-black uppercase tracking-widest">Connection Active</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Live Video (Agora) -->
        <div class="lg:col-span-7">
            <div class="bg-slate-900 rounded-[2.5rem] aspect-video relative overflow-hidden shadow-2xl border-4 border-slate-800 group">
                <!-- Remote Doctor Video -->
                <div id="remote-video" class="absolute inset-0 w-full h-full bg-slate-900 z-0">
                    <div x-show="!callJoined" class="placeholder-overlay absolute inset-0 flex flex-col items-center justify-center text-slate-500 z-10">
                        <div class="w-20 h-20 bg-slate-700/50 rounded-full flex items-center justify-center mb-4 animate-pulse">
                            <i data-lucide="user" class="w-10 h-10"></i>
                        </div>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Consultation is ready</p>
                        <template x-if="!callJoined">
                            <button @click="agora.join(); callJoined=true" class="mt-6 bg-teal-600 hover:bg-teal-700 text-white px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-xl shadow-teal-600/20 flex items-center gap-3">
                                <i data-lucide="video" class="w-5 h-5"></i>
                                Join Video Call
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Mini Debug Console -->
                <div class="absolute top-4 left-4 flex flex-col gap-2 z-20">
                    <div id="agora-debug" class="bg-black/80 text-[9px] font-mono text-teal-400 p-2 rounded-lg max-w-[200px] pointer-events-none">
                        System: Initializing...
                    </div>
                    <div class="flex gap-1">
                        <button @click="agora.forceSync()" class="bg-blue-600 hover:bg-blue-700 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded shadow-lg">
                            Sync Video
                        </button>
                        <button @click="window.location.href='index.php'" class="bg-red-600 hover:bg-red-700 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded shadow-lg">
                            Force Exit
                        </button>
                    </div>
                </div>

                <!-- Local Patient Preview -->
                <div id="local-video" class="absolute bottom-6 right-6 w-48 aspect-video bg-slate-700 rounded-2xl border-2 border-white/20 shadow-2xl overflow-hidden z-10"></div>
            </div>
            
            <div class="mt-8 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Doctor's Advice & Observations</h4>
                <div class="bg-slate-50 rounded-[1.5rem] p-6 min-h-[150px]">
                    <p class="text-slate-700 text-sm font-medium leading-relaxed whitespace-pre-line" x-text="notes"></p>
                </div>
            </div>
        </div>

        <!-- Right: Real-time Summaries -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900">Live Health Status</h4>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Diagnosis Summary</p>
                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                            <p class="text-sm font-bold text-emerald-700" x-text="diagnosis"></p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Recorded Symptoms</p>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed italic" x-text="'“' + symptoms + '”'"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
