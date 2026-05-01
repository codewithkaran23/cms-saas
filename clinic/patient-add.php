<?php
// clinic/patient-add.php
require_once '../core/init.php';
Auth::protect('Clinic Admin');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect Data
    $id_no      = trim($_POST['id_no'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = trim($_POST['password'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $mobile     = trim($_POST['mobile'] ?? '');
    $blood      = $_POST['blood_group'] ?? '';
    $sex        = $_POST['sex'] ?? 'Male';
    $dob        = $_POST['dob'] ?? '';
    $address    = trim($_POST['address'] ?? '');
    $status     = $_POST['status'] ?? 'Active';
    $full_name  = $first_name . ' ' . $last_name;

    // Basic Validation
    if (empty($first_name)) $errors[] = "First Name is required.";
    if (empty($last_name))  $errors[] = "Last Name is required.";
    if (empty($email))      $errors[] = "Email Address is required.";
    if (empty($password))   $errors[] = "Password is required.";
    if (empty($mobile))     $errors[] = "Mobile Number is required.";
    if (empty($dob))        $errors[] = "Date of Birth is required.";
    if (empty($address))    $errors[] = "Address is required.";

    // Check email unique
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND clinic_id = ?");
    $stmt->execute([$email, $clinic_id]);
    if ($stmt->fetch()) {
        $errors[] = "A patient with this email already exists.";
    }

    if (empty($errors)) {
        try {
            $db->beginTransaction();

            // 1. Create User
            $role_stmt = $db->prepare("SELECT id FROM roles WHERE name = 'Patient'");
            $role_stmt->execute();
            $role_id = $role_stmt->fetchColumn();

            $user_stmt = $db->prepare("
                INSERT INTO users (clinic_id, role_id, name, email, password_hash, phone) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $user_stmt->execute([
                $clinic_id, 
                $role_id, 
                $full_name, 
                $email, 
                password_hash($password, PASSWORD_DEFAULT),
                $mobile
            ]);
            $user_id = $db->lastInsertId();

            // 2. Handle Picture Upload
            $picture_url = '';
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
                $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                $filename = 'patient_' . $user_id . '_' . time() . '.' . $ext;
                $upload_dir = '../uploads/patients/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                move_uploaded_file($_FILES['picture']['tmp_name'], $upload_dir . $filename);
                $picture_url = 'uploads/patients/' . $filename;
            }

            // 3. Create Patient Profile
            $profile_stmt = $db->prepare("
                INSERT INTO patient_profiles (user_id, clinic_id, id_no, first_name, last_name, phone_no, mobile_no, blood_group, sex, dob, address, picture_url, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $profile_stmt->execute([
                $user_id,
                $clinic_id,
                $id_no ?: 'P'.str_pad($user_id, 6, '0', STR_PAD_LEFT),
                $first_name,
                $last_name,
                $phone,
                $mobile,
                $blood,
                $sex,
                $dob,
                $address,
                $picture_url,
                $status
            ]);

            $db->commit();
            $success = "Patient record created successfully!";
            header("Refresh:1; url=patients.php");
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = "Transaction failed: " . $e->getMessage();
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
            <h2 class="text-xl font-bold text-slate-800">Dashboard Doctor</h2>
            <p class="text-xs text-slate-500 font-medium">Add Patient</p>
        </div>
        <a href="patients.php" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-bold text-xs shadow-md shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center gap-2">
            <span class="material-icons-round text-sm">list</span> Patient List
        </a>
    </header>

    <?php if ($success): ?>
        <div class="bg-green-600 text-white p-4 rounded-xl font-bold shadow-lg shadow-green-600/20 flex items-center gap-3 text-sm">
            <span class="material-icons-round">check_circle</span> <?php echo $success; ?>
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
                    <input type="text" name="id_no" placeholder="ID No." class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm max-w-lg">
                </div>

                <!-- Row: Names -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" required placeholder="First Name" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" required placeholder="Last Name" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                </div>

                <!-- Row: Email & Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="Email Address" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required placeholder="Password" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                </div>

                <!-- Row: Phone & Mobile -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Phone No</label>
                        <input type="text" name="phone" placeholder="Phone No" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Mobile No <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile" required placeholder="Mobile No" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                    </div>
                </div>

                <!-- Row: Blood Group & Sex -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Blood Group</label>
                        <select name="blood_group" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                            <option value="">Select option</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Sex <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                                <input type="radio" name="sex" value="Male" checked class="w-3.5 h-3.5 text-blue-600"> Male
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                                <input type="radio" name="sex" value="Female" class="w-3.5 h-3.5 text-blue-600"> Female
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                                <input type="radio" name="sex" value="Other" class="w-3.5 h-3.5 text-blue-600"> Other
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Row: DOB & Picture -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Date of Birth <span class="text-red-500">*</span></label>
                        <div class="flex-1 relative">
                            <input type="text" name="dob" id="dob_picker" required placeholder="Select Date" class="w-full bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm">
                            <span class="material-icons-round absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">calendar_today</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-xs font-bold text-slate-700">Picture</label>
                        <input type="file" name="picture" class="flex-1 text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700">
                    </div>
                </div>

                <!-- Row: Address -->
                <div class="flex items-start">
                    <label class="w-1/6 text-xs font-bold text-slate-700 mt-2">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" required rows="3" placeholder="Address" class="flex-1 bg-slate-50 border border-slate-100 px-4 py-2 rounded-lg focus:bg-white focus:border-blue-500 transition-all outline-none text-sm"></textarea>
                </div>

                <!-- Row: Status -->
                <div class="flex items-center">
                    <label class="w-1/6 text-xs font-bold text-slate-700">Status</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                            <input type="radio" name="status" value="Active" checked class="w-3.5 h-3.5 text-blue-600"> Active
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-slate-600">
                            <input type="radio" name="status" value="Inactive" class="w-3.5 h-3.5 text-blue-600"> Inactive
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50/80 px-8 py-3 flex items-center gap-2">
                <button type="reset" class="bg-slate-200 text-slate-700 px-5 py-2 rounded-lg font-bold text-xs hover:bg-slate-300 transition-all">Reset</button>
                <div class="text-slate-300 text-xs font-bold italic px-2">or</div>
                <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-lg font-bold text-xs shadow-md shadow-green-600/20 hover:bg-green-700 transition-all">Save</button>
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
