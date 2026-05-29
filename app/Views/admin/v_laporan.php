<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<?php
    $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $summary = $laporan['summary'] ?? ['total_pendapatan' => 0, 'jumlah_transaksi' => 0, 'jumlah_refund' => 0];
    $rataRata = ($summary['jumlah_transaksi'] > 0) ? $summary['total_pendapatan'] / $summary['jumlah_transaksi'] : 0;
    
    // Title berdasarkan mode
    if($mode == 'harian') {
        $judulPeriode = date('l, d F Y', strtotime($tanggal));
    } elseif($mode == 'bulanan') {
        $judulPeriode = $namaBulan[(int)$bulan] . ' ' . $tahun;
    } else {
        $judulPeriode = 'Tahun ' . $tahun;
    }
?>

<!-- Header -->
<div class="mb-6 animate-fade-in-up">
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#100F3E] tracking-tight flex items-center gap-3">
                <span class="bg-[#58CC02]/10 text-[#58CC02] p-2.5 rounded-xl shadow-inner border border-[#58CC02]/20">📈</span>
                Laporan Penjualan
            </h1>
            <p class="text-[#777] font-semibold text-sm mt-2">Analisis performa penjualan Toko Kopi Jaya Lestari</p>
        </div>
    </div>
</div>

<!-- Tab Switcher -->
<div class="bg-white rounded-2xl border-2 border-[#E5E5E5] p-2 mb-6 animate-fade-in-up flex gap-1">
    <a href="<?= base_url('admin/laporan?mode=harian&tanggal=' . $tanggal) ?>" 
       class="flex-1 text-center py-3 px-4 rounded-xl font-extrabold text-sm transition-all <?= $mode == 'harian' ? 'bg-[#58CC02] text-white shadow-[0_3px_0_#4BB200]' : 'text-[#777] hover:bg-[#f7f7f7]' ?>">
        📅 Harian
    </a>
    <a href="<?= base_url('admin/laporan?mode=bulanan&bulan=' . $bulan . '&tahun=' . $tahun) ?>" 
       class="flex-1 text-center py-3 px-4 rounded-xl font-extrabold text-sm transition-all <?= $mode == 'bulanan' ? 'bg-[#1CB0F6] text-white shadow-[0_3px_0_#1899D6]' : 'text-[#777] hover:bg-[#f7f7f7]' ?>">
        📆 Bulanan
    </a>
    <a href="<?= base_url('admin/laporan?mode=tahunan&tahun=' . $tahun) ?>" 
       class="flex-1 text-center py-3 px-4 rounded-xl font-extrabold text-sm transition-all <?= $mode == 'tahunan' ? 'bg-[#FF9600] text-white shadow-[0_3px_0_#E08600]' : 'text-[#777] hover:bg-[#f7f7f7]' ?>">
        📊 Tahunan
    </a>
</div>

