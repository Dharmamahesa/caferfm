<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
            <span class="bg-purple-100 text-purple-600 p-2 rounded-xl shadow-inner">💎</span> 
            Master Katalog Reward
        </h1>
        <p class="text-[#777] font-medium mt-1 text-sm">Kelola daftar item reward yang bisa ditukarkan dengan poin loyalitas.</p>
    </div>
    <button id="btn-open-modal-reward" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-5 py-3 rounded-xl font-bold shadow-none shadow-purple-500/30 hover:shadow-none hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 self-start sm:self-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        Tambah Reward
    </button>
</div>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200 animate-fade-in-up">
        <?= session()->getFlashdata('sukses') ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden animate-fade-in-up hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f7f7f7] text-[#AFAFAF] text-xs uppercase font-extrabold border-b border-[#E5E5E5]">
                    <th class="px-6 py-4">Ikon & Nama Reward</th>
                    <th class="px-6 py-4">Deskripsi</th>
                    <th class="px-6 py-4">Harga Poin</th>
                    <th class="px-6 py-4">Tipe & Nominal Diskon</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($reward)): ?>
                    <tr><td colspan="5" class="px-6 py-10 text-center text-[#AFAFAF] font-bold">Belum ada item di katalog reward.</td></tr>
                <?php else: ?>
                    <?php foreach($reward as $r): ?>
                    <tr class="border-b border-gray-50 hover:bg-[#f7f7f7] transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-50 flex items-center justify-center rounded-xl text-xl shadow-inner border border-purple-100">
                                <?= esc($r['ikon']) ?>
                            </div>
                            <span class="font-extrabold text-[#4B4B4B] text-base"><?= esc($r['nama_reward']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-[#777] font-medium max-w-xs truncate">
                            <?= esc($r['deskripsi']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-[#58CC02]/10 text-[#58CC02] px-3 py-1 rounded-full text-xs font-extrabold shadow-sm flex items-center gap-1 w-max">
                                <?= number_format($r['poin_dibutuhkan'], 0, ',', '.') ?> Pts
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($r['tipe_diskon'] == 'nominal'): ?>
                                <span class="font-bold text-[#4B4B4B]">Potongan Rp <?= number_format($r['nominal_diskon'], 0, ',', '.') ?></span>
                            <?php elseif($r['tipe_diskon'] == 'persen'): ?>
                                <span class="font-bold text-[#4B4B4B]">Diskon <?= $r['nominal_diskon'] ?>%</span>
                            <?php else: ?>
                                <span class="font-bold text-[#4B4B4B]">Gratis Produk</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?= base_url('admin/katalog_reward/hapus/' . $r['id_reward']) ?>" onclick="return confirm('Hapus item reward ini dari katalog?')" class="text-[#FF4B4B] hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm inline-block">Hapus</a>
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
    <?php if(empty($reward)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-[#E5E5E5] shadow-sm">
            <div class="text-3xl mb-3">💎</div>
            <p class="text-[#AFAFAF] font-bold text-sm">Belum ada item di katalog reward.</p>
        </div>
    <?php else: ?>
        <?php foreach($reward as $r): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden">
            <div class="p-4 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-purple-50 flex items-center justify-center rounded-xl text-2xl shadow-inner border border-purple-100 flex-shrink-0">
                        <?= esc($r['ikon']) ?>
                    </div>
                    <div>
                        <p class="font-extrabold text-[#4B4B4B]"><?= esc($r['nama_reward']) ?></p>
                        <p class="text-xs text-[#777] mt-0.5"><?= esc($r['deskripsi']) ?></p>
                    </div>
                </div>
            </div>
            <div class="p-4 flex items-center justify-between bg-[#f7f7f7]">
                <div class="flex items-center gap-3">
                    <span class="bg-[#58CC02]/10 text-[#58CC02] px-3 py-1 rounded-full text-xs font-extrabold shadow-sm">
                        <?= number_format($r['poin_dibutuhkan'], 0, ',', '.') ?> Pts
                    </span>
                    <span class="text-gray-600 text-xs font-bold">
                        <?php if($r['tipe_diskon'] == 'nominal'): ?>Rp <?= number_format($r['nominal_diskon'], 0, ',', '.') ?><?php elseif($r['tipe_diskon'] == 'persen'): ?><?= $r['nominal_diskon'] ?>%<?php else: ?>Gratis Produk<?php endif; ?>
                    </span>
                </div>
                <a href="<?= base_url('admin/katalog_reward/hapus/' . $r['id_reward']) ?>" onclick="return confirm('Hapus item reward ini?')" class="bg-red-50 text-[#FF4B4B] hover:bg-[#FF4B4B] hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors">Hapus</a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Tambah Reward -->
<div id="modal-reward" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4" style="display:none">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-none p-6 relative animate-fade-in-up max-h-[90vh] overflow-y-auto">
        <button id="btn-close-modal-reward" class="absolute right-5 top-5 text-[#AFAFAF] hover:text-gray-600 font-bold text-xl w-9 h-9 flex items-center justify-center rounded-full hover:bg-[#f7f7f7]">&times;</button>
        
        <h2 class="text-2xl font-extrabold text-[#4B4B4B] mb-6 flex items-center gap-2">
            <span class="text-purple-500">💎</span> Tambah Item Reward
        </h2>
        
        <form action="<?= base_url('admin/katalog_reward/simpan') ?>" method="POST" class="space-y-4">
            <div class="flex gap-4">
                <div class="w-20">
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Ikon</label>
                    <input type="text" name="ikon" placeholder="🎁" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 outline-none font-extrabold text-[#4B4B4B] text-center transition-all">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Nama Reward</label>
                    <input type="text" name="nama_reward" required placeholder="Contoh: Gratis Kopi Susu" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 outline-none font-bold text-[#4B4B4B] transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" required rows="2" placeholder="Jelaskan detail reward ini..." class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 outline-none font-medium text-[#4B4B4B] transition-all"></textarea>
            </div>
            
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Harga (Poin Dibutuhkan)</label>
                <input type="number" name="poin_dibutuhkan" required placeholder="Contoh: 100" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 outline-none font-bold text-[#58CC02] transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Tipe Diskon</label>
                    <select name="tipe_diskon" id="tipe_diskon" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 outline-none font-bold text-[#4B4B4B] transition-all">
                        <option value="produk">Gratis Produk (Tidak Potong Bill)</option>
                        <option value="nominal">Potongan Nominal (Rp)</option>
                        <option value="persen">Potongan Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Nominal / Persentase</label>
                    <input type="number" name="nominal_diskon" id="nominal_diskon" value="0" placeholder="0" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 outline-none font-bold text-[#4B4B4B] transition-all">
                </div>
            </div>
            
            <button type="submit" class="w-full mt-4 bg-gray-800 text-white font-extrabold py-4 rounded-xl shadow-none hover:bg-gray-900 active:scale-95 transition-all">
                Simpan ke Katalog
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
    #modal-reward:not(.hidden) { display: flex !important; }
</style>

<script>
    const btnOpenReward = document.getElementById('btn-open-modal-reward');
    const btnCloseReward = document.getElementById('btn-close-modal-reward');
    const modalReward = document.getElementById('modal-reward');
    if(btnOpenReward) btnOpenReward.addEventListener('click', () => { modalReward.classList.remove('hidden'); modalReward.style.display = 'flex'; });
    if(btnCloseReward) btnCloseReward.addEventListener('click', () => { modalReward.classList.add('hidden'); modalReward.style.display = 'none'; });
    if(modalReward) modalReward.addEventListener('click', (e) => { if(e.target === modalReward) { modalReward.classList.add('hidden'); modalReward.style.display = 'none'; } });

    document.getElementById('tipe_diskon').addEventListener('change', function() {
        const nominalInput = document.getElementById('nominal_diskon');
        if(this.value === 'produk') {
            nominalInput.value = 0;
            nominalInput.setAttribute('readonly', true);
            nominalInput.classList.add('bg-[#f7f7f7]', 'text-[#AFAFAF]');
        } else {
            nominalInput.removeAttribute('readonly');
            nominalInput.classList.remove('bg-[#f7f7f7]', 'text-[#AFAFAF]');
        }
    });
</script>

<?= $this->endSection() ?>
