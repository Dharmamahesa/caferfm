<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-orange-100 text-orange-600 p-2 rounded-xl shadow-inner">🍔</span> 
            Manajemen Menu
        </h1>
        <p class="text-gray-500 font-medium mt-2">Kelola daftar makanan, minuman, dan snack di Kafe Anda.</p>
    </div>
    <a href="<?= base_url('admin/menu/tambah') ?>" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3.5 rounded-xl font-bold shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 hover:-translate-y-0.5 active:scale-95 transition-all flex items-center gap-2 group whitespace-nowrap">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        Tambah Menu
    </a>
</div>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-gradient-to-r from-green-50 to-green-100 text-green-700 p-5 rounded-2xl mb-8 font-bold border border-green-200 shadow-sm flex items-center justify-between animate-fade-in-up">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-inner border border-green-400">✓</div>
            <?= session()->getFlashdata('sukses') ?>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700 p-2">✖</button>
    </div>
<?php endif; ?>

<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 text-gray-400 text-[10px] uppercase font-black tracking-widest border-b border-gray-100">
                    <th class="px-6 py-5">Visual</th>
                    <th class="px-6 py-5">Nama Item</th>
                    <th class="px-6 py-5">Kategori</th>
                    <th class="px-6 py-5">Harga</th>
                    <th class="px-6 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($menu)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-inner">🍽️</div>
                            <p class="text-gray-400 font-bold">Belum ada data menu. Silakan tambahkan menu baru.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($menu as $m): ?>
                    <tr class="border-b border-gray-50 hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <?php if(!empty($m['foto'])): ?>
                                <img src="<?= base_url('uploads/menu/' . $m['foto']) ?>" class="w-14 h-14 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform">
                            <?php else: ?>
                                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center text-[10px] font-bold text-gray-400 border border-gray-200 border-dashed">No Image</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-black text-gray-800 text-base"><?= esc($m['nama_item']) ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $bgColor = 'bg-gray-100 text-gray-600';
                                if($m['kategori'] == 'makanan') $bgColor = 'bg-red-50 text-red-600 border border-red-100';
                                if($m['kategori'] == 'minuman') $bgColor = 'bg-blue-50 text-blue-600 border border-blue-100';
                                if($m['kategori'] == 'snack') $bgColor = 'bg-yellow-50 text-yellow-600 border border-yellow-100';
                            ?>
                            <span class="<?= $bgColor ?> px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-sm inline-block">
                                <?= esc($m['kategori']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-black text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg inline-block border border-orange-100 shadow-sm">
                                Rp <?= number_format($m['harga'], 0, ',', '.') ?>
                            </p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-50 group-hover:opacity-100 transition-opacity">
                                <a href="<?= base_url('admin/menu/edit/' . $m['id_menu']) ?>" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    Edit
                                </a>
                                <a href="<?= base_url('admin/menu/hapus/' . $m['id_menu']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus menu <?= esc($m['nama_item']) ?>?')" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center gap-1">
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