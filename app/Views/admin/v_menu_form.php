<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<?php 
    $isEdit = isset($menu); 
    $actionUrl = $isEdit ? base_url('admin/menu/update/' . $menu['id_menu']) : base_url('admin/menu/simpan');
?>

<div class="mb-6 flex items-center gap-3 animate-fade-in-up">
    <a href="<?= base_url('admin/menu') ?>" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-orange-600 transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <h1 class="text-3xl font-black text-gray-800 tracking-tight">
        <?= $isEdit ? 'Edit Menu' : 'Tambah Menu Baru' ?>
    </h1>
</div>

<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 max-w-2xl animate-fade-in-up">
    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
        <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-3xl shadow-inner">
            <?= $isEdit ? '✏️' : '🍔' ?>
        </div>
        <div>
            <h2 class="text-lg font-black text-gray-800"><?= $isEdit ? 'Perbarui Informasi Menu' : 'Informasi Menu Baru' ?></h2>
            <p class="text-sm text-gray-500 font-medium">Pastikan semua kolom terisi dengan benar.</p>
        </div>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Menu</label>
            <input type="text" name="nama_item" value="<?= $isEdit ? esc($menu['nama_item']) : '' ?>" placeholder="Contoh: Kopi Susu Aren" required class="w-full px-5 py-4 rounded-xl bg-gray-50/50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-gray-800 font-bold transition-all shadow-sm">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kategori</label>
                <div class="relative">
                    <select name="kategori" required class="w-full px-5 py-4 rounded-xl bg-gray-50/50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-gray-800 font-bold appearance-none transition-all shadow-sm">
                        <option value="makanan" <?= ($isEdit && $menu['kategori'] == 'makanan') ? 'selected' : '' ?>>Makanan Utama</option>
                        <option value="minuman" <?= ($isEdit && $menu['kategori'] == 'minuman') ? 'selected' : '' ?>>Minuman</option>
                        <option value="snack" <?= ($isEdit && $menu['kategori'] == 'snack') ? 'selected' : '' ?>>Snack / Camilan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Harga (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 font-bold">
                        Rp
                    </div>
                    <input type="number" name="harga" value="<?= $isEdit ? esc($menu['harga']) : '' ?>" placeholder="15000" required class="w-full pl-12 pr-5 py-4 rounded-xl bg-gray-50/50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none text-gray-800 font-bold transition-all shadow-sm">
                </div>
            </div>
        </div>

        <div class="bg-slate-50 p-6 rounded-2xl border border-gray-100 border-dashed">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Upload Visual / Foto</label>
            
            <?php if($isEdit && !empty($menu['foto'])): ?>
                <div class="flex items-center gap-4 mb-4 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                    <img src="<?= base_url('uploads/menu/' . $menu['foto']) ?>" class="w-16 h-16 rounded-lg object-cover">
                    <div>
                        <p class="text-xs font-bold text-gray-800">Foto Saat Ini</p>
                        <p class="text-[10px] text-gray-500">Biarkan kosong jika tidak ingin mengubah foto.</p>
                    </div>
                </div>
            <?php endif; ?>

            <input type="file" name="foto" accept="image/*" <?= !$isEdit ? 'required' : '' ?> class="w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-wider file:bg-white file:text-orange-600 hover:file:bg-orange-50 hover:file:text-orange-700 cursor-pointer file:shadow-sm file:border file:border-gray-200 transition-all">
        </div>

        <div class="pt-6 flex flex-col-reverse sm:flex-row gap-4 border-t border-gray-100">
            <a href="<?= base_url('admin/menu') ?>" class="w-full sm:w-1/3 bg-white border border-gray-200 text-gray-600 font-bold py-4 rounded-xl text-center hover:bg-gray-50 transition-colors shadow-sm">Batal</a>
            <button type="submit" class="w-full sm:w-2/3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-black py-4 rounded-xl shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 active:scale-95 transition-all flex justify-center items-center gap-2 group">
                <?= $isEdit ? 'Simpan Perubahan' : 'Tambahkan Menu' ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </div>
    </form>
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