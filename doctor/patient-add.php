<?php
// clinic/patient-add.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect Data
    $first_name     = trim($_POST['first_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = generate_random_password();
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
    if (empty($dob))        $errors[] = "Date of Birth is required.";

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
                INSERT INTO users (clinic_id, role_id, name, email, password_hash, phone, require_reset) 
                VALUES (?, ?, ?, ?, ?, ?, 1)
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
                
                // Use absolute path from ROOT_PATH
                $upload_dir = ROOT_PATH . '/uploads/patients/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $upload_dir . $filename)) {
                    $picture_url = 'uploads/patients/' . $filename;
                } else {
                    // Log error if move fails
                    error_log("Failed to move uploaded file to: " . $upload_dir . $filename);
                }
            } elseif (isset($_FILES['picture']) && $_FILES['picture']['error'] !== 4) {
                // Error 4 means no file was uploaded, which is fine. Other errors should be logged.
                error_log("Upload Error Code: " . $_FILES['picture']['error']);
            }

            // 3. Create Patient Profile
            $id_no = 'MED-' . str_pad($user_id, 6, '0', STR_PAD_LEFT);
            $profile_stmt = $db->prepare("
                INSERT INTO patient_profiles (
                    user_id, clinic_id, id_no, first_name, last_name, 
                    phone_no, mobile_no, blood_group, sex, dob, 
                    marital_status, address, city, state, zip_code, 
                    picture_url, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $profile_stmt->execute([
                $user_id, $clinic_id, $id_no, $first_name, $last_name,
                $phone, $mobile, $blood_group, $gender, $dob,
                $marital_status, $address, $city, $state, $zip_code,
                $picture_url, $status
            ]);

            $db->commit();
            
            // Send Credentials Email (Suppress errors on local servers)
            @send_credentials_email($email, $full_name, $password, 'Patient');

            $success = "Patient record created successfully! Credentials sent to email.";
            header("Refresh:2; url=patients.php");
        } catch (PDOException $e) {
            $db->rollBack();
            $errors[] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = "System Error: " . $e->getMessage();
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
                <i data-lucide="user-plus" class="w-7 h-7"></i>
            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Add New <span class="text-emerald-600">Patient</span></h2>
                <p class="text-slate-500 text-sm font-medium mt-1">Register a new patient profile and send automated clinical credentials.</p>
            </div>
        </div>
        <a href="patients.php" class="bg-white border border-slate-200 text-slate-700 px-6 py-3 rounded-2xl font-bold text-xs shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </a>
    </header>

    <!-- Auto-Email Alert Box -->
    <div class="bg-emerald-50/50 border border-emerald-100/50 p-6 rounded-[1.5rem] flex items-start gap-4">
        <div class="w-10 h-10 bg-emerald-100/50 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
            <i data-lucide="mail" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-emerald-800 text-sm font-bold">Credential Automation Enabled</p>
            <p class="text-emerald-600/80 text-xs font-medium mt-1 leading-relaxed">
                A professional welcome email with login credentials and a temporary password will be sent automatically to the <strong>email address</strong> provided below.
            </p>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-100 text-red-600 p-6 rounded-[2rem] space-y-2">
            <?php foreach ($errors as $e): ?>
                <div class="flex items-center gap-3 text-xs font-bold">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo $e; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-emerald-600 text-white p-6 rounded-[2rem] shadow-xl shadow-emerald-600/20 flex items-center gap-4 animate-in zoom-in duration-300">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
            <p class="font-bold tracking-tight"><?php echo $success; ?></p>
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
                    <input type="text" name="first_name" required placeholder="e.g. Priya" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" required placeholder="e.g. Sharma" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Gender</label>
                    <select name="gender" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm appearance-none cursor-pointer">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Date of Birth <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="dob" id="dob_picker" required placeholder="mm/dd/yyyy" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                        <i data-lucide="calendar" class="w-5 h-5 absolute right-6 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Marital Status</label>
                    <select name="marital_status" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm appearance-none cursor-pointer">
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Blood Group</label>
                    <select name="blood_group" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm appearance-none cursor-pointer">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option><option value="A-">A-</option>
                        <option value="B+">B+</option><option value="B-">B-</option>
                        <option value="O+">O+</option><option value="O-">O-</option>
                        <option value="AB+">AB+</option><option value="AB-">AB-</option>
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
                    <input type="email" name="email" required placeholder="priya@example.com" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                    <p class="text-[9px] text-emerald-600 font-bold ml-1 italic uppercase tracking-widest mt-2 flex items-center gap-1">
                        <i data-lucide="info" class="w-3 h-3"></i> Login details will be sent here
                    </p>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="text" name="mobile" required placeholder="+91 98765 43210" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Residential Address</label>
                    <textarea name="address" rows="2" placeholder="Street address..." class="w-full bg-slate-50/50 border border-slate-100 px-8 py-6 rounded-[2rem] focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm resize-none"></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                    <input type="text" name="city" placeholder="Chandigarh" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">State</label>
                        <input type="text" name="state" placeholder="Punjab" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">PIN Code</label>
                        <input type="text" name="zip_code" placeholder="160055" class="w-full bg-slate-50/50 border border-slate-100 px-6 py-4 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700 text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Clinical Registration -->
        <div class="bg-white p-10 rounded-[1.5rem] border border-slate-100 shadow-sm space-y-8">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-50">
                <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] font-black shadow-sm">03</span>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Clinical Registration</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Patient ID (Auto)</label>
                    <div class="w-full bg-slate-50 border border-dashed border-slate-200 px-6 py-4 rounded-2xl font-bold text-slate-400 text-sm italic">
                        MED-XXXXXX (Generated on Save)
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Initial Status</label>
                    <div class="flex items-center gap-6 p-4">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-slate-600">
                            <input type="radio" name="status" value="Active" checked class="w-4 h-4 text-emerald-500 focus:ring-emerald-500 border-slate-300"> Active
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-slate-600">
                            <input type="radio" name="status" value="Inactive" class="w-4 h-4 text-emerald-500 focus:ring-emerald-500 border-slate-300"> Inactive
                        </label>
                    </div>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Profile Photo</label>
                    <input type="file" name="picture" class="w-full text-xs text-slate-500 file:mr-6 file:py-4 file:px-8 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer">
                </div>
            </div>
        </div>

        <!-- Action Bar (Integrated, non-fixed) -->
        <div class="flex items-center justify-between bg-slate-900 p-10 rounded-[1.5rem] shadow-2xl">
            <div>
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Ready to onboard
                </p>
                <p class="text-slate-400 text-xs font-medium mt-1">Credentials will be sent on submission.</p>
            </div>
            <div class="flex items-center gap-4">
                <button type="reset" class="px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-400 hover:text-white transition-all">Reset Form</button>
                <button type="submit" class="bg-emerald-600 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/30 hover:bg-emerald-700 hover:-translate-y-1 transition-all flex items-center gap-3">
                    <i data-lucide="check" class="w-4 h-4"></i> Create Patient Record
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
