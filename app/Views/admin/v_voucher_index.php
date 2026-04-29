<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-orange-100 text-orange-600 p-2 rounded-xl shadow-inner">🎟️</span> 
            Manajemen Voucher Promo
        </h1>
        <p class="text-gray-500 font-medium mt-2">Buat kode voucher diskon global untuk event atau promo khusus.</p>
    </div>
    <button onclick="document.getElementById('modal-voucher').classList.remove('hidden')" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3.5 rounded-xl font-bold shadow-lg shadow-orange-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        Buat Voucher Baru
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

<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-xs uppercase font-black border-b border-gray-100">
                    <th class="px-6 py-4">Nama Voucher</th>
                    <th class="px-6 py-4">Kode Voucher</th>
                    <th class="px-6 py-4">Nilai Diskon</th>
                    <th class="px-6 py-4">Kuota</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($voucher)): ?>
                    <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 font-bold">Belum ada voucher promo.</td></tr>
                <?php else: ?>
                    <?php foreach($voucher as $v): ?>
                    <tr class="border-b border-gray-50 hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-black text-gray-800 text-base"><?= esc($v['nama_voucher']) ?></td>
                        <td class="px-6 py-4">
                            <code class="bg-orange-50 text-orange-600 font-black px-3 py-1.5 rounded-lg border border-orange-100 uppercase tracking-widest shadow-sm"><?= esc($v['kode_voucher']) ?></code>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">
                            <?= $v['tipe_diskon'] == 'nominal' ? 'Rp ' . number_format($v['diskon'], 0, ',', '.') : $v['diskon'] . '%' ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">
                            <?= $v['kuota'] == 0 ? '<span class="text-gray-400">Unlimited</span>' : number_format($v['kuota'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="<?= $v['status'] == 'aktif' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' ?> px-3 py-1 rounded-full text-xs font-bold uppercase border shadow-sm inline-block">
                                <?= esc($v['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= base_url('admin/voucher/hapus/' . $v['id_voucher']) ?>" onclick="return confirm('Hapus voucher promo ini?')" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Voucher -->
<div id="modal-voucher" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl p-6 relative animate-fade-in-up">
        <button onclick="document.getElementById('modal-voucher').classList.add('hidden')" class="absolute right-6 top-6 text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
        
        <h2 class="text-2xl font-black text-gray-800 mb-6 flex items-center gap-2">
            <span class="text-orange-500">🎟️</span> Buat Voucher Promo
        </h2>
        
        <form action="<?= base_url('admin/voucher/simpan') ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Judul/Nama Promo</label>
                <input type="text" name="nama_voucher" required placeholder="Contoh: Promo Akhir Tahun" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none font-bold text-gray-800 transition-all">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Kode Voucher (Unik)</label>
                <input type="text" name="kode_voucher" required placeholder="Contoh: NEWYEAR2026" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none font-black text-gray-800 uppercase transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase mb-2">Tipe Diskon</label>
                    <select name="tipe_diskon" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 outline-none font-bold text-gray-800 transition-all">
                        <option value="nominal">Nominal (Rp)</option>
                        <option value="persen">Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase mb-2">Nilai Diskon</label>
                    <input type="number" name="diskon" required placeholder="Contoh: 15000" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 outline-none font-bold text-gray-800 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Kuota Penggunaan (0 = Unlimited)</label>
                <input type="number" name="kuota" value="0" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-orange-500 outline-none font-bold text-gray-800 transition-all">
            </div>
            
            <button type="submit" class="w-full mt-4 bg-gray-800 text-white font-black py-4 rounded-xl shadow-lg hover:bg-gray-900 active:scale-95 transition-all">
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
</style>

<?= $this->endSection() ?>
