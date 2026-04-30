<?php
// doctor/visit.php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$appointment_id = $_GET['appointment_id'] ?? null;

if (!$appointment_id) redirect('doctor/index.php');

// Fetch appointment and patient details
$stmt = $db->prepare("
    SELECT a.*, p.name as patient_name, p.email as patient_email 
    FROM appointments a 
    JOIN users p ON a.patient_id = p.id 
    WHERE a.id = ? AND a.doctor_id = ?
");
$stmt->execute([$appointment_id, $_SESSION['user_id']]);
$appointment = $stmt->fetch();

if (!$appointment) redirect('doctor/index.php');

// Check if a visit already exists
$visit_stmt = $db->prepare("SELECT * FROM visits WHERE appointment_id = ?");
$visit_stmt->execute([$appointment_id]);
$visit = $visit_stmt->fetch();

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $symptoms = $_POST['symptoms'] ?? '';
    $diagnosis = $_POST['diagnosis'] ?? '';
    $prescription = $_POST['prescription'] ?? '';

    if ($visit) {
        $upd = $db->prepare("UPDATE visits SET symptoms = ?, diagnosis = ?, prescription_notes = ? WHERE appointment_id = ?");
        $upd->execute([$symptoms, $diagnosis, $prescription, $appointment_id]);
    } else {
        $ins = $db->prepare("INSERT INTO visits (appointment_id, symptoms, diagnosis, prescription_notes) VALUES (?, ?, ?, ?)");
        $ins->execute([$appointment_id, $symptoms, $diagnosis, $prescription]);
        
        // Mark appointment as completed
        $comp = $db->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?");
        $comp->execute([$appointment_id]);
    }
    $success = 'Medical records updated successfully!';
    $visit = ['symptoms' => $symptoms, 'diagnosis' => $diagnosis, 'prescription_notes' => $prescription];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Visit | <?php echo e($appointment['patient_name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex">

    <aside class="w-64 bg-indigo-950 min-h-screen text-white p-6 sticky top-0 h-screen">
        <h1 class="text-sm font-bold opacity-60 uppercase mb-8">Consultation</h1>
        <div class="bg-white/10 p-4 rounded-xl">
            <p class="text-xs text-indigo-300 font-bold uppercase">Patient</p>
            <p class="font-bold"><?php echo e($appointment['patient_name']); ?></p>
        </div>
        <nav class="mt-10">
            <a href="index.php" class="text-indigo-400 hover:text-white font-bold">← Back to Schedule</a>
        </nav>
    </aside>

    <main class="flex-1 p-10 max-w-5xl">
        <header class="mb-10 flex justify-between items-center">
            <h2 class="text-3xl font-black text-gray-900">Clinical <span class="text-indigo-600">Observation</span></h2>
            <?php if ($success): ?><span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-bold text-sm">✅ Saved</span><?php endif; ?>
        </header>

        <form method="POST" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-4 tracking-widest">Subjective (Symptoms)</label>
                    <textarea name="symptoms" rows="6" placeholder="Patient complains of..." class="w-full border-none bg-gray-50 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 outline-none transition"><?php echo e($visit['symptoms'] ?? ''); ?></textarea>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-4 tracking-widest">Objective (Diagnosis)</label>
                    <textarea name="diagnosis" rows="6" placeholder="Clinical findings..." class="w-full border-none bg-gray-50 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-600 outline-none transition"><?php echo e($visit['diagnosis'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest">Plan (Prescription & Notes)</label>
                    <span class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full font-bold">PDF Auto-Generator Ready</span>
                </div>
                <textarea name="prescription" rows="8" placeholder="1. Amoxicillin 500mg - 2x daily..." class="w-full border-none bg-indigo-50/30 rounded-2xl p-6 focus:ring-2 focus:ring-indigo-600 outline-none font-mono text-indigo-900 transition"><?php echo e($visit['prescription_notes'] ?? ''); ?></textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-bold shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition">
                    Save Clinical Record
                </button>
                <button type="button" onclick="window.print()" class="bg-white text-gray-600 border border-gray-200 px-8 py-4 rounded-2xl font-bold hover:bg-gray-50 transition">
                    Print Prescription
                </button>
            </div>
        </form>
    </main>

</body>
</html>
