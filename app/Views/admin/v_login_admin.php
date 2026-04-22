<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kafe Gamified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl max-w-sm w-full border border-gray-700">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h1 class="text-2xl font-black text-white tracking-widest uppercase">Admin Panel</h1>
            <p class="text-gray-400 mt-1 text-sm">Akses Khusus Karyawan</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-900/50 text-red-400 p-3 rounded-lg text-sm mb-5 border border-red-800 text-center font-bold">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('sukses')): ?>
            <div class="bg-green-900/50 text-green-400 p-3 rounded-lg text-sm mb-5 border border-green-800 text-center font-bold">
                <?= session()->getFlashdata('sukses') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/proses_login') ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Username</label>
                <input type="text" name="username" required autocomplete="off" class="w-full px-4 py-3 rounded-xl bg-gray-900 border border-gray-700 text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition-all">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-gray-900 border border-gray-700 text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition-all">
            </div>

            <button type="submit" class="w-full bg-orange-600 text-white font-black py-3 rounded-xl hover:bg-orange-700 transition-all mt-6 uppercase tracking-widest shadow-[0_0_15px_rgba(234,88,12,0.4)]">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>