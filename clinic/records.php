<?php
// clinic/records.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

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

<div class="space-y-6 animate-in fade-in duration-500">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Clinical <span class="text-blue-600">Records</span></h2>
            <p class="text-slate-500 text-xs font-medium mt-1">Manage and access all patient documents and medical reports.</p>
        </div>
        <a href="patient-document-add.php" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center gap-2">
            <span class="material-icons-round text-sm">add</span> Upload Document
        </a>
    </header>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                <span class="material-icons-round text-2xl">description</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Documents</p>
                <h4 class="text-xl font-bold text-slate-900"><?php echo count($documents); ?></h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center">
                <span class="material-icons-round text-2xl">today</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Added This Month</p>
                <h4 class="text-xl font-bold text-slate-900">0</h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center">
                <span class="material-icons-round text-2xl">storage</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Storage Used</p>
                <h4 class="text-xl font-bold text-slate-900">1.2 MB</h4>
            </div>
        </div>
    </div>

    <!-- Documents List -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Document</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Patient</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Upload Date</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($documents as $doc): 
                        $ext = pathinfo($doc['file_url'], PATHINFO_EXTENSION);
                        $icon = 'description';
                        $color = 'text-blue-500 bg-blue-50';
                        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                            $icon = 'image';
                            $color = 'text-purple-500 bg-purple-50';
                        } elseif (strtolower($ext) === 'pdf') {
                            $icon = 'picture_as_pdf';
                            $color = 'text-red-500 bg-red-50';
                        }
                    ?>
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl <?php echo $color; ?> flex items-center justify-center">
                                        <span class="material-icons-round"><?php echo $icon; ?></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900"><?php echo basename($doc['file_url']); ?></p>
                                        <p class="text-[10px] text-slate-400 font-medium capitalize"><?php echo $ext; ?> File</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                        <?php echo substr($doc['patient_name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700"><?php echo e($doc['patient_name']); ?></p>
                                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest">ID: <?php echo e($doc['id_no']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-500">
                                <?php echo date('M d, Y', strtotime($doc['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                    <a href="<?php echo base_url($doc['file_url']); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                        <span class="material-icons-round text-sm">visibility</span>
                                    </a>
                                    <a href="<?php echo base_url($doc['file_url']); ?>" download class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-green-600 hover:text-white transition-all">
                                        <span class="material-icons-round text-sm">download</span>
                                    </a>
                                    <button class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                        <span class="material-icons-round text-sm">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($documents)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-icons-round text-4xl mb-2">cloud_off</span>
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
