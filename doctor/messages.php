<?php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$doctor_id = $_SESSION['user_id'];
$clinic_id = $_SESSION['clinic_id'];

// Fetch unique patients the doctor has appointments with, showing the latest one
$stmt = $db->prepare("
    SELECT a.id as apt_id, p.id as patient_id, p.name as patient_name,
           (SELECT message FROM chat_messages WHERE appointment_id IN (SELECT id FROM appointments WHERE doctor_id = ? AND patient_id = p.id) ORDER BY created_at DESC LIMIT 1) as last_msg,
           (SELECT created_at FROM chat_messages WHERE appointment_id IN (SELECT id FROM appointments WHERE doctor_id = ? AND patient_id = p.id) ORDER BY created_at DESC LIMIT 1) as last_time
    FROM (
        SELECT MAX(id) as id, patient_id 
        FROM appointments 
        WHERE doctor_id = ? AND clinic_id = ? 
        GROUP BY patient_id
    ) latest_apts
    JOIN appointments a ON latest_apts.id = a.id
    JOIN users p ON a.patient_id = p.id
    ORDER BY last_time DESC, a.date_time DESC
");
$stmt->execute([$doctor_id, $doctor_id, $doctor_id, $clinic_id]);
$conversations = $stmt->fetchAll();

$page_title = "Messages Hub";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="flex h-[calc(100vh-140px)] bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden" x-data="{
    activeApt: null,
    activeName: '',
    messages: [],
    newMessage: '',
    selectedFile: null,
    lastId: 0,
    isFetching: false,
    unreadCounts: {},
    senderId: <?php echo $_SESSION['user_id']; ?>,
    
    init() {
        this.fetchUnreadCounts();
        setInterval(() => { this.fetchMessages(); this.fetchUnreadCounts(); }, 3000);
        // Watch for message changes to re-run Lucide
        this.$watch('messages', () => {
            this.$nextTick(() => lucide.createIcons());
        });
    },

    selectConversation(id, name) {
        if(this.activeApt == id) return;
        this.activeApt = id;
        this.activeName = name;
        this.messages = [];
        this.lastId = 0;
        this.fetchMessages();
    },
    
    fetchMessages() {
        if(!this.activeApt || this.isFetching) return;
        this.isFetching = true;
        fetch('../api/chat.php?fetch=1&appointment_id=' + this.activeApt + '&last_id=' + this.lastId)
            .then(r => r.json())
            .then(data => {
                this.isFetching = false;
                if(data.messages && data.messages.length > 0) {
                    const newMsgs = data.messages.filter(m => !this.messages.find(em => em.id == m.id));
                    if(newMsgs.length > 0) {
                        this.messages = [...this.messages, ...newMsgs];
                        this.lastId = this.messages[this.messages.length - 1].id;
                        this.$nextTick(() => {
                            const container = $refs.chatContainer;
                            if(container) container.scrollTop = container.scrollHeight;
                        });
                        
                        const last = newMsgs[newMsgs.length - 1];
                        if(last.sender_id != this.senderId) {
                            this.playPing();
                        }
                    }
                }
            })
            .catch(() => { this.isFetching = false; });
    },

    handleFileUpload(e) {
        this.selectedFile = e.target.files[0];
    },

    sendMessage() {
        if(!this.newMessage.trim() && !this.selectedFile) return;
        const fd = new FormData();
        fd.append('send', '1');
        fd.append('appointment_id', this.activeApt);
        fd.append('message', this.newMessage);
        if(this.selectedFile) fd.append('attachment', this.selectedFile);
        
        fetch('../api/chat.php', { method: 'POST', body: fd })
            .then(() => {
                this.newMessage = '';
                this.selectedFile = null;
                $refs.fileInput.value = '';
                this.fetchMessages();
            });
    },

    fetchUnreadCounts() {
        fetch('../api/chat.php?unread_counts=1')
            .then(r => r.json())
            .then(data => {
                this.unreadCounts = data.unread_counts || {};
            });
    },

    playPing() {
        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3');
        audio.play().catch(e => console.log('Blocked'));
    },
    sendMessage() {
        if(!this.newMessage.trim() || !this.activeApt) return;
        const fd = new FormData();
        fd.append('send', '1');
        fd.append('appointment_id', this.activeApt);
        fd.append('message', this.newMessage);
        
        fetch('../api/chat.php', { method: 'POST', body: fd })
            .then(() => {
                this.newMessage = '';
                this.fetchMessages();
            });
    }
}" x-init="init()">

    <!-- Sidebar: Conversation List -->
    <div class="w-80 border-r border-slate-50 flex flex-col bg-slate-50/30">
        <div class="p-6 border-b border-slate-50">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Conversations</h3>
        </div>
        <div class="flex-1 overflow-y-auto">
            <?php foreach ($conversations as $conv): ?>
                <button @click="selectConversation(<?php echo $conv['apt_id']; ?>, '<?php echo e($conv['patient_name']); ?>')"
                        class="w-full p-6 text-left hover:bg-white transition-all border-b border-slate-50 flex gap-4 group relative"
                        :class="activeApt == <?php echo $conv['apt_id']; ?> ? 'bg-white shadow-sm border-l-4 border-l-teal-500' : ''">
                    
                    <!-- Unread Badge -->
                    <template x-if="unreadCounts[<?php echo $conv['apt_id']; ?>] > 0">
                        <span class="absolute top-4 right-4 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full shadow-lg shadow-red-500/20" x-text="unreadCounts[<?php echo $conv['apt_id']; ?>]"></span>
                    </template>

                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center font-black shrink-0 group-hover:scale-110 transition-transform">
                        <?php echo strtoupper(substr($conv['patient_name'], 0, 1)); ?>
                    </div>
                    <div class="overflow-hidden">
                        <p class="font-bold text-slate-900 text-sm truncate"><?php echo e($conv['patient_name']); ?></p>
                        <p class="text-[10px] font-medium text-slate-400 mt-1 truncate">
                            <?php echo $conv['last_msg'] ? e($conv['last_msg']) : 'No messages yet...'; ?>
                        </p>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main: Chat Window -->
    <div class="flex-1 flex flex-col bg-white">
        <template x-if="!activeApt">
            <div class="flex-1 flex flex-col items-center justify-center text-center p-10">
                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 text-slate-200">
                    <i data-lucide="message-square" class="w-10 h-10"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Select a conversation</h3>
                <p class="text-slate-400 text-sm font-medium mt-2">Pick a patient from the list to start messaging.</p>
            </div>
        </template>

        <template x-if="activeApt">
            <div class="flex flex-col h-full">
                <!-- Chat Header -->
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-white">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center font-black">
                            <span x-text="activeName.substring(0,1).toUpperCase()"></span>
                        </div>
                        <div>
                            <p class="font-black text-slate-900 text-sm" x-text="activeName"></p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Consultation</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages container -->
                <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar bg-slate-50/50">
                    <template x-for="(m, index) in messages" :key="m.id">
                        <div class="flex flex-col">
                            <!-- Date Divider (WhatsApp style) -->
                            <template x-if="index === 0 || new Date(m.created_at).toDateString() !== new Date(messages[index-1].created_at).toDateString()">
                                <div class="flex justify-center my-8">
                                    <span class="px-4 py-1.5 bg-white border border-slate-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest shadow-sm">
                                        <span x-text="new Date(m.created_at).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) === new Date().toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) ? 'Today' : (new Date(m.created_at).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) === new Date(Date.now() - 86400000).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) ? 'Yesterday' : new Date(m.created_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }))"></span>
                                    </span>
                                </div>
                            </template>

                            <div class="flex flex-col" :class="m.sender_id == senderId ? 'items-end' : 'items-start'">
                                <div class="max-w-[75%] space-y-1.5">
                                    <template x-if="m.message">
                                        <div class="relative group">
                                            <div class="px-5 py-3 rounded-[1.8rem] text-sm font-medium leading-relaxed shadow-sm flex flex-col gap-1"
                                                 :class="m.sender_id == senderId ? 'bg-teal-600 text-white rounded-tr-none' : 'bg-white text-slate-700 border border-slate-100 rounded-tl-none'">
                                                <span x-text="m.message"></span>
                                                <div class="flex justify-end">
                                                    <span class="text-[9px] font-bold uppercase tracking-tighter opacity-60" 
                                                          x-text="new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <template x-if="m.attachment_path">
                                        <div class="p-3 rounded-[1.5rem] bg-white border border-slate-100 flex items-center gap-3 shadow-sm"
                                             :class="m.sender_id == senderId ? 'rounded-tr-none' : 'rounded-tl-none'">
                                            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 shadow-sm">
                                                <i data-lucide="file" class="w-5 h-5"></i>
                                            </div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-[10px] font-black text-slate-900 truncate" x-text="m.attachment_name"></p>
                                                <div class="flex items-center justify-between mt-1">
                                                    <a :href="'../' + m.attachment_path" :download="m.attachment_name" class="text-[9px] font-black text-teal-600 uppercase tracking-widest hover:underline">Download</a>
                                                    <span class="text-[8px] font-bold text-slate-300" x-text="new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input area -->
                <div class="p-6 border-t border-slate-50">
                    <template x-if="selectedFile">
                        <div class="mb-4 p-3 bg-teal-50 rounded-2xl flex items-center justify-between border border-teal-100 animate-in slide-in-from-bottom-2">
                            <div class="flex items-center gap-3">
                                <i data-lucide="file-check" class="w-5 h-5 text-teal-600"></i>
                                <span class="text-xs font-bold text-teal-800" x-text="selectedFile.name"></span>
                            </div>
                            <button @click="selectedFile = null; $refs.fileInput.value = ''" class="text-teal-400 hover:text-teal-600">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>

                    <div class="relative flex items-center gap-3">
                        <input type="file" x-ref="fileInput" @change="handleFileUpload" class="hidden">
                        <button @click="$refs.fileInput.click()" class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-slate-100 hover:text-slate-600 transition-all border border-slate-100">
                            <i data-lucide="paperclip" class="w-5 h-5"></i>
                        </button>
                        <div class="flex-1 relative">
                            <input type="text" x-model="newMessage" @keydown.enter="sendMessage()" placeholder="Write your message here..."
                                class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-6 pr-16 text-sm font-medium focus:outline-none focus:border-teal-500 transition-all shadow-inner">
                            <button @click="sendMessage()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-teal-600 text-white rounded-xl flex items-center justify-center hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/20">
                                <i data-lucide="send" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