<!-- Filter Bar -->
<div class="bg-white rounded-2xl border-2 border-[#E5E5E5] p-5 mb-6 animate-fade-in-up">
    <form action="<?= base_url('admin/laporan') ?>" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
        <input type="hidden" name="mode" value="<?= esc($mode) ?>">
        
        <?php if($mode == 'harian'): ?>
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Pilih Tanggal</label>
                <input type="date" name="tanggal" value="<?= esc($tanggal) ?>" 
                       class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border-2 border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] outline-none font-bold text-[#4B4B4B] transition-all">
            </div>
        <?php elseif($mode == 'bulanan'): ?>
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Bulan</label>
                <select name="bulan" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border-2 border-[#E5E5E5] focus:bg-white focus:border-[#1CB0F6] outline-none font-bold text-[#4B4B4B] transition-all">
                    <?php for($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $bulan ? 'selected' : '' ?>><?= $namaBulan[$i] ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Tahun</label>
                <select name="tahun" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border-2 border-[#E5E5E5] focus:bg-white focus:border-[#1CB0F6] outline-none font-bold text-[#4B4B4B] transition-all">
                    <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        <?php else: ?>
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Pilih Tahun</label>
                <select name="tahun" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border-2 border-[#E5E5E5] focus:bg-white focus:border-[#FF9600] outline-none font-bold text-[#4B4B4B] transition-all">
                    <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $tahun ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        <?php endif; ?>
        
        <button type="submit" class="w-full sm:w-auto bg-[#100F3E] text-white px-8 py-3 rounded-xl font-extrabold shadow-[0_3px_0_#0a0930] hover:shadow-none hover:translate-y-[3px] transition-all text-sm">
            Terapkan
        </button>
    </form>
</div>

<!-- Periode Label -->
<div class="mb-6 animate-fade-in-up">
    <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-[2px]">Menampilkan Laporan</p>
    <h2 class="text-xl font-black text-[#100F3E] tracking-tight"><?= $judulPeriode ?></h2>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 animate-fade-in-up">
    <!-- Total Pendapatan -->
    <div class="bg-[#58CC02] p-5 rounded-2xl shadow-[0_4px_0_#4BB200] text-white relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-[10px] font-extrabold text-white/60 uppercase tracking-widest mb-1">Total Pendapatan</p>
            <h3 class="text-xl md:text-2xl font-black tracking-tighter">Rp <?= number_format($summary['total_pendapatan'], 0, ',', '.') ?></h3>
        </div>
        <div class="absolute -bottom-2 -right-2 text-5xl opacity-10 group-hover:opacity-20 transition-opacity">💰</div>
    </div>
    
    <!-- Jumlah Transaksi -->
    <div class="bg-[#1CB0F6] p-5 rounded-2xl shadow-[0_4px_0_#1899D6] text-white relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-[10px] font-extrabold text-white/60 uppercase tracking-widest mb-1">Jumlah Transaksi</p>
            <h3 class="text-xl md:text-2xl font-black tracking-tighter"><?= number_format($summary['jumlah_transaksi']) ?></h3>
        </div>
        <div class="absolute -bottom-2 -right-2 text-5xl opacity-10 group-hover:opacity-20 transition-opacity">🧾</div>
    </div>
    
    <!-- Rata-rata -->
    <div class="bg-[#FF9600] p-5 rounded-2xl shadow-[0_4px_0_#E08600] text-white relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-[10px] font-extrabold text-white/60 uppercase tracking-widest mb-1">Rata-rata / Trx</p>
            <h3 class="text-xl md:text-2xl font-black tracking-tighter">Rp <?= number_format($rataRata, 0, ',', '.') ?></h3>
        </div>
        <div class="absolute -bottom-2 -right-2 text-5xl opacity-10 group-hover:opacity-20 transition-opacity">📊</div>
    </div>
    
    <!-- Refund -->
    <div class="bg-white border-2 border-[#E5E5E5] p-5 rounded-2xl relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-1">Refund</p>
            <h3 class="text-xl md:text-2xl font-black tracking-tighter text-[#FF4B4B]"><?= number_format($summary['jumlah_refund']) ?></h3>
        </div>
        <div class="absolute -bottom-2 -right-2 text-5xl opacity-5 group-hover:opacity-10 transition-opacity">❌</div>
    </div>
</div>

<!-- Chart + Sidebar Widgets -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Main Chart -->
    <div class="bg-white rounded-2xl border-2 border-[#E5E5E5] p-6 lg:col-span-2 animate-fade-in-up">
        <h3 class="font-extrabold text-[#100F3E] uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
            <span class="text-xl">📈</span> 
            Grafik Pendapatan 
            <?php if($mode == 'harian'): ?>(Per Jam)<?php elseif($mode == 'bulanan'): ?>(Per Hari)<?php else: ?>(Per Bulan)<?php endif; ?>
        </h3>
        <div class="w-full h-72">
            <canvas id="laporanChart"></canvas>
        </div>
    </div>

    <!-- Sidebar Widgets -->
    <div class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s">
        <!-- Metode Pembayaran -->
        <div class="bg-white rounded-2xl border-2 border-[#E5E5E5] p-6">
            <h3 class="font-extrabold text-[#100F3E] uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
                <span class="text-xl">💳</span> Metode Bayar
            </h3>
            <?php if(empty($metodeBayar)): ?>
                <div class="text-center py-6 text-[#AFAFAF]">
                    <p class="font-bold text-sm">Belum ada data.</p>
                </div>
            <?php else: ?>
                <div class="w-full h-40 mb-4">
                    <canvas id="paymentChart"></canvas>
                </div>
                <div class="space-y-2">
                    <?php 
                        $payColors = ['#58CC02','#1CB0F6','#FF9600','#FF4B4B','#CE82FF'];
                        foreach($metodeBayar as $idx => $mb): 
                        $color = $payColors[$idx % count($payColors)];
                    ?>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background: <?= $color ?>"></div>
                                <span class="font-bold text-[#4B4B4B] capitalize"><?= esc($mb['metode_bayar']) ?></span>
                            </div>
                            <span class="font-extrabold text-[#100F3E]"><?= $mb['jumlah'] ?>x</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Top Menu -->
        <div class="bg-white rounded-2xl border-2 border-[#E5E5E5] p-6">
            <h3 class="font-extrabold text-[#100F3E] uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
                <span class="text-xl">🔥</span> Menu Terlaris
            </h3>
            <?php if(empty($topMenu)): ?>
                <div class="text-center py-6 text-[#AFAFAF]">
                    <p class="font-bold text-sm">Belum ada data.</p>
                </div>
            <?php else: ?>
                <div class="space-y-2.5">
                    <?php foreach($topMenu as $idx => $tm): ?>
                        <div class="flex items-center gap-3 bg-[#f7f7f7] p-2.5 rounded-xl border border-[#E5E5E5]">
                            <div class="w-8 h-8 rounded-lg bg-[#58CC02]/10 text-[#58CC02] flex items-center justify-center font-black text-xs flex-shrink-0">
                                #<?= $idx + 1 ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[#4B4B4B] text-xs truncate"><?= esc($tm['nama_item']) ?></p>
                                <p class="text-[9px] text-[#AFAFAF] font-bold"><?= $tm['total_terjual'] ?> terjual</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-2xl border-2 border-[#E5E5E5] overflow-hidden animate-fade-in-up mb-6">
    <div class="px-6 py-5 border-b-2 border-[#E5E5E5] bg-[#f7f7f7] flex justify-between items-center">
        <h3 class="font-extrabold text-[#100F3E] uppercase tracking-wider text-sm flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#58CC02]"></span>
            Detail Data
        </h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-[#AFAFAF] text-[10px] uppercase font-extrabold tracking-[1.5px] border-b-2 border-[#E5E5E5]">
                    <?php if($mode == 'harian'): ?>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Meja</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    <?php elseif($mode == 'bulanan'): ?>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-right">Pendapatan</th>
                        <th class="px-6 py-4 text-center">Transaksi</th>
                        <th class="px-6 py-4 text-center">Refund</th>
                    <?php else: ?>
                        <th class="px-6 py-4">Bulan</th>
                        <th class="px-6 py-4 text-right">Pendapatan</th>
                        <th class="px-6 py-4 text-center">Transaksi</th>
                        <th class="px-6 py-4 text-center">Refund</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php $detail = $laporan['detail'] ?? []; ?>
                <?php if(empty($detail)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="w-16 h-16 bg-[#f7f7f7] rounded-full flex items-center justify-center mx-auto mb-3 text-2xl border-2 border-[#E5E5E5]">💤</div>
                            <p class="text-[#AFAFAF] font-bold">Belum ada data untuk periode ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php if($mode == 'harian'): ?>
                        <?php foreach($detail as $d): ?>
                        <tr class="hover:bg-[#f7f7f7] transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-[#777] bg-[#f7f7f7] px-3 py-1 rounded-lg text-xs border border-[#E5E5E5]">
                                    <?= date('H:i', strtotime($d['tgl_pesanan'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-[#4B4B4B]"><?= esc($d['nama_pelanggan'] ?? 'Guest') ?></td>
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-[#1CB0F6] bg-[#1CB0F6]/10 px-3 py-1 rounded-lg text-xs border border-[#1CB0F6]/20">
                                    Meja <?= esc($d['no_meja']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-[#100F3E]">Rp <?= number_format($d['total_bayar'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if($d['status_pesanan'] == 'selesai'): ?>
                                    <span class="bg-[#58CC02]/10 text-[#58CC02] px-3 py-1 rounded-full text-[10px] font-extrabold uppercase">Selesai</span>
                                <?php else: ?>
                                    <span class="bg-[#FF4B4B]/10 text-[#FF4B4B] px-3 py-1 rounded-full text-[10px] font-extrabold uppercase">Refund</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php elseif($mode == 'bulanan'): ?>
                        <?php foreach($detail as $d): ?>
                        <tr class="hover:bg-[#f7f7f7] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#1CB0F6]/5 text-[#1CB0F6] flex flex-col items-center justify-center border border-[#1CB0F6]/20">
                                        <span class="text-xs font-extrabold leading-none"><?= date('d', strtotime($d['tanggal'])) ?></span>
                                        <span class="text-[8px] font-bold uppercase"><?= date('M', strtotime($d['tanggal'])) ?></span>
                                    </div>
                                    <span class="font-bold text-[#4B4B4B]"><?= date('l', strtotime($d['tanggal'])) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-[#58CC02] text-base">Rp <?= number_format($d['pendapatan'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-[#1CB0F6]/10 text-[#1CB0F6] px-3 py-1 rounded-full text-xs font-extrabold"><?= $d['transaksi'] ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($d['refund'] > 0): ?>
                                    <span class="bg-[#FF4B4B]/10 text-[#FF4B4B] px-3 py-1 rounded-full text-xs font-extrabold"><?= $d['refund'] ?></span>
                                <?php else: ?>
                                    <span class="text-[#AFAFAF] font-bold">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach($detail as $d): ?>
                        <tr class="hover:bg-[#f7f7f7] transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-[#4B4B4B]"><?= $namaBulan[(int)$d['bulan']] ?></span>
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-[#58CC02] text-base">Rp <?= number_format($d['pendapatan'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-[#FF9600]/10 text-[#FF9600] px-3 py-1 rounded-full text-xs font-extrabold"><?= $d['transaksi'] ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($d['refund'] > 0): ?>
                                    <span class="bg-[#FF4B4B]/10 text-[#FF4B4B] px-3 py-1 rounded-full text-xs font-extrabold"><?= $d['refund'] ?></span>
                                <?php else: ?>
                                    <span class="text-[#AFAFAF] font-bold">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const namaBulanArr = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    const mode = '<?= $mode ?>';
    const chartData = <?= json_encode($laporan['chart'] ?? []) ?>;
    
    // ====== MAIN CHART ======
    const ctx = document.getElementById('laporanChart');
    if(ctx) {
        let labels = [];
        let data = [];
        let barColor = '#58CC02';
        
        if(mode === 'harian') {
            // Per jam (0-23)
            for(let h = 0; h < 24; h++) {
                labels.push(String(h).padStart(2,'0') + ':00');
                const found = chartData.find(r => parseInt(r.jam) === h);
                data.push(found ? parseInt(found.total) : 0);
            }
            barColor = '#58CC02';
        } else if(mode === 'bulanan') {
            // Per hari (1-31)
            const daysInMonth = new Date(<?= $tahun ?>, <?= $bulan ?>, 0).getDate();
            for(let d = 1; d <= daysInMonth; d++) {
                labels.push(d);
                const found = chartData.find(r => parseInt(r.hari) === d);
                data.push(found ? parseInt(found.total) : 0);
            }
            barColor = '#1CB0F6';
        } else {
            // Per bulan (1-12)
            for(let m = 1; m <= 12; m++) {
                labels.push(namaBulanArr[m-1]);
                const found = chartData.find(r => parseInt(r.bulan) === m);
                data.push(found ? parseInt(found.total) : 0);
            }
            barColor = '#FF9600';
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
                        if(!chartArea) return barColor;
                        const gradient = c.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, barColor + '99');
                        gradient.addColorStop(1, barColor);
                        return gradient;
                    },
                    borderRadius: 5,
                    borderSkipped: false,
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
                        grid: { display: false },
                        ticks: { 
                            font: { family: "'Nunito', sans-serif", size: 9 }, 
                            color: '#AFAFAF',
                            maxRotation: mode === 'bulanan' ? 0 : 45,
                            autoSkip: true,
                            maxTicksLimit: mode === 'harian' ? 12 : (mode === 'bulanan' ? 31 : 12)
                        }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    }

    // ====== PAYMENT PIE CHART ======
    const payCtx = document.getElementById('paymentChart');
    if(payCtx) {
        const payData = <?= json_encode($metodeBayar ?? []) ?>;
        const payColors = ['#58CC02','#1CB0F6','#FF9600','#FF4B4B','#CE82FF'];
        
        if(payData.length > 0) {
            new Chart(payCtx, {
                type: 'doughnut',
                data: {
                    labels: payData.map(p => p.metode_bayar),
                    datasets: [{
                        data: payData.map(p => parseInt(p.jumlah)),
                        backgroundColor: payColors.slice(0, payData.length),
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#100F3E',
                            padding: 10,
                            cornerRadius: 8,
                            bodyFont: { family: "'Nunito', sans-serif", weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a,b) => a+b, 0);
                                    const pct = ((context.parsed / total) * 100).toFixed(0);
                                    return context.label + ': ' + pct + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?= $this->endSection() ?>
