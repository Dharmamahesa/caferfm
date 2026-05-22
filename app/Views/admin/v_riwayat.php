<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-6 animate-fade-in-up">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
            <span class="bg-[#1CB0F6]/10 text-[#1CB0F6] p-2 rounded-xl shadow-inner">🧾</span> 
            Riwayat Transaksi
        </h1>
        <p class="text-[#777] font-medium mt-2 text-sm">Daftar semua pesanan yang telah selesai dimasak dan dibayar lunas.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        <!-- Cetak Laporan Button -->
        <?php 
            $exportUrl = base_url('admin/laporan/cetak');
            if(isset($start_date) && isset($end_date)) {
                $exportUrl .= "?start_date={$start_date}&end_date={$end_date}";
            }
        ?>
        <a href="<?= $exportUrl ?>" target="_blank" class="bg-white border border-[#E5E5E5] text-gray-700 px-5 py-3 rounded-xl shadow-sm hover:bg-[#f7f7f7] hover:shadow-md active:scale-95 transition-all flex items-center justify-center gap-3 group">
            <div class="w-8 h-8 bg-[#f7f7f7] rounded-lg flex items-center justify-center text-lg group-hover:scale-110 transition-transform">🖨️</div>
            <div class="text-left">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#AFAFAF]">Export</p>
                <p class="text-sm font-bold">Cetak PDF</p>
            </div>
        </a>

        <!-- Total Omzet Widget -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-3.5 rounded-xl shadow-none shadow-green-500/30 flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center text-xl shadow-inner border border-white/20">💰</div>
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-green-100 opacity-90">Omzet Hari Ini</p>
                <p class="text-xl font-extrabold tracking-tight drop-shadow-sm">Rp <?= number_format($omzet, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tanggal -->
<div class="bg-white p-5 rounded-2xl shadow-sm border border-[#E5E5E5] mb-6 animate-fade-in-up">
    <form action="<?= base_url('admin/riwayat') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Dari Tanggal</label>
            <input type="date" name="start_date" value="<?= esc($start_date ?? '') ?>" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#1CB0F6] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-gray-700 transition-all">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Sampai Tanggal</label>
            <input type="date" name="end_date" value="<?= esc($end_date ?? '') ?>" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#1CB0F6] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-gray-700 transition-all">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none bg-[#1CB0F6]/5 text-[#1CB0F6] px-6 py-3 rounded-xl font-bold shadow-sm hover:bg-[#1899D6] hover:text-white transition-colors">Terapkan Filter</button>
            <?php if(!empty($start_date) && !empty($end_date)): ?>
                <a href="<?= base_url('admin/riwayat') ?>" class="flex-1 md:flex-none bg-[#f7f7f7] text-gray-600 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors flex items-center justify-center">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Desktop Table View (hidden on mobile) -->
<div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden animate-fade-in-up hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f7f7f7]/80 text-[#AFAFAF] text-[10px] uppercase font-extrabold tracking-widest border-b border-[#E5E5E5]">
                    <th class="px-6 py-5">ID / Struk</th>
                    <th class="px-6 py-5">Tanggal & Waktu</th>
                    <th class="px-6 py-5">Pelanggan</th>
                    <th class="px-6 py-5">Meja</th>
                    <th class="px-6 py-5 text-right">Total Bayar</th>
                    <th class="px-6 py-5 text-center">Status</th>
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php if(empty($riwayat)): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center">
                            <div class="w-16 h-16 bg-[#f7f7f7] rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-inner border border-[#E5E5E5]">💤</div>
                            <p class="text-[#AFAFAF] font-bold text-base">Belum ada riwayat transaksi hari ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($riwayat as $r): ?>
                    <tr class="hover:bg-[#1CB0F6]/5/30 transition-colors group">
                        <td class="px-6 py-5">
                            <span class="font-mono font-bold text-[#777] bg-[#f7f7f7] px-3 py-1.5 rounded-lg text-xs group-hover:bg-white border border-transparent group-hover:border-[#E5E5E5] transition-colors">
                                #<?= esc($r['id_pesanan']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#1CB0F6]/5 text-[#1CB0F6] flex flex-col items-center justify-center border border-indigo-100">
                                    <span class="text-xs font-extrabold leading-none"><?= date('d', strtotime($r['tgl_pesanan'])) ?></span>
                                    <span class="text-[8px] font-bold uppercase"><?= date('M', strtotime($r['tgl_pesanan'])) ?></span>
                                </div>
                                <div>
                                    <span class="font-bold text-[#4B4B4B] block"><?= date('l', strtotime($r['tgl_pesanan'])) ?></span>
                                    <span class="text-[10px] text-[#AFAFAF] font-mono font-bold"><?= date('H:i:s', strtotime($r['tgl_pesanan'])) ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="font-extrabold text-[#4B4B4B] text-base"><?= esc($r['nama_pelanggan']) ?></p>
                            <?php if($r['id_pelanggan'] != 1): ?>
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 text-[9px] px-2 py-0.5 rounded-md uppercase font-extrabold tracking-widest inline-block mt-1 shadow-sm">Member</span>
                            <?php else: ?>
                                <span class="bg-[#f7f7f7] text-[#777] border border-[#E5E5E5] text-[9px] px-2 py-0.5 rounded-md uppercase font-extrabold tracking-widest inline-block mt-1">Guest</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-5">
                            <span class="font-extrabold text-[#58CC02] bg-[#58CC02]/10 border border-[#58CC02]/20 px-3 py-1.5 rounded-lg text-xs shadow-sm">
                                Meja <?= esc($r['no_meja']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right font-extrabold text-lg text-green-600 tracking-tight">
                            Rp <?= number_format($r['total_bayar'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <?php if($r['status_pesanan'] == 'refund'): ?>
                                <span class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-widest shadow-md shadow-red-500/20 inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Refund
                                </span>
                            <?php else: ?>
                                <span class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-1.5 rounded-xl text-[10px] font-extrabold uppercase tracking-widest shadow-md shadow-green-500/20 inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    Selesai
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <?php if($r['status_pesanan'] == 'selesai'): ?>
                                <a href="<?= base_url('admin/riwayat/refund/' . $r['id_pesanan']) ?>" onclick="return confirm('Apakah Anda yakin ingin me-refund pesanan ini? Stok akan dikembalikan.')" class="bg-red-50 text-[#FF4B4B] hover:bg-[#e04343] hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm inline-block">
                                    Refund
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View (shown only on mobile) -->
<div class="space-y-4 animate-fade-in-up md:hidden">
    <?php if(empty($riwayat)): ?>
        <div class="bg-white rounded-2xl p-12 text-center border border-[#E5E5E5] shadow-sm flex flex-col items-center">
            <div class="w-16 h-16 bg-[#f7f7f7] rounded-full flex items-center justify-center mb-3 text-2xl shadow-inner">💤</div>
            <p class="text-[#AFAFAF] font-bold">Belum ada riwayat transaksi.</p>
        </div>
    <?php else: ?>
        <?php foreach($riwayat as $r): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-50 bg-[#f7f7f7]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#1CB0F6]/5 text-[#1CB0F6] flex flex-col items-center justify-center border border-indigo-100 flex-shrink-0">
                        <span class="text-xs font-extrabold leading-none"><?= date('d', strtotime($r['tgl_pesanan'])) ?></span>
                        <span class="text-[8px] font-bold uppercase"><?= date('M', strtotime($r['tgl_pesanan'])) ?></span>
                    </div>
                    <div>
                        <p class="font-extrabold text-[#4B4B4B] text-sm"><?= esc($r['nama_pelanggan']) ?></p>
                        <p class="text-[10px] text-[#AFAFAF] font-mono"><?= date('H:i', strtotime($r['tgl_pesanan'])) ?> · Meja <?= esc($r['no_meja']) ?></p>
                    </div>
                </div>
                <span class="font-mono font-bold text-[#AFAFAF] bg-[#f7f7f7] px-2 py-1 rounded-lg text-[10px]">#<?= esc($r['id_pesanan']) ?></span>
            </div>
            <div class="p-4 flex items-center justify-between">
                <div>
                    <?php if($r['status_pesanan'] == 'refund'): ?>
                        <p class="font-extrabold text-lg text-[#FF4B4B] line-through">Rp <?= number_format($r['total_bayar'], 0, ',', '.') ?></p>
                        <span class="text-xs text-[#FF4B4B] font-bold">Refunded</span>
                    <?php else: ?>
                        <p class="font-extrabold text-lg text-green-600">Rp <?= number_format($r['total_bayar'], 0, ',', '.') ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-2">
                    <?php if($r['status_pesanan'] == 'selesai'): ?>
                        <a href="<?= base_url('admin/riwayat/refund/' . $r['id_pesanan']) ?>" onclick="return confirm('Refund pesanan ini?')" class="bg-red-50 text-[#FF4B4B] hover:bg-[#e04343] hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-colors shadow-sm">Refund</a>
                        <span class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-widest shadow-sm inline-flex items-center gap-1">
                            Selesai
                        </span>
                    <?php endif; ?>
                </div>
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
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?= $this->endSection() ?>