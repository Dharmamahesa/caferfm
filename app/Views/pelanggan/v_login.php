<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - Kafe Gamified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-3xl shadow-lg max-w-md w-full border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Selamat Datang!</h1>
            <p class="text-gray-500 mt-2 text-sm">Masuk untuk mulai mengumpulkan poin loyalitas.</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-4 border border-red-100 text-center font-medium">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('sukses')): ?>
            <div class="bg-green-50 text-green-600 p-3 rounded-xl text-sm mb-4 border border-green-100 text-center font-medium">
                <?= session()->getFlashdata('sukses') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/proses_login') ?>" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>

            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-200 hover:bg-orange-700 active:scale-95 transition-all mt-4">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Belum punya akun? 
            <a href="<?= base_url('auth/register') ?>" class="font-bold text-orange-600 hover:underline">Daftar di sini</a>
        </p>
    </div>

</body>
</html>