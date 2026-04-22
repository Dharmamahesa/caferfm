<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 pb-20">

    <div class="bg-white p-4 flex items-center justify-between shadow-sm sticky top-0 z-40">
        <div class="flex items-center">
            <a href="<?= base_url('/') ?>" class="text-orange-600 font-bold mr-4 text-xl">←</a>
            <h1 class="text-xl font-bold tracking-tight text-gray-800">Profil Saya</h1>
        </div>
        <a href="<?= base_url('auth/logout') ?>" class="text-sm font-semibold text-red-500 hover:underline">Keluar</a>
    </div>

    <div class="max-w-md mx-auto p-4 space-y-6 mt-2">
        
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl"></div>
            
            <p class="text-gray-400 text-sm font-medium">Hai,</p>
            <h2 class="text-2xl font-black mb-6 tracking-tight"><?= esc($user['nama_pelanggan']) ?></h2>

            <div class="flex justify-between items-end">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-1">Total Poin Loyalitas</p>
                    <p class="text-5xl font-black text-orange-400"><?= esc($user['poin_loyalitas']) ?> <span class="text-lg text-gray-300 font-medium">pts</span></p>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20">
                    <p class="text-sm font-bold text-yellow-400 uppercase tracking-widest"><?= esc($tier) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-800">Progress ke <?= esc($nextTier) ?></h3>
                <p class="text-sm font-bold text-orange-600"><?= esc($user['poin_loyalitas']) ?> / <?= esc($poinNextTier) ?></p>
            </div>
            
            <div class="w-full bg-gray-100 rounded-full h-4 mb-4 overflow-hidden shadow-inner">
                <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-4 rounded-full transition-all duration-1000 ease-out relative" style="width: <?= $progress ?>%">
                    <div class="absolute top-0 left-0 right-0 bottom-0 bg-white opacity-20 w-full h-1/2 rounded-t-full"></div>
                </div>
            </div>
            
            <p class="text-sm text-gray-500 text-center">
                Kumpulkan <span class="font-bold text-gray-800"><?= $poinNextTier - $user['poin_loyalitas'] ?> poin lagi</span> untuk naik level dan dapatkan reward spesial!
            </p>
        </div>

        <a href="<?= base_url('/') ?>" class="block w-full bg-orange-50 text-orange-600 font-bold text-center py-4 rounded-2xl border border-orange-200 hover:bg-orange-600 hover:text-white transition-colors active:scale-95 shadow-sm">
            Mulai Pesan Menu
        </a>

    </div>

</body>
</html>