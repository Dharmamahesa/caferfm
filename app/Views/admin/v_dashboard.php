<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 animate-fade-in-up">
    <h1 class="text-3xl font-black text-gray-800 mb-2 tracking-tight">Selamat Datang, <?= esc(session()->get('nama_admin')) ?>! 👋</h1>
    <p class="text-gray-500 font-medium">Berikut adalah ringkasan performa Kafe hari ini.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <!-- Card Pendapatan -->
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-8 rounded-3xl shadow-xl shadow-green-500/20 text-white relative overflow-hidden group transform hover:-translate-y-1 transition-all duration-300">
        <!-- Abstract shape -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-green-100 uppercase tracking-widest mb-2 opacity-90">Pendapatan Lunas Hari Ini</p>
                <h3 class="text-4xl font-black tracking-tighter drop-shadow-md">Rp <?= number_format($omzet, 0, ',', '.') ?></h3>
                <p class="text-[10px] text-green-100 mt-2 italic opacity-80">*Hanya pesanan berstatus selesai</p>
            </div>
            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl group-hover:rotate-12 transition-transform shadow-inner border border-white/30">💰</div>
        </div>
    </div>
    
    <!-- Card Pesanan -->
    <div class="bg-gradient-to-br from-orange-500 to-red-500 p-8 rounded-3xl shadow-xl shadow-orange-500/20 text-white relative overflow-hidden group transform hover:-translate-y-1 transition-all duration-300">
        <!-- Abstract shape -->
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-orange-100 uppercase tracking-widest mb-2 opacity-90">Volume Pesanan Hari Ini</p>
                <h3 class="text-4xl font-black tracking-tighter drop-shadow-md"><?= $total_pesanan ?> <span class="text-xl text-orange-200 font-bold">Struk</span></h3>
                <p class="text-[10px] text-orange-100 mt-2 italic opacity-80">*Mencakup antrean kasir & dapur</p>
            </div>
            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-inner border border-white/30">🧾</div>
        </div>
    </div>
</div>

<!-- Tabel Aktivitas Terakhir -->
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="font-black text-gray-800 uppercase tracking-wider text-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                Aktivitas Terakhir
            </h3>
            <p class="text-xs text-gray-500 mt-1 font-medium">5 transaksi terbaru yang masuk ke sistem.</p>
        </div>
        <a href="<?= base_url('admin/riwayat') ?>" class="text-xs font-bold text-orange-600 hover:text-white bg-orange-50 hover:bg-orange-600 px-5 py-2.5 rounded-xl transition-colors shadow-sm group flex items-center gap-2">
            Lihat Semua 
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-gray-400 text-[10px] uppercase font-black tracking-widest border-b">
                    <th class="px-8 py-5">Waktu</th>
                    <th class="px-8 py-5">Pelanggan</th>
                    <th class="px-8 py-5">Meja</th>
                    <th class="px-8 py-5">Total</th>
                    <th class="px-8 py-5">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($pesanan_baru)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">💤</div>
                            <p class="text-gray-400 font-bold">Belum ada aktivitas pesanan hari ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($pesanan_baru as $p): ?>
                    <tr class="border-b border-gray-50 hover:bg-slate-50 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-gray-500 font-mono bg-gray-100 px-3 py-1 rounded-lg text-xs font-bold group-hover:bg-white border border-transparent group-hover:border-gray-200 transition-colors">
                                <?= date('H:i', strtotime($p['tgl_pesanan'])) ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 font-bold text-gray-800"><?= esc($p['nama_pelanggan']) ?></td>
                        <td class="px-8 py-5">
                            <span class="font-black text-orange-500 bg-orange-50 px-3 py-1 rounded-lg text-xs border border-orange-100 shadow-sm">
                                Meja #<?= esc($p['no_meja']) ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 font-bold text-gray-900">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></td>
                        <td class="px-8 py-5">
                            <?php if($p['status_pesanan'] == 'selesai'): ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border border-green-200 shadow-sm flex inline-flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Selesai
                                </span>
                            <?php elseif($p['status_pesanan'] == 'pending'): ?>
                                <span class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border border-blue-200 shadow-sm flex inline-flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div> Dimasak
                                </span>
                            <?php else: ?>
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border border-yellow-200 shadow-sm flex inline-flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-yellow-500"></div> Belum Bayar
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?= $this->endSection() ?>