<?= $this->extend('pelanggan/layout_pelanggan') ?>

<?= $this->section('content') ?>

<div class="bg-white/80 backdrop-blur-xl p-5 flex items-center justify-between shadow-sm sticky top-0 z-40 border-b border-gray-100">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('profil') ?>" class="text-gray-600 font-black text-xl bg-gray-100 w-11 h-11 flex items-center justify-center rounded-2xl hover:bg-purple-50 hover:text-purple-600 transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-800">Katalog Reward</h1>
            <p class="text-[10px] uppercase font-bold tracking-widest text-gray-400">Tukar Poin Loyalitas</p>
        </div>
    </div>
</div>

<div class="max-w-md mx-auto p-5 space-y-6 mt-2 animate-fade-in-up">

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-50 text-red-700 p-4 rounded-xl font-bold border border-red-200 text-sm">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Status Poin -->
    <div class="bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 rounded-[2rem] p-7 text-white shadow-[0_10px_40px_-10px_rgba(147,51,234,0.5)] relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-purple-500/30 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <p class="text-purple-200 text-[10px] uppercase tracking-widest font-black mb-1">Poin Tersedia</p>
            <p class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-100 to-white drop-shadow-sm tracking-tighter">
                <?= number_format($user['poin_loyalitas'], 0, ',', '.') ?> <span class="text-lg text-purple-300 font-bold tracking-normal">pts</span>
            </p>
        </div>
    </div>

    <!-- Grid Katalog -->
    <div class="space-y-4">
        <h3 class="font-black text-gray-800 text-sm tracking-tight flex items-center gap-2 mb-2">
            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            Pilih Hadiah Pilihanmu
        </h3>

        <?php if(empty($katalog)): ?>
            <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 text-center border-dashed">
                <span class="text-3xl mb-3 block opacity-50">😢</span>
                <p class="text-xs text-gray-400 font-bold">Katalog reward sedang kosong.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach($katalog as $r): ?>
                <?php $isBisaTukar = $user['poin_loyalitas'] >= $r['poin_dibutuhkan']; ?>
                
                <div class="bg-white rounded-2xl p-5 border <?= $isBisaTukar ? 'border-purple-100 shadow-sm hover:shadow-md hover:border-purple-300' : 'border-gray-100 opacity-60 grayscale' ?> transition-all relative flex flex-col justify-between h-full">
                    <div>
                        <div class="w-12 h-12 <?= $isBisaTukar ? 'bg-purple-50 text-purple-600' : 'bg-gray-100 text-gray-400' ?> rounded-xl flex items-center justify-center text-2xl mb-4 shadow-inner">
                            <?= esc($r['ikon']) ?>
                        </div>
                        <h4 class="font-black text-gray-800 text-base mb-1 leading-tight"><?= esc($r['nama_reward']) ?></h4>
                        <p class="text-[10px] text-gray-500 font-medium mb-4"><?= esc($r['deskripsi']) ?></p>
                    </div>
                    
                    <div class="mt-auto border-t border-gray-50 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Harga</span>
                            <span class="font-black <?= $isBisaTukar ? 'text-orange-500' : 'text-gray-500' ?>"><?= number_format($r['poin_dibutuhkan'], 0, ',', '.') ?> pts</span>
                        </div>
                        
                        <?php if($isBisaTukar): ?>
                            <form action="<?= base_url('tukar_poin/proses') ?>" method="POST" onsubmit="return confirm('Tukar <?= number_format($r['poin_dibutuhkan'], 0, ',', '.') ?> poin dengan <?= esc($r['nama_reward']) ?>?');">
                                <input type="hidden" name="id_reward" value="<?= $r['id_reward'] ?>">
                                <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-black py-2.5 rounded-xl text-sm shadow-md hover:shadow-lg active:scale-95 transition-all">
                                    Tukar Poin
                                </button>
                            </form>
                        <?php else: ?>
                            <button disabled class="w-full bg-gray-100 text-gray-400 font-black py-2.5 rounded-xl text-sm cursor-not-allowed">
                                Poin Kurang
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
