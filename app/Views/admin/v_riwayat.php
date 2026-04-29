<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-6 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-xl shadow-inner">🧾</span> 
            Riwayat Transaksi
        </h1>
        <p class="text-gray-500 font-medium mt-2">Daftar semua pesanan yang telah selesai dimasak dan dibayar lunas.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-center gap-4">
        <!-- Cetak Laporan Button -->
        <a href="<?= base_url('admin/laporan/cetak') ?>" target="_blank" class="w-full sm:w-auto bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-xl shadow-sm hover:bg-gray-50 hover:shadow-md hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-3 group">
            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-lg group-hover:scale-110 transition-transform">🖨️</div>
            <div class="text-left">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Export Laporan</p>
                <p class="text-sm font-bold">Cetak PDF</p>
            </div>
        </a>

        <!-- Total Omzet Hari Ini Widget -->
        <div class="w-full sm:w-auto bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3.5 rounded-xl shadow-lg shadow-green-500/30 flex items-center gap-4">
            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center text-xl shadow-inner border border-white/20">
                💰
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-green-100 mb-0.5 opacity-90">Omzet Hari Ini</p>
                <p class="text-2xl font-black tracking-tight drop-shadow-sm">Rp <?= number_format($omzet, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-gray-400 text-[10px] uppercase font-black tracking-widest border-b border-gray-100">
                    <th class="px-8 py-5">ID / Struk</th>
                    <th class="px-8 py-5">Tanggal & Waktu</th>
                    <th class="px-8 py-5">Pelanggan</th>
                    <th class="px-8 py-5">Meja</th>
                    <th class="px-8 py-5 text-right">Total Bayar</th>
                    <th class="px-8 py-5 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php if(empty($riwayat)): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-inner border border-gray-100">💤</div>
                            <p class="text-gray-400 font-bold text-base">Belum ada riwayat transaksi hari ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($riwayat as $r): ?>
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="font-mono font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg text-xs group-hover:bg-white border border-transparent group-hover:border-gray-200 transition-colors">
                                #<?= esc($r['id_pesanan']) ?>
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex flex-col items-center justify-center border border-indigo-100">
                                    <span class="text-xs font-black leading-none"><?= date('d', strtotime($r['tgl_pesanan'])) ?></span>
                                    <span class="text-[8px] font-bold uppercase"><?= date('M', strtotime($r['tgl_pesanan'])) ?></span>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block"><?= date('l', strtotime($r['tgl_pesanan'])) ?></span>
                                    <span class="text-[10px] text-gray-400 font-mono font-bold"><?= date('H:i:s', strtotime($r['tgl_pesanan'])) ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="font-black text-gray-800 text-base"><?= esc($r['nama_pelanggan']) ?></p>
                            <?php if($r['id_pelanggan'] != 1): ?>
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 text-[9px] px-2 py-0.5 rounded-md uppercase font-black tracking-widest inline-block mt-1 shadow-sm">Member VIP</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-500 border border-gray-200 text-[9px] px-2 py-0.5 rounded-md uppercase font-black tracking-widest inline-block mt-1">Guest</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5">
                            <span class="font-black text-orange-600 bg-orange-50 border border-orange-100 px-3 py-1.5 rounded-lg text-xs shadow-sm">
                                Meja <?= esc($r['no_meja']) ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right font-black text-lg text-green-600 tracking-tight">
                            Rp <?= number_format($r['total_bayar'], 0, ',', '.') ?>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-green-500/20 inline-flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                Selesai
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
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?= $this->endSection() ?>