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
<body class="p-6 md:p-10 max-w-5xl mx-auto">

    <div class="mb-8">
        <a href="<?= base_url('admin/dashboard') ?>" class="text-orange-600 font-bold hover:underline mb-2 block">← Kembali ke Dashboard</a>
        <h1 class="text-3xl font-black text-gray-800">Penukaran Poin (Redeem)</h1>
        <p class="text-gray-500">Bantu pelanggan menukarkan poin loyalitas mereka dengan hadiah.</p>
    </div>

    <?php if(session()->getFlashdata('sukses')): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200 shadow-sm flex items-center">
            <span class="text-2xl mr-3">🎁</span> <?= session()->getFlashdata('sukses') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 font-bold border border-red-200 shadow-sm flex items-center">
            <span class="text-2xl mr-3">❌</span> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="" method="GET" class="flex gap-4">
            <input type="text" name="q" value="<?= esc($keyword) ?>" placeholder="Cari nama pelanggan..." class="flex-1 px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            <button type="submit" class="bg-gray-800 text-white font-bold px-8 py-3 rounded-xl hover:bg-gray-900 transition-colors">Cari</button>
            <?php if($keyword): ?>
                <a href="<?= base_url('admin/reward') ?>" class="bg-gray-200 text-gray-700 font-bold px-6 py-3 rounded-xl hover:bg-gray-300 transition-colors">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wider border-b">
                    <th class="p-4 font-semibold">Nama Pelanggan</th>
                    <th class="p-4 font-semibold">Sisa Poin</th>
                    <th class="p-4 font-semibold w-1/2">Aksi Tukar Reward</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($pelanggan)): ?>
                    <tr><td colspan="3" class="p-8 text-center text-gray-400 font-semibold">Pelanggan tidak ditemukan.</td></tr>
                <?php else: ?>
                    <?php foreach($pelanggan as $p): ?>
                    <tr class="border-b border-gray-50 hover:bg-orange-50 transition-colors">
                        <td class="p-4">
                            <p class="font-bold text-gray-800 text-base"><?= esc($p['nama_pelanggan']) ?></p>
                            <p class="text-xs text-gray-400 uppercase">ID: <?= $p['id_pelanggan'] ?></p>
                        </td>
                        <td class="p-4">
                            <span class="text-2xl font-black text-orange-600"><?= esc($p['poin_loyalitas']) ?> <span class="text-sm text-gray-400 font-medium">pts</span></span>
                        </td>
                        <td class="p-4">
                            <form action="<?= base_url('admin/reward/proses') ?>" method="POST" class="flex gap-2 items-center" onsubmit="return confirm('Tukar poin pelanggan ini sekarang? Pastikan reward sudah diberikan secara fisik ke pelanggan.');">
                                <input type="hidden" name="id_pelanggan" value="<?= $p['id_pelanggan'] ?>">
                                
                                <select name="katalog_reward" id="katalog_reward_<?= $p['id_pelanggan'] ?>" onchange="updateForm(this, <?= $p['id_pelanggan'] ?>)" required class="flex-1 px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 outline-none text-sm font-semibold">
                                    <option value="" data-poin="0">-- Pilih Hadiah --</option>
                                    <option value="Gratis Es Teh Manis" data-poin="50">50 Poin - Gratis Es Teh Manis</option>
                                    <option value="Diskon Bill 10%" data-poin="100">100 Poin - Diskon Bill 10%</option>
                                    <option value="Gratis 1 Kopi Susu Aren" data-poin="200">200 Poin - Gratis 1 Kopi Susu Aren</option>
                                    <option value="Voucher Makan Rp 50.000" data-poin="500">500 Poin - Voucher Makan Rp 50.000</option>
                                </select>

                                <input type="hidden" name="nama_reward" id="nama_reward_<?= $p['id_pelanggan'] ?>" value="">
                                <input type="hidden" name="poin_dibutuhkan" id="poin_dibutuhkan_<?= $p['id_pelanggan'] ?>" value="0">

                                <button type="submit" class="bg-orange-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-orange-700 transition-all text-sm shadow-md">
                                    Tukar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function updateForm(selectElement, id) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            document.getElementById('nama_reward_' + id).value = selectedOption.value;
            document.getElementById('poin_dibutuhkan_' + id).value = selectedOption.getAttribute('data-poin');
        }
    </script>
</body>
</html>