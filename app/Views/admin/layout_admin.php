<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel - Toko Kopi Jaya Lestari') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="<?= base_url('css/duolingo-theme.css') ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="flex h-screen bg-[#f7f7f7] overflow-hidden text-[#4B4B4B]">

    <!-- Mobile Overlay for Sidebar -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-[#100F3E]/50 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="w-72 bg-[#100F3E] text-white flex flex-col fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50 shadow-2xl lg:shadow-none lg:static">
        
        <!-- Sidebar Header (Brand) -->
        <div class="h-[64px] flex items-center justify-between px-6 border-b border-white/10">
            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-[#58CC02] rounded-xl flex items-center justify-center shadow-[0_3px_0_#4BB200] group-hover:shadow-[0_3px_0_#3d9600] transition-all">
                    <span class="text-xl font-black text-white">K</span>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tight leading-none text-white font-display group-hover:text-[#58CC02] transition-colors">TOKO KOPI</h2>
                    <span class="text-[10px] font-bold tracking-widest text-[#58CC02] uppercase">Jaya Lestari</span>
                </div>
            </a>
            <!-- Close Button for Mobile -->
            <button onclick="toggleSidebar()" class="lg:hidden text-white/50 hover:text-white p-2 rounded-lg hover:bg-white/10 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto no-scrollbar">
            <?php 
                $uri = service('uri')->getSegment(2);
                $adminRole = session()->get('admin_role') ?? 'kasir';
            ?>
            
            <?php if (in_array($adminRole, ['super_admin', 'manajer'])): ?>
            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'dashboard' || empty($uri)) ? 'bg-[#58CC02] text-white shadow-[0_3px_0_#4BB200]' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Dashboard
            </a>
            <?php endif; ?>
            
            <p class="px-4 pt-5 pb-2 text-[10px] font-extrabold text-white/30 uppercase tracking-[2px]">Operasional</p>
            
            <?php if (in_array($adminRole, ['super_admin', 'kasir'])): ?>
            <a href="<?= base_url('admin/kasir') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'kasir') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">💳</span> Kasir
            </a>
            <?php endif; ?>
            
            <a href="<?= base_url('admin/dapur') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'dapur') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">👨‍🍳</span> Dapur (Kitchen)
            </a>
            
            <?php if (in_array($adminRole, ['super_admin', 'manajer'])): ?>
            <a href="<?= base_url('admin/menu') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'menu') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🍔</span> Manajemen Menu
            </a>
            
            <a href="<?= base_url('admin/riwayat') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'riwayat') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🧾</span> Riwayat Transaksi
            </a>

            <a href="<?= base_url('admin/laporan') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'laporan') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">📈</span> Laporan Penjualan
            </a>
            <?php elseif ($adminRole == 'kasir'): ?>
            <a href="<?= base_url('admin/riwayat') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'riwayat') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🧾</span> Riwayat Transaksi
            </a>
            <?php endif; ?>
            
            <?php if (in_array($adminRole, ['super_admin', 'manajer'])): ?>
            <p class="px-4 pt-5 pb-2 text-[10px] font-extrabold text-white/30 uppercase tracking-[2px]">Gamifikasi & CRM</p>
            
            <a href="<?= base_url('admin/rfm') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'rfm') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">📊</span> Analitik RFM
            </a>
            
            <a href="<?= base_url('admin/reward') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'reward') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🎁</span> Tukar Poin Reward
            </a>

            <a href="<?= base_url('admin/katalog_reward') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'katalog_reward') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">💎</span> Master Reward
            </a>

            <a href="<?= base_url('admin/misi') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'misi') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🎯</span> Manajemen Misi
            </a>

            <a href="<?= base_url('admin/voucher') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'voucher') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🎟️</span> Manajemen Voucher
            </a>

            <a href="<?= base_url('admin/spin') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'spin') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🎰</span> Pengaturan Spin
            </a>
            <?php endif; ?>

            <?php if (in_array($adminRole, ['super_admin', 'manajer'])): ?>
            <p class="px-4 pt-5 pb-2 text-[10px] font-extrabold text-white/30 uppercase tracking-[2px]">Sistem & Utility</p>

            <a href="<?= base_url('admin/qr_meja') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'qr_meja') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">📱</span> Smart QR Meja
            </a>

            <a href="<?= base_url('admin/map_meja') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'map_meja') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">🗺️</span> Visual Map Meja
            </a>

            <a href="<?= base_url('admin/pengaturan') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'pengaturan') ? 'bg-white/10 text-white border border-white/5' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">⚙️</span> Pengaturan
            </a>
            <?php endif; ?>

            <?php if ($adminRole === 'super_admin'): ?>
            <a href="<?= base_url('admin/users') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all duration-200 <?= ($uri == 'users') ? 'bg-[#a855f7]/20 text-[#d8b4fe] border border-[#a855f7]/30' : 'text-white/50 hover:bg-white/5 hover:text-white' ?>">
                <span class="text-lg">👥</span> Kelola Admin
            </a>
            <?php endif; ?>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-white/10 bg-black/20">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div class="w-10 h-10 rounded-full bg-[#58CC02] flex items-center justify-center text-white font-black shadow-[0_2px_0_#4BB200]">
                    <?= substr(session()->get('nama_admin') ?? 'A', 0, 1) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-white/40 uppercase font-bold tracking-wider">Admin Aktif</p>
                    <p class="text-sm font-bold text-white truncate"><?= esc(session()->get('nama_admin')) ?></p>
                    <?php
                        $role = session()->get('admin_role') ?? 'kasir';
                        $roleColors = [
                            'super_admin' => 'bg-[#a855f7]/20 text-[#d8b4fe] border-[#a855f7]/40',
                            'manajer'     => 'bg-blue-500/20 text-blue-300 border-blue-500/40',
                            'kasir'       => 'bg-[#58CC02]/20 text-[#86efac] border-[#58CC02]/40',
                            'koki'        => 'bg-orange-500/20 text-orange-300 border-orange-500/40',
                        ];
                        $roleLabels = [
                            'super_admin' => '👑 Super Admin',
                            'manajer'     => '📋 Manajer',
                            'kasir'       => '💳 Kasir',
                            'koki'        => '👨‍🍳 Koki',
                        ];
                        $colorClass = $roleColors[$role] ?? 'bg-white/10 text-white/50 border-white/20';
                        $roleLabel  = $roleLabels[$role] ?? $role;
                    ?>
                    <span class="inline-block mt-1 text-[9px] font-bold px-2 py-0.5 rounded-full border <?= $colorClass ?>">
                        <?= $roleLabel ?>
                    </span>
                </div>
            </div>
            <a href="<?= base_url('admin/logout') ?>" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-[#FF4B4B]/10 text-[#FF4B4B] hover:bg-[#FF4B4B] hover:text-white rounded-xl font-bold transition-all text-xs uppercase tracking-widest border border-[#FF4B4B]/20 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Keluar
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#f7f7f7] relative">
        
        <!-- TOPBAR -->
        <header class="h-[64px] bg-white border-b-2 border-[#E5E5E5] flex items-center justify-between px-6 lg:px-10 z-30 sticky top-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden text-[#4B4B4B] hover:text-[#58CC02] hover:bg-[#58CC02]/10 p-2.5 rounded-xl transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <h1 class="text-xl md:text-2xl font-extrabold text-[#100F3E] tracking-tight hidden sm:block"><?= esc($title ?? 'Dashboard') ?></h1>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Clock / Date indicator -->
                <div class="hidden md:flex items-center gap-2 bg-[#f7f7f7] px-4 py-2 rounded-full border-2 border-[#E5E5E5] text-sm font-bold text-[#777]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#58CC02]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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
