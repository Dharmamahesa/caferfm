<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Kafe Gamified') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        /* Desktop layout: two-column for customer pages */
        @media (min-width: 1024px) {
            .customer-page-wrapper {
                display: flex;
                min-height: 100vh;
                background: #f8fafc;
            }
            .customer-sidebar-left {
                width: 340px;
                flex-shrink: 0;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
                background: linear-gradient(160deg, #1f2937 0%, #111827 100%);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2.5rem;
                z-index: 10;
            }
            .customer-main-content {
                flex: 1;
                max-width: 700px;
                margin: 0 auto;
                min-height: 100vh;
                background: #f8fafc;
                box-shadow: 0 0 60px rgba(0,0,0,0.07);
            }
        }
        @media (max-width: 1023px) {
            .customer-sidebar-left { display: none; }
            .customer-main-content { width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body class="bg-slate-100 selection:bg-orange-200 selection:text-orange-900 min-h-screen">

<div class="customer-page-wrapper">

    <!-- Desktop-only Left Panel -->
    <aside class="customer-sidebar-left">
        <div class="text-center text-white">
            <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-orange-500/30">
                <span class="text-4xl">☕</span>
            </div>
            <h2 class="text-3xl font-black tracking-tight mb-2">Kafe</h2>
            <p class="text-xs font-bold tracking-widest uppercase text-orange-400 mb-8">Gamified Experience</p>
            
            <div class="space-y-4 text-left w-full">
                <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-4">
                    <span class="text-2xl">⭐</span>
                    <div>
                        <p class="font-black text-white text-sm">Poin Loyalitas</p>
                        <p class="text-gray-400 text-xs">Kumpulkan & tukar hadiah</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-4">
                    <span class="text-2xl">🎯</span>
                    <div>
                        <p class="font-black text-white text-sm">Misi & Tantangan</p>
                        <p class="text-gray-400 text-xs">Selesaikan & raih reward</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-4">
                    <span class="text-2xl">🎟️</span>
                    <div>
                        <p class="font-black text-white text-sm">Voucher Eksklusif</p>
                        <p class="text-gray-400 text-xs">Diskon spesial untuk member</p>
                    </div>
                </div>
            </div>

            <p class="mt-10 text-xs text-gray-600">© <?= date('Y') ?> Kafe Gamified</p>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="customer-main-content pb-28 bg-white min-h-screen">
        <?= $this->renderSection('content') ?>
    </main>

</div>

</body>
</html>
