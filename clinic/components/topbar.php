<!-- clinic/components/topbar.php -->
<header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-40">
    <!-- Search Bar -->
    <div class="flex-1 max-w-md">
        <div class="relative group">
            <span class="material-icons-round absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors">search</span>
            <input type="text" placeholder="Search for patients, records..." class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-6">
        <!-- Icons -->
        <div class="flex items-center gap-2 border-r border-slate-200 pr-6">
            <button class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-50 rounded-xl transition-colors relative">
                <span class="material-icons-round">mail</span>
            </button>
            <button class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-50 rounded-xl transition-colors relative">
                <span class="material-icons-round">notifications</span>
                <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 border-2 border-white rounded-full"></span>
            </button>
        </div>

        <!-- User Profile -->
        <div class="flex items-center gap-3 pl-2">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-slate-900 leading-tight"><?php echo e($_SESSION['user_name']); ?></p>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Clinic Admin</p>
            </div>
            <div class="w-10 h-10 bg-slate-200 rounded-xl overflow-hidden border border-slate-200">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name']); ?>&background=random" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</header>
