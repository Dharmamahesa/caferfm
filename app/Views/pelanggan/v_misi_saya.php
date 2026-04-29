<?= $this->extend('pelanggan/layout_pelanggan') ?>

<?= $this->section('content') ?>

<div class="bg-white/80 backdrop-blur-xl p-5 flex items-center shadow-sm sticky top-0 z-40 border-b border-gray-100">
    <a href="<?= base_url('profil') ?>" class="text-gray-600 font-black mr-4 text-xl bg-gray-100 w-11 h-11 flex items-center justify-center rounded-2xl hover:bg-orange-50 hover:text-orange-600 transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-black tracking-tight text-gray-800">Misi Reward</h1>
        <p class="text-[10px] uppercase font-bold tracking-widest text-gray-400">Selesaikan & Kumpulkan Poin</p>
    </div>
</div>

<div class="max-w-md mx-auto p-5 mt-2 space-y-5 animate-fade-in-up">

    <?php if(session()->getFlashdata('sukses')): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-2xl border border-green-200 shadow-sm flex items-center gap-3">
            <span class="text-2xl">🎉</span>
            <span class="font-bold text-sm"><?= session()->getFlashdata('sukses') ?></span>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-50 text-red-700 p-4 rounded-2xl border border-red-200 shadow-sm flex items-center gap-3">
            <span class="text-2xl">⚠️</span>
            <span class="font-bold text-sm"><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if(empty($misi)): ?>
        <div class="text-center py-16 bg-white rounded-[2rem] border border-gray-100 shadow-sm">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner text-5xl">🎯</div>
            <h2 class="text-xl font-black text-gray-800 mb-2">Belum Ada Misi</h2>
            <p class="text-gray-500 font-medium mb-8 text-sm">Misi baru akan segera hadir!</p>
        </div>
    <?php else: ?>
        <?php foreach($misi as $m): ?>
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden relative group">
            
            <div class="p-5 flex gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-inner flex-shrink-0 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/20"></div>
                    <span class="text-2xl relative z-10">💎</span>
                </div>
                
                <div class="flex-1">
                    <h3 class="font-black text-gray-800 text-lg leading-tight mb-1"><?= esc($m['nama_misi']) ?></h3>
                    <p class="text-xs text-gray-500 font-medium leading-relaxed mb-3"><?= esc($m['deskripsi']) ?></p>
                    
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Reward</p>
                            <p class="font-black text-orange-600 flex items-center gap-1">
                                <span class="bg-orange-100 px-2 py-0.5 rounded text-sm">+<?= $m['poin_reward'] ?></span> pts
                            </p>
                        </div>
                        
                        <?php if($m['status'] == 'berjalan'): ?>
                            <div class="text-right">
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-black uppercase px-3 py-1.5 rounded-lg border border-gray-200">Berjalan</span>
                            </div>
                        <?php elseif($m['status'] == 'selesai'): ?>
                            <form action="<?= base_url('klaim_misi/' . $m['id_misi']) ?>" method="POST">
                                <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-black uppercase px-4 py-2 rounded-xl shadow-md hover:scale-105 active:scale-95 transition-transform flex items-center gap-1">
                                    Klaim Hadiah
                                </button>
                            </form>
                        <?php elseif($m['status'] == 'diklaim'): ?>
                            <div class="text-right">
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-black uppercase px-3 py-1.5 rounded-lg border border-blue-200">Selesai ✅</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if($m['status'] == 'berjalan'): ?>
                <div class="px-5 pb-5">
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-gray-200">
                        <!-- Progress statis dulu untuk prototype -->
                        <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-full rounded-full w-1/3"></div>
                    </div>
                    <p class="text-[10px] text-right text-gray-400 font-bold mt-1">Selesaikan segera!</p>
                </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
