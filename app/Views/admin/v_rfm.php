<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 animate-fade-in-up">
    <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
        <span class="bg-indigo-100 text-indigo-600 p-2 rounded-xl shadow-inner">📊</span> 
        Analitik Segmentasi Pelanggan
    </h1>
    <p class="text-gray-500 font-medium mt-2">Berdasarkan Algoritma Recency, Frequency, dan Monetary (RFM).</p>
</div>

<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-gray-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Pelanggan</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Recency</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Frequency</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Monetary</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Skor Akhir</th>
                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Segmen Pelanggan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if(empty($pelanggan)): ?>
                    <tr><td colspan="6" class="px-8 py-16 text-center text-gray-400 font-bold">Belum ada data analitik.</td></tr>
                <?php else: ?>
                    <?php foreach($pelanggan as $p): ?>
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <p class="font-black text-gray-800 text-base"><?= esc($p['nama_pelanggan']) ?></p>
                            <p class="text-[10px] text-gray-400 font-mono font-bold uppercase tracking-wider mt-0.5">ID: #<?= $p['id_pelanggan'] ?></p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-lg text-xs group-hover:bg-white border border-transparent group-hover:border-gray-200 transition-colors">
                                <?= $p['recency_raw'] ?> Hari lalu
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-lg text-xs group-hover:bg-white border border-transparent group-hover:border-gray-200 transition-colors">
                                <?= $p['frequency_raw'] ?>x Beli
                            </span>
                        </td>
                        <td class="px-8 py-5 font-black text-green-600">
                            Rp <?= number_format($row['monetary_raw'] ?? $p['monetary_raw'], 0, ',', '.') ?>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex gap-1.5">
                                <span class="bg-orange-50 text-orange-600 border border-orange-100 px-2.5 py-1 rounded-lg text-[10px] font-black shadow-sm">R:<?= $p['skor_r'] ?></span>
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-1 rounded-lg text-[10px] font-black shadow-sm">F:<?= $p['skor_f'] ?></span>
                                <span class="bg-green-50 text-green-600 border border-green-100 px-2.5 py-1 rounded-lg text-[10px] font-black shadow-sm">M:<?= $p['skor_m'] ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <?php 
                                $color = 'bg-gray-100 text-gray-600 border border-gray-200';
                                if($p['segment'] == 'Champions') $color = 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-md shadow-yellow-500/30';
                                if($p['segment'] == 'Loyal Customers') $color = 'bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-md shadow-green-500/30';
                                if($p['segment'] == 'At Risk') $color = 'bg-red-50 text-red-600 border border-red-200';
                                if($p['segment'] == 'Lost Customers') $color = 'bg-gray-800 text-white shadow-md shadow-gray-800/30';
                            ?>
                            <span class="<?= $color ?> px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest inline-block transition-transform group-hover:scale-105">
                                <?= $p['segment'] ?>
                            </span>
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
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?= $this->endSection() ?>