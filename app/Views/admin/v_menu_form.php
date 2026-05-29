<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<?php 
    $isEdit = isset($menu); 
    $actionUrl = $isEdit ? base_url('admin/menu/update/' . $menu['id_menu']) : base_url('admin/menu/simpan');
?>

<div class="mb-6 flex items-center gap-3 animate-fade-in-up">
    <a href="<?= base_url('admin/menu') ?>" class="w-10 h-10 bg-white border border-[#E5E5E5] rounded-full flex items-center justify-center text-[#777] hover:bg-[#f7f7f7] hover:text-[#58CC02] transition-colors shadow-sm flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight">
        <?= $isEdit ? 'Edit Menu' : 'Tambah Menu Baru' ?>
    </h1>
</div>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-[#E5E5E5] max-w-2xl animate-fade-in-up">
    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-[#E5E5E5]">
        <div class="w-16 h-16 bg-[#58CC02]/10 text-[#58CC02] rounded-2xl flex items-center justify-center text-3xl shadow-inner">
            <?= $isEdit ? '✏️' : '🍔' ?>
        </div>
        <div>
            <h2 class="text-lg font-extrabold text-[#4B4B4B]"><?= $isEdit ? 'Perbarui Informasi Menu' : 'Informasi Menu Baru' ?></h2>
            <p class="text-sm text-[#777] font-medium">Pastikan semua kolom terisi dengan benar.</p>
        </div>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2 flex items-center gap-2">
                    Kode Item
                    <span class="bg-[#1CB0F6]/10 text-[#1CB0F6] px-2 py-0.5 rounded-full text-[8px]">Opsional</span>
                </label>
                <input type="text" name="kode_item" value="<?= $isEdit ? esc($menu['kode_item'] ?? '') : '' ?>" placeholder="Contoh: KSA-01" class="w-full px-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-[#4B4B4B] font-bold uppercase transition-all shadow-sm">
            </div>
            <div>
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Nama Menu</label>
                <input type="text" name="nama_item" value="<?= $isEdit ? esc($menu['nama_item']) : '' ?>" placeholder="Contoh: Kopi Susu Aren" required class="w-full px-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-[#4B4B4B] font-bold transition-all shadow-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Kategori</label>
                <div class="relative">
                    <select name="kategori" required class="w-full px-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-[#4B4B4B] font-bold appearance-none transition-all shadow-sm">
                        <option value="makanan" <?= ($isEdit && $menu['kategori'] == 'makanan') ? 'selected' : '' ?>>Makanan Utama</option>
                        <option value="minuman" <?= ($isEdit && $menu['kategori'] == 'minuman') ? 'selected' : '' ?>>Minuman</option>
                        <option value="snack" <?= ($isEdit && $menu['kategori'] == 'snack') ? 'selected' : '' ?>>Snack / Camilan</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-[#AFAFAF]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Harga (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#AFAFAF] font-bold">
                        Rp
                    </div>
                    <input type="number" name="harga" value="<?= $isEdit ? esc($menu['harga']) : '' ?>" placeholder="15000" required class="w-full pl-12 pr-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-[#4B4B4B] font-bold transition-all shadow-sm">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Stok</label>
                <div class="relative">
                    <input type="number" name="stok" value="<?= $isEdit ? esc($menu['stok']) : '0' ?>" placeholder="0" required class="w-full px-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-[#4B4B4B] font-bold transition-all shadow-sm">
                </div>
            </div>
        </div>

        <div class="bg-[#f7f7f7] p-6 rounded-2xl border border-[#E5E5E5] border-dashed">
            <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-4">Upload Visual / Foto</label>
            
            <?php if($isEdit && !empty($menu['foto'])): ?>
                <div class="flex items-center gap-4 mb-4 bg-white p-3 rounded-xl shadow-sm border border-[#E5E5E5]">
                    <img src="<?= base_url('uploads/menu/' . $menu['foto']) ?>" class="w-16 h-16 rounded-lg object-cover">
                    <div>
                        <p class="text-xs font-bold text-[#4B4B4B]">Foto Saat Ini</p>
                        <p class="text-[10px] text-[#777]">Biarkan kosong jika tidak ingin mengubah foto.</p>
                    </div>
                </div>
            <?php endif; ?>

            <input type="file" name="foto" accept="image/*" <?= !$isEdit ? 'required' : '' ?> class="w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:uppercase file:tracking-wider file:bg-white file:text-[#58CC02] hover:file:bg-[#58CC02]/10 hover:file:text-[#4BB200] cursor-pointer file:shadow-sm file:border file:border-[#E5E5E5] transition-all">
        </div>

        <div class="pt-6 flex flex-col-reverse sm:flex-row gap-4 border-t border-[#E5E5E5]">
            <a href="<?= base_url('admin/menu') ?>" class="w-full sm:w-1/3 bg-white border border-[#E5E5E5] text-gray-600 font-bold py-4 rounded-xl text-center hover:bg-[#f7f7f7] transition-colors shadow-sm">Batal</a>
            <button type="submit" class="w-full sm:w-2/3 bg-gradient-to-r from-[#58CC02] to-[#4BB200] text-white font-extrabold py-4 rounded-xl shadow-none shadow-[#58CC02]/25 hover:shadow-none hover:shadow-[#58CC02]/30 active:scale-95 transition-all flex justify-center items-center gap-2 group">
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