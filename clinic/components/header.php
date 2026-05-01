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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: { 
                        primary: '<?php echo $clinic['primary_color'] ?? '#0f766e'; ?>',
                        sidebar: '#1e293b',
                        accent: '#3b82f6'
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Hard-Lock Layout to prevent Jitter */
        body { margin: 0; padding: 0; overflow-x: hidden; }
        aside { width: 18rem !important; flex-shrink: 0; }
        main { flex: 1; min-width: 0; }
        .flex.min-h-screen { display: flex; align-items: stretch; }

        /* Smooth Transitions */
        .no-flicker { backface-visibility: hidden; transform: translateZ(0); }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 font-sans antialiased selection:bg-primary selection:text-white">

<!-- Global Page Loader -->
<div id="global-loader" class="fixed inset-0 z-[9999] bg-[#f8fafc] flex items-center justify-center transition-opacity duration-300">
    <div class="w-10 h-10 border-4 border-slate-200 border-t-primary rounded-full animate-spin"></div>
</div>

<script>
    // Hide loader when page is fully loaded
    window.addEventListener('load', function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => { loader.style.display = 'none'; }, 300);
        }
    });

    // Show loader when clicking navigation links to smooth out transitions
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"])');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                // Ignore modifier clicks (new tab, etc)
                if (e.ctrlKey || e.shiftKey || e.metaKey || e.button === 1) return;
                
                const loader = document.getElementById('global-loader');
                if (loader) {
                    loader.style.display = 'flex';
                    // Force reflow for transition
                    void loader.offsetWidth;
                    loader.style.opacity = '1';
                }
            });
        });
    });
</script>

<div class="flex min-h-screen">
