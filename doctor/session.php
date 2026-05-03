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
    
    init() {
        this.fetchVisit();
        setInterval(() => {
            if(this.visitId && this.status === 'in_progress') {
                this.autoSave();
            }
        }, 5000);
        
        this.$watch('status', (val) => {
            if(val === 'in_progress') {
                this.$nextTick(() => this.initAgora());
            }
            this.$nextTick(() => lucide.createIcons());
        });
        
        if(this.status === 'in_progress') {
            this.$nextTick(() => this.initAgora());
        }

        this.$watch('showHistory', () => this.$nextTick(() => lucide.createIcons()));
        this.$watch('showReports', () => this.$nextTick(() => lucide.createIcons()));
        this.$watch('status', (val) => {
            if(val === 'in_progress' && !this.agora) {
                this.$nextTick(() => this.initAgora());
            }
        });
    },

    initAgora() {
        if(!this.agoraAppId || this.agoraAppId === 'YOUR_AGORA_APP_ID_HERE') {
            console.warn('Agora App ID not set. Video call will not start.');
            return;
        }
        const testingChannel = '<?php echo $agora_config['testing_channel'] ?? ''; ?>';
        const channel = (testingChannel && testingChannel !== '' && testingChannel !== 'ENTER_THE_CHANNEL_NAME_YOU_USED_HERE') 
            ? testingChannel 
            : 'medos-session-' + this.aptId;
            
        this.agora = new AgoraHandler(this.agoraAppId, channel, this.agoraToken, 1);
        this.agora.join();
    },

    fetchVisit() {
        fetch(`../api/consultation.php?action=fetch&appointment_id=${this.aptId}`)
            .then(r => r.json())
            .then(data => {
                this.isLoading = false;
                if(data.id) {
                    this.visitId = data.id;
                    window.currentVisitId = data.id;
                    this.symptoms = data.symptoms || '';
                    this.diagnosis = data.diagnosis || '';
                    this.notes = data.notes || '';
                    this.status = 'in_progress';
                }
            });
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
                } else {
                    alert('Error: ' + (data.error || 'Could not start session'));
                }
            }).catch(e => console.error('Start Error:', e));
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
        console.log('End Session Clicked. Visit ID:', this.visitId);
        if(!this.visitId) {
            alert('Cannot end session: Visit ID not found. Try starting the consultation first.');
            return;
        }
        if(!confirm('Are you sure you want to end this consultation? This will finalize the clinical records.')) return;
        
        // Safety wrap for Agora leave
        try {
            if(this.agora) {
                this.agora.leave();
                console.log('Agora cleanup successful');
            }
        } catch (e) {
            console.warn('Agora leave failed, continuing anyway:', e);
        }
        
        const fd = new FormData();
        fd.append('visit_id', this.visitId);
        fetch('../api/consultation.php?action=end', { method: 'POST', body: fd })
            .then(r => {
                if(!r.ok) throw new Error('HTTP error ' + r.status);
                return r.json();
            })
            .then(data => {
                console.log('End Response:', data);
                if(data.success) {
                    window.location.href = 'appointments.php?view=all&success=completed';
                } else {
                    alert('Error ending session: ' + (data.error || 'Unknown server error'));
                }
            }).catch(e => {
                console.error('End Session Error:', e);
                alert('Connection error while ending session: ' + e.message);
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
    },

    importNotes(h) {
        this.symptoms = h.symptoms || '';
        this.diagnosis = h.diagnosis || '';
        this.notes = h.notes || '';
        this.showHistory = false;
        alert('Data imported from previous session!');
    }
}">

    <!-- Top Bar -->
    <div class="bg-slate-900 text-white -mx-8 -mt-8 px-8 py-5 flex items-center justify-between mb-8 rounded-b-[2rem] sticky top-0 z-[40]">
        <div class="flex items-center gap-4">
            <a href="appointments.php" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <template x-if="status === 'in_progress'">
                        <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                    </template>
                    <h3 class="font-black text-sm uppercase tracking-widest">Consultation Room</h3>
                </div>
                <p class="text-slate-400 text-xs font-medium mt-0.5">Patient: <?php echo e($apt['patient_name']); ?></p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <template x-if="isSaving">
                <span class="text-[10px] font-black text-teal-400 uppercase tracking-widest animate-pulse">Auto-saving...</span>
            </template>
            <template x-if="status === 'pending' || status === 'confirmed'">
                <button @click="startConsultation()" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-teal-600/20">
                    Start Consultation
                </button>
            </template>
            <template x-if="status === 'in_progress'">
                <button @click="endConsultation()" class="bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-red-500/20 flex items-center gap-2">
                    <i data-lucide="phone-off" class="w-4 h-4"></i> End Session
                </button>
            </template>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Clinical Editor -->
        <div class="lg:col-span-8 space-y-6">
            <template x-if="status !== 'in_progress'">
                <div class="bg-white rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-100">
                    <div class="w-20 h-20 bg-teal-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-teal-600">
                        <i data-lucide="play-circle" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Ready to begin?</h3>
                    <p class="text-slate-500 text-sm font-medium mt-2 max-w-sm mx-auto mb-8">Click the button below to start the consultation and unlock the clinical notes editor.</p>
                    <button @click="startConsultation()" class="bg-teal-600 hover:bg-teal-700 text-white px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-xl shadow-teal-600/20">
                        Initialize Session
                    </button>
                </div>
            </template>

            <template x-if="status === 'in_progress'">
                <div class="space-y-6 animate-in slide-in-from-bottom-4 duration-500">
                    <!-- Live Video Call (Agora) -->
                    <div class="bg-slate-900 rounded-[2.5rem] aspect-video relative overflow-hidden shadow-2xl border-4 border-slate-800 group">
                        <!-- Remote Patient Video -->
                        <div id="remote-video" class="absolute inset-0 w-full h-full bg-slate-800">
                            <!-- Placeholder when patient is not yet connected -->
                            <div class="placeholder-overlay absolute inset-0 flex flex-col items-center justify-center text-slate-500">
                                <div class="w-20 h-20 bg-slate-700/50 rounded-full flex items-center justify-center mb-4 animate-pulse">
                                    <i data-lucide="user" class="w-10 h-10"></i>
                                </div>
                                <p class="text-slate-500 text-sm font-medium mt-2">Waiting for patient to join...</p>
                                <div class="flex gap-2 mt-6">
                                    <button @click="agora.forceSync()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest transition-all">
                                        Force Sync
                                    </button>
                                    <button @click="agora.retryTracks()" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest transition-all">
                                        Retry Cam
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Local Doctor Preview (Floating) -->
                        <div id="local-video" class="absolute bottom-6 right-6 w-48 aspect-video bg-slate-700 rounded-2xl border-2 border-white/20 shadow-2xl overflow-hidden z-10"></div>
                        
                        <!-- Video Controls Overlay -->
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-3 opacity-100 transition-opacity duration-300 z-30">
                            <button @click="agora.toggleAudio()" id="btn-audio" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:bg-white/20 transition-all">
                                <i data-lucide="mic" class="w-5 h-5"></i>
                            </button>
                            <button @click="agora.toggleVideo()" id="btn-video" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:bg-white/20 transition-all">
                                <i data-lucide="video" class="w-5 h-5"></i>
                            </button>
                            <button @click="endConsultation()" class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center text-white hover:bg-red-600 shadow-lg shadow-red-500/20">
                                <i data-lucide="phone-off" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <!-- Mini Debug Console -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2 z-20">
                            <div id="agora-debug" class="bg-black/80 text-[9px] font-mono text-teal-400 p-2 rounded-lg max-w-[200px] pointer-events-none">
                                System: Initializing...
                            </div>
                            <div class="flex gap-1">
                                <button @click="agora.forceSync()" class="bg-blue-600 hover:bg-blue-700 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded shadow-lg">
                                    Force Sync
                                </button>
                                <button @click="window.location.href='appointments.php'" class="bg-red-600 hover:bg-red-700 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded shadow-lg">
                                    Force Exit
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Symptoms & Diagnosis -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Presenting Symptoms</h4>
                            <textarea x-model="symptoms" placeholder="Describe symptoms reported by patient..."
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-teal-500/5 outline-none transition-all h-32 resize-none"></textarea>
                        </div>
                        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Provisional Diagnosis</h4>
                            <textarea x-model="diagnosis" placeholder="Enter clinical diagnosis..."
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-teal-500/5 outline-none transition-all h-32 resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Main Clinical Notes -->
                    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Comprehensive Clinical Notes</h4>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-teal-500 rounded-full animate-pulse"></span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Auto-saving Active</span>
                            </div>
                        </div>
                        <textarea x-model="notes" placeholder="Write detailed clinical observations, treatment plan, and follow-up instructions..."
                            class="w-full bg-slate-50 border border-slate-100 rounded-[2rem] p-8 text-sm font-medium focus:ring-8 focus:ring-teal-500/5 outline-none transition-all h-96 resize-none custom-scrollbar"></textarea>
                    </div>
                </div>
            </template>
        </div>

        <!-- Right: Patient Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm sticky top-28">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Patient Profile</h4>
                
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="w-20 h-20 bg-teal-50 text-teal-600 rounded-[2rem] flex items-center justify-center text-3xl font-black mb-4 shadow-sm border border-teal-100">
                        <?php echo strtoupper(substr($apt['patient_name'], 0, 1)); ?>
                    </div>
                    <p class="font-black text-slate-900 text-xl tracking-tight"><?php echo e($apt['patient_name']); ?></p>
                    <p class="text-xs text-slate-400 font-medium mt-1"><?php echo e($apt['patient_email']); ?></p>
                </div>

                <div class="space-y-6 py-6 border-y border-slate-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Chief Complaint</p>
                            <p class="text-sm font-bold text-slate-700 mt-1 leading-relaxed"><?php echo e($apt['reason'] ?: 'No reason provided'); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Apt ID</p>
                            <p class="text-sm font-bold text-slate-700 mt-1">#<?php echo str_pad($apt_id, 4, '0', STR_PAD_LEFT); ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h5 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-4 text-center">Clinical Deep-Dive</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="fetchHistory()" class="flex flex-col items-center justify-center gap-3 p-5 bg-slate-50 rounded-3xl hover:bg-teal-600 hover:text-white transition-all group border border-transparent shadow-sm">
                            <i data-lucide="history" class="w-6 h-6 text-slate-400 group-hover:text-white transition-colors"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">History</span>
                        </button>
                        <button @click="fetchReports()" class="flex flex-col items-center justify-center gap-3 p-5 bg-slate-50 rounded-3xl hover:bg-teal-600 hover:text-white transition-all group border border-transparent shadow-sm">
                            <i data-lucide="file-text" class="w-6 h-6 text-slate-400 group-hover:text-white transition-colors"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Reports</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORY SLIDE-OVER -->
    <template x-if="showHistory">
        <div class="fixed inset-0 z-[100] flex justify-end" x-transition>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showHistory = false"></div>
            <div class="relative w-full max-w-xl bg-white h-full shadow-2xl flex flex-col animate-in slide-in-from-right duration-300">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Medical History</h3>
                        <p class="text-xs text-slate-400 font-medium">Past consultations for <?php echo e($apt['patient_name']); ?></p>
                    </div>
                    <button @click="showHistory = false" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-900 shadow-sm border border-slate-100">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar">
                    <template x-for="h in historyData" :key="h.id">
                        <div class="bg-white border border-slate-100 rounded-[2rem] p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3 py-1 bg-teal-50 text-teal-600 rounded-lg text-[9px] font-black uppercase tracking-widest" x-text="new Date(h.completed_at).toLocaleDateString()"></span>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="'Dr. ' + h.doctor_name"></p>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Diagnosis</p>
                                    <p class="text-sm font-bold text-slate-800" x-text="h.diagnosis || 'No diagnosis recorded'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Clinical Notes</p>
                                    <p class="text-xs text-slate-600 leading-relaxed italic" x-text="h.notes || 'No notes provided'"></p>
                                </div>
                                <button @click="importNotes(h)" class="w-full py-2 bg-slate-50 hover:bg-teal-50 text-teal-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border border-transparent hover:border-teal-100">
                                    Import These Notes
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>

    <!-- REPORTS SLIDE-OVER -->
    <template x-if="showReports">
        <div class="fixed inset-0 z-[100] flex justify-end" x-transition>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showReports = false"></div>
            <div class="relative w-full max-w-xl bg-white h-full shadow-2xl flex flex-col animate-in slide-in-from-right duration-300">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Medical Reports</h3>
                        <p class="text-xs text-slate-400 font-medium">Uploaded documents and lab results</p>
                    </div>
                    <button @click="showReports = false" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-900 shadow-sm border border-slate-100">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-8 grid grid-cols-2 gap-4 custom-scrollbar">
                    <template x-for="r in reportsData" :key="r.id">
                        <div class="bg-white border border-slate-100 rounded-[1.5rem] p-5 shadow-sm hover:border-teal-200 transition-all group">
                            <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-teal-600 group-hover:text-white transition-all">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <h5 class="text-xs font-black text-slate-900 truncate" x-text="r.file_url.split('/').pop()"></h5>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="new Date(r.created_at).toLocaleDateString()"></p>
                            <a :href="'../' + r.file_url" target="_blank" class="mt-4 inline-block text-[9px] font-black text-teal-600 uppercase tracking-widest hover:underline">Open File →</a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>

<?php require_once 'components/footer.php'; ?>
