<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 animate-fade-in-up">
    <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
        <span class="bg-[#1CB0F6]/10 text-[#1CB0F6] p-2 rounded-xl shadow-inner">📊</span> 
        Analitik Segmentasi Pelanggan
    </h1>
    <p class="text-[#777] font-medium mt-1 text-sm">Berdasarkan Algoritma Recency, Frequency, dan Monetary (RFM).</p>
</div>

<!-- Broadcast Promo Form -->
<div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-[#E5E5E5] mb-8 animate-fade-in-up relative overflow-hidden">
    <!-- Abstract gradient -->
    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-[#1CB0F6]/5 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="flex items-center gap-3 mb-5 relative z-10">
        <div class="w-10 h-10 bg-[#1CB0F6]/10 text-[#1CB0F6] rounded-xl flex items-center justify-center text-xl shadow-inner">📢</div>
        <div>
            <h3 class="font-extrabold text-[#4B4B4B] text-lg">Broadcast Promo Khusus</h3>
            <p class="text-xs text-[#777] font-medium">Kirim voucher secara massal ke segmen RFM tertentu.</p>
        </div>
    </div>

    <form action="<?= base_url('admin/rfm/broadcast') ?>" method="POST" class="flex flex-col md:flex-row gap-5 relative z-10 items-end">
        <div class="flex-1 w-full">
            <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Pilih Segmen Sasaran</label>
            <select name="segment" required class="w-full px-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#1CB0F6] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-gray-700 appearance-none transition-all cursor-pointer shadow-inner">
                <option value="Champions">🏆 Champions</option>
                <option value="Loyal Customers">💎 Loyal Customers</option>
                <option value="Potential Loyalist">⭐ Potential Loyalist</option>
                <option value="Recent Customers">👋 Recent Customers</option>
                <option value="Promising">🌱 Promising</option>
                <option value="Customers Needing Attention">⚠️ Needing Attention</option>
                <option value="About To Sleep">💤 About To Sleep</option>
                <option value="At Risk">🚨 At Risk</option>
                <option value="Can't Lose Them">🔥 Can't Lose Them</option>
                <option value="Hibernating">❄️ Hibernating</option>
                <option value="Lost Customers">💔 Lost Customers</option>
            </select>
        </div>
        <div class="flex-1 w-full">
            <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Nama Promo</label>
            <input type="text" name="nama_voucher" placeholder="Misal: Diskon Comeback" required class="w-full px-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#1CB0F6] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-gray-700 transition-all shadow-inner">
        </div>
        <div class="flex-1 w-full flex gap-3">
            <div class="w-1/3">
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Tipe</label>
                <select name="tipe_diskon" required class="w-full px-4 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#1CB0F6] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-gray-700 appearance-none transition-all cursor-pointer shadow-inner">
                    <option value="nominal">Nominal (Rp)</option>
                    <option value="persen">Persen (%)</option>
                    <option value="produk">Produk (Gratis)</option>
                </select>
            </div>
            <div class="w-2/3">
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Nilai Potongan</label>
                <input type="number" name="nominal_diskon" placeholder="Misal: 50000" required class="w-full px-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#1CB0F6] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-gray-700 transition-all shadow-inner">
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button type="submit" onclick="return confirm('Kirim voucher massal ke semua pelanggan di segmen ini?')" class="w-full md:w-auto bg-gradient-to-r from-[#1CB0F6] to-[#1899D6] text-white px-8 py-4 rounded-xl font-extrabold shadow-none shadow-[#1CB0F6]/25 hover:shadow-none hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                Kirim Massal
            </button>
        </div>
    </form>
</div>

<!-- Desktop Table -->
<div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden animate-fade-in-up hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-[#f7f7f7] border-b border-[#E5E5E5]">
                <tr>
                    <th class="px-6 py-5 text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Pelanggan</th>
                    <th class="px-6 py-5 text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Recency</th>
                    <th class="px-6 py-5 text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Frequency</th>
                    <th class="px-6 py-5 text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Monetary</th>
                    <th class="px-6 py-5 text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Skor</th>
                    <th class="px-6 py-5 text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Segmen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if(empty($pelanggan)): ?>
                    <tr><td colspan="6" class="px-8 py-16 text-center text-[#AFAFAF] font-bold">Belum ada data analitik.</td></tr>
                <?php else: ?>
                    <?php foreach($pelanggan as $p): ?>
                    <tr class="hover:bg-[#1CB0F6]/5/30 transition-colors group">
                        <td class="px-6 py-5">
                            <p class="font-extrabold text-[#4B4B4B] text-base"><?= esc($p['nama_pelanggan']) ?></p>
                            <p class="text-[10px] text-[#AFAFAF] font-mono font-bold uppercase tracking-wider mt-0.5">ID: #<?= $p['id_pelanggan'] ?></p>
                        </td>
                        <td class="px-6 py-5">
                            <span class="font-bold text-gray-600 bg-[#f7f7f7] px-3 py-1.5 rounded-lg text-xs"><?= $p['recency_raw'] ?> Hari lalu</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="font-bold text-gray-600 bg-[#f7f7f7] px-3 py-1.5 rounded-lg text-xs"><?= $p['frequency_raw'] ?>x Beli</span>
                        </td>
                        <td class="px-6 py-5 font-extrabold text-green-600">
                            Rp <?= number_format($row['monetary_raw'] ?? $p['monetary_raw'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex gap-1.5">
                                <span class="bg-[#58CC02]/10 text-[#58CC02] border border-[#58CC02]/20 px-2 py-1 rounded-lg text-[10px] font-extrabold shadow-sm">R:<?= $p['skor_r'] ?></span>
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-1 rounded-lg text-[10px] font-extrabold shadow-sm">F:<?= $p['skor_f'] ?></span>
                                <span class="bg-green-50 text-green-600 border border-green-100 px-2 py-1 rounded-lg text-[10px] font-extrabold shadow-sm">M:<?= $p['skor_m'] ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <?php 
                                $color = 'bg-[#f7f7f7] text-gray-600 border border-[#E5E5E5]';
                                if($p['segment'] == 'Champions') $color = 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-md shadow-yellow-500/30';
                                if($p['segment'] == 'Loyal Customers') $color = 'bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-md shadow-green-500/30';
                                if($p['segment'] == 'At Risk') $color = 'bg-red-50 text-[#FF4B4B] border border-red-200';
                                if($p['segment'] == 'Lost Customers') $color = 'bg-gray-800 text-white shadow-md shadow-gray-800/30';
                            ?>
                            <span class="<?= $color ?> px-3 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-widest inline-block">
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

<!-- Mobile Card View -->
<div class="space-y-4 animate-fade-in-up md:hidden">
    <?php if(empty($pelanggan)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-[#E5E5E5] shadow-sm">
            <p class="text-[#AFAFAF] font-bold">Belum ada data analitik.</p>
        </div>
    <?php else: ?>
        <?php foreach($pelanggan as $p): ?>
        <?php 
            $colorMobile = 'bg-[#f7f7f7] text-gray-600';
            if($p['segment'] == 'Champions') $colorMobile = 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-white';
            if($p['segment'] == 'Loyal Customers') $colorMobile = 'bg-gradient-to-r from-green-500 to-emerald-500 text-white';
            if($p['segment'] == 'At Risk') $colorMobile = 'bg-red-50 text-[#FF4B4B] border border-red-200';
            if($p['segment'] == 'Lost Customers') $colorMobile = 'bg-gray-800 text-white';
        ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden">
            <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <p class="font-extrabold text-[#4B4B4B]"><?= esc($p['nama_pelanggan']) ?></p>
                    <p class="text-[10px] text-[#AFAFAF] font-mono mt-0.5">ID: #<?= $p['id_pelanggan'] ?></p>
                </div>
                <span class="<?= $colorMobile ?> px-3 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-widest"><?= $p['segment'] ?></span>
            </div>
            <div class="p-4 grid grid-cols-3 gap-3">
                <div class="text-center bg-[#58CC02]/10 rounded-xl p-3">
                    <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase">Recency</p>
                    <p class="font-bold text-[#4B4B4B] text-sm mt-1"><?= $p['recency_raw'] ?>h</p>
                </div>
                <div class="text-center bg-blue-50 rounded-xl p-3">
                    <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase">Freq</p>
                    <p class="font-bold text-[#4B4B4B] text-sm mt-1"><?= $p['frequency_raw'] ?>x</p>
                </div>
                <div class="text-center bg-green-50 rounded-xl p-3">
                    <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase">Monetary</p>
                    <p class="font-bold text-green-700 text-xs mt-1"><?= number_format($p['monetary_raw'], 0, ',', '.') ?></p>
                </div>
            </div>
            <div class="px-4 pb-4 flex gap-1.5">
                <span class="bg-[#58CC02]/10 text-[#58CC02] border border-[#58CC02]/20 px-2 py-1 rounded-lg text-[10px] font-extrabold">R:<?= $p['skor_r'] ?></span>
                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-1 rounded-lg text-[10px] font-extrabold">F:<?= $p['skor_f'] ?></span>
                <span class="bg-green-50 text-green-600 border border-green-100 px-2 py-1 rounded-lg text-[10px] font-extrabold">M:<?= $p['skor_m'] ?></span>
            </div>
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
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?= $this->endSection() ?>