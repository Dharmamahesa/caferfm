<?= $this->extend('pelanggan/layout_pelanggan') ?>

<?= $this->section('content') ?>

<div class="bg-white/80 backdrop-blur-xl p-5 flex items-center justify-between shadow-sm sticky top-0 z-40 border-b border-gray-100">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('/') ?>" class="text-gray-600 font-black text-xl bg-gray-100 w-11 h-11 flex items-center justify-center rounded-2xl hover:bg-orange-50 hover:text-orange-600 transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-800">Profil Saya</h1>
            <p class="text-[10px] uppercase font-bold tracking-widest text-gray-400">Area Member Kafe</p>
        </div>
    </div>
    <a href="<?= base_url('auth/logout') ?>" class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors shadow-sm border border-red-100" title="Keluar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
    </a>
</div>

<div class="max-w-md mx-auto p-5 space-y-6 mt-2 animate-fade-in-up">
    
    <!-- Member Card -->
    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-black rounded-[2rem] p-7 text-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)] relative overflow-hidden group">
        <!-- Abstract Shapes -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-orange-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        
        <div class="relative z-10 flex items-center gap-4 mb-8 border-b border-white/10 pb-6">
            <div class="w-14 h-14 bg-gradient-to-r from-orange-400 to-red-500 rounded-2xl flex items-center justify-center text-2xl font-black shadow-inner border border-white/20">
                <?= substr(esc($user['nama_pelanggan']), 0, 1) ?>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Welcome Back,</p>
                <h2 class="text-xl font-black tracking-tight drop-shadow-md truncate max-w-[200px]"><?= esc($user['nama_pelanggan']) ?></h2>
            </div>
        </div>

        <div class="relative z-10 flex justify-between items-end">
            <div>
                <p class="text-gray-400 text-[10px] uppercase tracking-widest font-black mb-1">Total Poin Loyalitas</p>
                <p class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-300 drop-shadow-sm tracking-tighter"><?= esc($user['poin_loyalitas']) ?> <span class="text-lg text-gray-500 font-bold tracking-normal">pts</span></p>
            </div>
            <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/20 shadow-inner">
                <p class="text-xs font-black text-yellow-400 uppercase tracking-widest drop-shadow-md flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd" /></svg>
                    <?= esc($tier) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Progress Tier -->
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
        <!-- Deco -->
        <div class="absolute right-0 top-0 w-24 h-24 bg-gray-50 rounded-bl-[100px] -z-10 group-hover:scale-110 transition-transform"></div>
        
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-black text-gray-800 text-sm tracking-tight flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                Progress ke <?= esc($nextTier) ?>
            </h3>
            <p class="text-sm font-black text-orange-600 bg-orange-50 px-3 py-1 rounded-lg border border-orange-100 shadow-sm"><?= esc($user['poin_loyalitas']) ?> / <?= esc($poinNextTier) ?></p>
        </div>
        
        <div class="w-full bg-slate-100 rounded-full h-4 mb-5 overflow-hidden shadow-inner border border-gray-200">
            <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-full rounded-full transition-all duration-1000 ease-out relative shadow-[0_0_10px_rgba(234,88,12,0.5)]" style="width: <?= $progress ?>%">
                <div class="absolute top-0 left-0 right-0 bottom-0 bg-white opacity-20 w-full h-1/2 rounded-t-full"></div>
                <div class="absolute right-1 top-0.5 w-3 h-3 bg-white/50 rounded-full blur-[2px]"></div>
            </div>
        </div>
        
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <p class="text-xs text-gray-500 font-semibold leading-relaxed">
                Kumpulkan <span class="font-black text-gray-800 bg-white px-2 py-0.5 rounded shadow-sm border border-gray-100"><?= $poinNextTier - $user['poin_loyalitas'] ?> poin lagi</span> untuk naik level dan buka benefit & reward eksklusif!
            </p>
        </div>
    </div>

    <!-- Quick Links / Menus -->
    <div class="grid grid-cols-2 gap-4">
        <a href="<?= base_url('pesanan_saya') ?>" class="bg-white p-5 rounded-[1.5rem] border border-gray-100 shadow-sm hover:shadow-md hover:border-orange-200 transition-all group flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">🧾</div>
            <h4 class="font-black text-gray-800 text-sm">Riwayat Pesanan</h4>
            <p class="text-[10px] text-gray-400 font-medium mt-1">Lacak status pesanan</p>
        </a>
        <a href="<?= base_url('misi_saya') ?>" class="bg-white p-5 rounded-[1.5rem] border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">🎯</div>
            <h4 class="font-black text-gray-800 text-sm">Misi & Tantangan</h4>
            <p class="text-[10px] text-gray-400 font-medium mt-1">Selesaikan & dapat poin</p>
        </a>
    </div>

    <!-- CTA -->
    <a href="<?= base_url('/') ?>" class="block w-full bg-gradient-to-r from-orange-50 to-orange-100 text-orange-600 font-black text-center py-4 rounded-2xl border border-orange-200 hover:bg-gradient-to-r hover:from-orange-500 hover:to-orange-600 hover:text-white transition-all active:scale-95 shadow-sm group relative overflow-hidden">
        <span class="relative z-10 flex items-center justify-center gap-2">
            Mulai Pesan Menu
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </span>
    </a>

</div>

<?= $this->endSection() ?>