<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kafe Gamified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        .bg-mesh {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.2) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0.2) 0, transparent 50%);
        }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Ambient Glows -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-orange-500/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="bg-white/10 backdrop-blur-2xl p-10 rounded-[2.5rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] max-w-sm w-full border border-white/20 relative z-10 animate-fade-in-up">
        
        <!-- Logo / Icon -->
        <div class="text-center mb-10 relative">
            <div class="absolute inset-0 bg-orange-500 blur-2xl opacity-40 rounded-full w-20 h-20 mx-auto"></div>
            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-5 relative z-10 shadow-xl border border-white/20 transform rotate-3">
                <svg class="w-10 h-10 text-white transform -rotate-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h1 class="text-3xl font-black text-white tracking-widest uppercase drop-shadow-md">Admin Area</h1>
            <p class="text-gray-400 mt-2 text-xs font-bold tracking-widest uppercase">Akses Terbatas Karyawan</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 text-red-300 p-4 rounded-xl text-sm mb-6 border border-red-500/30 flex items-center gap-3 backdrop-blur-md">
                <span class="text-xl">⚠️</span>
                <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('sukses')): ?>
            <div class="bg-green-500/20 text-green-300 p-4 rounded-xl text-sm mb-6 border border-green-500/30 flex items-center gap-3 backdrop-blur-md">
                <span class="text-xl">✅</span>
                <span class="font-bold"><?= session()->getFlashdata('sukses') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/proses_login') ?>" method="POST" class="space-y-5">
            <div>
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest pl-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" name="username" required autocomplete="off" placeholder="Masukkan username" class="w-full pl-12 pr-4 py-4 rounded-2xl bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:bg-black/40 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50 outline-none transition-all font-bold backdrop-blur-md">
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest pl-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full pl-12 pr-4 py-4 rounded-2xl bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:bg-black/40 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50 outline-none transition-all font-bold backdrop-blur-md">
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white font-black py-4 rounded-2xl hover:from-orange-600 hover:to-red-700 transition-all mt-8 uppercase tracking-widest shadow-[0_0_20px_rgba(234,88,12,0.4)] hover:shadow-[0_0_30px_rgba(234,88,12,0.6)] active:scale-95 group flex items-center justify-center gap-2">
                Masuk Sistem
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </form>
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