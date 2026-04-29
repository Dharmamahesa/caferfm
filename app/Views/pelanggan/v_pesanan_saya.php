<?= $this->extend('pelanggan/layout_pelanggan') ?>

<?= $this->section('content') ?>

<div class="bg-white/80 backdrop-blur-xl p-5 flex items-center shadow-sm sticky top-0 z-40 border-b border-gray-100">
    <a href="<?= base_url('profil') ?>" class="text-gray-600 font-black mr-4 text-xl bg-gray-100 w-11 h-11 flex items-center justify-center rounded-2xl hover:bg-orange-50 hover:text-orange-600 transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-black tracking-tight text-gray-800">Pesanan Saya</h1>
        <p class="text-[10px] uppercase font-bold tracking-widest text-gray-400">Riwayat & Status</p>
    </div>
</div>

<div class="max-w-md mx-auto p-5 mt-2 space-y-5 animate-fade-in-up">

    <?php if(empty($pesanan)): ?>
        <div class="text-center py-16 bg-white rounded-[2rem] border border-gray-100 shadow-sm">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner text-5xl">🧾</div>
            <h2 class="text-xl font-black text-gray-800 mb-2">Belum Ada Pesanan</h2>
            <p class="text-gray-500 font-medium mb-8 text-sm">Kamu belum pernah memesan apapun.</p>
            <a href="<?= base_url('/') ?>" class="bg-orange-50 text-orange-600 font-black px-8 py-3.5 rounded-2xl hover:bg-orange-600 hover:text-white transition-colors shadow-sm inline-block">Pesan Sekarang</a>
        </div>
    <?php else: ?>
        <?php foreach($pesanan as $p): ?>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden relative group">
            
            <?php 
                $statusColor = 'bg-yellow-50 text-yellow-600 border-yellow-200';
                $statusIcon = '🕒';
                $statusText = 'Diproses';
                
                if($p['status_pesanan'] == 'Selesai') {
                    $statusColor = 'bg-green-50 text-green-600 border-green-200';
                    $statusIcon = '✅';
                    $statusText = 'Selesai';
                }
            ?>

            <div class="p-5 flex justify-between items-center border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 <?= $statusColor ?> rounded-2xl flex items-center justify-center text-xl shadow-inner border">
                        <?= $statusIcon ?>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</p>
                        <p class="font-bold text-gray-800"><?= $statusText ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Order ID</p>
                    <p class="font-mono font-bold text-gray-600">#<?= $p['id_pesanan'] ?></p>
                </div>
            </div>

            <div class="p-5 bg-gray-50/50">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Tagihan</p>
                        <p class="text-xl font-black text-orange-600">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal</p>
                        <p class="text-xs font-bold text-gray-600"><?= date('d M Y, H:i', strtotime($p['tgl_pesanan'])) ?></p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-100 text-sm">
                    <p class="text-gray-600 font-medium">Metode: <span class="font-bold text-gray-800"><?= esc($p['metode_bayar']) ?></span></p>
                    <p class="text-gray-600 font-medium">Meja: <span class="font-bold text-gray-800"><?= esc($p['no_meja']) ?></span></p>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
