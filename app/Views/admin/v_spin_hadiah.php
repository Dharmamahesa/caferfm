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
            <h2 class="text-2xl font-black text-[#100F3E]">🎰 Pengaturan Lucky Spin</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola hadiah, persentase, dan kalibrasi voucher yang bisa didapat dari spin.</p>
        </div>
        <div class="flex gap-2">
            <?php if (session()->get('admin_role') === 'super_admin'): ?>
            <a href="<?= base_url('admin/spin/reset') ?>"
               onclick="return confirm('Reset semua hadiah ke konfigurasi default? Data saat ini akan terhapus!')"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                🔄 Reset Default
            </a>
            <?php endif; ?>
            <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#58CC02] text-white font-bold rounded-2xl shadow-[0_4px_0_#4BB200] hover:shadow-[0_2px_0_#4BB200] hover:translate-y-0.5 transition-all text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Hadiah
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <?php
        $aktif  = count(array_filter($hadiah, fn($h) => $h['is_active'] == 1));
        $nonaktif = count($hadiah) - $aktif;
    ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border-2 border-gray-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-[#100F3E]"><?= count($hadiah) ?></p>
            <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-wide">Total Hadiah</p>
        </div>
        <div class="bg-green-50 border-2 border-green-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-green-600"><?= $aktif ?></p>
            <p class="text-xs font-bold text-green-400 mt-1 uppercase tracking-wide">Aktif di Roda</p>
        </div>
        <div class="bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-gray-500"><?= $nonaktif ?></p>
            <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-wide">Nonaktif</p>
        </div>
        <div class="bg-blue-50 border-2 border-blue-100 rounded-2xl p-4 text-center">
            <p class="text-3xl font-black text-blue-600"><?= $totalWeight ?></p>
            <p class="text-xs font-bold text-blue-400 mt-1 uppercase tracking-wide">Total Bobot</p>
        </div>
    </div>

    <!-- Probability Preview -->
    <?php if (!empty($hadiah)): ?>
    <div class="bg-white border-2 border-gray-100 rounded-2xl p-5">
        <h3 class="font-black text-[#100F3E] mb-4 text-sm uppercase tracking-wider">📊 Visualisasi Peluang Hadiah (hanya yang aktif)</h3>
        <?php $hadiahAktif = array_filter($hadiah, fn($h) => $h['is_active'] == 1); ?>
        <?php $totalW = array_sum(array_column(array_values($hadiahAktif), 'weight')); ?>
        <div class="space-y-2.5">
            <?php foreach ($hadiahAktif as $h):
                $persen = $totalW > 0 ? round(($h['weight'] / $totalW) * 100, 1) : 0;
                $barColor = match($h['tipe']) {
                    'poin'    => '#58CC02',
                    'voucher' => '#1CB0F6',
                    'zonk'    => '#FF4B4B',
                    default   => '#AFAFAF'
                };
            ?>
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-bold text-[#100F3E]"><?= esc($h['emoji']) ?> <?= esc($h['nama_hadiah']) ?></span>
                    <span class="text-xs font-black" style="color: <?= $barColor ?>"><?= $persen ?>% <span class="text-gray-400 font-normal">(bobot <?= $h['weight'] ?>)</span></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full transition-all duration-500" style="width: <?= $persen ?>%; background-color: <?= $barColor ?>"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="text-xs text-gray-400 mt-3">💡 Bobot lebih besar = peluang keluar lebih besar. Total bobot aktif: <strong><?= $totalW ?></strong></p>
    </div>
    <?php endif; ?>

    <!-- Tabel Hadiah -->
    <div class="bg-white border-2 border-gray-100 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-100 bg-gray-50">
                        <th class="text-left px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Urutan</th>
                        <th class="text-left px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Hadiah</th>
                        <th class="text-left px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Tipe</th>
                        <th class="text-left px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Nilai</th>
                        <th class="text-center px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Bobot</th>
                        <th class="text-center px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Peluang</th>
                        <th class="text-center px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Warna</th>
                        <th class="text-center px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Status</th>
                        <th class="text-center px-4 py-3 font-black text-[#100F3E] text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($hadiah)): ?>
                    <tr><td colspan="9" class="text-center py-12 text-gray-400"><span class="text-4xl block mb-2">🎰</span>Belum ada hadiah dikonfigurasi.</td></tr>
                    <?php else: ?>
                    <?php foreach ($hadiah as $h):
                        $persen = $totalWeight > 0 ? round(($h['weight'] / $totalWeight) * 100, 1) : 0;
                        $tipeLabel = match($h['tipe']) {
                            'poin'    => ['label' => '🪙 Poin',    'class' => 'bg-green-100 text-green-700'],
                            'voucher' => ['label' => '🎫 Voucher', 'class' => 'bg-blue-100 text-blue-700'],
                            'zonk'    => ['label' => '😭 Zonk',   'class' => 'bg-red-100 text-red-700'],
                            default   => ['label' => $h['tipe'],  'class' => 'bg-gray-100 text-gray-700']
                        };
                        $nilaiLabel = '-';
                        if ($h['tipe'] === 'poin') $nilaiLabel = '+' . number_format($h['nominal']) . ' Poin';
                        elseif ($h['tipe'] === 'voucher') {
                            if ($h['tipe_diskon'] === 'nominal') $nilaiLabel = 'Diskon Rp ' . number_format($h['nominal']);
                            elseif ($h['tipe_diskon'] === 'persen') $nilaiLabel = 'Diskon ' . $h['nominal'] . '%';
                            elseif ($h['tipe_diskon'] === 'produk') {
                                $nilaiLabel = !empty($h['nama_menu_target'])
                                    ? 'GRATIS: ' . $h['nama_menu_target']
                                    : '⚠️ Menu belum dipilih';
                            }
                        }
                    ?>
                    <tr class="hover:bg-gray-50/50 transition-colors <?= $h['is_active'] ? '' : 'opacity-50' ?>">
                        <td class="px-4 py-3 text-center font-black text-gray-400 text-lg"><?= $h['urutan'] ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="text-2xl"><?= esc($h['emoji']) ?></span>
                                <div>
                                    <p class="font-bold text-[#100F3E]"><?= esc($h['nama_hadiah']) ?></p>
                                    <div class="flex gap-1 mt-0.5">
                                        <div class="w-4 h-4 rounded-sm border border-gray-200" style="background-color: <?= $h['warna_bg'] ?>" title="Warna BG"></div>
                                        <div class="w-4 h-4 rounded-sm border border-gray-200" style="background-color: <?= $h['warna_text'] ?>" title="Warna Teks"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold <?= $tipeLabel['class'] ?>">
                                <?= $tipeLabel['label'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium">
                            <?php if ($h['tipe_diskon'] === 'produk' && !empty($h['nama_menu_target'])): ?>
                                <span class="text-[#100F3E] font-bold">GRATIS:</span>
                                <span class="text-blue-600"><?= esc($h['nama_menu_target']) ?></span>
                            <?php elseif ($h['tipe_diskon'] === 'produk'): ?>
                                <span class="text-orange-500 font-bold">⚠️ Menu belum dipilih</span>
                            <?php else: ?>
                                <span class="text-gray-600"><?= $nilaiLabel ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-black text-[#100F3E]"><?= $h['weight'] ?></span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-sm font-bold <?= $h['is_active'] ? 'text-blue-600' : 'text-gray-400' ?>"><?= $persen ?>%</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="w-8 h-8 rounded-full mx-auto border-2 border-white shadow" style="background-color: <?= $h['warna_bg'] ?>"></div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($h['is_active']): ?>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="bukaEdit(<?= htmlspecialchars(json_encode($h), ENT_QUOTES) ?>)"
                                        class="p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <a href="<?= base_url('admin/spin/toggle/' . $h['id']) ?>"
                                   class="p-2 rounded-xl <?= $h['is_active'] ? 'bg-orange-50 text-orange-500 hover:bg-orange-100' : 'bg-green-50 text-green-600 hover:bg-green-100' ?> transition-colors"
                                   title="<?= $h['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                    <?php if ($h['is_active']): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <?php endif; ?>
                                </a>
                                <a href="<?= base_url('admin/spin/hapus/' . $h['id']) ?>"
                                   onclick="return confirm('Hapus hadiah \"<?= esc($h['nama_hadiah']) ?>\"?')"
                                   class="p-2 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-2 border-blue-100 rounded-2xl p-5 text-sm text-blue-700">
        <p class="font-black mb-2">ℹ️ Cara Kalibrasi Hadiah</p>
        <ul class="space-y-1 text-blue-600">
            <li>• <strong>Bobot (Weight)</strong>: Angka relatif. Contoh: bobot 10 dari total 100 = peluang 10%.</li>
            <li>• Hadiah langka (FREE produk) = bobot kecil (misal 5). Hadiah sering = bobot besar (misal 30).</li>
            <li>• <strong>Nonaktifkan</strong> hadiah agar tidak muncul di roda tanpa harus menghapusnya.</li>
            <li>• Urutan menentukan posisi slice di roda spin (mulai dari atas, searah jarum jam).</li>
        </ul>
    </div>
</div>

<!-- ==============================
     MODAL TAMBAH HADIAH
============================== -->
<div id="modal-tambah" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b-2 border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="font-black text-[#100F3E]">➕ Tambah Hadiah Spin</h3>
            <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?= base_url('admin/spin/simpan') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <?php echo renderSpinForm('', $menuList); ?>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="flex-1 py-3 text-center font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-[#58CC02] text-white font-black rounded-xl shadow-[0_3px_0_#4BB200] hover:shadow-[0_1px_0_#4BB200] hover:translate-y-px transition-all text-sm">✅ Simpan Hadiah</button>
            </div>
        </form>
    </div>
</div>

<!-- ==============================
     MODAL EDIT HADIAH
============================== -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b-2 border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="font-black text-[#100F3E]">✏️ Edit Hadiah Spin</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit" action="" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-black text-[#100F3E] mb-1.5">Urutan</label>
                <input type="number" name="urutan" id="edit-urutan" min="1" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none text-sm font-medium">
            </div>
            <?php echo renderSpinForm('edit-', $menuList); ?>
            <!-- Status aktif untuk edit -->
            <div>
                <label class="block text-sm font-black text-[#100F3E] mb-2">Status Hadiah</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="1" id="edit-aktif" class="accent-[#58CC02]"> <span class="text-sm font-bold text-green-600">✅ Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="0" id="edit-nonaktif" class="accent-red-500"> <span class="text-sm font-bold text-red-500">🚫 Nonaktif</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="flex-1 py-3 text-center font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-blue-500 text-white font-black rounded-xl shadow-[0_3px_0_#1d7dd4] hover:shadow-[0_1px_0_#1d7dd4] hover:translate-y-px transition-all text-sm">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php
// Helper: render form fields (digunakan modal tambah & edit)
function renderSpinForm($prefix = '', $menuList = []) {
    ob_start(); ?>
    <div>
        <label class="block text-sm font-black text-[#100F3E] mb-1.5">Nama Hadiah <span class="text-red-500">*</span></label>
        <input type="text" name="nama_hadiah" id="<?= $prefix ?>nama_hadiah" required placeholder="Contoh: FREE KOPI SUSU" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none text-sm font-medium">
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-black text-[#100F3E] mb-1.5">Tipe Hadiah <span class="text-red-500">*</span></label>
            <select name="tipe" id="<?= $prefix ?>tipe" required onchange="onTipeChange('<?= $prefix ?>')" class="w-full px-3 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none text-sm bg-white">
                <option value="">-- Pilih --</option>
                <option value="poin">🪙 Poin</option>
                <option value="voucher_nominal">🎫 Voucher Nominal (Rp)</option>
                <option value="voucher_persen">🎟️ Voucher Persen (%)</option>
                <option value="voucher_produk">☕ Voucher Produk Gratis</option>
                <option value="zonk">😭 Zonk (Tidak menang)</option>
            </select>
        </div>
        <div id="<?= $prefix ?>wrap-nominal">
            <label class="block text-sm font-black text-[#100F3E] mb-1.5" id="<?= $prefix ?>label-nominal">Nilai</label>
            <input type="number" name="nominal" id="<?= $prefix ?>nominal" min="0" value="0" class="w-full px-3 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none text-sm">
        </div>
    </div>
    <!-- Pilih Menu Target — hanya muncul saat voucher_produk -->
    <div id="<?= $prefix ?>wrap-menu" class="hidden">
        <label class="block text-sm font-black text-[#100F3E] mb-1.5">Menu yang Digratiskan <span class="text-red-500">*</span></label>
        <select name="target_id_menu" id="<?= $prefix ?>target_id_menu" class="w-full px-4 py-3 rounded-xl border-2 border-blue-200 focus:border-blue-500 focus:outline-none text-sm bg-white">
            <option value="">-- Pilih menu yang akan digratiskan --</option>
            <?php foreach ($menuList as $m): ?>
            <option value="<?= $m['id_menu'] ?>"
                    data-harga="<?= $m['harga'] ?>">
                <?= esc($m['nama_item']) ?> — Rp <?= number_format($m['harga']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <p class="text-xs text-blue-500 mt-1">💡 Pelanggan harus memesan item ini agar voucher bisa dipakai saat checkout.</p>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-black text-[#100F3E] mb-1.5">Bobot Peluang <span class="text-red-500">*</span></label>
            <input type="number" name="weight" id="<?= $prefix ?>weight" required min="1" value="10" class="w-full px-3 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none text-sm">
            <p class="text-xs text-gray-400 mt-1">Angka lebih besar = lebih sering keluar</p>
        </div>
        <div>
            <label class="block text-sm font-black text-[#100F3E] mb-1.5">Emoji</label>
            <input type="text" name="emoji" id="<?= $prefix ?>emoji" maxlength="4" placeholder="🎁" class="w-full px-3 py-3 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none text-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-black text-[#100F3E] mb-1.5">Warna Background Slice</label>
            <div class="flex items-center gap-2">
                <input type="color" name="warna_bg" id="<?= $prefix ?>warna_bg" value="#f0fff0" class="h-10 w-12 rounded-lg border-2 border-gray-200 cursor-pointer">
                <input type="text" id="<?= $prefix ?>warna_bg_text" value="#f0fff0" class="flex-1 px-3 py-2.5 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none text-sm font-mono"
                       oninput="document.getElementById('<?= $prefix ?>warna_bg').value = this.value">
            </div>
        </div>
        <div>
            <label class="block text-sm font-black text-[#100F3E] mb-1.5">Warna Teks Slice</label>
            <div class="flex items-center gap-2">
                <input type="color" name="warna_text" id="<?= $prefix ?>warna_text" value="#3d9600" class="h-10 w-12 rounded-lg border-2 border-gray-200 cursor-pointer">
                <input type="text" id="<?= $prefix ?>warna_text_text" value="#3d9600" class="flex-1 px-3 py-2.5 rounded-xl border-2 border-gray-200 focus:border-[#58CC02] focus:outline-none text-sm font-mono"
                       oninput="document.getElementById('<?= $prefix ?>warna_text').value = this.value">
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>

<script>
function onTipeChange(prefix = '') {
    const tipe = document.getElementById(prefix + 'tipe').value;
    const wrapNominal = document.getElementById(prefix + 'wrap-nominal');
    const labelNominal = document.getElementById(prefix + 'label-nominal');
    const inputNominal = document.getElementById(prefix + 'nominal');
    const wrapMenu = document.getElementById(prefix + 'wrap-menu');
    const selectMenu = document.getElementById(prefix + 'target_id_menu');

    // Reset
    wrapMenu && wrapMenu.classList.add('hidden');
    if (selectMenu) selectMenu.removeAttribute('required');

    if (tipe === 'poin') {
        wrapNominal.classList.remove('hidden');
        labelNominal.textContent = 'Jumlah Poin';
        inputNominal.placeholder = 'Contoh: 50';
        if (inputNominal.hasAttribute('max')) inputNominal.removeAttribute('max');
    } else if (tipe === 'voucher_nominal') {
        wrapNominal.classList.remove('hidden');
        labelNominal.textContent = 'Nominal Diskon (Rp)';
        inputNominal.placeholder = 'Contoh: 5000';
    } else if (tipe === 'voucher_persen') {
        wrapNominal.classList.remove('hidden');
        labelNominal.textContent = 'Persen Diskon (%)';
        inputNominal.placeholder = 'Contoh: 10';
        inputNominal.max = 100;
    } else if (tipe === 'voucher_produk') {
        // Sembunyikan nominal, tampilkan dropdown menu
        wrapNominal.classList.add('hidden');
        if (wrapMenu) {
            wrapMenu.classList.remove('hidden');
            if (selectMenu) selectMenu.setAttribute('required', 'required');
        }
    } else {
        wrapNominal.classList.add('hidden');
    }
}

// Sinkronkan color picker dengan input text
['', 'edit-'].forEach(prefix => {
    ['warna_bg', 'warna_text'].forEach(field => {
        const colorInput = document.getElementById(prefix + field);
        const textInput  = document.getElementById(prefix + field + '_text');
        if (colorInput && textInput) {
            colorInput.addEventListener('input', () => textInput.value = colorInput.value);
        }
    });
});

function bukaEdit(data) {
    const f = document.getElementById('form-edit');
    f.action = `<?= base_url('admin/spin/update/') ?>${data.id}`;

    document.getElementById('edit-urutan').value      = data.urutan;
    document.getElementById('edit-nama_hadiah').value = data.nama_hadiah;
    document.getElementById('edit-weight').value      = data.weight;
    document.getElementById('edit-emoji').value       = data.emoji;
    document.getElementById('edit-warna_bg').value         = data.warna_bg;
    document.getElementById('edit-warna_bg_text').value    = data.warna_bg;
    document.getElementById('edit-warna_text').value       = data.warna_text;
    document.getElementById('edit-warna_text_text').value  = data.warna_text;

    // Set tipe dropdown
    let tipe = data.tipe;
    if (tipe === 'voucher') {
        if (data.tipe_diskon === 'nominal') tipe = 'voucher_nominal';
        else if (data.tipe_diskon === 'persen') tipe = 'voucher_persen';
        else if (data.tipe_diskon === 'produk') tipe = 'voucher_produk';
    }
    document.getElementById('edit-tipe').value = tipe;
    onTipeChange('edit-'); // Update tampilan field nominal / dropdown menu
    document.getElementById('edit-nominal').value = data.nominal;

    // Set dropdown target_id_menu
    const selMenu = document.getElementById('edit-target_id_menu');
    if (selMenu) {
        selMenu.value = data.target_id_menu ?? '';
    }

    // Status aktif
    document.getElementById('edit-aktif').checked    = data.is_active == 1;
    document.getElementById('edit-nonaktif').checked = data.is_active == 0;

    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>

<?php $this->endSection(); ?>
