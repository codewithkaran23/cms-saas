<?php
// clinic/components/header.php
if (!isset($page_title)) {
    $page_title = $clinic['name'] . ' | Admin Panel';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '<?php echo $clinic['primary_color'] ?? '#0f766e'; ?>' }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f8fafc] text-slate-800 font-sans antialiased selection:bg-primary selection:text-white">

<div class="flex min-h-screen">
