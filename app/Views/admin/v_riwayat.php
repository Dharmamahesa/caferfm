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
<body class="p-6 md:p-10 max-w-6xl mx-auto">

    <div class="flex justify-between items-end mb-8">
        <div>
            <a href="<?= base_url('admin/dashboard') ?>" class="text-orange-600 font-bold hover:underline mb-2 block">← Kembali ke Dashboard</a>
            <h1 class="text-3xl font-black text-gray-800">Riwayat Transaksi</h1>
            <p class="text-gray-500">Semua pesanan yang telah selesai dimasak dan dibayar.</p>
        </div>
        
        <div class="bg-green-500 text-white px-6 py-3 rounded-2xl shadow-lg border border-green-600 text-right">
            <p class="text-xs font-bold uppercase tracking-widest text-green-100 mb-1">Pendapatan Hari Ini</p>
            <p class="text-2xl font-black">Rp <?= number_format($omzet, 0, ',', '.') ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white text-xs uppercase tracking-wider border-b">
                        <th class="p-4 font-semibold">ID / Struk</th>
                        <th class="p-4 font-semibold">Tanggal & Waktu</th>
                        <th class="p-4 font-semibold">Pelanggan</th>
                        <th class="p-4 font-semibold">No Meja</th>
                        <th class="p-4 font-semibold">Total Bayar</th>
                        <th class="p-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if(empty($riwayat)): ?>
                        <tr><td colspan="6" class="p-8 text-center text-gray-400 font-semibold">Belum ada riwayat transaksi.</td></tr>
                    <?php else: ?>
                        <?php foreach($riwayat as $r): ?>
                        <tr class="border-b border-gray-50 hover:bg-orange-50 transition-colors">
                            <td class="p-4 font-mono font-bold text-gray-500">#<?= esc($r['id_pesanan']) ?></td>
                            <td class="p-4 text-gray-600">
                                <span class="font-bold text-gray-800 block"><?= date('d M Y', strtotime($r['tgl_pesanan'])) ?></span>
                                <?= date('H:i:s', strtotime($r['tgl_pesanan'])) ?>
                            </td>
                            <td class="p-4 font-bold text-gray-800">
                                <?= esc($r['nama_pelanggan']) ?>
                                <?php if($r['id_pelanggan'] != 1): ?>
                                    <span class="ml-2 bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full uppercase font-bold">Member</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 font-bold text-gray-800">Meja <?= esc($r['no_meja']) ?></td>
                            <td class="p-4 font-black text-green-600">Rp <?= number_format($r['total_bayar'], 0, ',', '.') ?></td>
                            <td class="p-4">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-200">
                                    ✓ Selesai
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>