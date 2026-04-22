<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; } </style>
</head>
<body class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-900 text-white flex flex-col hidden md:flex">
        <div class="p-6 border-b border-gray-800 text-center">
            <h2 class="text-2xl font-black text-orange-500 tracking-wider">KAFE<br><span class="text-white text-lg font-semibold">GAMIFIED</span></h2>
        </div>
        
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="<?= base_url('admin/dashboard') ?>" class="block px-4 py-3 bg-orange-600 rounded-xl font-semibold shadow-lg text-white">🏠 Dashboard</a>
            
            <p class="px-4 pt-4 pb-1 text-xs font-bold text-gray-500 uppercase tracking-widest">Operasional</p>
            <a href="<?= base_url('admin/kasir') ?>" class="block px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl transition-colors flex items-center gap-3">
                <span>💳</span> Kasir (Pembayaran)
            </a>
            <a href="<?= base_url('admin/dapur') ?>" class="block px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl transition-colors flex items-center gap-3">
                <span>👨‍🍳</span> Kitchen (Dapur)
            </a>
            <a href="<?= base_url('admin/menu') ?>" class="block px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl transition-colors flex items-center gap-3">
                <span>🍔</span> Manajemen Menu
            </a>
            <a href="<?= base_url('admin/riwayat') ?>" class="block px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl transition-colors flex items-center gap-3">
                <span>🧾</span> Riwayat Transaksi
            </a>
            
            <p class="px-4 pt-4 pb-1 text-xs font-bold text-gray-500 uppercase tracking-widest">Gamifikasi & CRM</p>
            <a href="<?= base_url('admin/rfm') ?>" class="block px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl transition-colors flex items-center gap-3">
                <span>📊</span> Analitik RFM
            </a>
            <a href="<?= base_url('admin/reward') ?>" class="block px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-xl transition-colors flex items-center gap-3">
                <span>🎁</span> Tukar Poin Reward
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800 bg-gray-900/50">
            <div class="mb-3 px-2">
                <p class="text-[10px] text-gray-500 uppercase font-bold">Admin Aktif</p>
                <p class="text-sm font-bold text-white"><?= esc(session()->get('nama_admin')) ?></p>
            </div>
            <a href="<?= base_url('admin/logout') ?>" class="block w-full text-center px-4 py-2 bg-red-600/10 text-red-500 hover:bg-red-600 hover:text-white rounded-lg font-bold transition-all text-xs uppercase tracking-widest border border-red-600/20">
                Keluar Sistem
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white shadow-sm p-4 md:hidden flex justify-between items-center sticky top-0 z-50">
            <h1 class="font-black text-xl text-orange-600">KAFE GAMIFIED</h1>
            <a href="<?= base_url('admin/logout') ?>" class="text-red-500 font-bold text-sm">Keluar</a>
        </header>

        <div class="p-6 md:p-10 max-w-6xl mx-auto w-full">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-gray-800 mb-2">Halo, <?= esc(session()->get('nama_admin')) ?>! 👋</h1>
                <p class="text-gray-500">Pantau performa kafe dan loyalitas pelanggan secara real-time.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md group">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Pendapatan Lunas Hari Ini</p>
                        <h3 class="text-4xl font-black text-green-600 tracking-tighter">Rp <?= number_format($omzet, 0, ',', '.') ?></h3>
                        <p class="text-[10px] text-gray-400 mt-2 italic">*Hanya pesanan berstatus selesai</p>
                    </div>
                    <div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">💰</div>
                </div>
                
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md group">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Volume Pesanan Hari Ini</p>
                        <h3 class="text-4xl font-black text-orange-600 tracking-tighter"><?= $total_pesanan ?> <span class="text-lg text-gray-400 font-medium">Struk</span></h3>
                        <p class="text-[10px] text-gray-400 mt-2 italic">*Mencakup antrean kasir & dapur</p>
                    </div>
                    <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">🧾</div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="font-black text-gray-800 uppercase tracking-wider text-sm">Aktivitas Terakhir</h3>
                        <p class="text-xs text-gray-500">5 transaksi terbaru yang masuk ke sistem.</p>
                    </div>
                    <a href="<?= base_url('admin/riwayat') ?>" class="text-xs font-bold text-orange-600 hover:text-orange-700 bg-orange-50 px-4 py-2 rounded-full transition-colors">Lihat Semua Riwayat →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-400 text-[10px] uppercase font-bold tracking-widest border-b">
                                <th class="px-8 py-4">Waktu</th>
                                <th class="px-8 py-4">Pelanggan</th>
                                <th class="px-8 py-4">Meja</th>
                                <th class="px-8 py-4">Total</th>
                                <th class="px-8 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php if(empty($pesanan_baru)): ?>
                                <tr><td colspan="5" class="p-12 text-center text-gray-400 font-medium italic">Belum ada aktivitas pesanan hari ini.</td></tr>
                            <?php else: ?>
                                <?php foreach($pesanan_baru as $p): ?>
                                <tr class="border-b border-gray-50 hover:bg-gray-50/80 transition-colors">
                                    <td class="px-8 py-5 text-gray-500 font-mono"><?= date('H:i', strtotime($p['tgl_pesanan'])) ?></td>
                                    <td class="px-8 py-5 font-bold text-gray-800"><?= esc($p['nama_pelanggan']) ?></td>
                                    <td class="px-8 py-5 font-black text-orange-500 italic">#<?= esc($p['no_meja']) ?></td>
                                    <td class="px-8 py-5 font-bold text-gray-900">Rp <?= number_format($p['total_bayar'], 0, ',', '.') ?></td>
                                    <td class="px-8 py-5">
                                        <?php if($p['status_pesanan'] == 'selesai'): ?>
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border border-green-200">Selesai</span>
                                        <?php elseif($p['status_pesanan'] == 'pending'): ?>
                                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border border-blue-200">Dimasak</span>
                                        <?php else: ?>
                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border border-yellow-200">Belum Bayar</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </main>
</body>
</html>