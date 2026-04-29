<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kafe Gamified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        .bg-mesh {
            background-color: #fdf4ff;
            background-image: 
                radial-gradient(at 0% 0%, hsla(28,100%,74%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(350,100%,76%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(279,100%,81%,1) 0, transparent 50%);
        }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 bg-white/40 backdrop-blur-[50px] z-0"></div>

    <div class="bg-white/80 backdrop-blur-2xl p-10 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] max-w-sm w-full border border-white relative z-10 animate-fade-in-up">
        
        <div class="text-center mb-8 relative">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-orange-500/30 transform rotate-3">
                <span class="text-3xl text-white transform -rotate-3">☕</span>
            </div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Selamat Datang!</h1>
            <p class="text-gray-500 mt-2 text-sm font-medium">Masuk untuk mulai mengumpulkan poin loyalitas.</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-sm mb-6 border border-red-100 flex items-center gap-3">
                <span class="text-lg">⚠️</span>
                <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('sukses')): ?>
            <div class="bg-green-50 text-green-600 p-4 rounded-2xl text-sm mb-6 border border-green-100 flex items-center gap-3">
                <span class="text-lg">✅</span>
                <span class="font-bold"><?= session()->getFlashdata('sukses') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/proses_login') ?>" method="POST" class="space-y-5">
            <div>
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest pl-1">Email Anda</label>
                <div class="relative">
                    <input type="email" name="email" required placeholder="contoh@email.com" class="w-full px-5 py-4 rounded-2xl bg-white/50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all font-bold text-gray-800 shadow-inner">
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest pl-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-5 py-4 rounded-2xl bg-white/50 border border-gray-200 focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 outline-none transition-all font-bold text-gray-800 shadow-inner">
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-black py-4 rounded-2xl hover:from-orange-600 hover:to-orange-700 transition-all mt-8 shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 active:scale-95 text-sm uppercase tracking-widest group flex items-center justify-center gap-2">
                Masuk
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </form>

        <p class="text-center text-sm font-semibold text-gray-500 mt-8">
            Belum punya akun? 
            <a href="<?= base_url('auth/register') ?>" class="font-black text-orange-600 hover:text-orange-700 hover:underline transition-colors">Daftar di sini</a>
        </p>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

</body>
</html>