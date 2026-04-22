<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="p-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <a href="<?= base_url('admin/dashboard') ?>" class="text-orange-600 font-bold mb-2 block">← Kembali</a>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Analitik Segmentasi Pelanggan</h1>
            <p class="text-gray-500">Berdasarkan Algoritma Recency, Frequency, dan Monetary.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <table class="w-full text-left">
                <thead class="bg-gray-900 text-white text-xs uppercase tracking-widest">
                    <tr>
                        <th class="p-5">Pelanggan</th>
                        <th class="p-5">Recency</th>
                        <th class="p-5">Frequency</th>
                        <th class="p-5">Monetary</th>
                        <th class="p-5">Skor Akhir</th>
                        <th class="p-5">Segmen Pelanggan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach($pelanggan as $p): ?>
                    <tr class="hover:bg-orange-50/50 transition-colors">
                        <td class="p-5">
                            <p class="font-bold text-gray-900"><?= $p['nama_pelanggan'] ?></p>
                            <p class="text-xs text-gray-400 italic">ID: #<?= $p['id_pelanggan'] ?></p>
                        </td>
                        <td class="p-5 text-sm text-gray-600"><?= $p['recency_raw'] ?> Hari lalu</td>
                        <td class="p-5 text-sm text-gray-600"><?= $p['frequency_raw'] ?>x Transaksi</td>
                        <td class="p-5 text-sm font-bold text-green-600">Rp <?= number_format($row['monetary_raw'] ?? $p['monetary_raw'], 0, ',', '.') ?></td>
                        <td class="p-5">
                            <div class="flex gap-1">
                                <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-[10px] font-bold">R:<?= $p['skor_r'] ?></span>
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-[10px] font-bold">F:<?= $p['skor_f'] ?></span>
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-bold">M:<?= $p['skor_m'] ?></span>
                            </div>
                        </td>
                        <td class="p-5">
                            <?php 
                                $color = 'bg-gray-100 text-gray-600';
                                if($p['segment'] == 'Champions') $color = 'bg-yellow-400 text-yellow-900';
                                if($p['segment'] == 'Loyal Customers') $color = 'bg-green-500 text-white';
                                if($p['segment'] == 'At Risk') $color = 'bg-red-100 text-red-600 border border-red-200';
                                if($p['segment'] == 'Lost Customers') $color = 'bg-gray-800 text-white';
                            ?>
                            <span class="<?= $color ?> px-4 py-2 rounded-xl text-xs font-black uppercase tracking-tighter">
                                <?= $p['segment'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>