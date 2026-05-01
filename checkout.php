<?php
// checkout.php
require_once 'core/init.php';

// Ensure user is logged in and is a Clinic Admin
Auth::protect('Clinic Admin');

$clinic_id = $_SESSION['clinic_id'];
$db = getDB();

// Fetch clinic details and current plan
$stmt = $db->prepare("
    SELECT c.*, sp.price, sp.features 
    FROM clinics c 
    LEFT JOIN subscription_plans sp ON c.subscription_tier = sp.tier_name 
    WHERE c.id = ? AND c.deleted_at IS NULL
");
$stmt->execute([$clinic_id]);
$clinic = $stmt->fetch();

if (!$clinic) {
    die("Clinic not found.");
}

// If already active, no need to checkout again
if ($clinic['status'] === 'active') {
    redirect('clinic/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process Dummy Payment
    $card_name = $_POST['card_name'] ?? '';
    $card_number = $_POST['card_number'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvc = $_POST['cvc'] ?? '';

    if (empty($card_name) || empty($card_number) || empty($expiry) || empty($cvc)) {
        $error = "Please fill in all payment details.";
    } else {
        // Update status to active
        $updateStmt = $db->prepare("UPDATE clinics SET status = 'active' WHERE id = ?");
        $updateStmt->execute([$clinic_id]);

        $success = "Payment successful! Your clinic website is now live.";
        
        // Redirect after short delay so they see the success message
        echo "<script>setTimeout(() => { window.location.href = 'clinic/index.php'; }, 2000);</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | MedOS Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 text-slate-800">

    <div class="max-w-4xl w-full grid md:grid-cols-2 gap-8 bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
        
        <!-- Left: Order Summary -->
        <div class="bg-teal-700 p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-teal-600 rounded-full blur-3xl opacity-50"></div>
            <div class="relative z-10">
                <h2 class="text-2xl font-black mb-2 tracking-tight">Order Summary</h2>
                <p class="text-teal-100 text-sm mb-10">You are about to publish your clinic website to the world.</p>

                <div class="bg-teal-800/50 p-6 rounded-2xl border border-teal-600/50 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-teal-100 uppercase tracking-wider text-xs">Plan</span>
                        <span class="font-black text-white capitalize"><?php echo e($clinic['subscription_tier']); ?> Tier</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-teal-100 uppercase tracking-wider text-xs">Total Due Today</span>
                        <span class="text-4xl font-black text-white">$<?php echo number_format($clinic['price'], 2); ?></span>
                    </div>
                </div>

                <div class="space-y-3 text-sm text-teal-100">
                    <p class="flex items-center gap-2"><span class="text-teal-400 font-bold">✓</span> Make website public</p>
                    <p class="flex items-center gap-2"><span class="text-teal-400 font-bold">✓</span> Unlock Patient Portal</p>
                    <p class="flex items-center gap-2"><span class="text-teal-400 font-bold">✓</span> <?php echo e($clinic['features']); ?></p>
                </div>
            </div>
            <div class="relative z-10 mt-12 text-teal-300 text-xs font-medium">
                Billed securely via simulated payment gateway.
            </div>
        </div>

        <!-- Right: Payment Form -->
        <div class="p-10 relative">
            <h2 class="text-2xl font-black text-slate-900 mb-8">Payment Details</h2>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold">
                    <?php echo e($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 p-6 rounded-xl mb-6 text-center shadow-sm">
                    <div class="text-3xl mb-2">🎉</div>
                    <div class="font-bold text-lg mb-1">Payment Successful!</div>
                    <div class="text-sm font-medium">Redirecting you to your dashboard...</div>
                </div>
            <?php else: ?>
                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Cardholder Name</label>
                        <input type="text" name="card_name" placeholder="John Doe" class="w-full border border-slate-200 bg-slate-50 p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 transition">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Card Number</label>
                        <div class="relative">
                            <input type="text" name="card_number" placeholder="4242 4242 4242 4242" class="w-full border border-slate-200 bg-slate-50 p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 transition tracking-widest font-mono">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex gap-1">
                                <div class="w-8 h-5 bg-slate-200 rounded"></div>
                                <div class="w-8 h-5 bg-slate-200 rounded"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Expiry Date</label>
                            <input type="text" name="expiry" placeholder="MM/YY" class="w-full border border-slate-200 bg-slate-50 p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 transition text-center tracking-widest font-mono">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">CVC</label>
                            <input type="text" name="cvc" placeholder="123" class="w-full border border-slate-200 bg-slate-50 p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-600/20 focus:border-teal-600 transition text-center tracking-widest font-mono">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-black text-lg py-5 rounded-xl shadow-xl shadow-slate-900/20 transition-all mt-4 transform hover:scale-[1.02]">
                        Pay $<?php echo number_format($clinic['price'], 2); ?> & Publish
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-4">You can use any dummy data for testing.</p>
                </form>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
