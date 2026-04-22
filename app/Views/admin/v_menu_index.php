<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; } </style>
</head>
<body class="p-6 md:p-10 max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="<?= base_url('admin/dashboard') ?>" class="text-orange-600 font-bold hover:underline mb-2 block">← Kembali ke Dashboard</a>
            <h1 class="text-3xl font-black text-gray-800">Manajemen Menu</h1>
        </div>
        <a href="<?= base_url('admin/menu/tambah') ?>" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 shadow-md transition-all">+ Tambah Menu</a>
    </div>

    <?php if(session()->getFlashdata('sukses')): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200">
            ✓ <?= session()->getFlashdata('sukses') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wider border-b">
                    <th class="p-4 font-semibold">Foto</th>
                    <th class="p-4 font-semibold">Nama Item</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Harga</th>
                    <th class="p-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($menu)): ?>
                    <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada data menu.</td></tr>
                <?php else: ?>
                    <?php foreach($menu as $m): ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="p-4">
                            <?php if(!empty($m['foto'])): ?>
                                <img src="<?= base_url('uploads/menu/' . $m['foto']) ?>" class="w-12 h-12 rounded-lg object-cover shadow-sm">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">Kosong</div>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 font-bold text-gray-800"><?= esc($m['nama_item']) ?></td>
                        <td class="p-4"><span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"><?= esc($m['kategori']) ?></span></td>
                        <td class="p-4 font-bold text-orange-600">Rp <?= number_format($m['harga'], 0, ',', '.') ?></td>
                        <td class="p-4 text-right space-x-2">
                            <a href="<?= base_url('admin/menu/edit/' . $m['id_menu']) ?>" class="text-blue-500 hover:underline font-semibold">Edit</a>
                            <a href="<?= base_url('admin/menu/hapus/' . $m['id_menu']) ?>" onclick="return confirm('Yakin ingin menghapus menu ini?')" class="text-red-500 hover:underline font-semibold">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>