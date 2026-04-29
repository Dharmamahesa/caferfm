<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-purple-100 text-purple-600 p-2 rounded-xl shadow-inner">💎</span> 
            Master Katalog Reward
        </h1>
        <p class="text-gray-500 font-medium mt-2">Kelola daftar item reward yang bisa ditukarkan dengan poin loyalitas.</p>
    </div>
    <button onclick="document.getElementById('modal-reward').classList.remove('hidden')" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3.5 rounded-xl font-bold shadow-lg shadow-purple-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        Tambah Reward Baru
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
                    <th class="px-6 py-4">Ikon & Nama Reward</th>
                    <th class="px-6 py-4">Deskripsi</th>
                    <th class="px-6 py-4">Harga Poin</th>
                    <th class="px-6 py-4">Tipe & Nominal Diskon</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($reward)): ?>
                    <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 font-bold">Belum ada item di katalog reward.</td></tr>
                <?php else: ?>
                    <?php foreach($reward as $r): ?>
                    <tr class="border-b border-gray-50 hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-50 flex items-center justify-center rounded-xl text-xl shadow-inner border border-purple-100">
                                <?= esc($r['ikon']) ?>
                            </div>
                            <span class="font-black text-gray-800 text-base"><?= esc($r['nama_reward']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-medium max-w-xs truncate">
                            <?= esc($r['deskripsi']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-xs font-black shadow-sm flex items-center gap-1 w-max">
                                <?= number_format($r['poin_dibutuhkan'], 0, ',', '.') ?> Pts
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($r['tipe_diskon'] == 'nominal'): ?>
                                <span class="font-bold text-gray-800">Potongan Rp <?= number_format($r['nominal_diskon'], 0, ',', '.') ?></span>
                            <?php elseif($r['tipe_diskon'] == 'persen'): ?>
                                <span class="font-bold text-gray-800">Diskon <?= $r['nominal_diskon'] ?>%</span>
                            <?php else: ?>
                                <span class="font-bold text-gray-800">Gratis Produk</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= base_url('admin/katalog_reward/hapus/' . $r['id_reward']) ?>" onclick="return confirm('Hapus item reward ini dari katalog?')" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm inline-block">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Reward -->
<div id="modal-reward" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl p-6 relative animate-fade-in-up">
        <button onclick="document.getElementById('modal-reward').classList.add('hidden')" class="absolute right-6 top-6 text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
        
        <h2 class="text-2xl font-black text-gray-800 mb-6 flex items-center gap-2">
            <span class="text-purple-500">💎</span> Tambah Item Reward
        </h2>
        
        <form action="<?= base_url('admin/katalog_reward/simpan') ?>" method="POST" class="space-y-4">
            <div class="flex gap-4">
                <div class="w-20">
                    <label class="block text-xs font-black text-gray-500 uppercase mb-2">Ikon</label>
                    <input type="text" name="ikon" placeholder="🎁" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-purple-500 outline-none font-black text-gray-800 text-center transition-all">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-black text-gray-500 uppercase mb-2">Nama Reward</label>
                    <input type="text" name="nama_reward" required placeholder="Contoh: Gratis Kopi Susu" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-purple-500 outline-none font-bold text-gray-800 transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" required rows="2" placeholder="Jelaskan detail reward ini..." class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-purple-500 outline-none font-medium text-gray-800 transition-all"></textarea>
            </div>
            
            <div>
                <label class="block text-xs font-black text-gray-500 uppercase mb-2">Harga (Poin Dibutuhkan)</label>
                <input type="number" name="poin_dibutuhkan" required placeholder="Contoh: 100" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-purple-500 outline-none font-bold text-orange-600 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase mb-2">Tipe Diskon</label>
                    <select name="tipe_diskon" id="tipe_diskon" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-purple-500 outline-none font-bold text-gray-800 transition-all">
                        <option value="produk">Gratis Produk (Tidak Potong Bill)</option>
                        <option value="nominal">Potongan Nominal (Rp)</option>
                        <option value="persen">Potongan Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase mb-2">Nominal / Persentase</label>
                    <input type="number" name="nominal_diskon" id="nominal_diskon" value="0" placeholder="0" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-purple-500 outline-none font-bold text-gray-800 transition-all">
                </div>
            </div>
            
            <button type="submit" class="w-full mt-4 bg-gray-800 text-white font-black py-4 rounded-xl shadow-lg hover:bg-gray-900 active:scale-95 transition-all">
                Simpan ke Katalog
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('tipe_diskon').addEventListener('change', function() {
        const nominalInput = document.getElementById('nominal_diskon');
        if(this.value === 'produk') {
            nominalInput.value = 0;
            nominalInput.setAttribute('readonly', true);
            nominalInput.classList.add('bg-gray-100', 'text-gray-400');
        } else {
            nominalInput.removeAttribute('readonly');
            nominalInput.classList.remove('bg-gray-100', 'text-gray-400');
        }
    });
</script>

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
