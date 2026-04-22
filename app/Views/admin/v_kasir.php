<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <meta http-equiv="refresh" content="30"> <style> body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; } </style>
</head>
<body class="p-6 md:p-10 max-w-6xl mx-auto">

    <div class="mb-8 flex justify-between items-end">
        <div>
            <a href="<?= base_url('admin/dashboard') ?>" class="text-orange-600 font-bold hover:underline mb-2 block">← Kembali ke Dashboard</a>
            <h1 class="text-3xl font-black text-gray-800">Verifikasi Pembayaran</h1>
            <p class="text-gray-500">Konfirmasi pembayaran sebelum pesanan diteruskan ke koki dapur.</p>
        </div>
    </div>

    <?php if(session()->getFlashdata('sukses')): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200">
            ✓ <?= session()->getFlashdata('sukses') ?>
        </div>
    <?php endif; ?>

    <?php if(empty($pesanan)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm mt-10">
            <span class="text-6xl block mb-4">💳</span>
            <p class="text-xl font-bold text-gray-800">Tidak ada antrean pembayaran.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($pesanan as $p): ?>
                <div class="bg-white rounded-2xl shadow-sm border-t-8 border-blue-500 overflow-hidden flex flex-col">
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <p class="font-black text-gray-800 text-lg"><?= esc($p['nama_pelanggan']) ?></p>
                            <span class="bg-blue-100 text-blue-700 text-[10px] font-black uppercase px-2 py-1 rounded">Meja <?= esc($p['no_meja']) ?></span>
                        </div>
                        <p class="text-xs text-gray-500 font-mono mb-4">ID Struk: #<?= $p['id_pesanan'] ?></p>
                        
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Tagihan</p>
                            <p class="text-3xl font-black text-green-600">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></p>
                            <p class="text-xs font-bold text-gray-600 mt-2">Metode: <span class="text-blue-600 uppercase"><?= esc($p['metode_bayar']) ?></span></p>
                        </div>
                    </div>
                    
                    <div class="p-4 flex-grow bg-gray-50/50">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Item Dipesan</p>
                        <ul class="space-y-1">
                            <?php foreach($p['detail'] as $item): ?>
                                <li class="text-sm text-gray-700 flex justify-between">
                                    <span><?= esc($item['nama_item']) ?></span>
                                    <span class="font-bold text-gray-900">x<?= $item['jumlah'] ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="p-4 border-t border-gray-100">
                        <a href="<?= base_url('admin/kasir/verifikasi/' . $p['id_pesanan']) ?>" onclick="return confirm('Uang sudah diterima kasir? Pesanan akan langsung masuk ke Dapur.')" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors shadow-md">
                            Verifikasi Lunas
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>
</html>