<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Kafe Gamified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 py-10">

    <div class="bg-white p-8 rounded-3xl shadow-lg max-w-md w-full border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Buat Akun</h1>
            <p class="text-gray-500 mt-2 text-sm">Daftar sekarang dan nikmati *reward* spesial!</p>
        </div>

        <form action="<?= base_url('auth/proses_register') ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_pelanggan" required placeholder="Sesuai KTP / Panggilan" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required placeholder="contoh@email.com" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp</label>
                <input type="text" name="no_telp" placeholder="0812xxxxxx" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-200 outline-none transition-all">
            </div>

            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-200 hover:bg-orange-700 active:scale-95 transition-all mt-6">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun? 
            <a href="<?= base_url('auth/login') ?>" class="font-bold text-orange-600 hover:underline">Masuk di sini</a>
        </p>
    </div>

</body>
</html>