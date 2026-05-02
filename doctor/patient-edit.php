<?php
// clinic/patient-edit.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$patient_id = $_GET['id'] ?? null;

if (!$patient_id) {
    redirect('clinic/patients.php');
}

// Fetch existing data
$stmt = $db->prepare("
    SELECT u.email, pp.* 
    FROM users u 
    JOIN patient_profiles pp ON u.id = pp.user_id 
    WHERE u.id = ? AND u.clinic_id = ?
");
$stmt->execute([$patient_id, $clinic_id]);
$patient = $stmt->fetch();

if (!$patient) {
    redirect('clinic/patients.php');
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect Data
    $id_no      = trim($_POST['id_no'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $mobile     = trim($_POST['mobile'] ?? '');
    $blood      = $_POST['blood_group'] ?? '';
    $sex        = $_POST['sex'] ?? '';
    $dob        = $_POST['dob'] ?? '';
    $address    = trim($_POST['address'] ?? '');
    $status     = $_POST['status'] ?? 'Active';
    $full_name  = $first_name . ' ' . $last_name;

    // Basic Validation
    if (empty($first_name)) $errors[] = "First Name is required.";
    if (empty($last_name))  $errors[] = "Last Name is required.";
    if (empty($email))      $errors[] = "Email Address is required.";
    if (empty($mobile))     $errors[] = "Mobile Number is required.";

    if (empty($errors)) {
        try {
            $db->beginTransaction();

            // 1. Update User
            $user_stmt = $db->prepare("
                UPDATE users SET name = ?, email = ?, phone = ? 
                WHERE id = ? AND clinic_id = ?
            ");
            $user_stmt->execute([$full_name, $email, $mobile, $patient_id, $clinic_id]);

            // 2. Handle Picture Upload (Optional)
            $picture_url = $patient['picture_url'];
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
                $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                $filename = 'patient_' . $patient_id . '_' . time() . '.' . $ext;
                $upload_dir = '../uploads/patients/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                move_uploaded_file($_FILES['picture']['tmp_name'], $upload_dir . $filename);
                $picture_url = 'uploads/patients/' . $filename;
            }

            // 3. Update Patient Profile
            $profile_stmt = $db->prepare("
                UPDATE patient_profiles SET 
                id_no = ?, first_name = ?, last_name = ?, phone_no = ?, mobile_no = ?, 
                blood_group = ?, sex = ?, dob = ?, address = ?, picture_url = ?, status = ?
                WHERE user_id = ? AND clinic_id = ?
            ");
            $profile_stmt->execute([
                $id_no, $first_name, $last_name, $phone, $mobile,
                $blood, $sex, $dob, $address, $picture_url, $status,
                $patient_id, $clinic_id
            ]);

            $db->commit();
            $success = "Patient record updated successfully!";
            // Refresh patient data
            $stmt->execute([$patient_id, $clinic_id]);
            $patient = $stmt->fetch();
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = "Update failed: " . $e->getMessage();
        }
    }
}

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-6xl mx-auto py-6 space-y-6 animate-in fade-in duration-500">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Patient</h2>
            <p class="text-xs text-slate-500 font-medium">Modify record for <?php echo e($patient['first_name']); ?></p>
        </div>
        <a href="patients.php" class="bg-slate-100 text-slate-600 px-5 py-2 rounded-lg font-bold text-xs hover:bg-slate-200 transition-all flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to List
        </a>
    </header>

    <?php if ($success): ?>
        <div class="bg-green-600 text-white p-4 rounded-xl font-bold shadow-lg shadow-green-600/20 flex items-center gap-3 text-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl text-xs font-bold space-y-1">
            <?php foreach ($errors as $e): ?>
                <p>• <?php echo $e; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded border border-slate-200 overflow-hidden shadow-sm">
        <form method="POST" enctype="multipart/form-data" class="divide-y divide-slate-100">
            <div class="p-8 space-y-6">
                <!-- Row: ID No -->
                <div class="flex items-center">
                    <label class="w-1/6 text-xs font-bold text-slate-700">ID No.</label>
                    <input type="text" name="id_no" value="<?php echo e($patient['id_no']); ?>" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm max-w-lg">
                </div>

                <!-- Row: Names -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" required value="<?php echo e($patient['first_name']); ?>" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" required value="<?php echo e($patient['last_name']); ?>" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                </div>

                <!-- Row: Email -->
                <div class="flex items-center">
                    <label class="w-1/6 text-xs font-bold text-slate-700">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required value="<?php echo e($patient['email']); ?>" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm max-w-lg">
                </div>

                <!-- Row: Phone & Mobile -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Phone No</label>
                        <input type="text" name="phone" value="<?php echo e($patient['phone_no']); ?>" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Mobile No <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile" required value="<?php echo e($patient['mobile_no']); ?>" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                </div>

                <!-- Row: Blood Group & Sex -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Blood Group</label>
                        <select name="blood_group" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                            <option value="">Select option</option>
                            <?php 
                            $groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                            foreach ($groups as $g) {
                                $selected = ($patient['blood_group'] === $g) ? 'selected' : '';
                                echo "<option value=\"$g\" $selected>$g</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Sex <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                                <input type="radio" name="sex" value="Male" <?php echo ($patient['sex'] === 'Male') ? 'checked' : ''; ?> class="w-3.5 h-3.5 text-blue-600"> Male
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                                <input type="radio" name="sex" value="Female" <?php echo ($patient['sex'] === 'Female') ? 'checked' : ''; ?> class="w-3.5 h-3.5 text-blue-600"> Female
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                                <input type="radio" name="sex" value="Other" <?php echo ($patient['sex'] === 'Other') ? 'checked' : ''; ?> class="w-3.5 h-3.5 text-blue-600"> Other
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Row: DOB & Picture -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Date of Birth <span class="text-red-500">*</span></label>
                        <div class="flex-1 relative">
                            <input type="text" name="dob" id="dob_picker" required value="<?php echo e($patient['dob']); ?>" class="w-full bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                            <i data-lucide="calendar" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Picture</label>
                        <div class="flex items-center gap-3">
                            <?php if ($patient['picture_url']): ?>
                                <img src="<?php echo base_url($patient['picture_url']); ?>" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                            <?php endif; ?>
                            <input type="file" name="picture" class="flex-1 text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700">
                        </div>
                    </div>
                </div>

                <!-- Row: Address -->
                <div class="flex items-start">
                    <label class="w-1/6 text-xs font-bold text-slate-700 mt-2">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" required rows="3" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm"><?php echo e($patient['address']); ?></textarea>
                </div>

                <!-- Row: Status -->
                <div class="flex items-center">
                    <label class="w-1/6 text-xs font-bold text-slate-700">Status</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                            <input type="radio" name="status" value="Active" <?php echo ($patient['status'] === 'Active') ? 'checked' : ''; ?> class="w-3.5 h-3.5 text-blue-600"> Active
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                            <input type="radio" name="status" value="Inactive" <?php echo ($patient['status'] === 'Inactive') ? 'checked' : ''; ?> class="w-3.5 h-3.5 text-blue-600"> Inactive
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50/80 px-8 py-3 flex items-center justify-end">
                <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg font-bold text-xs shadow-md shadow-blue-600/20 hover:bg-blue-700 transition-all">Update Patient</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    flatpickr("#dob_picker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "F j, Y",
        maxDate: "today",
        disableMobile: "true"
    });
});
</script>

<?php require_once 'components/footer.php'; ?>
