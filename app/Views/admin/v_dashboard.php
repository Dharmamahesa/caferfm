<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 animate-fade-in-up">
    <h1 class="text-2xl md:text-3xl font-extrabold text-[#100F3E] mb-1 tracking-tight">Selamat Datang, <?= esc(session()->get('nama_admin')) ?>! 👋</h1>
    <p class="text-[#777] font-semibold text-sm">Berikut adalah ringkasan performa Kafe hari ini.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
    <!-- Card Pendapatan -->
    <div class="bg-[#58CC02] p-6 md:p-8 rounded-2xl shadow-[0_4px_0_#4BB200] text-white relative overflow-hidden group hover:-translate-y-1 transition-all duration-200">
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <p class="text-[11px] font-extrabold text-white/70 uppercase tracking-[2px] mb-2">Pendapatan Lunas Hari Ini</p>
                <h3 class="text-3xl md:text-4xl font-black tracking-tighter">Rp <?= number_format($omzet, 0, ',', '.') ?></h3>
                <p class="text-[10px] text-white/60 mt-2 italic">*Hanya pesanan berstatus selesai</p>
            </div>
            <div class="w-14 h-14 md:w-16 md:h-16 bg-white/15 rounded-2xl flex items-center justify-center text-3xl group-hover:rotate-12 transition-transform border-2 border-white/20">💰</div>
        </div>
    </div>
    
    <!-- Card Pesanan -->
    <div class="bg-[#1CB0F6] p-6 md:p-8 rounded-2xl shadow-[0_4px_0_#1899D6] text-white relative overflow-hidden group hover:-translate-y-1 transition-all duration-200">
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <p class="text-[11px] font-extrabold text-white/70 uppercase tracking-[2px] mb-2">Volume Pesanan Hari Ini</p>
                <h3 class="text-3xl md:text-4xl font-black tracking-tighter"><?= $total_pesanan ?> <span class="text-xl text-white/70 font-bold">Struk</span></h3>
                <p class="text-[10px] text-white/60 mt-2 italic">*Mencakup antrean kasir & dapur</p>
            </div>
            <div class="w-14 h-14 md:w-16 md:h-16 bg-white/15 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform border-2 border-white/20">🧾</div>
        </div>
    </div>
</div>

