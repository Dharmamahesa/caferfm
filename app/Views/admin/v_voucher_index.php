<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
            <span class="bg-[#58CC02]/10 text-[#58CC02] p-2 rounded-xl shadow-inner">🎟️</span> 
            Manajemen Voucher Promo
        </h1>
        <p class="text-[#777] font-medium mt-1 text-sm">Buat kode voucher diskon global untuk event atau promo khusus.</p>
    </div>
    <button id="btn-open-modal-voucher" class="bg-gradient-to-r from-[#58CC02] to-[#4BB200] text-white px-5 py-3 rounded-xl font-bold shadow-none shadow-[#58CC02]/25 hover:shadow-none hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 self-start sm:self-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        Buat Voucher
    </button>
</div>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200 animate-fade-in-up">
        <?= session()->getFlashdata('sukses') ?>
    </div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 font-bold border border-red-200 animate-fade-in-up">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Desktop Table -->
<div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden animate-fade-in-up hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f7f7f7] text-[#AFAFAF] text-xs uppercase font-extrabold border-b border-[#E5E5E5]">
                    <th class="px-6 py-4">Nama Voucher</th>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Diskon</th>
                    <th class="px-6 py-4">Kuota</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($voucher)): ?>
                    <tr><td colspan="6" class="px-6 py-10 text-center text-[#AFAFAF] font-bold">Belum ada voucher promo.</td></tr>
                <?php else: ?>
                    <?php foreach($voucher as $v): ?>
                    <tr class="border-b border-gray-50 hover:bg-[#f7f7f7] transition-colors">
                        <td class="px-6 py-4 font-extrabold text-[#4B4B4B]"><?= esc($v['nama_voucher']) ?></td>
                        <td class="px-6 py-4">
                            <code class="bg-[#58CC02]/10 text-[#58CC02] font-extrabold px-3 py-1.5 rounded-lg border border-[#58CC02]/20 uppercase tracking-widest shadow-sm text-xs"><?= esc($v['kode_voucher']) ?></code>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#4B4B4B]">
                            <?= $v['tipe_diskon'] == 'nominal' ? 'Rp ' . number_format($v['diskon'], 0, ',', '.') : $v['diskon'] . '%' ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#4B4B4B]">
                            <?= $v['kuota'] == 0 ? '<span class="text-[#AFAFAF]">Unlimited</span>' : number_format($v['kuota'], 0, ',', '.') ?>
                            <?php if(!empty($v['target_id_menu'])): ?>
                                <div class="text-[10px] text-[#1CB0F6] bg-[#1CB0F6]/10 px-2 py-0.5 rounded mt-1 inline-block">Item Khusus</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="<?= $v['status'] == 'aktif' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' ?> px-3 py-1 rounded-full text-xs font-bold uppercase border shadow-sm inline-block">
                                <?= esc($v['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= base_url('admin/voucher/hapus/' . $v['id_voucher']) ?>" onclick="return confirm('Hapus voucher promo ini?')" class="text-[#FF4B4B] hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View -->
<div class="space-y-4 animate-fade-in-up md:hidden">
    <?php if(empty($voucher)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-[#E5E5E5] shadow-sm">
            <div class="text-3xl mb-3">🎟️</div>
            <p class="text-[#AFAFAF] font-bold text-sm">Belum ada voucher promo.</p>
        </div>
    <?php else: ?>
        <?php foreach($voucher as $v): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden">
            <div class="p-4 border-b border-gray-50">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <p class="font-extrabold text-[#4B4B4B]"><?= esc($v['nama_voucher']) ?></p>
                        <code class="bg-[#58CC02]/10 text-[#58CC02] font-extrabold px-2 py-1 rounded-lg border border-[#58CC02]/20 uppercase tracking-widest shadow-sm text-xs mt-1 inline-block"><?= esc($v['kode_voucher']) ?></code>
                    </div>
                    <span class="<?= $v['status'] == 'aktif' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' ?> px-3 py-1 rounded-full text-xs font-bold uppercase border shadow-sm flex-shrink-0">
                        <?= esc($v['status']) ?>
                    </span>
                </div>
            </div>
            <div class="p-4 flex items-center justify-between bg-[#f7f7f7]">
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase">Diskon</p>
                        <p class="font-extrabold text-[#4B4B4B] text-sm"><?= $v['tipe_diskon'] == 'nominal' ? 'Rp ' . number_format($v['diskon'], 0, ',', '.') : $v['diskon'] . '%' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase">Kuota</p>
                        <p class="font-bold text-[#4B4B4B] text-sm"><?= $v['kuota'] == 0 ? 'Unlimited' : number_format($v['kuota'], 0, ',', '.') ?></p>
                    </div>
                </div>
                <a href="<?= base_url('admin/voucher/hapus/' . $v['id_voucher']) ?>" onclick="return confirm('Hapus voucher promo ini?')" class="bg-red-50 text-[#FF4B4B] hover:bg-[#FF4B4B] hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors">Hapus</a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Tambah Voucher -->
<div id="modal-voucher" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4" style="display:none">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-none p-6 relative animate-fade-in-up max-h-[90vh] overflow-y-auto">
        <button id="btn-close-modal-voucher" class="absolute right-5 top-5 text-[#AFAFAF] hover:text-gray-600 font-bold text-xl w-9 h-9 flex items-center justify-center rounded-full hover:bg-[#f7f7f7]">&times;</button>
        
        <h2 class="text-2xl font-extrabold text-[#4B4B4B] mb-6 flex items-center gap-2">
            <span class="text-[#58CC02]">🎟️</span> Buat Voucher Promo
        </h2>
        
        <form action="<?= base_url('admin/voucher/simpan') ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Judul/Nama Promo</label>
                <input type="text" name="nama_voucher" required placeholder="Contoh: Promo Akhir Tahun" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-[#4B4B4B] transition-all">
            </div>
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Kode Voucher (Unik)</label>
                <input type="text" name="kode_voucher" required placeholder="Contoh: NEWYEAR2026" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-extrabold text-[#4B4B4B] uppercase transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Tipe Diskon</label>
                    <select name="tipe_diskon" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] outline-none font-bold text-[#4B4B4B] transition-all">
                        <option value="nominal">Nominal (Rp)</option>
                        <option value="persen">Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Nilai Diskon</label>
                    <input type="number" name="diskon" required placeholder="Contoh: 15000" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] outline-none font-bold text-[#4B4B4B] transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Target Item Khusus (Opsional)</label>
                <select name="target_id_menu" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] outline-none font-bold text-[#4B4B4B] transition-all">
                    <option value="">-- Berlaku untuk Semua Menu --</option>
                    <?php if(!empty($menuList)): ?>
                        <?php foreach($menuList as $m): ?>
                            <option value="<?= $m['id_menu'] ?>"><?= esc($m['kode_item'] ? '['.$m['kode_item'].'] ' : '') . esc($m['nama_item']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="text-[10px] text-[#AFAFAF] mt-1 font-bold">Jika dipilih, diskon HANYA memotong harga item ini.</p>
            </div>
            
            <button type="submit" class="w-full mt-4 bg-gray-800 text-white font-extrabold py-4 rounded-xl shadow-none hover:bg-gray-900 active:scale-95 transition-all">
                Simpan Voucher
            </button>
        </form>
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
    #modal-voucher:not(.hidden) { display: flex !important; }
</style>

<script>
    const btnOpen = document.getElementById('btn-open-modal-voucher');
    const btnClose = document.getElementById('btn-close-modal-voucher');
    const modal = document.getElementById('modal-voucher');
    if(btnOpen) btnOpen.addEventListener('click', () => { modal.classList.remove('hidden'); modal.style.display = 'flex'; });
    if(btnClose) btnClose.addEventListener('click', () => { modal.classList.add('hidden'); modal.style.display = 'none'; });
    if(modal) modal.addEventListener('click', (e) => { if(e.target === modal) { modal.classList.add('hidden'); modal.style.display = 'none'; } });
</script>

<?= $this->endSection() ?>
