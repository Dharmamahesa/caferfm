<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 animate-fade-in-up">
    <h1 class="text-2xl md:text-3xl font-black text-gray-800 mb-1 tracking-tight">Selamat Datang, <?= esc(session()->get('nama_admin')) ?>! 👋</h1>
    <p class="text-gray-500 font-medium text-sm">Berikut adalah ringkasan performa Kafe hari ini.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
    <!-- Card Pendapatan -->
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 md:p-8 rounded-3xl shadow-xl shadow-green-500/20 text-white relative overflow-hidden group transform hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-green-100 uppercase tracking-widest mb-2 opacity-90">Pendapatan Lunas Hari Ini</p>
                <h3 class="text-3xl md:text-4xl font-black tracking-tighter drop-shadow-md">Rp <?= number_format($omzet, 0, ',', '.') ?></h3>
                <p class="text-[10px] text-green-100 mt-2 italic opacity-80">*Hanya pesanan berstatus selesai</p>
            </div>
            <div class="w-14 h-14 md:w-16 md:h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl group-hover:rotate-12 transition-transform shadow-inner border border-white/30">💰</div>
        </div>
    </div>
    
    <!-- Card Pesanan -->
    <div class="bg-gradient-to-br from-orange-500 to-red-500 p-6 md:p-8 rounded-3xl shadow-xl shadow-orange-500/20 text-white relative overflow-hidden group transform hover:-translate-y-1 transition-all duration-300">
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-orange-100 uppercase tracking-widest mb-2 opacity-90">Volume Pesanan Hari Ini</p>
                <h3 class="text-3xl md:text-4xl font-black tracking-tighter drop-shadow-md"><?= $total_pesanan ?> <span class="text-xl text-orange-200 font-bold">Struk</span></h3>
                <p class="text-[10px] text-orange-100 mt-2 italic opacity-80">*Mencakup antrean kasir & dapur</p>
            </div>
            <div class="w-14 h-14 md:w-16 md:h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-inner border border-white/30">🧾</div>
        </div>
    </div>
</div>