<!-- Analitik: Chart Penjualan & Menu Terlaris -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Chart Penjualan 7 Hari -->
    <div class="bg-white rounded-2xl border-2 border-[#E5E5E5] p-6 lg:col-span-2 animate-fade-in-up">
        <h3 class="font-extrabold text-[#100F3E] uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
            <span class="text-xl">📈</span> Tren Penjualan (7 Hari Terakhir)
        </h3>
        <div class="w-full h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Menu Terlaris -->
    <div class="bg-white rounded-2xl border-2 border-[#E5E5E5] p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <h3 class="font-extrabold text-[#100F3E] uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
            <span class="text-xl">🔥</span> Top 5 Menu Terlaris
        </h3>
        <div class="space-y-3">
            <?php if(empty($menu_terlaris)): ?>
                <div class="text-center py-8 text-[#AFAFAF]">
                    <div class="text-3xl mb-2">🍽️</div>
                    <p class="font-bold text-sm">Belum ada data penjualan.</p>
                </div>
            <?php else: ?>
                <?php foreach($menu_terlaris as $idx => $mt): ?>
                    <div class="flex items-center gap-3 bg-[#f7f7f7] p-2.5 rounded-xl border-2 border-[#E5E5E5] hover:border-[#58CC02]/30 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-[#58CC02]/10 text-[#58CC02] flex items-center justify-center font-black flex-shrink-0">
                            #<?= $idx + 1 ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-[#4B4B4B] text-sm truncate"><?= esc($mt['nama_item']) ?></p>
                            <p class="text-[10px] text-[#AFAFAF] font-bold uppercase"><?= esc($mt['kategori']) ?></p>
                        </div>
                        <div class="bg-white px-3 py-1.5 rounded-xl border-2 border-[#E5E5E5] flex flex-col items-center flex-shrink-0">
                            <span class="text-xs font-black text-[#58CC02] leading-none"><?= esc($mt['total_terjual']) ?></span>
                            <span class="text-[8px] font-bold text-[#AFAFAF] uppercase mt-0.5">Terjual</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Aktivitas Terakhir: Desktop Table -->
<div class="bg-white rounded-2xl border-2 border-[#E5E5E5] overflow-hidden mb-6 hidden md:block animate-fade-in-up">
    <div class="px-6 py-5 border-b-2 border-[#E5E5E5] flex justify-between items-center bg-[#f7f7f7]">
        <div>
            <h3 class="font-extrabold text-[#100F3E] uppercase tracking-wider text-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#58CC02] animate-pulse"></span>
                Aktivitas Terakhir
            </h3>
            <p class="text-xs text-[#AFAFAF] mt-1 font-semibold">5 transaksi terbaru yang masuk ke sistem.</p>
        </div>
        <a href="<?= base_url('admin/riwayat') ?>" class="text-xs font-bold text-[#1CB0F6] bg-[#1CB0F6]/10 hover:bg-[#1CB0F6] hover:text-white px-4 py-2 rounded-xl transition-colors group flex items-center gap-1.5">
            Lihat Semua 
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-[#AFAFAF] text-[11px] uppercase font-extrabold tracking-[1.5px] border-b-2 border-[#E5E5E5]">
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
                            <div class="w-14 h-14 bg-[#f7f7f7] rounded-full flex items-center justify-center mx-auto mb-3 text-2xl border-2 border-[#E5E5E5]">💤</div>
                            <p class="text-[#AFAFAF] font-bold">Belum ada aktivitas pesanan hari ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($pesanan_baru as $p): ?>
                    <tr class="border-b border-[#E5E5E5] hover:bg-[#58CC02]/3 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="text-[#777] font-mono bg-[#f7f7f7] px-3 py-1 rounded-lg text-xs font-bold border-2 border-[#E5E5E5]">
                                <?= date('H:i', strtotime($p['tgl_pesanan'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#4B4B4B]"><?= esc($p['nama_pelanggan']) ?></td>
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-[#1CB0F6] bg-[#1CB0F6]/10 px-3 py-1 rounded-lg text-xs border border-[#1CB0F6]/20">
                                Meja #<?= esc($p['no_meja']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#100F3E]">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4">
                            <?php if($p['status_pesanan'] == 'selesai'): ?>
                                <span class="bg-[#58CC02]/10 text-[#58CC02] px-3 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider inline-flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-[#58CC02]"></div> Selesai
                                </span>
                            <?php elseif($p['status_pesanan'] == 'pending'): ?>
                                <span class="bg-[#1CB0F6]/10 text-[#1CB0F6] px-3 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider inline-flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-[#1CB0F6] animate-pulse"></div> Dimasak
                                </span>
                            <?php else: ?>
                                <span class="bg-[#FF9600]/10 text-[#FF9600] px-3 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider inline-flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-[#FF9600]"></div> Belum Bayar
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
        <h3 class="font-extrabold text-[#100F3E] text-sm flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#58CC02] animate-pulse"></span> Aktivitas Terakhir
        </h3>
        <a href="<?= base_url('admin/riwayat') ?>" class="text-xs font-bold text-[#1CB0F6] bg-[#1CB0F6]/10 px-3 py-1.5 rounded-lg">Lihat Semua →</a>
    </div>
    <?php if(empty($pesanan_baru)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border-2 border-[#E5E5E5]">
            <span class="text-3xl block mb-2">💤</span>
            <p class="text-[#AFAFAF] font-bold text-sm">Belum ada aktivitas hari ini.</p>
        </div>
    <?php else: ?>
        <?php foreach($pesanan_baru as $p): ?>
        <div class="bg-white rounded-2xl border-2 border-[#E5E5E5] p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="font-mono text-[#777] bg-[#f7f7f7] px-2 py-1.5 rounded-lg text-xs font-bold flex-shrink-0 border border-[#E5E5E5]"><?= date('H:i', strtotime($p['tgl_pesanan'])) ?></div>
                <div>
                    <p class="font-bold text-[#4B4B4B] text-sm"><?= esc($p['nama_pelanggan']) ?></p>
                    <p class="text-[10px] text-[#AFAFAF]">Meja #<?= esc($p['no_meja']) ?> · Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></p>
                </div>
            </div>
            <?php if($p['status_pesanan'] == 'selesai'): ?>
                <span class="bg-[#58CC02]/10 text-[#58CC02] px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase flex-shrink-0">Selesai</span>
            <?php elseif($p['status_pesanan'] == 'pending'): ?>
                <span class="bg-[#1CB0F6]/10 text-[#1CB0F6] px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase flex-shrink-0">Dimasak</span>
            <?php else: ?>
                <span class="bg-[#FF9600]/10 text-[#FF9600] px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase flex-shrink-0">Belum Bayar</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if(!ctx) return;
        
        const namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        
        // Data dari PHP (monthly)
        const rawData = <?= json_encode($grafik_penjualan ?? []) ?>;
        
        // Build 12-month labels and data
        const now = new Date();
        const labels = [];
        const data = [];
        
        for(let i = 11; i >= 0; i--) {
            const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
            const key = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0');
            labels.push(namaBulan[d.getMonth()] + ' ' + d.getFullYear());
            
            const found = rawData.find(r => r.bulan === key);
            data.push(found ? parseInt(found.total) : 0);
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx: c, chartArea} = chart;
                        if(!chartArea) return '#58CC02';
                        const gradient = c.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, 'rgba(88, 204, 2, 0.6)');
                        gradient.addColorStop(1, 'rgba(88, 204, 2, 1)');
                        return gradient;
                    },
                    borderColor: '#4BB200',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                    hoverBackgroundColor: '#58CC02',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#100F3E',
                        padding: 12,
                        cornerRadius: 10,
                        titleFont: { size: 12, family: "'Nunito', sans-serif", weight: '700' },
                        bodyFont: { size: 14, weight: 'bold', family: "'Nunito', sans-serif" },
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
                        grid: { color: '#f0f0f0', drawBorder: false },
                        ticks: {
                            font: { family: "'Nunito', sans-serif", size: 10 },
                            color: '#AFAFAF',
                            callback: function(value) {
                                if(value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'M';
                                if(value >= 1000) return 'Rp ' + (value/1000) + 'k';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { 
                            font: { family: "'Nunito', sans-serif", size: 9 }, 
                            color: '#AFAFAF',
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    });
</script>

<?= $this->endSection() ?>