<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-blue-100 text-blue-600 p-2 rounded-xl shadow-inner">📱</span> 
            Smart Ordering QR
        </h1>
        <p class="text-gray-500 font-medium mt-2">Cetak QR Code ini dan letakkan di masing-masing meja kafe Anda.</p>
    </div>
    <button onclick="window.print()" class="bg-white border border-gray-200 text-gray-700 px-6 py-3.5 rounded-xl shadow-sm hover:bg-gray-50 active:scale-95 transition-all flex items-center justify-center gap-3 font-bold no-print">
        🖨️ Cetak Semua QR
    </button>
</div>

<!-- Halaman Cetak QR Code -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 animate-fade-in-up" id="qr-container">
    
    <?php 
    // Misal kafe punya 12 meja
    $jumlah_meja = 12; 
    $base_domain = base_url(); // otomatis mendeteksi domain server saat ini

    for($i = 1; $i <= $jumlah_meja; $i++): 
        $url_pesan = $base_domain . '?meja=' . $i;
        // Menggunakan API pihak ketiga yang gratis untuk generate QR Code
        $qr_api_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url_pesan);
    ?>
    
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex flex-col items-center text-center relative break-inside-avoid">
        <!-- Deco -->
        <div class="absolute -top-3 -right-3 w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-black border-4 border-slate-50 shadow-sm z-10">
            <?= $i ?>
        </div>
        
        <h2 class="text-xl font-black text-gray-800 mb-1">Meja <?= $i ?></h2>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Scan untuk Memesan</p>
        
        <div class="w-full aspect-square bg-gray-50 rounded-2xl p-4 border-2 border-dashed border-gray-200 mb-6 flex items-center justify-center">
            <img src="<?= $qr_api_url ?>" alt="QR Meja <?= $i ?>" class="w-full h-full object-contain rounded-lg mix-blend-multiply">
        </div>
        
        <div class="bg-gray-100 px-4 py-2 rounded-xl w-full">
            <p class="text-xs font-mono text-gray-500 truncate" title="<?= $url_pesan ?>">
                <?= $url_pesan ?>
            </p>
        </div>
    </div>

    <?php endfor; ?>

</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #qr-container, #qr-container * {
            visibility: visible;
        }
        #qr-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        .bg-white { box-shadow: none !important; border-color: #000 !important; }
    }
</style>

<?= $this->endSection() ?>
