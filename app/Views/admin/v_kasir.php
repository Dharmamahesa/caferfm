<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
            <span class="bg-blue-100 text-blue-600 p-2 rounded-xl shadow-inner">💳</span> 
            Verifikasi Kasir
        </h1>
        <p class="text-[#777] font-medium mt-1 text-sm">Konfirmasi pembayaran sebelum pesanan diteruskan ke koki dapur.</p>
    </div>
    <!-- Auto-refresh Indicator -->
    <div class="bg-white px-4 py-2 rounded-full border border-[#E5E5E5] shadow-sm flex items-center gap-2 self-start sm:self-auto">
        <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        </span>
        <span class="text-xs font-bold text-[#777] uppercase tracking-widest">Live Update</span>
    </div>
</div>

<!-- Meta Refresh via JS for Layout Compatibility -->
<script>
    setTimeout(function() { window.location.reload(); }, 30000);
</script>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-gradient-to-r from-green-50 to-green-100 text-green-700 p-4 rounded-2xl mb-6 font-bold border border-green-200 shadow-sm flex items-center gap-3 animate-fade-in-up">
        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-inner flex-shrink-0">✓</div>
        <?= session()->getFlashdata('sukses') ?>
    </div>
<?php endif; ?>

<?php if(empty($pesanan)): ?>
    <div class="bg-white rounded-2xl p-12 md:p-16 text-center border border-[#E5E5E5] shadow-sm mt-6 flex flex-col items-center justify-center animate-fade-in-up">
        <div class="w-20 h-20 bg-[#f7f7f7] rounded-full flex items-center justify-center mb-5 shadow-inner">
            <span class="text-4xl">💤</span>
        </div>
        <h2 class="text-xl md:text-2xl font-extrabold text-[#4B4B4B] mb-2">Kasir Sedang Kosong</h2>
        <p class="text-[#777] font-medium text-sm">Belum ada antrean pembayaran dari pelanggan saat ini.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 md:gap-7">
        <?php foreach($pesanan as $p): ?>
            <div class="bg-white rounded-2xl shadow-md hover:shadow-none hover:-translate-y-1 transition-all duration-300 border-t-[6px] border-blue-500 overflow-hidden flex flex-col group animate-fade-in-up">
                <div class="p-5 border-b border-gray-50 relative">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                    
                    <div class="flex justify-between items-start mb-3 relative z-10">
                        <div>
                            <p class="font-extrabold text-[#4B4B4B] text-lg tracking-tight"><?= esc($p['nama_pelanggan']) ?></p>
                            <p class="text-xs text-[#AFAFAF] font-mono mt-0.5">ID: #<?= $p['id_pesanan'] ?></p>
                        </div>
                        <span class="bg-blue-50 text-blue-600 text-xs font-extrabold uppercase px-3 py-1.5 rounded-xl border border-blue-100 shadow-sm flex-shrink-0">
                            Meja <?= esc($p['no_meja']) ?>
                        </span>
                    </div>
                    
                    <div class="bg-gradient-to-br from-gray-50 to-slate-100 p-4 rounded-2xl border border-[#E5E5E5]/50 mt-3 relative z-10 shadow-inner">
                        <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-1">Total Tagihan</p>
                        <p class="text-2xl md:text-3xl font-extrabold text-green-600 tracking-tighter drop-shadow-sm">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-[10px] font-bold text-[#777] uppercase">Metode:</span>
                            <span class="text-xs font-extrabold <?= $p['metode_bayar'] == 'Cash' ? 'text-[#58CC02] bg-[#58CC02]/10 border-[#58CC02]/20' : 'text-purple-600 bg-purple-50 border-purple-100' ?> px-2 py-0.5 rounded-lg border uppercase tracking-wider shadow-sm">
                                <?= esc($p['metode_bayar']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-5 flex-grow bg-white relative z-10">
                    <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        Item Dipesan
                    </p>
                    <ul class="space-y-2">
                        <?php foreach($p['detail'] as $item): ?>
                            <li class="text-sm font-semibold text-gray-600 flex justify-between items-center py-1 border-b border-gray-50 border-dashed last:border-0">
                                <span><?= esc($item['nama_item']) ?></span>
                                <span class="bg-[#f7f7f7] text-[#4B4B4B] px-2 py-0.5 rounded-lg font-extrabold text-xs">x<?= $item['jumlah'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="p-4 border-t border-gray-50 bg-[#f7f7f7]">
                    <a href="<?= base_url('admin/kasir/verifikasi/' . $p['id_pesanan']) ?>" onclick="return confirm('Apakah pembayaran sudah diterima? Pesanan akan diteruskan ke Dapur.')" class="flex items-center justify-center gap-2 w-full text-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-none shadow-blue-500/30 hover:shadow-none active:scale-95 group/btn">
                        Verifikasi Lunas
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

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