<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel - Kafe Gamified') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Custom Scrollbar for Main Content */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="flex h-screen bg-slate-50 overflow-hidden text-gray-800 selection:bg-orange-200 selection:text-orange-900">

    <!-- Mobile Overlay for Sidebar -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="w-72 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50 shadow-2xl lg:shadow-none lg:static">
        
        <!-- Sidebar Header (Brand) -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-white/10">
            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-orange-500/30 transition-all">
                    <span class="text-xl font-black text-white">K</span>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tight leading-none text-white group-hover:text-orange-400 transition-colors">KAFE</h2>
                    <span class="text-[10px] font-bold tracking-widest text-orange-400 uppercase">Gamified</span>
                </div>
            </a>
            <!-- Close Button for Mobile -->
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white p-2 rounded-lg hover:bg-white/10 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Sidebar Navigation -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto no-scrollbar">
            <?php $uri = service('uri')->getSegment(2); ?>
            
            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all duration-300 <?= ($uri == 'dashboard' || empty($uri)) ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/25 translate-x-1' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Dashboard
            </a>
            
            <p class="px-4 pt-6 pb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">Operasional</p>
            
            <a href="<?= base_url('admin/kasir') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'kasir') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">💳</span> Kasir
            </a>
            
            <a href="<?= base_url('admin/dapur') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'dapur') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">👨‍🍳</span> Dapur (Kitchen)
            </a>
            
            <a href="<?= base_url('admin/menu') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'menu') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🍔</span> Manajemen Menu
            </a>
            
            <a href="<?= base_url('admin/riwayat') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'riwayat') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🧾</span> Riwayat Transaksi
            </a>
            
            <p class="px-4 pt-6 pb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">Gamifikasi & CRM</p>
            
            <a href="<?= base_url('admin/rfm') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'rfm') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">📊</span> Analitik RFM
            </a>
            
            <a href="<?= base_url('admin/reward') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'reward') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🎁</span> Tukar Poin Reward
            </a>

            <a href="<?= base_url('admin/katalog_reward') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'katalog_reward') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">💎</span> Master Reward
            </a>

            <a href="<?= base_url('admin/misi') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'misi') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🎯</span> Manajemen Misi
            </a>

            <a href="<?= base_url('admin/voucher') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'voucher') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🎟️</span> Manajemen Voucher
            </a>

            <p class="px-4 pt-6 pb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">Sistem & Utility</p>

            <a href="<?= base_url('admin/qr_meja') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'qr_meja') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">📱</span> Smart QR Meja
            </a>

            <a href="<?= base_url('admin/pengaturan') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 <?= ($uri == 'pengaturan') ? 'bg-white/10 text-white translate-x-1 border border-white/5' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">⚙️</span> Pengaturan
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-white/10 bg-black/20">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-black shadow-inner">
                    <?= substr(session()->get('nama_admin') ?? 'A', 0, 1) ?>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Admin Aktif</p>
                    <p class="text-sm font-bold text-white truncate max-w-[150px]"><?= esc(session()->get('nama_admin')) ?></p>
                </div>
            </div>
            <a href="<?= base_url('admin/logout') ?>" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-xl font-bold transition-all text-xs uppercase tracking-widest border border-red-500/20 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Keluar
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative">
        
        <!-- TOPBAR -->
        <header class="h-20 bg-white/80 backdrop-blur-xl border-b border-gray-200 flex items-center justify-between px-6 lg:px-10 z-30 sticky top-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-orange-600 hover:bg-orange-50 p-2.5 rounded-xl transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <h1 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight hidden sm:block"><?= esc($title ?? 'Dashboard') ?></h1>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Clock / Date indicator -->
                <div class="hidden md:flex items-center gap-2 bg-slate-100 px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold text-gray-600 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span id="live-clock"><?= date('d M Y') ?></span>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 relative">
            <div class="max-w-7xl mx-auto w-full">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.add('hidden');
            } else {
                overlay.classList.remove('hidden');
            }
        }

        // Simple Live Clock
        setInterval(() => {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            const dateString = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            const clockEl = document.getElementById('live-clock');
            if(clockEl) clockEl.innerText = `${dateString} - ${timeString}`;
        }, 1000);
    </script>
</body>
</html>
