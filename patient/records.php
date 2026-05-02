<?php
// patient/records.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

// Fetch all documents for this patient
$stmt = $db->prepare("
    SELECT * FROM patient_documents 
    WHERE patient_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$patient_id]);
$documents = $stmt->fetchAll();

$page_title = "My Medical Records";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Medical <span class="text-teal-600">Records</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Access your clinical documents, prescriptions, and reports.</p>
        </div>
        <div class="bg-white border border-slate-200 px-6 py-3 rounded-2xl flex items-center gap-3 shadow-sm">
            <i data-lucide="shield-check" class="w-5 h-5 text-teal-500"></i>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Secure Vault Active</span>
        </div>
    </header>

    <!-- Storage Info (Mockup) -->
    <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-xl shadow-slate-900/20 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 relative z-10">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Health Data Storage</p>
                <h3 class="text-3xl font-black mt-2">Personal Records Vault</h3>
                <p class="text-slate-400 text-sm font-medium mt-1">Encrypted storage for your medical history.</p>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Files</p>
                    <p class="text-2xl font-black mt-1"><?php echo count($documents); ?></p>
                </div>
                <div class="w-px h-10 bg-white/10"></div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Capacity</p>
                    <p class="text-2xl font-black mt-1">100 GB</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($documents as $doc): 
            $ext = pathinfo($doc['file_url'], PATHINFO_EXTENSION);
            $icon = 'file-text';
            $color = 'text-teal-600 bg-teal-50 border-teal-100/50';
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                $icon = 'image';
                $color = 'text-orange-500 bg-orange-50 border-orange-100/50';
            } elseif (strtolower($ext) === 'pdf') {
                $icon = 'file';
                $color = 'text-red-500 bg-red-50 border-red-100/50';
            }
        ?>
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/20 transition-all group">
                <div class="flex items-start justify-between mb-8">
                    <div class="w-16 h-16 rounded-2xl <?php echo $color; ?> border flex items-center justify-center transition-transform group-hover:scale-110">
                        <i data-lucide="<?php echo $icon; ?>" class="w-8 h-8"></i>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="<?php echo base_url($doc['file_url']); ?>" target="_blank" class="w-9 h-9 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-teal-50 hover:text-teal-600 transition-all border border-transparent hover:border-teal-100/50">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                        <a href="<?php echo base_url($doc['file_url']); ?>" download class="w-9 h-9 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100/50">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
                
                <h5 class="text-lg font-black text-slate-900 tracking-tight truncate"><?php echo basename($doc['file_url']); ?></h5>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1"><?php echo $ext; ?> File • <?php echo date('M d, Y', strtotime($doc['created_at'])); ?></p>
                
                <div class="mt-8 pt-8 border-t border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Verified by Clinic</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($documents)): ?>
            <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="cloud-off" class="w-10 h-10"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">No Documents Yet</h3>
                <p class="text-slate-400 text-sm font-medium mt-2 max-w-xs mx-auto">Any documents or reports shared by your doctor will appear here automatically.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
