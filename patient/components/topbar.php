<?php
// patient/components/topbar.php

// Fetch the patient's profile picture if not already fetched in the main page
if (!isset($user_data['picture_url'])) {
    $db = getDB();
    $stmt = $db->prepare("SELECT picture_url FROM patient_profiles WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $p_data = $stmt->fetch();
    $top_picture_url = $p_data['picture_url'] ?? '';
} else {
    $top_picture_url = $user_data['picture_url'];
}
?>
<header class="h-20 bg-white border-b border-slate-100 px-8 flex items-center justify-between sticky top-0 z-40">
    <!-- Search Bar -->
    <div class="flex-1 max-w-md">
        <div class="relative group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 transition-colors group-focus-within:text-teal-600"></i>
            <input type="text" placeholder="Search for patients, records..." class="w-full bg-slate-50 border border-slate-100 rounded-xl py-2 pl-12 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/10 focus:border-teal-500/20 transition-all font-medium">
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-6" x-data="{ 
        notifOpen: false, 
        unreadCount: 0, 
        notifications: [],
        incomingCall: null,
        audioContext: null,
        ringInterval: null,

        fetchNotifications() {
            fetch('api/notifications.php')
                .then(r => r.json())
                .then(data => {
                    if (data.unread_count > this.unreadCount) {
                        const hasLive = data.notifications.some(n => n.type === 'live_session' && n.is_read == 0);
                        if (!hasLive) this.playPing();
                    }

                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                    
                    const liveCall = this.notifications.find(n => n.type === 'live_session' && n.is_read == 0);
                    if (liveCall && !this.incomingCall) {
                        this.incomingCall = liveCall;
                        this.playRingtone();
                    } else if (!liveCall && this.incomingCall) {
                        this.stopRingtone();
                        this.incomingCall = null;
                    }
                });
        },
        initAudio() {
            if (!this.audioContext) {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }
        },
        playPing() {
            this.initAudio();
            const ctx = this.audioContext;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            osc.connect(gain); gain.connect(ctx.destination);
            gain.gain.setValueAtTime(0, ctx.currentTime);
            gain.gain.linearRampToValueAtTime(0.1, ctx.currentTime + 0.05);
            gain.gain.linearRampToValueAtTime(0, ctx.currentTime + 0.3);
            osc.start(); osc.stop(ctx.currentTime + 0.3);
        },
        playRingtone() {
            this.initAudio();
            if (this.ringInterval) return;
            this.ringInterval = setInterval(() => {
                if (!this.incomingCall) { this.stopRingtone(); return; }
                this.generateRingTone();
            }, 2000);
            this.generateRingTone();
        },
        generateRingTone() {
            this.initAudio();
            const ctx = this.audioContext;
            const osc1 = ctx.createOscillator();
            const osc2 = ctx.createOscillator();
            const gain = ctx.createGain();
            osc1.type = 'sine'; osc1.frequency.setValueAtTime(440, ctx.currentTime);
            osc2.type = 'sine'; osc2.frequency.setValueAtTime(480, ctx.currentTime);
            osc1.connect(gain); osc2.connect(gain); gain.connect(ctx.destination);
            gain.gain.setValueAtTime(0, ctx.currentTime);
            gain.gain.linearRampToValueAtTime(0.1, ctx.currentTime + 0.1);
            gain.gain.linearRampToValueAtTime(0.1, ctx.currentTime + 1.0);
            gain.gain.linearRampToValueAtTime(0, ctx.currentTime + 1.1);
            osc1.start(); osc2.start(); osc1.stop(ctx.currentTime + 1.2); osc2.stop(ctx.currentTime + 1.2);
        },
        stopRingtone() {
            if (this.ringInterval) { clearInterval(this.ringInterval); this.ringInterval = null; }
        },
        markAllRead() {
            fetch('api/notifications.php?read_all=1')
                .then(() => {
                    this.unreadCount = 0;
                    this.stopRingtone();
                    this.incomingCall = null;
                    this.fetchNotifications();
                });
        },
        acceptCall() {
            this.stopRingtone();
            window.location.href = this.incomingCall.link;
        }
    }" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 5000)">
        
        <!-- Icons -->
        <div class="flex items-center gap-2 border-r border-slate-100 pr-6">
            <div class="flex items-center gap-2 relative">
                <button @click="notifOpen = !notifOpen; if(notifOpen) fetchNotifications()" class="p-2 text-slate-400 hover:text-teal-600 transition-colors relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <template x-if="unreadCount > 0">
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                    </template>
                </button>

                <!-- Notifications Dropdown -->
                <div x-show="notifOpen" 
                     @click.away="notifOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full right-0 mt-4 w-80 bg-white border border-slate-100 rounded-3xl shadow-2xl shadow-slate-200/50 overflow-hidden z-50" x-cloak>
                    
                    <div class="p-5 border-b border-slate-50 flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Notifications</h4>
                        <button @click="markAllRead()" class="text-[10px] font-bold text-teal-600 hover:underline">Mark all as read</button>
                    </div>

                    <!-- Special Case: Live Session Call at the top -->
                    <template x-if="incomingCall">
                        <div class="p-5 bg-teal-600 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-10">
                                <i data-lucide="video" class="w-12 h-12"></i>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Incoming Call</p>
                            <p class="text-sm font-black mt-1" x-text="incomingCall.message"></p>
                            <div class="flex items-center gap-3 mt-4">
                                <button @click="acceptCall()" class="flex-1 bg-white text-teal-600 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-50 transition-all shadow-lg flex items-center justify-center gap-2">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i> Accept
                                </button>
                                <button @click="markAllRead()" class="px-4 py-2.5 bg-teal-700/50 hover:bg-teal-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                    Ignore
                                </button>
                            </div>
                        </div>
                    </template>

                    <div class="max-h-80 overflow-y-auto">
                        <template x-if="notifications.length === 0 && !incomingCall">
                            <div class="p-10 text-center">
                                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                                    <i data-lucide="bell-off" class="w-6 h-6"></i>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No notifications</p>
                            </div>
                        </template>

                        <template x-for="n in notifications" :key="n.id">
                            <template x-if="n.type !== 'live_session'">
                                <a :href="n.link || '#'" class="block p-5 hover:bg-slate-50 transition-colors border-b border-slate-50/50">
                                    <div class="flex gap-4">
                                        <div class="w-2 h-2 rounded-full mt-1.5 shrink-0" :class="n.is_read == 1 ? 'bg-slate-200' : 'bg-teal-500'"></div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900" x-text="n.title"></p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-0.5" x-text="n.message"></p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2" x-text="n.created_at"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </template>
                    </div>

                    <div class="p-4 bg-slate-50/50 text-center">
                        <a href="#" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-teal-600 transition-colors">View All Notifications</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile -->
        <div class="flex items-center gap-3 pl-2">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-black text-slate-900 leading-tight"><?php echo e($_SESSION['user_name']); ?></p>
                <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mt-0.5">Patient</p>
            </div>
            <div class="w-10 h-10 rounded-xl overflow-hidden border-2 border-slate-50 shadow-sm bg-teal-50 flex items-center justify-center">
                <?php if ($top_picture_url): ?>
                    <img src="<?php echo base_url($top_picture_url); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-teal-600 font-black text-xs"><?php echo substr($_SESSION['user_name'], 0, 1) . substr(strrchr($_SESSION['user_name'], " "), 1, 1); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
