<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-blue-100 text-blue-600 p-2 rounded-xl shadow-inner">🎯</span> 
            Manajemen Misi Gamifikasi
        </h1>
        <p class="text-gray-500 font-medium mt-2">Buat dan kelola tantangan berhadiah poin untuk pelanggan.</p>
    </div>
    <button onclick="document.getElementById('modal-misi').classList.remove('hidden')" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3.5 rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        Tambah Misi Baru
    </button>
</div>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200 animate-fade-in-up">
        <?= session()->getFlashdata('sukses') ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-400 text-xs uppercase font-black border-b border-gray-100">
                    <th class="px-6 py-4">Nama & Deskripsi Misi</th>
                    <th class="px-6 py-4">Tipe Misi</th>
                    <th class="px-6 py-4">Target Jumlah</th>
                    <th class="px-6 py-4">Reward Poin</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($misi)): ?>
                    <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 font-bold">Belum ada data misi.</td></tr>
                <?php else: ?>
                    <?php foreach($misi as $m): ?>
                    <tr class="border-b border-gray-50 hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-black text-gray-800 text-base"><?= esc($m['nama_misi']) ?></p>
                            <p class="text-xs text-gray-500 mt-1"><?= esc($m['deskripsi']) ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase shadow-sm">
                                <?= esc($m['tipe_misi']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800"><?= number_format($m['target_jumlah'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-orange-600 font-black">+<?= $m['poin_reward'] ?> Poin</td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= base_url('admin/misi/hapus/' . $m['id_misi']) ?>" onclick="return confirm('Hapus misi ini? Progress pelanggan untuk misi ini akan ikut terhapus.')" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Misi -->
<div id="modal-misi" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl p-6 relative animate-fade-in-up">
        <button onclick="document.getElementById('modal-misi').classList.add('hidden')" class="absolute right-6 top-6 text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
        
        <h2 class="text-2xl font-black text-gray-800 mb-6 flex items-center gap-2">
            <span class="text-blue-500">🎯</span> Tambah Misi Gamifikasi
        </h2>
        
        <form action="<?= base_url('admin/misi/simpan') ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Nama Misi</label>
                <input type="text" name="nama_misi" required placeholder="Contoh: Coffee Lover" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-gray-800 transition-all">
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Deskripsi</label>
                <textarea name="deskripsi" required rows="2" placeholder="Contoh: Beli 5 minuman bulan ini." class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-gray-800 transition-all"></textarea>
            </div>
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Tipe Misi (Cara Mencapai Target)</label>
                <select name="tipe_misi" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-gray-800 transition-all">
                    <option value="transaksi">Berdasarkan Total Kunjungan / Transaksi</option>
                    <option value="item_minuman">Berdasarkan Jumlah Minuman Dibeli</option>
                    <option value="nominal_belanja">Berdasarkan Nominal Total Belanja (Rp)</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase mb-2">Target Angka</label>
                    <input type="number" name="target_jumlah" required placeholder="10" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 outline-none font-bold text-gray-800 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase mb-2">Reward Poin</label>
                    <input type="number" name="poin_reward" required placeholder="50" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-blue-500 outline-none font-bold text-gray-800 transition-all">
                </div>
            </div>
            
            <button type="submit" class="w-full mt-4 bg-gray-800 text-white font-black py-4 rounded-xl shadow-lg hover:bg-gray-900 active:scale-95 transition-all">
                Simpan Misi
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
