<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-[#4B4B4B] tracking-tight flex items-center gap-3">
            <span class="bg-blue-100 text-blue-600 p-2 rounded-xl shadow-inner">🎯</span> 
            Manajemen Misi Gamifikasi
        </h1>
        <p class="text-[#777] font-medium mt-1 text-sm">Buat dan kelola tantangan berhadiah poin untuk pelanggan.</p>
    </div>
    <button id="btn-open-modal-misi" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-5 py-3 rounded-xl font-bold shadow-none shadow-blue-500/30 hover:shadow-none hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 self-start sm:self-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        Tambah Misi
    </button>
</div>

<?php if(session()->getFlashdata('sukses')): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 font-bold border border-green-200 animate-fade-in-up flex items-center gap-3">
        <div class="w-7 h-7 bg-green-500 rounded-full flex items-center justify-center text-white flex-shrink-0">✓</div>
        <?= session()->getFlashdata('sukses') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 font-bold border border-red-200 animate-fade-in-up flex items-center gap-3">
        <div class="w-7 h-7 bg-red-500 rounded-full flex items-center justify-center text-white flex-shrink-0">✕</div>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Desktop Table -->
<div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden animate-fade-in-up hidden md:block">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f7f7f7] text-[#AFAFAF] text-xs uppercase font-extrabold border-b border-[#E5E5E5]">
                    <th class="px-6 py-4">Nama & Deskripsi Misi</th>
                    <th class="px-6 py-4">Tipe Misi</th>
                    <th class="px-6 py-4">Item Khusus</th>
                    <th class="px-6 py-4">Target</th>
                    <th class="px-6 py-4">Reward Poin</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <?php if(empty($misi)): ?>
                    <tr><td colspan="6" class="px-6 py-10 text-center text-[#AFAFAF] font-bold">Belum ada data misi.</td></tr>
                <?php else: ?>
                    <?php foreach($misi as $m): ?>
                    <tr class="border-b border-gray-50 hover:bg-[#f7f7f7] transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-extrabold text-[#4B4B4B] text-base"><?= esc($m['nama_misi']) ?></p>
                            <p class="text-xs text-[#777] mt-1"><?= esc($m['deskripsi']) ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-[#f7f7f7] text-gray-600 px-3 py-1.5 rounded-lg text-[10px] font-extrabold uppercase shadow-sm">
                                <?= esc($m['tipe_misi']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if(!empty($m['nama_item_target'])): ?>
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-1 rounded-lg text-[11px] font-bold flex items-center gap-1 w-max">
                                    🍵 <?= esc($m['kode_item_target'] ? '['.$m['kode_item_target'].'] ' : '') . esc($m['nama_item_target']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-[#AFAFAF] text-xs font-bold">Semua Item</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#4B4B4B]"><?= number_format($m['target_jumlah'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-[#58CC02] font-extrabold">+<?= $m['poin_reward'] ?> Poin</td>
                        <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                            <button onclick="bukaModalEdit(<?= htmlspecialchars(json_encode($m)) ?>)" 
                                class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm">Edit</button>
                            <a href="<?= base_url('admin/misi/hapus/' . $m['id_misi']) ?>" 
                                onclick="return confirm('Hapus misi ini? Progress pelanggan untuk misi ini akan ikut terhapus.')" 
                                class="text-[#FF4B4B] hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View -->
<div class="space-y-4 animate-fade-in-up md:hidden">
    <?php if(empty($misi)): ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-[#E5E5E5] shadow-sm">
            <div class="text-3xl mb-3">🎯</div>
            <p class="text-[#AFAFAF] font-bold text-sm">Belum ada data misi.</p>
        </div>
    <?php else: ?>
        <?php foreach($misi as $m): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E5E5] overflow-hidden">
            <div class="p-4 border-b border-gray-50">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                        <p class="font-extrabold text-[#4B4B4B]"><?= esc($m['nama_misi']) ?></p>
                        <p class="text-xs text-[#777] mt-1"><?= esc($m['deskripsi']) ?></p>
                        <?php if(!empty($m['nama_item_target'])): ?>
                            <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-lg text-[10px] font-bold mt-1 inline-block">
                                🍵 <?= esc($m['nama_item_target']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="bg-[#f7f7f7] text-gray-600 px-2 py-1 rounded-lg text-[10px] font-extrabold uppercase flex-shrink-0"><?= esc($m['tipe_misi']) ?></span>
                </div>
            </div>
            <div class="p-4 flex items-center justify-between bg-[#f7f7f7]">
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase">Target</p>
                        <p class="font-bold text-[#4B4B4B] text-sm"><?= number_format($m['target_jumlah'], 0, ',', '.') ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase">Reward</p>
                        <p class="font-extrabold text-[#58CC02] text-sm">+<?= $m['poin_reward'] ?> pts</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="bukaModalEdit(<?= htmlspecialchars(json_encode($m)) ?>)" 
                        class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-2 rounded-xl text-xs font-bold transition-colors">Edit</button>
                    <a href="<?= base_url('admin/misi/hapus/' . $m['id_misi']) ?>" 
                        onclick="return confirm('Hapus misi ini?')" 
                        class="bg-red-50 text-[#FF4B4B] hover:bg-[#FF4B4B] hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition-colors">Hapus</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ==================== MODAL TAMBAH MISI ==================== -->
<div id="modal-misi" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl p-6 relative animate-fade-in-up max-h-[90vh] overflow-y-auto">
        <button id="btn-close-modal-misi" class="absolute right-5 top-5 text-[#AFAFAF] hover:text-gray-600 font-bold text-xl w-9 h-9 flex items-center justify-center rounded-full hover:bg-[#f7f7f7] transition-colors">&times;</button>
        
        <h2 class="text-xl md:text-2xl font-extrabold text-[#4B4B4B] mb-6 flex items-center gap-2">
            <span class="text-blue-500">🎯</span> Tambah Misi Gamifikasi
        </h2>
        
        <form action="<?= base_url('admin/misi/simpan') ?>" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Nama Misi</label>
                <input type="text" name="nama_misi" required placeholder="Contoh: Coffee Lover" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-[#4B4B4B] transition-all">
            </div>
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Deskripsi</label>
                <textarea name="deskripsi" required rows="2" placeholder="Contoh: Beli 5 minuman bulan ini." class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-[#4B4B4B] transition-all resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Tipe Misi</label>
                <select name="tipe_misi" id="tipe_misi_tambah" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-[#4B4B4B] transition-all" onchange="toggleMenuPilihan('tambah', this.value)">
                    <option value="transaksi">Berdasarkan Total Kunjungan / Transaksi</option>
                    <option value="item_minuman">Berdasarkan Jumlah Minuman Dibeli</option>
                    <option value="nominal_belanja">Berdasarkan Nominal Total Belanja (Rp)</option>
                </select>
            </div>
            <!-- Pilih Item Menu Khusus (muncul jika tipe = item_minuman) -->
            <div id="wrap-menu-tambah" class="hidden">
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Item Menu Khusus <span class="text-blue-500">(Opsional)</span></label>
                <select name="target_id_menu" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 outline-none font-bold text-[#4B4B4B] transition-all">
                    <option value="">-- Semua Item Minuman --</option>
                    <?php if(!empty($menuList)): ?>
                        <?php foreach($menuList as $m): ?>
                            <option value="<?= $m['id_menu'] ?>"><?= esc($m['kode_item'] ? '['.$m['kode_item'].'] ' : '') . esc($m['nama_item']) ?> (<?= ucfirst($m['kategori']) ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="text-[10px] text-[#AFAFAF] mt-1 font-bold">Jika dipilih, misi hanya menghitung pembelian item ini saja.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Target Angka</label>
                    <input type="number" name="target_jumlah" required placeholder="10" min="1" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 outline-none font-bold text-[#4B4B4B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Reward Poin</label>
                    <input type="number" name="poin_reward" required placeholder="50" min="1" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 outline-none font-bold text-[#4B4B4B] transition-all">
                </div>
            </div>
            
            <button type="submit" class="w-full mt-4 bg-gray-800 text-white font-extrabold py-4 rounded-xl shadow-none hover:bg-gray-900 active:scale-95 transition-all">
                Simpan Misi
            </button>
        </form>
    </div>
</div>

<!-- ==================== MODAL EDIT MISI ==================== -->
<div id="modal-edit-misi" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl p-6 relative animate-fade-in-up max-h-[90vh] overflow-y-auto">
        <button id="btn-close-modal-edit-misi" class="absolute right-5 top-5 text-[#AFAFAF] hover:text-gray-600 font-bold text-xl w-9 h-9 flex items-center justify-center rounded-full hover:bg-[#f7f7f7] transition-colors">&times;</button>
        
        <h2 class="text-xl md:text-2xl font-extrabold text-[#4B4B4B] mb-6 flex items-center gap-2">
            <span class="text-blue-500">✏️</span> Edit Misi Gamifikasi
        </h2>
        
        <form id="form-edit-misi" action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Nama Misi</label>
                <input type="text" id="edit_nama_misi" name="nama_misi" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-[#4B4B4B] transition-all">
            </div>
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Deskripsi</label>
                <textarea id="edit_deskripsi" name="deskripsi" required rows="2" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-[#4B4B4B] transition-all resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Tipe Misi</label>
                <select id="edit_tipe_misi" name="tipe_misi" required class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none font-bold text-[#4B4B4B] transition-all" onchange="toggleMenuPilihan('edit', this.value)">
                    <option value="transaksi">Berdasarkan Total Kunjungan / Transaksi</option>
                    <option value="item_minuman">Berdasarkan Jumlah Minuman Dibeli</option>
                    <option value="nominal_belanja">Berdasarkan Nominal Total Belanja (Rp)</option>
                </select>
            </div>
            <!-- Pilih Item Menu Khusus -->
            <div id="wrap-menu-edit" class="hidden">
                <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Item Menu Khusus <span class="text-blue-500">(Opsional)</span></label>
                <select id="edit_target_id_menu" name="target_id_menu" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 outline-none font-bold text-[#4B4B4B] transition-all">
                    <option value="">-- Semua Item Minuman --</option>
                    <?php if(!empty($menuList)): ?>
                        <?php foreach($menuList as $m): ?>
                            <option value="<?= $m['id_menu'] ?>"><?= esc($m['kode_item'] ? '['.$m['kode_item'].'] ' : '') . esc($m['nama_item']) ?> (<?= ucfirst($m['kategori']) ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="text-[10px] text-[#AFAFAF] mt-1 font-bold">Jika dipilih, misi hanya menghitung pembelian item ini saja.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Target Angka</label>
                    <input type="number" id="edit_target_jumlah" name="target_jumlah" required min="1" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 outline-none font-bold text-[#4B4B4B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-[#777] uppercase mb-2">Reward Poin</label>
                    <input type="number" id="edit_poin_reward" name="poin_reward" required min="1" class="w-full px-4 py-3 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-blue-500 outline-none font-bold text-[#4B4B4B] transition-all">
                </div>
            </div>
            
            <button type="submit" class="w-full mt-4 bg-blue-600 text-white font-extrabold py-4 rounded-xl shadow-none hover:bg-blue-700 active:scale-95 transition-all">
                ✏️ Perbarui Misi
            </button>
        </form>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    #modal-misi:not(.hidden) { display: flex !important; }
    #modal-edit-misi:not(.hidden) { display: flex !important; }
</style>

<script>
    // ===== FUNGSI TOGGLE PILIHAN MENU ITEM =====
    function toggleMenuPilihan(mode, tipe) {
        const wrap = document.getElementById('wrap-menu-' + mode);
        if (tipe === 'item_minuman') {
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
            // Reset value jika disembunyikan
            const sel = wrap.querySelector('select');
            if(sel) sel.value = '';
        }
    }

    // ===== MODAL TAMBAH =====
    const btnOpenMisi = document.getElementById('btn-open-modal-misi');
    const btnCloseMisi = document.getElementById('btn-close-modal-misi');
    const modalMisi = document.getElementById('modal-misi');

    function bukaModalTambah() {
        modalMisi.classList.remove('hidden');
        modalMisi.style.display = 'flex';
    }
    function tutupModalTambah() {
        modalMisi.classList.add('hidden');
        modalMisi.style.display = 'none';
    }

    if(btnOpenMisi) btnOpenMisi.addEventListener('click', bukaModalTambah);
    if(btnCloseMisi) btnCloseMisi.addEventListener('click', tutupModalTambah);
    if(modalMisi) modalMisi.addEventListener('click', (e) => { if(e.target === modalMisi) tutupModalTambah(); });

    // ===== MODAL EDIT =====
    const modalEditMisi = document.getElementById('modal-edit-misi');
    const btnCloseEditMisi = document.getElementById('btn-close-modal-edit-misi');

    function bukaModalEdit(data) {
        // Isi form dengan data misi
        document.getElementById('edit_nama_misi').value = data.nama_misi || '';
        document.getElementById('edit_deskripsi').value = data.deskripsi || '';
        document.getElementById('edit_tipe_misi').value = data.tipe_misi || 'transaksi';
        document.getElementById('edit_target_jumlah').value = data.target_jumlah || '';
        document.getElementById('edit_poin_reward').value = data.poin_reward || '';

        // Tampilkan/sembunyikan pilihan item
        toggleMenuPilihan('edit', data.tipe_misi);

        // Set item menu yang dipilih
        const selMenu = document.getElementById('edit_target_id_menu');
        if(selMenu) selMenu.value = data.target_id_menu || '';

        // Set action form
        document.getElementById('form-edit-misi').action = '<?= base_url('admin/misi/update/') ?>' + data.id_misi;

        // Buka modal
        modalEditMisi.classList.remove('hidden');
        modalEditMisi.style.display = 'flex';
    }

    function tutupModalEdit() {
        modalEditMisi.classList.add('hidden');
        modalEditMisi.style.display = 'none';
    }

    if(btnCloseEditMisi) btnCloseEditMisi.addEventListener('click', tutupModalEdit);
    if(modalEditMisi) modalEditMisi.addEventListener('click', (e) => { if(e.target === modalEditMisi) tutupModalEdit(); });
</script>

<?= $this->endSection() ?>
