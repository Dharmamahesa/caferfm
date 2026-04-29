<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-orange-100 text-orange-600 p-2 rounded-xl shadow-inner">👨‍🍳</span> 
            Kitchen Display System
        </h1>
        <p class="text-gray-500 font-medium mt-2">Daftar antrean pesanan yang harus segera dimasak.</p>
    </div>
    
    <div class="flex items-center gap-3">
        <!-- Live Status Indicator -->
        <div class="bg-red-50 px-4 py-2 rounded-xl border border-red-100 shadow-sm flex items-center gap-2">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            <span class="text-xs font-black text-red-600 uppercase tracking-widest">Live Sync</span>
        </div>
        
        <!-- Live Clock -->
        <div class="bg-white px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm">
            <p id="jam-sekarang" class="font-mono text-xl font-black text-gray-800"></p>
        </div>
    </div>
</div>

<!-- Meta Refresh via JS for Layout Compatibility -->
<script>
    setTimeout(function() { window.location.reload(); }, 30000);
</script>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-gradient-to-r from-green-50 to-green-100 text-green-700 p-5 rounded-2xl mb-8 font-bold border border-green-200 shadow-sm flex items-center justify-between animate-fade-in-up">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white shadow-inner">✓</div>
            <?= session()->getFlashdata('sukses') ?>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700 p-2">✖</button>
    </div>
<?php endif; ?>

<?php if(empty($pesanan)): ?>
    <div class="bg-white rounded-[2rem] p-16 text-center border border-gray-100 shadow-sm mt-10 flex flex-col items-center justify-center animate-fade-in-up">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 shadow-inner relative">
            <span class="text-5xl block z-10">🍳</span>
        </div>
        <h2 class="text-2xl font-black text-gray-800 mb-2">Dapur Sedang Kosong</h2>
        <p class="text-gray-500 font-medium">Belum ada pesanan masuk. Halaman ini akan otomatis refresh setiap 30 detik.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach($pesanan as $p): ?>
            <div class="bg-white rounded-[2rem] shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-t-8 border-orange-500 overflow-hidden flex flex-col group animate-fade-in-up">
                
                <div class="bg-gradient-to-b from-orange-50 to-white p-6 border-b border-orange-100 flex justify-between items-start relative">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-orange-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <p class="text-xs font-black text-orange-600 uppercase tracking-widest mb-1 shadow-sm px-2 py-0.5 bg-white rounded-lg inline-block">Meja</p>
                        <p class="text-6xl font-black text-gray-800 leading-none tracking-tighter drop-shadow-sm"><?= esc($p['no_meja']) ?></p>
                    </div>
                    <div class="text-right relative z-10">
                        <p class="text-xs text-gray-400 font-mono font-bold mb-2">#<?= $p['id_pesanan'] ?></p>
                        <span class="bg-yellow-50 text-yellow-600 text-[10px] font-black uppercase px-2 py-1.5 rounded-lg border border-yellow-200 shadow-sm animate-pulse flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Pending
                        </span>
                    </div>
                </div>

                <div class="p-6 flex-grow bg-white">
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-dashed border-gray-200">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pemesan</p>
                            <p class="font-bold text-gray-800 text-sm"><?= esc($p['nama_pelanggan']) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Masuk</p>
                            <p class="font-mono text-gray-800 font-bold bg-gray-50 px-2 py-1 rounded-lg border border-gray-100"><?= date('H:i', strtotime($p['tgl_pesanan'])) ?></p>
                        </div>
                    </div>
                    
                    <ul class="space-y-3">
                        <?php foreach($p['detail'] as $item): ?>
                            <li class="flex items-start bg-slate-50 p-3 rounded-xl border border-gray-100 group-hover:bg-white transition-colors">
                                <span class="font-black text-lg text-orange-600 mr-3 bg-orange-100 w-10 h-10 flex items-center justify-center rounded-lg shadow-inner"><?= $item['jumlah'] ?>x</span>
                                <span class="font-bold text-gray-800 leading-tight mt-1.5"><?= esc($item['nama_item']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="p-5 border-t border-gray-50 bg-gray-50/50">
                    <a href="<?= base_url('admin/selesai/' . $p['id_pesanan']) ?>" onclick="return confirm('Selesaikan pesanan Meja <?= $p['no_meja'] ?>?')" class="flex items-center justify-center gap-2 w-full text-center bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black py-4 rounded-xl shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 active:scale-95 transition-all text-lg uppercase tracking-wider group/btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        Selesai
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<script>
    function updateJam() {
        const now = new Date();
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');
        const jamEl = document.getElementById('jam-sekarang');
        if(jamEl) jamEl.innerText = `${jam}:${menit}:${detik}`;
    }
    setInterval(updateJam, 1000);
    updateJam();
</script>

<?= $this->endSection() ?>