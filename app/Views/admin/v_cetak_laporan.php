<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - <?= $tanggal ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fff; color: #000; }
        /* CSS Khusus agar rapi saat dicetak ke kertas A4 / PDF */
        @media print {
            @page { margin: 2cm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-border { border: 1px solid #000 !important; }
        }
    </style>
</head>
<body class="p-8 max-w-4xl mx-auto">

    <div class="mb-8 no-print flex justify-between items-center bg-gray-100 p-4 rounded-xl">
        <a href="<?= base_url('admin/riwayat') ?>" class="text-gray-600 font-bold hover:underline">← Kembali</a>
        <button onclick="window.print()" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-orange-700 shadow-md">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <div class="text-center border-b-2 border-black pb-6 mb-6">
        <h1 class="text-3xl font-black uppercase tracking-widest">KAFE GAMIFIED</h1>
        <p class="text-sm">Jl. Skripsi Mahasiswa No. 1, Kota Akademik, 65145</p>
        <p class="text-sm">Telp: (0341) 123456 | Email: hello@kafegamified.com</p>
        
        <h2 class="text-xl font-bold mt-6 uppercase">Laporan Pendapatan Harian</h2>
        <p class="text-sm font-semibold">Periode: <?= $tanggal ?></p>
    </div>

    <table class="w-full text-left border-collapse mb-8 print-border">
        <thead>
            <tr class="bg-gray-200 print-border">
                <th class="p-3 border border-black text-sm uppercase">No</th>
                <th class="p-3 border border-black text-sm uppercase">Waktu</th>
                <th class="p-3 border border-black text-sm uppercase">ID Struk</th>
                <th class="p-3 border border-black text-sm uppercase">Pelanggan</th>
                <th class="p-3 border border-black text-sm uppercase">Metode</th>
                <th class="p-3 border border-black text-sm uppercase text-right">Total Nominal</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($riwayat)): ?>
                <tr>
                    <td colspan="6" class="p-4 border border-black text-center italic">Tidak ada transaksi yang selesai pada hari ini.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach($riwayat as $r): ?>
                <tr>
                    <td class="p-3 border border-black text-sm text-center"><?= $no++ ?></td>
                    <td class="p-3 border border-black text-sm"><?= date('H:i:s', strtotime($r['tgl_pesanan'])) ?></td>
                    <td class="p-3 border border-black text-sm font-mono">#<?= $r['id_pesanan'] ?></td>
                    <td class="p-3 border border-black text-sm font-semibold"><?= esc($r['nama_pelanggan']) ?></td>
                    <td class="p-3 border border-black text-sm uppercase"><?= esc($r['metode_bayar']) ?></td>
                    <td class="p-3 border border-black text-sm text-right font-bold text-gray-800">Rp <?= number_format($r['total_bayar'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="bg-gray-200 print-border">
                <th colspan="5" class="p-3 border border-black text-right uppercase">Total Omzet Hari Ini</th>
                <th class="p-3 border border-black text-right text-lg font-black">Rp <?= number_format($omzet, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="flex justify-end mt-12">
        <div class="text-center w-64">
            <p class="text-sm mb-16">Dicetak oleh, Manajer Operasional</p>
            <p class="font-bold border-b border-black pb-1"><?= esc(session()->get('nama_admin')) ?></p>
            <p class="text-xs mt-1">Waktu Cetak: <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Memberi sedikit waktu agar Tailwind me-render sempurna sebelum print
            setTimeout(() => {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>