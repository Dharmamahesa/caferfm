<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kafe Gamified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="<?= base_url('css/duolingo-theme.css') ?>" rel="stylesheet">
</head>
<body class="bg-[#100F3E] min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Ambient Glows -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#58CC02]/15 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#1CB0F6]/15 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="bg-white/6 backdrop-blur-2xl p-10 rounded-2xl border-2 border-white/8 max-w-sm w-full relative z-10 animate-fade-in-up">
        
        <!-- Logo / Icon -->
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-[#58CC02] rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-[0_4px_0_#4BB200]">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h1 class="text-3xl font-black text-white tracking-widest uppercase font-display">Admin Area</h1>
            <p class="text-white/40 mt-2 text-xs font-bold tracking-widest uppercase">Akses Terbatas Karyawan</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-[#FF4B4B]/15 text-[#FF4B4B] p-4 rounded-xl text-sm mb-6 border-2 border-[#FF4B4B]/20 flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('sukses')): ?>
            <div class="bg-[#58CC02]/15 text-[#58CC02] p-4 rounded-xl text-sm mb-6 border-2 border-[#58CC02]/20 flex items-center gap-3">
                <span class="text-xl">✅</span>
                <span class="font-bold"><?= session()->getFlashdata('sukses') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/proses_login') ?>" method="POST" class="space-y-5">
            <div>
                <label class="block text-[11px] font-extrabold text-white/40 mb-2 uppercase tracking-[1.5px] pl-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" name="username" required autocomplete="off" placeholder="Masukkan username" class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/6 border-2 border-white/10 text-white placeholder-white/30 focus:bg-white/10 focus:border-[#58CC02] focus:ring-2 focus:ring-[#58CC02]/30 outline-none transition-all font-bold">
                </div>
            </div>
            
            <div>
                <label class="block text-[11px] font-extrabold text-white/40 mb-2 uppercase tracking-[1.5px] pl-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full pl-12 pr-4 py-4 rounded-xl bg-white/6 border-2 border-white/10 text-white placeholder-white/30 focus:bg-white/10 focus:border-[#58CC02] focus:ring-2 focus:ring-[#58CC02]/30 outline-none transition-all font-bold">
                </div>
            </div>

            <button type="submit" class="btn-duo-primary w-full mt-8 group">
                Masuk Sistem
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </form>
    </div>

</body>
</html>