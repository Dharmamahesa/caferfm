<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 animate-fade-in-up">
    <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
        <span class="bg-[#f7f7f7] text-gray-600 p-2 rounded-xl shadow-inner">⚙️</span> 
        Pengaturan Sistem Kafe
    </h1>
    <p class="text-[#777] font-medium mt-1 text-sm">Konfigurasi variabel dinamis aplikasi tanpa perlu menyentuh kode program.</p>
</div>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-gradient-to-r from-green-50 to-green-100 text-green-700 p-5 rounded-2xl mb-8 font-bold border border-green-200 shadow-sm flex items-center justify-between animate-fade-in-up">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-inner">✓</div>
            <?= session()->getFlashdata('sukses') ?>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700 p-2">✖</button>
    </div>
<?php endif; ?>

<div class="bg-white p-8 rounded-2xl shadow-sm border border-[#E5E5E5] max-w-3xl animate-fade-in-up">
    <form action="<?= base_url('admin/pengaturan/update') ?>" method="POST" class="space-y-6">
        
        <div class="bg-[#f7f7f7] p-6 rounded-2xl border border-[#E5E5E5]">
            <h3 class="text-lg font-extrabold text-[#4B4B4B] mb-4 flex items-center gap-2">
                <span class="text-[#58CC02]">🏬</span> Profil Usaha
            </h3>
            
            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Nama Toko / Kafe</label>
                    <input type="text" name="nama_toko" value="<?= esc($pengaturan['nama_toko'] ?? 'Toko Kopi Jaya Lestari') ?>" class="w-full px-5 py-4 rounded-xl bg-white border border-[#E5E5E5] focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-[#4B4B4B] font-bold transition-all shadow-sm">
                </div>
                
                <div>
                    <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Pesan Footer Struk (Catatan Tambahan)</label>
                    <textarea name="pesan_struk" rows="3" class="w-full px-5 py-4 rounded-xl bg-white border border-[#E5E5E5] focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-[#4B4B4B] font-bold transition-all shadow-sm"><?= esc($pengaturan['pesan_struk'] ?? 'Terima kasih atas kunjungan Anda!') ?></textarea>
                </div>
            </div>
        </div>

        <div class="bg-[#58CC02]/10/50 p-6 rounded-2xl border border-[#58CC02]/20">
            <h3 class="text-lg font-extrabold text-[#4B4B4B] mb-4 flex items-center gap-2">
                <span class="text-yellow-500">⭐</span> Gamifikasi & Loyalitas
            </h3>
            
            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-extrabold text-[#777] uppercase tracking-widest mb-2">Konversi Poin (Berapa Rupiah untuk dapat 1 Poin?)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#AFAFAF] font-bold">
                            Rp
                        </div>
                        <input type="number" name="poin_per_rp" value="<?= esc($pengaturan['poin_per_rp'] ?? '10000') ?>" class="w-full pl-12 pr-5 py-4 rounded-xl bg-white border border-[#E5E5E5] focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-[#4B4B4B] font-extrabold transition-all shadow-sm">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-[10px] font-bold text-[#AFAFAF] uppercase tracking-widest">
                            = 1 Poin
                        </div>
                    </div>
                    <p class="text-xs text-[#58CC02] font-medium mt-2">Contoh: Jika diatur Rp 10.000, maka belanja Rp 50.000 akan mendapat 5 poin loyalitas.</p>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-[#E5E5E5]">
            <button type="submit" class="w-full bg-gray-800 text-white font-extrabold py-4 rounded-xl shadow-none hover:bg-gray-900 active:scale-95 transition-all flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
