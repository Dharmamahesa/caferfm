<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <meta http-equiv="refresh" content="30">
    
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="bg-gray-900 text-white p-4 shadow-md flex justify-between items-center sticky top-0 z-50">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-orange-500">KDS <span class="text-white text-lg font-semibold">| Dapur Kafe Gamified</span></h1>
        </div>
        <div class="flex items-center gap-4">
            <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">Live</span>
            <p id="jam-sekarang" class="font-mono text-xl font-bold"></p>
        </div>
    </div>

    <div class="p-6">
        <?php if(session()->getFlashdata('sukses')): ?>
            <div class="bg-green-500 text-white p-4 rounded-xl mb-6 font-bold shadow-lg flex justify-between items-center">
                <?= session()->getFlashdata('sukses') ?>
                <button onclick="this.parentElement.style.display='none'" class="text-white hover:text-green-200">✖</button>
            </div>
        <?php endif; ?>

        <?php if(empty($pesanan)): ?>
            <div class="flex flex-col items-center justify-center h-64 text-gray-400 mt-10">
                <svg class="w-24 h-24 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <p class="text-2xl font-semibold">Dapur Kosong. Menunggu Pesanan Masuk...</p>
                <p class="text-md mt-2">Halaman ini akan otomatis refresh setiap 30 detik.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach($pesanan as $p): ?>
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col border-t-8 border-orange-500 transform transition hover:-translate-y-1">
                        
                        <div class="bg-orange-50 p-4 border-b border-orange-100 flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold text-orange-600 uppercase tracking-widest">Meja</p>
                                <p class="text-5xl font-black text-gray-800 leading-none mt-1"><?= esc($p['no_meja']) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-semibold mb-1">#<?= $p['id_pesanan'] ?></p>
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-1 rounded border border-yellow-200">Pending</span>
                            </div>
                        </div>

                        <div class="p-5 flex-grow bg-white">
                            <div class="text-sm text-gray-500 mb-4 border-b pb-3 flex justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-wider mb-1">Pemesan</p>
                                    <p class="font-bold text-gray-800 text-base"><?= esc($p['nama_pelanggan']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs uppercase tracking-wider mb-1">Masuk</p>
                                    <p class="font-mono text-gray-800 font-bold"><?= date('H:i', strtotime($p['tgl_pesanan'])) ?></p>
                                </div>
                            </div>
                            
                            <ul class="space-y-4">
                                <?php foreach($p['detail'] as $item): ?>
                                    <li class="flex items-start text-gray-800">
                                        <span class="font-black text-xl text-orange-600 mr-4 bg-orange-50 px-2 py-1 rounded"><?= $item['jumlah'] ?>x</span>
                                        <span class="font-bold text-lg leading-tight mt-1"><?= esc($item['nama_item']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="p-4 bg-gray-50 border-t border-gray-100">
                            <a href="<?= base_url('admin/selesai/' . $p['id_pesanan']) ?>" onclick="return confirm('Apakah pesanan Meja <?= $p['no_meja'] ?> sudah selesai dimasak?')" class="block w-full text-center bg-green-500 hover:bg-green-600 text-white font-black py-4 rounded-xl shadow-md transition-colors text-xl uppercase tracking-wider">
                                ✓ Selesai
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateJam() {
            const now = new Date();
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('jam-sekarang').innerText = `${jam}:${menit}:${detik}`;
        }
        setInterval(updateJam, 1000); // Update setiap 1 detik
        updateJam();
    </script>
</body>
</html>