<!-- Analitik: Chart Penjualan & Menu Terlaris -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Chart Penjualan 7 Hari -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 lg:col-span-2 animate-fade-in-up">
        <h3 class="font-black text-gray-800 uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
            <span class="text-xl">📈</span> Tren Penjualan (7 Hari Terakhir)
        </h3>
        <div class="w-full h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Menu Terlaris -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <h3 class="font-black text-gray-800 uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
            <span class="text-xl">🔥</span> Top 5 Menu Terlaris
        </h3>
        <div class="space-y-4">
            <?php if(empty($menu_terlaris)): ?>
                <div class="text-center py-8 text-gray-400">
                    <div class="text-3xl mb-2">🍽️</div>
                    <p class="font-bold text-sm">Belum ada data penjualan.</p>
                </div>
            <?php else: ?>
                <?php foreach($menu_terlaris as $idx => $mt): ?>
                    <div class="flex items-center gap-3 bg-gray-50/50 p-2.5 rounded-2xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black flex-shrink-0">
                            #<?= $idx + 1 ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-800 text-sm truncate"><?= esc($mt['nama_item']) ?></p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase"><?= esc($mt['kategori']) ?></p>
                        </div>
                        <div class="bg-white px-3 py-1.5 rounded-xl border border-gray-100 shadow-sm flex flex-col items-center flex-shrink-0">
                            <span class="text-xs font-black text-green-600 leading-none"><?= esc($mt['total_terjual']) ?></span>
                            <span class="text-[8px] font-bold text-gray-400 uppercase mt-0.5">Terjual</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Aktivitas Terakhir: Desktop Table -->
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-6 hidden md:block animate-fade-in-up">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="font-black text-gray-800 uppercase tracking-wider text-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                Aktivitas Terakhir
            </h3>
            <p class="text-xs text-gray-500 mt-1 font-medium">5 transaksi terbaru yang masuk ke sistem.</p>
        </div>
        <a href="<?= base_url('admin/riwayat') ?>" class="text-xs font-bold text-orange-600 hover:text-white bg-orange-50 hover:bg-orange-600 px-4 py-2 rounded-xl transition-colors shadow-sm group flex items-center gap-1.5">
            Lihat Semua 
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-gray-400 text-[10px] uppercase font-black tracking-widest border-b">
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Meja</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($pesanan_baru)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center">
                            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">💤</div>
                            <p class="text-gray-400 font-bold">Belum ada aktivitas pesanan hari ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($pesanan_baru as $p): ?>
                    <tr class="border-b border-gray-50 hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="text-gray-500 font-mono bg-gray-100 px-3 py-1 rounded-lg text-xs font-bold group-hover:bg-white border border-transparent group-hover:border-gray-200 transition-colors">
                                <?= date('H:i', strtotime($p['tgl_pesanan'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800"><?= esc($p['nama_pelanggan']) ?></td>
                        <td class="px-6 py-4">
                            <span class="font-black text-orange-500 bg-orange-50 px-3 py-1 rounded-lg text-xs border border-orange-100 shadow-sm">
                                Meja #<?= esc($p['no_meja']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4">
                            <?php if($p['status_pesanan'] == 'selesai'): ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border border-green-200 shadow-sm inline-flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Selesai
                                </span>
                            <?php elseif($p['status_pesanan'] == 'pending'): ?>
                                <span class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border border-blue-200 shadow-sm inline-flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div> Dimasak
                                </span>
                            <?php else: ?>
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border border-yellow-200 shadow-sm inline-flex items-center gap-1.5">
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

<!-- Aktivitas Terakhir: Mobile Card View -->
<div class="space-y-3 md:hidden animate-fade-in-up">
    <div class="flex justify-between items-center mb-3">
        <h3 class="font-black text-gray-800 text-sm flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span> Aktivitas Terakhir
        </h3>
        <a href="<?= base_url('admin/riwayat') ?>" class="text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg">Lihat Semua →</a>
    </div>
    <?php if(empty($pesanan_baru)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm">
            <span class="text-3xl block mb-2">💤</span>
            <p class="text-gray-400 font-bold text-sm">Belum ada aktivitas hari ini.</p>
        </div>
    <?php else: ?>
        <?php foreach($pesanan_baru as $p): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="font-mono text-gray-400 bg-gray-100 px-2 py-1.5 rounded-lg text-xs font-bold flex-shrink-0"><?= date('H:i', strtotime($p['tgl_pesanan'])) ?></div>
                <div>
                    <p class="font-bold text-gray-800 text-sm"><?= esc($p['nama_pelanggan']) ?></p>
                    <p class="text-[10px] text-gray-400">Meja #<?= esc($p['no_meja']) ?> · Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></p>
                </div>
            </div>
            <?php if($p['status_pesanan'] == 'selesai'): ?>
                <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase flex-shrink-0">Selesai</span>
            <?php elseif($p['status_pesanan'] == 'pending'): ?>
                <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase flex-shrink-0">Dimasak</span>
            <?php else: ?>
                <span class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase flex-shrink-0">Belum Bayar</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
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
</style>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if(!ctx) return;
        
        // Data dari PHP
        const rawData = <?= json_encode($grafik_penjualan ?? []) ?>;
        
        // Format label tanggal
        const labels = rawData.map(item => {
            const date = new Date(item.tanggal);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        
        // Data nominal
        const data = rawData.map(item => parseInt(item.total));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#f97316', // orange-500
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f97316',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6', drawBorder: false },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 10 },
                            color: '#9ca3af',
                            callback: function(value) {
                                if(value >= 1000000) return 'Rp ' + (value/1000000) + 'M';
                                if(value >= 1000) return 'Rp ' + (value/1000) + 'k';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: "'Inter', sans-serif", size: 11 }, color: '#9ca3af' }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    });
</script>

<?= $this->endSection() ?>