<?php
// clinic/patients.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];

// Pagination & Search logic
$search = $_GET['search'] ?? '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Base query joining users with patient_profiles
$sql = "
    FROM users u 
    JOIN patient_profiles pp ON u.id = pp.user_id
    WHERE u.clinic_id = ? 
    AND u.role_id = (SELECT id FROM roles WHERE name = 'Patient')
    AND u.deleted_at IS NULL
";

$params = [$clinic_id];

if ($search) {
    $sql .= " AND (pp.first_name LIKE ? OR pp.last_name LIKE ? OR u.email LIKE ? OR pp.mobile_no LIKE ? OR pp.id_no LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Get total count for pagination
$count_stmt = $db->prepare("SELECT COUNT(*) " . $sql);
$count_stmt->execute($params);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Fetch actual data
$data_sql = "SELECT u.id as user_actual_id, u.email, u.created_at as create_date, pp.* " . $sql . " ORDER BY u.created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $db->prepare($data_sql);
$stmt->execute($params);
$patients = $stmt->fetchAll();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<!-- DataTables & Buttons Styles/Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Container -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Patient <span class="text-teal-600">Directory</span></h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Manage and monitor all registered patients in your practice.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-2xl font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i> Export CSV
            </button>
            <a href="patient-add.php" class="bg-teal-600 text-white px-6 py-3 rounded-2xl font-bold text-xs shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Patient
            </a>
        </div>
    </header>

    <!-- Main Table Container -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="patientTable" class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-4 py-4">SL</th>
                        <th class="px-4 py-4">ID No.</th>
                        <th class="px-4 py-4">Patient Name</th>
                        <th class="px-4 py-4">Contact</th>
                        <th class="px-4 py-4">Gender</th>
                        <th class="px-4 py-4 text-center">Blood</th>
                        <th class="px-4 py-4">Joined Date</th>
                        <th class="px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php 
                    $sl = $offset + 1;
                    foreach($patients as $p): 
                    ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-4 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-slate-300">#<?php echo str_pad($sl++, 2, '0', STR_PAD_LEFT); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-bold"><?php echo e($p['id_no'] ?? 'P'.str_pad($p['user_actual_id'], 6, '0', STR_PAD_LEFT)); ?></span>
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center font-black text-sm border border-teal-100/50">
                                        <?php echo strtoupper(substr($p['first_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-slate-900 text-sm"><?php echo e($p['first_name'] . ' ' . $p['last_name']); ?></h5>
                                        <p class="text-[10px] font-medium text-slate-400"><?php echo e($p['email']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <p class="text-xs font-bold text-slate-700"><?php echo e($p['mobile_no'] ?: 'N/A'); ?></p>
                            </td>
                            <td class="px-4 py-5">
                                <span class="text-xs font-medium text-slate-500"><?php echo e($p['sex'] ?: 'N/A'); ?></span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <span class="text-xs font-black text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-100/50"><?php echo e($p['blood_group'] ?: '--'); ?></span>
                            </td>
                            <td class="px-4 py-5">
                                <p class="text-xs font-medium text-slate-500"><?php echo date('M d, Y', strtotime($p['create_date'])); ?></p>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="patient-profile.php?id=<?php echo $p['user_id']; ?>" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-teal-50 hover:text-teal-600 transition-all border border-transparent hover:border-teal-100/50" title="View Profile">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="patient-edit.php?id=<?php echo $p['user_id']; ?>" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100/50" title="Edit Record">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <a href="patient-history.php?id=<?php echo $p['user_id']; ?>" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-orange-50 hover:text-orange-600 transition-all border border-transparent hover:border-orange-100/50" title="Clinical History">
                                        <i data-lucide="folder-heart" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#patientTable').DataTable({
        "dom": '<"flex flex-col md:flex-row justify-between items-center mb-6"<"flex items-center"l>Bf>rt<"flex justify-between items-center mt-4"ip>',
        "buttons": [
            { extend: 'copy', className: 'dt-button-custom' },
            { extend: 'csv', className: 'dt-button-custom' },
            { extend: 'excel', className: 'dt-button-custom' },
            { extend: 'pdf', className: 'dt-button-custom' },
            { extend: 'print', className: 'dt-button-custom' }
        ],
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries"
        },
        "pagingType": "simple_numbers",
        "drawCallback": function(settings) {
            // Re-draw Lucide icons every time the table paginates or searches
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    });
});
</script>

<style>
/* Custom DataTables Styling to match Emerald Theme */
.dataTables_wrapper .dataTables_filter input {
    @apply bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs focus:ring-4 focus:ring-teal-500/5 focus:border-teal-500 outline-none transition-all w-64 ml-2 !important;
}
.dataTables_wrapper .dataTables_length select {
    @apply bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs outline-none mx-2 !important;
}
.dt-buttons .dt-button {
    @apply bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm mr-2 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    @apply bg-teal-600 text-white border-transparent rounded-xl px-4 py-2 text-xs font-bold shadow-lg shadow-teal-600/20 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    @apply bg-slate-100 text-slate-900 border-transparent rounded-xl !important;
}
</style>

<?php require_once 'components/footer.php'; ?>
