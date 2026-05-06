<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-2xl md:text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-blue-100 text-blue-600 p-2 rounded-xl shadow-inner">🗺️</span> 
            Visual Map Meja
        </h1>
        <p class="text-gray-500 font-medium mt-1 text-sm">Denah interaktif status ketersediaan meja secara real-time.</p>
    </div>
    <div class="flex items-center gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100 self-start sm:self-auto">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded-full bg-red-500 shadow-inner"></div>
            <span class="text-xs font-bold text-gray-600">Terisi</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded-full bg-green-500 shadow-inner"></div>
            <span class="text-xs font-bold text-gray-600">Kosong</span>
        </div>
    </div>
</div>

<div class="bg-gray-100/50 p-6 md:p-10 rounded-[3rem] shadow-inner border border-gray-200/50 relative overflow-hidden animate-fade-in-up">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-64 h-8 bg-gray-200 rounded-b-3xl shadow-sm flex items-center justify-center">
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Pintu Masuk</span>
    </div>
    <div class="absolute bottom-10 right-10 w-32 h-32 bg-orange-100 rounded-3xl border-4 border-white shadow-sm flex items-center justify-center rotate-12">
        <span class="text-2xl">☕ Bar</span>
    </div>

    <!-- Table Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-8 relative z-10">
        <?php for($i = 1; $i <= $totalTables; $i++): ?>
            <?php 
                $isOccupied = in_array($i, $activeTables);
                $bgColor = $isOccupied ? 'bg-gradient-to-br from-red-500 to-rose-600' : 'bg-gradient-to-br from-green-500 to-emerald-500';
                $shadowColor = $isOccupied ? 'shadow-red-500/30' : 'shadow-green-500/30';
                $icon = $isOccupied ? '🍽️' : '✨';
            ?>
            <div class="relative group cursor-pointer transform hover:-translate-y-2 transition-all duration-300">
                <div class="<?= $bgColor ?> w-full aspect-square rounded-[2rem] flex flex-col items-center justify-center shadow-xl <?= $shadowColor ?> relative overflow-hidden border-4 border-white">
                    <div class="absolute inset-0 bg-white/10 group-hover:scale-150 transition-transform duration-500 rounded-full blur-2xl"></div>
                    <span class="text-4xl relative z-10 mb-2 drop-shadow-md"><?= $icon ?></span>
                    <span class="text-white font-black text-2xl relative z-10 drop-shadow-md"><?= $i ?></span>
                </div>
                
                <!-- Tooltip Hover -->
                <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg whitespace-nowrap pointer-events-none z-20">
                    Meja <?= $i ?>: <?= $isOccupied ? 'Terisi / Belum Lunas' : 'Tersedia' ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?= $this->endSection() ?>
