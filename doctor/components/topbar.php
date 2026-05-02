<!-- clinic/components/topbar.php -->
<header class="h-20 bg-white border-b border-slate-100 px-8 flex items-center justify-between sticky top-0 z-40">
    <!-- Search Bar -->
    <div class="flex-1 max-w-md">
        <div class="relative group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 transition-colors group-focus-within:text-blue-600"></i>
            <input type="text" placeholder="Search for patients, records..." class="w-full bg-slate-50 border border-slate-100 rounded-xl py-2 pl-12 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500/20 transition-all">
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-6">
        <!-- Icons -->
        <div class="flex items-center gap-2 border-r border-slate-100 pr-6">
            <div class="flex items-center gap-2">
                <a href="#" class="p-2 text-slate-400 hover:text-blue-600 transition-colors relative">
                    <i data-lucide="mail" class="w-5 h-5"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-blue-600 rounded-full border-2 border-white"></span>
                </a>
                <a href="#" class="p-2 text-slate-400 hover:text-blue-600 transition-colors relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </a>
            </div>
        </div>

        <!-- User Profile -->
        <div class="flex items-center gap-3 pl-2">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-slate-900 leading-tight"><?php echo e($_SESSION['user_name']); ?></p>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Administrator</p>
            </div>
            <div class="w-10 h-10 bg-slate-200 rounded-lg overflow-hidden border border-slate-200">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name']); ?>&background=random" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</header>
