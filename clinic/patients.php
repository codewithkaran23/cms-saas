<?php
// clinic/patients.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

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

<div class="space-y-4 animate-in fade-in duration-500">
    <!-- Header Container -->
    <div class="bg-white p-4 rounded border border-slate-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Dashboard Doctor</h2>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Patient List</p>
            </div>
            <a href="patient-add.php" class="bg-[#32a852] text-white px-4 py-2 rounded font-bold text-xs hover:bg-[#288a42] transition-all flex items-center gap-2 w-max">
                <span class="material-icons-round text-sm">add</span> Add Patient
            </a>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded border border-slate-200 p-4 shadow-sm">
        <div class="overflow-x-auto">
            <table id="patientTable" class="display cell-border w-full text-left text-[11px] stripe">
                <thead class="bg-[#f8f9fa]">
                    <tr>
                        <th class="border text-slate-700">SL.NO</th>
                        <th class="border text-slate-700">ID No.</th>
                        <th class="border text-slate-700">First Name</th>
                        <th class="border text-slate-700">Last Name</th>
                        <th class="border text-slate-700">Email Address</th>
                        <th class="border text-slate-700">Phone No</th>
                        <th class="border text-slate-700">Mobile No</th>
                        <th class="border text-slate-700">Address</th>
                        <th class="border text-slate-700">Sex</th>
                        <th class="border text-slate-700">Blood Group</th>
                        <th class="border text-slate-700">Action</th>
                        <th class="border text-slate-700">Date of Birth</th>
                        <th class="border text-slate-700">Create Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sl = $offset + 1;
                    foreach ($patients as $p): 
                    ?>
                        <tr class="hover:bg-slate-50">
                            <td class="border text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="material-icons-round text-green-500 text-base">add_circle</span>
                                    <?php echo $sl++; ?>
                                </div>
                            </td>
                            <td class="border"><?php echo e($p['id_no'] ?? 'P'.str_pad($p['user_actual_id'], 6, '0', STR_PAD_LEFT)); ?></td>
                            <td class="border font-medium"><?php echo e($p['first_name']); ?></td>
                            <td class="border"><?php echo e($p['last_name']); ?></td>
                            <td class="border"><?php echo e($p['email']); ?></td>
                            <td class="border"><?php echo e($p['phone_no'] ?: 'N/A'); ?></td>
                            <td class="border"><?php echo e($p['mobile_no'] ?: 'N/A'); ?></td>
                            <td class="border"><?php echo e($p['address'] ?: 'N/A'); ?></td>
                            <td class="border"><?php echo e($p['sex'] ?: 'N/A'); ?></td>
                            <td class="border text-center"><?php echo e($p['blood_group'] ?: 'N/A'); ?></td>
                            <td class="border">
                                <div class="grid grid-cols-2 gap-1 w-max mx-auto">
                                    <a href="patient-profile.php?id=<?php echo $p['user_id']; ?>" class="w-6 h-6 bg-green-600 text-white rounded flex items-center justify-center hover:bg-green-700 transition-all shadow-sm" title="View">
                                        <span class="material-icons-round text-[14px]">visibility</span>
                                    </a>
                                    <a href="patient-edit.php?id=<?php echo $p['user_id']; ?>" class="w-6 h-6 bg-blue-500 text-white rounded flex items-center justify-center hover:bg-blue-600 transition-all shadow-sm" title="Edit">
                                        <span class="material-icons-round text-[14px]">edit</span>
                                    </a>
                                    <a href="patient-history.php?id=<?php echo $p['user_id']; ?>" class="w-6 h-6 bg-orange-400 text-white rounded flex items-center justify-center hover:bg-orange-500 transition-all col-span-2 mx-auto shadow-sm" title="Add Document">
                                        <span class="material-icons-round text-[14px]">add</span>
                                    </a>
                                </div>
                            </td>
                            <td class="border"><?php echo date('Y-m-d', strtotime($p['dob'])); ?></td>
                            <td class="border"><?php echo date('Y-m-d', strtotime($p['create_date'])); ?></td>
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
        "pagingType": "simple_numbers"
    });
});
</script>

<style>
/* Exact Matching Styles */
.dt-button-custom {
    @apply bg-[#efefef] border border-slate-300 px-4 py-1.5 text-[11px] font-bold text-slate-700 hover:bg-slate-200 rounded-sm transition-all !important;
}
#patientTable thead th {
    @apply border border-slate-200 font-bold text-slate-700 py-3 px-4 !important;
}
#patientTable tbody td {
    @apply border border-slate-200 py-4 px-4 !important;
}
.dataTables_length select {
    @apply border border-slate-200 rounded px-2 py-1 mx-1 outline-none !important;
}
.dataTables_filter input {
    @apply border border-slate-200 rounded px-2 py-1 ml-1 outline-none !important;
}
</style>

<?php require_once 'components/footer.php'; ?>
