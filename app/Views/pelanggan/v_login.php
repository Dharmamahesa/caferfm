<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Kopi Jaya Lestari</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="<?= base_url('css/duolingo-theme.css') ?>" rel="stylesheet">
</head>
<body class="bg-[#f7f7f7] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-10 rounded-2xl border-2 border-[#E5E5E5] max-w-sm w-full animate-fade-in-up">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#58CC02] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-[0_4px_0_#4BB200]">
                <span class="text-3xl text-white">☕</span>
            </div>
            <h1 class="text-3xl font-black text-[#100F3E] tracking-tight">Toko Kopi<br>Jaya Lestari</h1>
            <p class="text-[#777] mt-2 text-sm font-semibold">Masuk untuk mulai mengumpulkan poin loyalitas.</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="duo-flash-error mb-6">
                <span class="text-lg">⚠️</span>
                <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('sukses')): ?>
            <div class="duo-flash-success mb-6">
                <span class="text-lg">✅</span>
                <span class="font-bold"><?= session()->getFlashdata('sukses') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/proses_login') ?>" method="POST" class="space-y-5">
            <div>
                <label class="block text-[11px] font-extrabold text-[#AFAFAF] mb-2 uppercase tracking-[1.5px] pl-1">Email Anda</label>
                <input type="email" name="email" required placeholder="contoh@email.com" class="duo-input">
            </div>
            
            <div>
                <label class="block text-[11px] font-extrabold text-[#AFAFAF] mb-2 uppercase tracking-[1.5px] pl-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="duo-input">
            </div>

            <button type="submit" class="btn-duo-primary w-full mt-8">
                Masuk
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </form>

        <p class="text-center text-sm font-bold text-[#777] mt-8">
            Belum punya akun? 
            <a href="<?= base_url('auth/register') ?>" class="font-extrabold text-[#1CB0F6] hover:text-[#58CC02] transition-colors">Daftar di sini</a>
        </p>
    </div>

</body>
</html>