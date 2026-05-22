<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
            <span class="bg-[#58CC02]/10 text-[#58CC02] p-2 rounded-xl shadow-inner">🍔</span> 
            Manajemen Menu
        </h1>
        <p class="text-[#777] font-medium mt-1 text-sm">Kelola daftar makanan, minuman, dan snack di Kafe Anda.</p>
    </div>
    <a href="<?= base_url('admin/menu/tambah') ?>" class="bg-gradient-to-r from-[#58CC02] to-[#4BB200] text-white px-5 py-3 rounded-xl font-bold shadow-none shadow-[#58CC02]/25 hover:shadow-none hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 group self-start sm:self-auto whitespace-nowrap">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        Tambah Menu
    </a>
</div>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-gradient-to-r from-green-50 to-green-100 text-green-700 p-4 rounded-2xl mb-6 font-bold border border-green-200 shadow-sm flex items-center justify-between animate-fade-in-up">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-inner border border-green-400 flex-shrink-0">✓</div>
            <?= session()->getFlashdata('sukses') ?>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700 p-2 flex-shrink-0">✖</button>
    </div>
<?php endif; ?>

<!-- Desktop Table (md+) -->
<div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden animate-fade-in-up hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f7f7f7]/80 text-[#AFAFAF] text-[10px] uppercase font-extrabold tracking-widest border-b border-[#E5E5E5]">
                    <th class="px-6 py-5">Visual</th>
                    <th class="px-6 py-5">Nama Item</th>
                    <th class="px-6 py-5">Kategori</th>
                    <th class="px-6 py-5">Harga</th>
                    <th class="px-6 py-5 text-center">Stok</th>
                    <th class="px-6 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($menu)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 bg-[#f7f7f7] rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-inner">🍽️</div>
                            <p class="text-[#AFAFAF] font-bold">Belum ada data menu. Silakan tambahkan menu baru.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($menu as $m): ?>
                    <tr class="border-b border-gray-50 hover:bg-[#f7f7f7]/50 transition-colors group">
                        <td class="px-6 py-4">
                            <?php if(!empty($m['foto'])): ?>
                                <img src="<?= base_url('uploads/menu/' . $m['foto']) ?>" class="w-14 h-14 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform">
                            <?php else: ?>
                                <div class="w-14 h-14 bg-[#f7f7f7] rounded-xl flex items-center justify-center text-[10px] font-bold text-[#AFAFAF] border border-[#E5E5E5] border-dashed">No Image</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-extrabold text-[#4B4B4B] text-base"><?= esc($m['nama_item']) ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $bgColor = 'bg-[#f7f7f7] text-gray-600';
                                if($m['kategori'] == 'makanan') $bgColor = 'bg-red-50 text-[#FF4B4B] border border-red-100';
                                if($m['kategori'] == 'minuman') $bgColor = 'bg-blue-50 text-blue-600 border border-blue-100';
                                if($m['kategori'] == 'snack') $bgColor = 'bg-yellow-50 text-yellow-600 border border-yellow-100';
                            ?>
                            <span class="<?= $bgColor ?> px-3 py-1.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-sm inline-block">
                                <?= esc($m['kategori']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-extrabold text-[#58CC02] bg-[#58CC02]/10 px-3 py-1.5 rounded-lg inline-block border border-[#58CC02]/20 shadow-sm">
                                Rp <?= number_format($m['harga'], 0, ',', '.') ?>
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php $stokColor = $m['stok'] <= 5 ? 'bg-red-50 text-[#FF4B4B] border-red-100' : 'bg-green-50 text-green-600 border-green-100'; ?>
                            <span class="font-extrabold <?= $stokColor ?> px-3 py-1.5 rounded-lg inline-block border shadow-sm text-sm">
                                <?= esc($m['stok'] ?? 0) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?= base_url('admin/menu/edit/' . $m['id_menu']) ?>" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    Edit
                                </a>
                                <a href="<?= base_url('admin/menu/hapus/' . $m['id_menu']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus menu <?= esc($m['nama_item']) ?>?')" class="bg-red-50 text-[#FF4B4B] hover:bg-[#e04343] hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View (< md) -->
<div class="space-y-4 animate-fade-in-up md:hidden">
    <?php if(empty($menu)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-[#E5E5E5] shadow-sm">
            <div class="text-3xl mb-3">🍽️</div>
            <p class="text-[#AFAFAF] font-bold text-sm">Belum ada data menu.</p>
        </div>
    <?php else: ?>
        <?php foreach($menu as $m): ?>
        <?php
            $bgColor = 'bg-[#f7f7f7] text-gray-600';
            if($m['kategori'] == 'makanan') $bgColor = 'bg-red-50 text-[#FF4B4B] border border-red-100';
            if($m['kategori'] == 'minuman') $bgColor = 'bg-blue-50 text-blue-600 border border-blue-100';
            if($m['kategori'] == 'snack') $bgColor = 'bg-yellow-50 text-yellow-600 border border-yellow-100';
        ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] p-4 flex items-center gap-4">
            <?php if(!empty($m['foto'])): ?>
                <img src="<?= base_url('uploads/menu/' . $m['foto']) ?>" class="w-16 h-16 rounded-2xl object-cover shadow-sm flex-shrink-0">
            <?php else: ?>
                <div class="w-16 h-16 bg-[#f7f7f7] rounded-2xl flex items-center justify-center text-[10px] font-bold text-[#AFAFAF] flex-shrink-0">No Img</div>
            <?php endif; ?>
            <div class="flex-1 min-w-0">
                <p class="font-extrabold text-[#4B4B4B] truncate"><?= esc($m['nama_item']) ?></p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="<?= $bgColor ?> px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase"><?= esc($m['kategori']) ?></span>
                    <span class="text-[#58CC02] font-extrabold text-sm">Rp <?= number_format($m['harga'], 0, ',', '.') ?></span>
                </div>
                <div class="mt-1">
                    <?php $stokColorMobile = $m['stok'] <= 5 ? 'text-[#FF4B4B]' : 'text-green-600'; ?>
                    <span class="text-xs font-bold <?= $stokColorMobile ?>">Stok: <?= esc($m['stok'] ?? 0) ?></span>
                </div>
            </div>
            <div class="flex flex-col gap-2 flex-shrink-0">
                <a href="<?= base_url('admin/menu/edit/' . $m['id_menu']) ?>" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-colors text-center">Edit</a>
                <a href="<?= base_url('admin/menu/hapus/' . $m['id_menu']) ?>" onclick="return confirm('Hapus <?= esc($m['nama_item']) ?>?')" class="bg-red-50 text-[#FF4B4B] hover:bg-[#e04343] hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-colors text-center">Hapus</a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?= $this->endSection() ?>