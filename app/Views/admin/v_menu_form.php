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
<body class="p-6 md:p-10 flex justify-center items-center min-h-screen">

    <?php 
        // Logika cerdas: Jika ada data $menu, berarti sedang mode Edit. Jika tidak, mode Tambah.
        $isEdit = isset($menu); 
        $actionUrl = $isEdit ? base_url('admin/menu/update/' . $menu['id_menu']) : base_url('admin/menu/simpan');
    ?>

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-lg border border-gray-100">
        <h1 class="text-2xl font-black text-gray-800 mb-6"><?= $isEdit ? '✏️ Edit Menu' : '🍔 Tambah Menu Baru' ?></h1>

        <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Menu</label>
                <input type="text" name="nama_item" value="<?= $isEdit ? esc($menu['nama_item']) : '' ?>" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 outline-none">
                        <option value="makanan" <?= ($isEdit && $menu['kategori'] == 'makanan') ? 'selected' : '' ?>>Makanan</option>
                        <option value="minuman" <?= ($isEdit && $menu['kategori'] == 'minuman') ? 'selected' : '' ?>>Minuman</option>
                        <option value="snack" <?= ($isEdit && $menu['kategori'] == 'snack') ? 'selected' : '' ?>>Snack</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" value="<?= $isEdit ? esc($menu['harga']) : '' ?>" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Upload Foto</label>
                <input type="file" name="foto" accept="image/*" <?= !$isEdit ? 'required' : '' ?> class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                <?php if($isEdit && !empty($menu['foto'])): ?>
                    <p class="text-xs text-gray-400 mt-2">Biarkan kosong jika tidak ingin mengubah foto.</p>
                <?php endif; ?>
            </div>

            <div class="pt-4 flex gap-4">
                <a href="<?= base_url('admin/menu') ?>" class="w-1/3 bg-gray-100 text-gray-600 font-bold py-3 rounded-xl text-center hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="w-2/3 bg-orange-600 text-white font-bold py-3 rounded-xl shadow-md hover:bg-orange-700 transition-colors">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Menu' ?>
                </button>
            </div>
        </form>
    </div>

</body>
</html>