<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 animate-fade-in-up">
    <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
        <span class="bg-purple-100 text-purple-600 p-2 rounded-xl shadow-inner">🎁</span> 
        Penukaran Poin Reward
    </h1>
    <p class="text-[#777] font-medium mt-1 text-sm">Bantu pelanggan menukarkan poin loyalitas mereka dengan hadiah menarik.</p>
</div>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-gradient-to-r from-green-50 to-green-100 text-green-700 p-5 rounded-2xl mb-8 font-bold border border-green-200 shadow-sm flex items-center justify-between animate-fade-in-up">
        <div class="flex items-center gap-3">
            <div class="text-2xl drop-shadow-sm">🎁</div>
            <?= session()->getFlashdata('sukses') ?>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700 p-2">✖</button>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="bg-gradient-to-r from-red-50 to-red-100 text-red-700 p-5 rounded-2xl mb-8 font-bold border border-red-200 shadow-sm flex items-center justify-between animate-fade-in-up">
        <div class="flex items-center gap-3">
            <div class="text-2xl drop-shadow-sm">❌</div>
            <?= session()->getFlashdata('error') ?>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-[#FF4B4B] hover:text-red-700 p-2">✖</button>
    </div>
<?php endif; ?>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-[#E5E5E5] mb-8 animate-fade-in-up relative overflow-hidden">
    <!-- Abstract shape -->
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-purple-50 rounded-full blur-3xl pointer-events-none"></div>
    
    <form action="" method="GET" class="flex flex-col md:flex-row gap-4 relative z-10">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#AFAFAF]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input type="text" name="q" value="<?= esc($keyword) ?>" placeholder="Cari berdasarkan nama pelanggan..." class="w-full pl-12 pr-4 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition-all font-bold text-[#4B4B4B] shadow-sm">
        </div>
        <button type="submit" class="bg-gray-800 text-white font-extrabold px-8 py-4 rounded-xl shadow-none shadow-gray-800/20 hover:bg-gray-900 active:scale-95 transition-all text-sm uppercase tracking-wider">
            Cari Pelanggan
        </button>
        <?php if($keyword): ?>
            <a href="<?= base_url('admin/reward') ?>" class="bg-white border border-[#E5E5E5] text-gray-600 font-extrabold px-8 py-4 rounded-xl hover:bg-[#f7f7f7] active:scale-95 transition-all text-sm uppercase tracking-wider shadow-sm flex items-center justify-center">
                Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Desktop Table -->
<div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden animate-fade-in-up hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f7f7f7]/80 text-[#AFAFAF] text-[10px] uppercase font-extrabold tracking-widest border-b border-[#E5E5E5]">
                    <th class="px-6 py-5">Nama Pelanggan</th>
                    <th class="px-6 py-5">Saldo Poin</th>
                    <th class="px-6 py-5">Aksi Tukar Reward</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($pelanggan)): ?>
                    <tr>
                        <td colspan="3" class="px-8 py-16 text-center">
                            <div class="w-16 h-16 bg-[#f7f7f7] rounded-full flex items-center justify-center mx-auto mb-3 text-2xl shadow-inner">🔍</div>
                            <p class="text-[#AFAFAF] font-bold">Pelanggan tidak ditemukan.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($pelanggan as $p): ?>
                    <tr class="border-b border-gray-50 hover:bg-purple-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <p class="font-extrabold text-[#4B4B4B] text-lg tracking-tight"><?= esc($p['nama_pelanggan']) ?></p>
                            <p class="text-[10px] text-[#AFAFAF] font-mono font-bold uppercase tracking-wider mt-0.5">ID: <?= $p['id_pelanggan'] ?></p>
                        </td>
                        <td class="px-6 py-5">
                            <div class="inline-block bg-[#58CC02]/10 px-4 py-2 rounded-xl border border-[#58CC02]/20 shadow-sm">
                                <span class="text-2xl font-extrabold text-[#58CC02] drop-shadow-sm"><?= esc($p['poin_loyalitas']) ?></span> 
                                <span class="text-xs text-[#58CC02] font-bold tracking-widest uppercase">pts</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <form action="<?= base_url('admin/reward/proses') ?>" method="POST" class="flex flex-col sm:flex-row gap-3 items-center" onsubmit="return confirm('Proses penukaran poin ini? Pastikan reward diberikan kepada pelanggan.');">
                                <input type="hidden" name="id_pelanggan" value="<?= $p['id_pelanggan'] ?>">
                                
                                <div class="relative w-full sm:flex-1">
                                    <select name="katalog_reward" id="katalog_reward_<?= $p['id_pelanggan'] ?>" onchange="updateForm(this, <?= $p['id_pelanggan'] ?>)" required class="w-full px-4 py-3.5 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none text-sm font-bold text-gray-700 appearance-none transition-all shadow-sm cursor-pointer">
                                        <option value="" data-poin="0">-- Pilih Hadiah Tersedia --</option>
                                        <?php if(isset($katalog)): foreach($katalog as $k): ?>
                                            <option value="<?= esc($k['nama_reward']) ?>" data-poin="<?= $k['poin_dibutuhkan'] ?>">
                                                <?= esc($k['ikon']) ?> <?= number_format($k['poin_dibutuhkan'], 0, ',', '.') ?> Poin - <?= esc($k['nama_reward']) ?>
                                            </option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-[#AFAFAF]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>

                                <input type="hidden" name="nama_reward" id="nama_reward_<?= $p['id_pelanggan'] ?>" value="">
                                <input type="hidden" name="poin_dibutuhkan" id="poin_dibutuhkan_<?= $p['id_pelanggan'] ?>" value="0">

                                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-purple-500 to-purple-600 text-white font-extrabold px-6 py-3.5 rounded-xl hover:from-purple-600 hover:to-purple-700 shadow-none shadow-purple-500/30 active:scale-95 transition-all text-sm flex items-center justify-center gap-2">
                                    Tukar
                                </button>
                            </form>
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
    <?php if(empty($pelanggan)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-[#E5E5E5] shadow-sm">
            <div class="text-3xl mb-3">🔍</div>
            <p class="text-[#AFAFAF] font-bold text-sm">Pelanggan tidak ditemukan.</p>
        </div>
    <?php else: ?>
        <?php foreach($pelanggan as $p): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden">
            <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <p class="font-extrabold text-[#4B4B4B]"><?= esc($p['nama_pelanggan']) ?></p>
                    <p class="text-[10px] text-[#AFAFAF] font-mono mt-0.5">ID: <?= $p['id_pelanggan'] ?></p>
                </div>
                <div class="bg-[#58CC02]/10 px-3 py-1.5 rounded-xl border border-[#58CC02]/20 shadow-sm">
                    <span class="text-xl font-extrabold text-[#58CC02]"><?= esc($p['poin_loyalitas']) ?></span>
                    <span class="text-xs text-[#58CC02] font-bold"> pts</span>
                </div>
            </div>
            <div class="p-4">
                <form action="<?= base_url('admin/reward/proses') ?>" method="POST" class="flex flex-col gap-3" onsubmit="return confirm('Proses penukaran poin ini?');">
                    <input type="hidden" name="id_pelanggan" value="<?= $p['id_pelanggan'] ?>">
                    <div class="relative">
                        <select name="katalog_reward" id="katalog_reward_m_<?= $p['id_pelanggan'] ?>" onchange="updateForm(this, '<?= $p['id_pelanggan'] ?>')" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-purple-500 outline-none text-sm font-bold text-gray-700 appearance-none cursor-pointer">
                            <option value="" data-poin="0">-- Pilih Hadiah --</option>
                            <?php if(isset($katalog)): foreach($katalog as $k): ?>
                                <option value="<?= esc($k['nama_reward']) ?>" data-poin="<?= $k['poin_dibutuhkan'] ?>">
                                    <?= esc($k['ikon']) ?> <?= number_format($k['poin_dibutuhkan'], 0, ',', '.') ?> pts - <?= esc($k['nama_reward']) ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-[#AFAFAF]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                    <input type="hidden" name="nama_reward" id="nama_reward_<?= $p['id_pelanggan'] ?>" value="">
                    <input type="hidden" name="poin_dibutuhkan" id="poin_dibutuhkan_<?= $p['id_pelanggan'] ?>" value="0">
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white font-extrabold py-3 rounded-xl shadow-md active:scale-95 transition-all text-sm flex items-center justify-center gap-2">
                        Tukar Poin
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    function updateForm(selectElement, id) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        document.getElementById('nama_reward_' + id).value = selectedOption.value;
        document.getElementById('poin_dibutuhkan_' + id).value = selectedOption.getAttribute('data-poin');
    }
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