<?php $this->extend('admin/layout_admin'); ?>
<?php $this->section('content'); ?>

<div class="space-y-6">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('sukses')): ?>
    <div class="bg-green-50 border-2 border-green-200 text-green-800 rounded-2xl px-5 py-4 flex items-center gap-3 font-bold">
        <span class="text-2xl">✅</span> <?= session()->getFlashdata('sukses') ?>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border-2 border-red-200 text-red-800 rounded-2xl px-5 py-4 flex items-center gap-3 font-bold">
        <span class="text-2xl">❌</span> <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-[#100F3E]">👥 Kelola Akun Admin</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola seluruh akun admin dan hak akses mereka.</p>
        </div>
        <a href="<?= base_url('admin/users/tambah') ?>" 
           class="inline-flex items-center gap-2 px-5 py-3 bg-[#58CC02] text-white font-bold rounded-2xl shadow-[0_4px_0_#4BB200] hover:shadow-[0_2px_0_#4BB200] hover:translate-y-0.5 transition-all text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Admin Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <?php
        $roleCounts = ['super_admin' => 0, 'manajer' => 0, 'kasir' => 0, 'koki' => 0];
        foreach ($listAdmin as $a) {
            if (isset($roleCounts[$a['role']])) $roleCounts[$a['role']]++;
        }
        $totalAktif = count(array_filter($listAdmin, fn($a) => $a['is_active'] == 1));
    ?>
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white border-2 border-gray-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-[#100F3E]"><?= count($listAdmin) ?></p>
            <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-wide">Total Akun</p>
        </div>
        <div class="bg-purple-50 border-2 border-purple-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-purple-600"><?= $roleCounts['super_admin'] ?></p>
            <p class="text-xs font-bold text-purple-400 mt-1 uppercase tracking-wide">Super Admin</p>
        </div>
        <div class="bg-blue-50 border-2 border-blue-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-blue-600"><?= $roleCounts['manajer'] ?></p>
            <p class="text-xs font-bold text-blue-400 mt-1 uppercase tracking-wide">Manajer</p>
        </div>
        <div class="bg-green-50 border-2 border-green-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-green-600"><?= $roleCounts['kasir'] ?></p>
            <p class="text-xs font-bold text-green-400 mt-1 uppercase tracking-wide">Kasir</p>
        </div>
        <div class="bg-orange-50 border-2 border-orange-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-orange-600"><?= $roleCounts['koki'] ?></p>
            <p class="text-xs font-bold text-orange-400 mt-1 uppercase tracking-wide">Koki</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border-2 border-gray-100 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-100 bg-gray-50">
                        <th class="text-left px-6 py-4 font-black text-[#100F3E] uppercase text-xs tracking-wider">#</th>
                        <th class="text-left px-6 py-4 font-black text-[#100F3E] uppercase text-xs tracking-wider">Nama Admin</th>
                        <th class="text-left px-6 py-4 font-black text-[#100F3E] uppercase text-xs tracking-wider">Username</th>
                        <th class="text-left px-6 py-4 font-black text-[#100F3E] uppercase text-xs tracking-wider">Email</th>
                        <th class="text-left px-6 py-4 font-black text-[#100F3E] uppercase text-xs tracking-wider">Role</th>
                        <th class="text-center px-6 py-4 font-black text-[#100F3E] uppercase text-xs tracking-wider">Status</th>
                        <th class="text-center px-6 py-4 font-black text-[#100F3E] uppercase text-xs tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($listAdmin)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-16 text-gray-400">
                            <span class="text-5xl block mb-3">👤</span>
                            <p class="font-bold">Belum ada akun admin lain.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php $no = 1; foreach ($listAdmin as $admin): ?>
                    <?php
                        $roleConfig = [
                            'super_admin' => ['label' => '👑 Super Admin', 'class' => 'bg-purple-100 text-purple-700 border-purple-200'],
                            'manajer'     => ['label' => '📋 Manajer',     'class' => 'bg-blue-100 text-blue-700 border-blue-200'],
                            'kasir'       => ['label' => '💳 Kasir',       'class' => 'bg-green-100 text-green-700 border-green-200'],
                            'koki'        => ['label' => '👨‍🍳 Koki',        'class' => 'bg-orange-100 text-orange-700 border-orange-200'],
                        ];
                        $rc = $roleConfig[$admin['role']] ?? ['label' => $admin['role'], 'class' => 'bg-gray-100 text-gray-700 border-gray-200'];
                        $isSelf = ($admin['id_admin'] == session()->get('id_admin'));
                    ?>
                    <tr class="hover:bg-gray-50/50 transition-colors <?= $isSelf ? 'bg-green-50/30' : '' ?>">
                        <td class="px-6 py-4 text-gray-400 font-bold"><?= $no++ ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#58CC02] to-[#1cb0f6] flex items-center justify-center text-white font-black text-sm flex-shrink-0">
                                    <?= strtoupper(substr($admin['nama_admin'], 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="font-bold text-[#100F3E]"><?= esc($admin['nama_admin']) ?></p>
                                    <?php if ($isSelf): ?>
                                    <span class="text-[10px] font-bold text-[#58CC02] bg-green-50 px-2 py-0.5 rounded-full">Anda</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-gray-600 font-medium"><?= esc($admin['username']) ?></td>
                        <td class="px-6 py-4 text-gray-500"><?= esc($admin['email'] ?? '-') ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?= $rc['class'] ?>">
                                <?= $rc['label'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($admin['is_active'] == 1): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 border border-green-200 text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Aktif
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 border border-red-200 text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit -->
                                <a href="<?= base_url('admin/users/edit/' . $admin['id_admin']) ?>"
                                   class="p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <?php if (!$isSelf && $admin['role'] !== 'super_admin'): ?>
                                <!-- Toggle Aktif/Nonaktif -->
                                <a href="<?= base_url('admin/users/toggle/' . $admin['id_admin']) ?>"
                                   onclick="return confirm('<?= $admin['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?> akun ini?')"
                                   class="p-2 rounded-xl <?= $admin['is_active'] ? 'bg-orange-50 text-orange-600 hover:bg-orange-100' : 'bg-green-50 text-green-600 hover:bg-green-100' ?> transition-colors"
                                   title="<?= $admin['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                    <?php if ($admin['is_active']): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <?php endif; ?>
                                </a>
                                <!-- Hapus -->
                                <a href="<?= base_url('admin/users/hapus/' . $admin['id_admin']) ?>"
                                   onclick="return confirm('Yakin hapus akun <?= esc($admin['nama_admin']) ?>? Aksi ini tidak bisa dibatalkan!')"
                                   class="p-2 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Role Legend -->
    <div class="bg-white border-2 border-gray-100 rounded-2xl p-5">
        <h3 class="font-black text-[#100F3E] mb-3 text-sm uppercase tracking-wider">📖 Panduan Hak Akses per Role</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            <div class="flex gap-3 p-3 bg-purple-50 rounded-xl border border-purple-100">
                <span class="text-xl">👑</span>
                <div><p class="font-black text-purple-700">Super Admin</p><p class="text-purple-500 mt-0.5">Semua fitur + Kelola Akun Admin</p></div>
            </div>
            <div class="flex gap-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                <span class="text-xl">📋</span>
                <div><p class="font-black text-blue-700">Manajer</p><p class="text-blue-500 mt-0.5">Dashboard, Menu, Laporan, RFM, Gamifikasi, Pengaturan</p></div>
            </div>
            <div class="flex gap-3 p-3 bg-green-50 rounded-xl border border-green-100">
                <span class="text-xl">💳</span>
                <div><p class="font-black text-green-700">Kasir</p><p class="text-green-500 mt-0.5">Kasir (Verifikasi Bayar) + Riwayat Transaksi</p></div>
            </div>
            <div class="flex gap-3 p-3 bg-orange-50 rounded-xl border border-orange-100">
                <span class="text-xl">👨‍🍳</span>
                <div><p class="font-black text-orange-700">Koki</p><p class="text-orange-500 mt-0.5">Dapur (Kitchen Display System) saja</p></div>
            </div>
        </div>
    </div>

</div>

<?php $this->endSection(); ?>
