<?php
// clinic/records.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Fetch all documents with patient info
$stmt = $db->prepare("
    SELECT d.*, u.name as patient_name, pp.id_no 
    FROM patient_documents d
    JOIN users u ON d.patient_id = u.id
    JOIN patient_profiles pp ON u.id = pp.user_id
    WHERE d.clinic_id = ?
    ORDER BY d.created_at DESC
");
$stmt->execute([$clinic_id]);
$documents = $stmt->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Clinical <span class="text-teal-600">Records</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Manage and access all patient documents and medical reports.</p>
        </div>
        <a href="patient-document-add.php" class="bg-teal-600 text-white px-6 py-3 rounded-2xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Upload Document
        </a>
    </header>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all">
            <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                <i data-lucide="file-text" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Documents</p>
                <h4 class="text-3xl font-black text-slate-900 mt-1"><?php echo count($documents); ?></h4>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-6 group hover:shadow-md transition-all">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                <i data-lucide="calendar" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Added This Month</p>
                <h4 class="text-3xl font-black text-slate-900 mt-1">0</h4>
            </div>
        </div>
        <div class="bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-900/20 flex items-center gap-6 group">
            <div class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center backdrop-blur-md transition-transform group-hover:scale-110">
                <i data-lucide="database" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Storage Used</p>
                <h4 class="text-3xl font-black text-white mt-1">1.2 MB</h4>
            </div>
        </div>
    </div>

    <!-- Documents List -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-50">
                        <th class="px-10 py-6">Document</th>
                        <th class="px-8 py-6">Patient</th>
                        <th class="px-8 py-6">Upload Date</th>
                        <th class="px-10 py-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($documents as $doc): 
                        $ext = pathinfo($doc['file_url'], PATHINFO_EXTENSION);
                        $icon = 'file-text';
                        $color = 'text-blue-500 bg-blue-50';
                        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                            $icon = 'image';
                            $color = 'text-purple-500 bg-purple-50';
                        } elseif (strtolower($ext) === 'pdf') {
                            $icon = 'file';
                            $color = 'text-red-500 bg-red-50';
                        }
                    ?>
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl <?php echo $color; ?> flex items-center justify-center border border-current opacity-80">
                                        <i data-lucide="<?php echo $icon; ?>" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900"><?php echo basename($doc['file_url']); ?></p>
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5"><?php echo $ext; ?> Format</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xs font-black border border-teal-100/50">
                                        <?php echo substr($doc['patient_name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-700"><?php echo e($doc['patient_name']); ?></p>
                                        <p class="text-[9px] font-black text-teal-600 uppercase tracking-widest">ID: <?php echo e($doc['id_no']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-xs font-bold text-slate-500 tracking-tight">
                                <?php echo date('M d, Y', strtotime($doc['created_at'])); ?>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                    <a href="<?php echo base_url($doc['file_url']); ?>" target="_blank" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-teal-50 hover:text-teal-600 transition-all border border-transparent hover:border-teal-100/50">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="<?php echo base_url($doc['file_url']); ?>" download class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100/50">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </a>
                                    <button class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-all border border-transparent hover:border-red-100/50">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($documents)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <i data-lucide="cloud-off" class="w-12 h-12 mb-2 mx-auto opacity-50"></i>
                                <p class="text-xs font-bold">No documents uploaded yet.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
