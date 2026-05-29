<?= $this->extend('pelanggan/layout_pelanggan') ?>

<?= $this->section('content') ?>

<div class="bg-white/80 backdrop-blur-xl p-5 flex items-center shadow-sm sticky top-0 z-40 border-b border-[#E5E5E5]">
    <a href="<?= base_url('profil') ?>" class="text-gray-600 font-extrabold mr-4 text-xl bg-[#f7f7f7] w-11 h-11 flex items-center justify-center rounded-2xl hover:bg-[#58CC02]/10 hover:text-[#58CC02] transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-[#4B4B4B]">Pesanan Saya</h1>
        <p class="text-[10px] uppercase font-bold tracking-widest text-[#AFAFAF]">Riwayat & Status</p>
    </div>
</div>

<div class="max-w-2xl mx-auto p-5 mt-2 space-y-5 animate-fade-in-up">

    <?php if(empty($pesanan)): ?>
        <div class="text-center py-16 bg-white rounded-2xl border border-[#E5E5E5] shadow-sm">
            <div class="w-24 h-24 bg-[#f7f7f7] rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner text-5xl">🧾</div>
            <h2 class="text-xl font-extrabold text-[#4B4B4B] mb-2">Belum Ada Pesanan</h2>
            <p class="text-[#777] font-medium mb-8 text-sm">Kamu belum pernah memesan apapun.</p>
            <a href="<?= base_url('/') ?>" class="bg-[#58CC02]/10 text-[#58CC02] font-extrabold px-8 py-3.5 rounded-2xl hover:bg-[#4BB200] hover:text-white transition-colors shadow-sm inline-block">Pesan Sekarang</a>
        </div>
    <?php else: ?>
        <?php foreach($pesanan as $p): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden relative group">
            
            <?php 
                $statusColor = 'bg-gray-50 text-gray-600 border-gray-200';
                $statusIcon = '🕒';
                $statusText = 'Diproses';
                
                $statusDb = strtolower($p['status_pesanan']);
                
                if($statusDb == 'belum_bayar') {
                    $statusColor = 'bg-orange-50 text-orange-500 border-orange-200';
                    $statusIcon = '💳';
                    $statusText = 'Menunggu Pembayaran';
                } elseif($statusDb == 'pending') {
                    $statusColor = 'bg-blue-50 text-blue-500 border-blue-200';
                    $statusIcon = '👨‍🍳';
                    $statusText = 'Diproses Dapur';
                } elseif($statusDb == 'selesai') {
                    $statusColor = 'bg-[#58CC02]/10 text-[#58CC02] border-[#58CC02]/20';
                    $statusIcon = '✅';
                    $statusText = 'Selesai';
                } elseif($statusDb == 'refund') {
                    $statusColor = 'bg-[#FF4B4B]/10 text-[#FF4B4B] border-[#FF4B4B]/20';
                    $statusIcon = '❌';
                    $statusText = 'Dibatalkan / Refund';
                }
            ?>

            <div class="p-5 flex justify-between items-center border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 <?= $statusColor ?> rounded-2xl flex items-center justify-center text-xl shadow-inner border">
                        <?= $statusIcon ?>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Status</p>
                        <p class="font-bold text-[#4B4B4B]"><?= $statusText ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Order ID</p>
                    <p class="font-mono font-bold text-gray-600">#<?= $p['id_pesanan'] ?></p>
                </div>
            </div>

            <div class="p-5 bg-[#f7f7f7]">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-1">Total Tagihan</p>
                        <p class="text-xl font-extrabold text-[#58CC02]">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-1">Tanggal</p>
                        <p class="text-xs font-bold text-gray-600"><?= date('d M Y, H:i', strtotime($p['tgl_pesanan'])) ?></p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-[#E5E5E5] text-sm mb-3">
                    <p class="text-gray-600 font-medium">Metode: <span class="font-bold text-[#4B4B4B]"><?= esc($p['metode_bayar']) ?></span></p>
                    <p class="text-gray-600 font-medium">Meja: <span class="font-bold text-[#4B4B4B]"><?= esc($p['no_meja']) ?></span></p>
                </div>

                <?php if(isset($p['poin_didapat']) && $p['poin_didapat'] > 0): ?>
                    <div class="flex items-center gap-2 bg-[#58CC02]/10 p-3 rounded-xl border border-[#58CC02]/20">
                        <span class="text-[#58CC02] text-lg">⭐</span>
                        <div>
                            <p class="text-[10px] font-extrabold text-[#58CC02] uppercase tracking-widest">Poin Didapat</p>
                            <p class="font-bold text-[#4B4B4B] text-sm">+<?= $p['poin_didapat'] ?> Poin Loyalitas</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
