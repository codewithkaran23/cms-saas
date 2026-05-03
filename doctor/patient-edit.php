<?php
// clinic/patient-edit.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$patient_id = $_GET['id'] ?? null;

if (!$patient_id) {
    header("Location: patients.php");
    exit;
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
    header("Location: patients.php");
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect Data
    $first_name     = trim($_POST['first_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $mobile         = trim($_POST['mobile'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $gender         = $_POST['gender'] ?? 'Male';
    $dob            = $_POST['dob'] ?? '';
    $marital_status = $_POST['marital_status'] ?? 'Single';
    $blood_group    = $_POST['blood_group'] ?? '';
    $address        = trim($_POST['address'] ?? '');
    $city           = trim($_POST['city'] ?? '');
    $state          = trim($_POST['state'] ?? '');
    $zip_code       = trim($_POST['zip_code'] ?? '');
    $status         = $_POST['status'] ?? 'Active';
    $full_name      = $first_name . ' ' . $last_name;

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

            // 2. Handle Picture Upload
            $picture_url = $patient['picture_url'];
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
                $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                $filename = 'patient_' . $patient_id . '_' . time() . '.' . $ext;
                $upload_dir = ROOT_PATH . '/uploads/patients/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $upload_dir . $filename)) {
                    $picture_url = 'uploads/patients/' . $filename;
                }
            }

            // 3. Update Patient Profile
            $profile_stmt = $db->prepare("
                UPDATE patient_profiles SET 
                first_name = ?, last_name = ?, phone_no = ?, mobile_no = ?, 
                blood_group = ?, sex = ?, dob = ?, marital_status = ?, 
                address = ?, city = ?, state = ?, zip_code = ?, 
                picture_url = ?, status = ?
                WHERE user_id = ? AND clinic_id = ?
            ");
            $profile_stmt->execute([
                $first_name, $last_name, $phone, $mobile,
                $blood_group, $gender, $dob, $marital_status,
                $address, $city, $state, $zip_code,
                $picture_url, $status,
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

<div class="max-w-5xl mx-auto py-8 space-y-10 animate-in fade-in duration-700">
    
    <!-- Top Header -->
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-600/20">
                <i data-lucide="user-cog" class="w-7 h-7"></i>
            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Edit <span class="text-emerald-600">Patient</span></h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Modify record for <?php echo e($patient['first_name'] . ' ' . $patient['last_name']); ?> (<?php echo e($patient['id_no']); ?>)</p>
            </div>
        </div>
        <a href="patients.php" class="bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-2xl font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to List
        </a>
    </header>

    <?php if ($success): ?>
        <div class="bg-emerald-600 text-white p-6 rounded-2xl shadow-xl shadow-emerald-600/20 flex items-center gap-4 animate-in zoom-in duration-300">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
            <p class="font-bold tracking-tight"><?php echo $success; ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-100 text-red-600 p-6 rounded-2xl space-y-2">
            <?php foreach ($errors as $e): ?>
                <div class="flex items-center gap-3 text-xs font-bold">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo $e; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Multi-Section Form -->
    <form method="POST" enctype="multipart/form-data" class="space-y-12">
        
        <!-- SECTION 1: Personal Information -->
        <div class="bg-white p-10 rounded-[1.5rem] border border-slate-100 shadow-sm space-y-8">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-50">
                <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] font-black shadow-sm">01</span>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Personal Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" required value="<?php echo e($patient['first_name']); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" required value="<?php echo e($patient['last_name']); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Gender</label>
                    <select name="gender" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm appearance-none cursor-pointer">
                        <option value="Male" <?php echo ($patient['sex'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($patient['sex'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($patient['sex'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Date of Birth <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="dob" id="dob_picker" required value="<?php echo e($patient['dob']); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                        <i data-lucide="calendar" class="w-5 h-5 absolute right-6 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Marital Status</label>
                    <select name="marital_status" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm appearance-none cursor-pointer">
                        <?php 
                        $m_options = ['Single', 'Married', 'Divorced', 'Widowed'];
                        foreach ($m_options as $opt) {
                            $selected = ($patient['marital_status'] === $opt) ? 'selected' : '';
                            echo "<option value=\"$opt\" $selected>$opt</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Blood Group</label>
                    <select name="blood_group" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm appearance-none cursor-pointer">
                        <option value="">Select Blood Group</option>
                        <?php 
                        $groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                        foreach ($groups as $g) {
                            $selected = ($patient['blood_group'] === $g) ? 'selected' : '';
                            echo "<option value=\"$g\" $selected>$g</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Contact Information -->
        <div class="bg-white p-10 rounded-[1.5rem] border border-slate-100 shadow-sm space-y-8">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-50">
                <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] font-black shadow-sm">02</span>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Contact Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Primary Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required value="<?php echo e($patient['email']); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="text" name="mobile" required value="<?php echo e($patient['mobile_no']); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Residential Address</label>
                    <textarea name="address" rows="2" class="w-full bg-slate-50 border border-slate-100 px-8 py-6 rounded-[2rem] focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm resize-none"><?php echo e($patient['address']); ?></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                    <input type="text" name="city" value="<?php echo e($patient['city'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">State</label>
                        <input type="text" name="state" value="<?php echo e($patient['state'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">PIN Code</label>
                        <input type="text" name="zip_code" value="<?php echo e($patient['zip_code'] ?? ''); ?>" class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Clinical & System Data -->
        <div class="bg-white p-10 rounded-[1.5rem] border border-slate-100 shadow-sm space-y-8">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-50">
                <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] font-black shadow-sm">03</span>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Clinical & System Data</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Patient Status</label>
                    <div class="flex items-center gap-6 p-4">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-slate-600">
                            <input type="radio" name="status" value="Active" <?php echo ($patient['status'] === 'Active') ? 'checked' : ''; ?> class="w-4 h-4 text-emerald-500 focus:ring-emerald-500 border-slate-300"> Active
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-slate-600">
                            <input type="radio" name="status" value="Inactive" <?php echo ($patient['status'] === 'Inactive') ? 'checked' : ''; ?> class="w-4 h-4 text-emerald-500 focus:ring-emerald-500 border-slate-300"> Inactive
                        </label>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Profile Photo</label>
                    <div class="flex items-center gap-4">
                        <?php if ($patient['picture_url']): ?>
                            <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-200">
                                <img src="<?php echo base_url($patient['picture_url']); ?>" class="w-full h-full object-cover">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="picture" class="flex-1 text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer">
                    </div>
                </div>
                <div class="md:col-span-2 pt-6 border-t border-slate-50">
                    <div class="bg-slate-50 p-4 rounded-xl flex items-center justify-between">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Patient System ID</span>
                        <span class="text-sm font-black text-slate-700"><?php echo e($patient['id_no']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar (Integrated) -->
        <div class="flex items-center justify-between bg-slate-900 p-10 rounded-[1.5rem] shadow-2xl">
            <div>
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                    Update Mode
                </p>
                <p class="text-slate-400 text-xs font-medium mt-1">Modifying existing clinical record.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="patients.php" class="px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-400 hover:text-white transition-all">Cancel</a>
                <button type="submit" class="bg-emerald-600 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/30 hover:bg-emerald-700 hover:-translate-y-1 transition-all flex items-center gap-3">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Record Changes
                </button>
            </div>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Icons
    lucide.createIcons();

    // Date Picker
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
