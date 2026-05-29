<?php $this->extend('admin/layout_admin'); ?>
<?php $this->section('content'); ?>

<?php $isEdit = isset($admin); ?>

<div class="max-w-2xl mx-auto space-y-6">

    <!-- Flash Error -->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border-2 border-red-200 text-red-800 rounded-2xl px-5 py-4 flex items-center gap-3 font-bold">
        <span class="text-2xl">❌</span> <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/users') ?>" class="p-2.5 rounded-xl bg-white border-2 border-gray-200 text-gray-500 hover:text-[#100F3E] hover:border-gray-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-black text-[#100F3E]">
                <?= $isEdit ? '✏️ Edit Akun Admin' : '➕ Tambah Akun Admin Baru' ?>
            </h2>
            <p class="text-sm text-gray-400 mt-0.5">
                <?= $isEdit ? 'Perbarui data akun admin.' : 'Buat akun admin dengan hak akses tertentu.' ?>
            </p>
        </div>
    </div>

    <!-- Form Card -->
    <form action="<?= $isEdit ? base_url('admin/users/update/' . $admin['id_admin']) : base_url('admin/users/simpan') ?>" method="POST" class="bg-white border-2 border-gray-100 rounded-2xl p-6 space-y-5">
        <?= csrf_field() ?>

        <!-- Nama Admin -->
        <div>
            <label for="nama_admin" class="block text-sm font-black text-[#100F3E] mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" id="nama_admin" name="nama_admin" required
                   value="<?= esc($isEdit ? ($admin['nama_admin'] ?? old('nama_admin')) : old('nama_admin')) ?>"
                   placeholder="Contoh: Budi Santoso"
                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none font-medium transition-colors text-sm">
        </div>

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-black text-[#100F3E] mb-1.5">Username <span class="text-red-500">*</span></label>
            <input type="text" id="username" name="username" required
                   value="<?= esc($isEdit ? ($admin['username'] ?? old('username')) : old('username')) ?>"
                   placeholder="Contoh: kasir_budi"
                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none font-medium transition-colors text-sm font-mono">
            <p class="text-xs text-gray-400 mt-1">Gunakan huruf kecil, angka, dan underscore. Tidak boleh sama dengan username lain.</p>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-black text-[#100F3E] mb-1.5">Email <span class="text-gray-400 font-normal">(opsional)</span></label>
            <input type="email" id="email" name="email"
                   value="<?= esc($isEdit ? ($admin['email'] ?? old('email')) : old('email')) ?>"
                   placeholder="budi@tokokopi.com"
                   class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none font-medium transition-colors text-sm">
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-black text-[#100F3E] mb-1.5">
                Password <?= $isEdit ? '<span class="text-gray-400 font-normal">(kosongkan jika tidak ingin diubah)</span>' : '<span class="text-red-500">*</span>' ?>
            </label>
            <div class="relative">
                <input type="password" id="password" name="password" <?= $isEdit ? '' : 'required' ?>
                       placeholder="<?= $isEdit ? 'Biarkan kosong jika tidak diubah' : 'Minimal 6 karakter' ?>"
                       minlength="6"
                       class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none font-medium transition-colors text-sm pr-12">
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
        </div>

        <!-- Role -->
        <div>
            <label for="role" class="block text-sm font-black text-[#100F3E] mb-1.5">Role / Level Akses <span class="text-red-500">*</span></label>
            <select id="role" name="role" required
                    class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none font-medium transition-colors text-sm bg-white"
                    <?= ($isEdit && $admin['id_admin'] == session()->get('id_admin')) ? 'disabled' : '' ?>>
                <option value="">-- Pilih Role --</option>
                <?php
                    $roles = [
                        'super_admin' => '👑 Super Admin — Akses penuh ke semua fitur',
                        'manajer'     => '📋 Manajer — Dashboard, Laporan, Menu, Gamifikasi',
                        'kasir'       => '💳 Kasir — Verifikasi Bayar & Riwayat',
                        'koki'        => '👨‍🍳 Koki — Dapur (Kitchen Display) saja',
                    ];
                    $currentRole = $isEdit ? $admin['role'] : old('role');
                    foreach ($roles as $val => $label):
                ?>
                <option value="<?= $val ?>" <?= ($currentRole === $val) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($isEdit && $admin['id_admin'] == session()->get('id_admin')): ?>
            <input type="hidden" name="role" value="<?= esc($admin['role']) ?>">
            <p class="text-xs text-orange-500 mt-1">⚠️ Anda tidak bisa mengubah role akun Anda sendiri.</p>
            <?php else: ?>
            <p class="text-xs text-gray-400 mt-1">Role menentukan halaman mana yang bisa diakses oleh admin ini.</p>
            <?php endif; ?>
        </div>

        <!-- Status Aktif (hanya saat edit, dan bukan diri sendiri) -->
        <?php if ($isEdit && $admin['id_admin'] != session()->get('id_admin')): ?>
        <div>
            <label class="block text-sm font-black text-[#100F3E] mb-2">Status Akun</label>
            <div class="flex gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="is_active" value="1" <?= ($admin['is_active'] == 1) ? 'checked' : '' ?> class="accent-[#58CC02]">
                    <span class="font-bold text-sm text-green-600">✅ Aktif</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="is_active" value="0" <?= ($admin['is_active'] == 0) ? 'checked' : '' ?> class="accent-red-500">
                    <span class="font-bold text-sm text-red-600">🚫 Nonaktif</span>
                </label>
            </div>
        </div>
        <?php elseif (!$isEdit): ?>
        <input type="hidden" name="is_active" value="1">
        <?php endif; ?>

        <!-- Role Preview Card -->
        <div id="role-preview" class="hidden p-4 rounded-xl border-2 transition-all">
            <p class="text-xs font-black uppercase tracking-wider mb-1" id="preview-title"></p>
            <p class="text-xs" id="preview-desc"></p>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 pt-2">
            <a href="<?= base_url('admin/users') ?>" 
               class="flex-1 py-3.5 text-center font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-2xl transition-colors text-sm">
                Batal
            </a>
            <button type="submit" 
                    class="flex-1 py-3.5 bg-[#58CC02] text-white font-black rounded-2xl shadow-[0_4px_0_#4BB200] hover:shadow-[0_2px_0_#4BB200] hover:translate-y-0.5 transition-all text-sm">
                <?= $isEdit ? '💾 Simpan Perubahan' : '✅ Buat Akun Admin' ?>
            </button>
        </div>
    </form>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

// Role preview
const rolePreviewData = {
    'super_admin': { 
        title: '👑 Super Admin — Full Access', 
        desc: 'Dapat mengakses SEMUA fitur termasuk Kelola Akun Admin, Laporan, RFM, Dashboard, Kasir, dan Dapur.', 
        border: 'border-purple-200', bg: 'bg-purple-50', title_color: 'text-purple-700', desc_color: 'text-purple-500' 
    },
    'manajer': { 
        title: '📋 Manajer', 
        desc: 'Akses: Dashboard, Manajemen Menu, Riwayat, Laporan, Analitik RFM, Gamifikasi (Misi, Reward, Voucher), dan Pengaturan.', 
        border: 'border-blue-200', bg: 'bg-blue-50', title_color: 'text-blue-700', desc_color: 'text-blue-500' 
    },
    'kasir': { 
        title: '💳 Kasir', 
        desc: 'Akses terbatas: Halaman Kasir (verifikasi pembayaran) dan Riwayat Transaksi. Setelah login langsung masuk ke halaman Kasir.', 
        border: 'border-green-200', bg: 'bg-green-50', title_color: 'text-green-700', desc_color: 'text-green-500' 
    },
    'koki': { 
        title: '👨‍🍳 Koki', 
        desc: 'Akses minimal: Hanya halaman Dapur (Kitchen Display System). Setelah login langsung masuk ke tampilan dapur.', 
        border: 'border-orange-200', bg: 'bg-orange-50', title_color: 'text-orange-700', desc_color: 'text-orange-500' 
    },
};

document.getElementById('role').addEventListener('change', function() {
    const val = this.value;
    const preview = document.getElementById('role-preview');
    const data = rolePreviewData[val];
    if (data) {
        preview.className = `p-4 rounded-xl border-2 transition-all ${data.border} ${data.bg}`;
        document.getElementById('preview-title').className = `text-xs font-black uppercase tracking-wider mb-1 ${data.title_color}`;
        document.getElementById('preview-title').textContent = data.title;
        document.getElementById('preview-desc').className = `text-xs ${data.desc_color}`;
        document.getElementById('preview-desc').textContent = data.desc;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
});

// Trigger preview on page load if role is already selected
const roleEl = document.getElementById('role');
if (roleEl.value) roleEl.dispatchEvent(new Event('change'));
</script>

<?php $this->endSection(); ?>
