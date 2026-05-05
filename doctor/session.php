<?php
// doctor/session.php — Consultation Room (Agora Powered)
require_once '../core/init.php';
Auth::protect('Doctor');

$agora_config = require_once '../config/agora.php';
$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$doctor_id = $_SESSION['user_id'];
$apt_id = (int)($_GET['id'] ?? 0);

if (!$apt_id) {
    header('Location: appointments.php');
    exit;
}

// Fetch Appointment
$stmt = $db->prepare("
    SELECT a.*, p.name as patient_name, p.email as patient_email
    FROM appointments a
    JOIN users p ON a.patient_id = p.id
    WHERE a.id = ? AND a.clinic_id = ?
");
$stmt->execute([$apt_id, $clinic_id]);
$apt = $stmt->fetch();

if (!$apt) {
    header('Location: appointments.php');
    exit;
}

$page_title = "Consultation Room";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<!-- Agora SDK -->
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.18.2.js"></script>
<script src="../assets/js/agora-handler.js"></script>

<div class="space-y-0 relative overflow-hidden" x-data="{
    aptId: <?php echo $apt_id; ?>,
    patientId: <?php echo $apt['patient_id']; ?>,
    agoraAppId: '<?php echo $agora_config['app_id']; ?>',
    agoraToken: '<?php echo $agora_config['token'] ?? ''; ?>',
    visitId: 0,
    status: '<?php echo $apt['status']; ?>',
    symptoms: '',
    diagnosis: '',
    notes: '',
    isLoading: true,
    isSaving: false,
    showHistory: false,
    showReports: false,
    historyData: [],
    reportsData: [],
    agora: null,
    callJoined: false,
    isMuted: false,
    isVideoOff: false,
    isReady: false,
    
    init() {
        this.fetchVisit();
        setInterval(() => {
            if(this.visitId && this.status === 'in_progress') {
                this.autoSave();
            }
        }, 5000);
        
        if(this.status === 'in_progress') {
            this.initAgora();
        }
    },

    initAgora() {
        if(!this.agoraAppId) return;
        const testingChannel = '<?php echo $agora_config['testing_channel'] ?? ''; ?>';
        const channel = (testingChannel && testingChannel !== '') ? testingChannel : 'medos-session-' + this.aptId;

        const self = this;
        this.agora = new AgoraHandler(
            this.agoraAppId, 
            channel, 
            this.agoraToken, 
            1, 
            () => { self.callJoined = true; },
            () => { self.isReady = true; }
        );
    },

    async toggleMic() {
        if(!this.agora || !this.isReady) return;
        this.isMuted = await this.agora.toggleAudio();
    },

    async toggleVideo() {
        if(!this.agora || !this.isReady) return;
        this.isVideoOff = await this.agora.toggleVideo();
    },

    fetchVisit() {
        fetch(`../api/consultation.php?action=fetch&appointment_id=${this.aptId}`)
            .then(r => r.json())
            .then(data => {
                this.isLoading = false;
                if(data.id) {
                    this.visitId = data.id;
                    this.symptoms = data.symptoms || '';
                    this.diagnosis = data.diagnosis || '';
                    this.notes = data.notes || '';
                    this.status = 'in_progress';
                    if(!this.agora) this.initAgora();
                }
            });
    },

    importNotes(h) {
        if(!confirm('This will overwrite current notes with data from the previous visit. Continue?')) return;
        this.symptoms = h.symptoms || '';
        this.diagnosis = h.diagnosis || '';
        this.notes = h.notes || '';
        this.showHistory = false;
        alert('Data imported successfully!');
    },

    startConsultation() {
        const fd = new FormData();
        fd.append('appointment_id', this.aptId);
        fetch('../api/consultation.php?action=start', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    this.visitId = data.visit_id;
                    this.status = 'in_progress';
                    this.initAgora();
                }
            });
    },

    autoSave() {
        this.isSaving = true;
        const fd = new FormData();
        fd.append('visit_id', this.visitId);
        fd.append('symptoms', this.symptoms);
        fd.append('diagnosis', this.diagnosis);
        fd.append('notes', this.notes);
        fetch('../api/consultation.php?action=update', { method: 'POST', body: fd })
            .then(() => {
                setTimeout(() => { this.isSaving = false; }, 500);
            });
    },

    endConsultation() {
        if(!confirm('End this consultation?')) return;
        if(this.agora) this.agora.leave();
        
        const fd = new FormData();
        fd.append('visit_id', this.visitId);
        fetch('../api/consultation.php?action=end', { method: 'POST', body: fd })
            .then(() => {
                window.location.href = 'appointments.php?success=completed';
            });
    },

    fetchHistory() {
        this.showHistory = true;
        fetch(`api/patient_info.php?action=history&patient_id=${this.patientId}`)
            .then(r => r.json())
            .then(data => this.historyData = data);
    },

    fetchReports() {
        this.showReports = true;
        fetch(`api/patient_info.php?action=reports&patient_id=${this.patientId}`)
            .then(r => r.json())
            .then(data => this.reportsData = data);
    }
}">

    <!-- Top Bar -->
    <div class="bg-slate-900 text-white -mx-8 -mt-8 px-8 py-5 flex items-center justify-between mb-8 rounded-b-[2rem] sticky top-0 z-[40]">
        <div class="flex items-center gap-4">
            <a href="appointments.php" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h3 class="font-black text-sm uppercase tracking-widest">Consultation Room</h3>
                <p class="text-slate-400 text-xs font-medium">Patient: <?php echo e($apt['patient_name']); ?></p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <template x-if="isSaving">
                <span class="text-[10px] font-black text-teal-400 uppercase tracking-widest animate-pulse">Auto-saving...</span>
            </template>
            <button @click="endConsultation()" class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-red-500/20">
                End Session
            </button>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Video & Notes -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Video Area -->
            <div class="bg-slate-900 rounded-[3rem] aspect-video relative overflow-hidden shadow-2xl border-[8px] border-white group">
                <div id="remote-video" class="absolute inset-0 w-full h-full bg-slate-950">
                    <div x-show="!callJoined" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/95 z-20">
                        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center animate-pulse mb-6">
                            <i data-lucide="video" class="w-10 h-10 text-white/20"></i>
                        </div>
                        <template x-if="status !== 'in_progress'">
                            <button @click="startConsultation()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/30">
                                Start Consultation
                            </button>
                        </template>
                        <template x-if="status === 'in_progress'">
                            <button @click="agora.join()" class="bg-teal-600 hover:bg-teal-500 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-teal-600/30">
                                Initialize Camera
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Local Mirror -->
                <div id="local-video" class="absolute bottom-6 right-6 w-48 aspect-video bg-slate-800 rounded-2xl border-2 border-white/20 shadow-2xl overflow-hidden z-30 transition-all"></div>
                
                <!-- HUD: Control Bar (Now correctly hidden until join) -->
                <div x-show="callJoined" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-3 px-6 py-3 bg-slate-900/80 backdrop-blur-2xl rounded-[2rem] border border-white/10 shadow-2xl z-40">
                    <button @click="toggleMic()" 
                        :disabled="!isReady"
                        :class="isMuted ? 'bg-red-500 text-white' : (isReady ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-white/5 text-white/30 cursor-not-allowed')" 
                        class="w-12 h-12 rounded-xl flex items-center justify-center transition-all relative">
                        <i x-show="!isMuted" data-lucide="mic" class="w-5 h-5 absolute"></i>
                        <i x-show="isMuted" data-lucide="mic-off" class="w-5 h-5 absolute text-white"></i>
                    </button>
                    <button @click="toggleVideo()" 
                        :disabled="!isReady"
                        :class="isVideoOff ? 'bg-red-500 text-white' : (isReady ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-white/5 text-white/30 cursor-not-allowed')" 
                        class="w-12 h-12 rounded-xl flex items-center justify-center transition-all relative">
                        <i x-show="!isVideoOff" data-lucide="video" class="w-5 h-5 absolute"></i>
                        <i x-show="isVideoOff" data-lucide="video-off" class="w-5 h-5 absolute text-white"></i>
                    </button>
                    <div class="w-px h-6 bg-white/10 mx-1"></div>
                    <button @click="endConsultation()" class="bg-red-600 hover:bg-red-700 text-white px-6 h-12 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-red-600/30 flex items-center gap-2">
                        <i data-lucide="phone-off" class="w-4 h-4"></i> End Call
                    </button>
                </div>

                <!-- Debug -->
                <div class="absolute top-6 left-6 z-40">
                    <div id="agora-debug" class="bg-black/60 text-[9px] font-black text-teal-400 px-4 py-2 rounded-full border border-white/10 uppercase tracking-widest">
                        System: Online
                    </div>
                </div>
            </div>

            <!-- Clinical Inputs -->
            <div x-show="status === 'in_progress'" x-transition class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Patient Symptoms</h4>
                        <textarea x-model="symptoms" class="w-full bg-slate-50 border-none rounded-2xl p-6 text-sm h-32 resize-none outline-none focus:ring-4 focus:ring-teal-500/5"></textarea>
                    </div>
                    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Provisional Diagnosis</h4>
                        <textarea x-model="diagnosis" class="w-full bg-slate-50 border-none rounded-2xl p-6 text-sm h-32 resize-none outline-none focus:ring-4 focus:ring-teal-500/5"></textarea>
                    </div>
                </div>
                <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-sm">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Comprehensive Clinical Observations</h4>
                    <textarea x-model="notes" class="w-full bg-slate-50 border-none rounded-[2rem] p-8 text-sm h-96 resize-none outline-none focus:ring-8 focus:ring-teal-500/5"></textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Patient Data -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm sticky top-28">
                <h3 class="text-lg font-black text-slate-800 mb-6">Patient Records</h3>
                <div class="space-y-4">
                    <button @click="fetchHistory()" class="w-full flex items-center justify-between p-5 bg-slate-50 hover:bg-slate-100 rounded-2xl text-sm font-bold text-slate-600 transition-all">
                        Consultation History <i data-lucide="history" class="w-4 h-4 text-slate-400"></i>
                    </button>
                    <button @click="fetchReports()" class="w-full flex items-center justify-between p-5 bg-slate-50 hover:bg-slate-100 rounded-2xl text-sm font-bold text-slate-600 transition-all">
                        Lab Reports <i data-lucide="file-text" class="w-4 h-4 text-slate-400"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'components/footer.php'; ?>
