<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Menyembunyikan elemen yang tidak sesuai filter Kategori */
        .hidden-item { display: none !important; }
        /* Sembunyikan scrollbar horizontal agar UI lebih bersih di HP */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 pb-24">

    <div class="bg-orange-600 text-white p-5 rounded-b-3xl shadow-md sticky top-0 z-40">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Kafe</h1>
                <?php if(session()->get('isLoggedIn')): ?>
                    <p class="text-orange-100 text-sm mt-1">Hai, <?= esc(session()->get('nama_pelanggan')) ?>! 👋</p>
                <?php else: ?>
                    <p class="text-orange-100 text-sm mt-1">Selamat datang, Sahabat!</p>
                <?php endif; ?>
            </div>
            
            <div class="bg-white text-orange-600 px-4 py-2 rounded-xl text-center shadow-inner">
                <p class="text-xs font-bold uppercase tracking-wider">NO. MEJA</p>
                <p class="text-xl font-black leading-none"><?= esc($no_meja) ?></p>
            </div>
        </div>

        <div class="flex">
            <?php if(session()->get('isLoggedIn')): ?>
                <a href="<?= base_url('profil') ?>" class="w-full bg-orange-700 hover:bg-orange-800 text-center py-2.5 rounded-xl text-sm font-semibold transition-colors border border-orange-500 flex items-center justify-center gap-2 shadow-inner">
                    <span>⭐</span> Lihat Profil & Poin Saya
                </a>
            <?php else: ?>
                <a href="<?= base_url('auth/login') ?>" class="w-full bg-white text-orange-600 hover:bg-gray-100 text-center py-2.5 rounded-xl text-sm font-bold transition-colors shadow-sm flex items-center justify-center gap-2">
                    <span>🎁</span> Masuk / Daftar untuk kumpulkan poin!
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex gap-3 p-4 mt-2 overflow-x-auto no-scrollbar">
        <button onclick="filterMenu('semua', this)" class="filter-btn active-filter bg-gray-800 text-white px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all shadow-sm">Semua Menu</button>
        <button onclick="filterMenu('makanan', this)" class="filter-btn bg-gray-200 text-gray-700 px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all shadow-sm">Makanan</button>
        <button onclick="filterMenu('minuman', this)" class="filter-btn bg-gray-200 text-gray-700 px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all shadow-sm">Minuman</button>
        <button onclick="filterMenu('snack', this)" class="filter-btn bg-gray-200 text-gray-700 px-5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all shadow-sm">Snack</button>
    </div>

    <div class="p-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php 
        // Menggabungkan semua array kategori dari Controller untuk di-looping
        $semua_menu = array_merge($makanan ?? [], $minuman ?? [], $snack ?? []);
        
        if(empty($semua_menu)): ?>
            <div class="col-span-2 md:col-span-3 lg:col-span-4 text-center py-10 text-gray-400">
                <p>Menu belum tersedia di database.</p>
            </div>
        <?php else: ?>
            <?php foreach($semua_menu as $item): ?>
                <div class="menu-card bg-white rounded-2xl shadow-sm border border-gray-100 p-3 flex flex-col" data-kategori="<?= esc($item['kategori']) ?>">
                    
                    <div class="w-full h-32 bg-gray-200 rounded-xl mb-3 flex items-center justify-center text-gray-400 text-xs overflow-hidden">
                        <?php if(!empty($item['foto'])): ?>
                            <img src="<?= base_url('uploads/menu/' . $item['foto']) ?>" class="w-full h-full object-cover" alt="<?= esc($item['nama_item']) ?>">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex-grow">
                        <h3 class="font-bold text-gray-800 leading-tight mb-1"><?= esc($item['nama_item']) ?></h3>
                        <p class="text-orange-600 font-black">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                    </div>

                    <button onclick="addToCart(<?= $item['id_menu'] ?>, '<?= addslashes($item['nama_item']) ?>', <?= $item['harga'] ?>)" class="mt-3 w-full bg-orange-50 text-orange-600 border border-orange-200 py-2 rounded-xl font-bold text-sm hover:bg-orange-600 hover:text-white transition-colors active:scale-95">
                        + Tambah
                    </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="floating-cart" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] transform translate-y-full transition-transform duration-300 z-50">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Keranjang (<span id="cart-count" class="font-bold text-gray-800">0</span>)</p>
                <p class="text-xl font-black text-orange-600" id="cart-total">Rp 0</p>
            </div>
            <button onclick="goToCart()" class="bg-orange-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-orange-200 hover:bg-orange-700 active:scale-95 transition-all">
                Checkout ➔
            </button>
        </div>
    </div>

    <script>
        // --- 1. Logika Filter Kategori ---
        function filterMenu(kategori, btnElement) {
            // Mengubah gaya tombol yang sedang aktif
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-gray-800', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            btnElement.classList.remove('bg-gray-200', 'text-gray-700');
            btnElement.classList.add('bg-gray-800', 'text-white');

            // Menampilkan atau menyembunyikan item berdasarkan data-kategori
            const cards = document.querySelectorAll('.menu-card');
            cards.forEach(card => {
                if (kategori === 'semua' || card.getAttribute('data-kategori') === kategori) {
                    card.classList.remove('hidden-item');
                } else {
                    card.classList.add('hidden-item');
                }
            });
        }

        // --- 2. Logika Keranjang (Local Storage) ---
        // Mengambil memori keranjang jika sudah ada, atau buat array kosong jika belum ada
        let cart = JSON.parse(localStorage.getItem('cafe_cart')) || [];

        function addToCart(id, nama, harga) {
            // Cek apakah makanan tersebut sudah ada di keranjang
            let existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                // Jika sudah ada, tambah jumlah qty-nya saja
                existingItem.qty += 1;
                existingItem.subtotal = existingItem.qty * harga;
            } else {
                // Jika belum ada, masukkan item baru ke array
                cart.push({
                    id: id,
                    nama: nama,
                    harga: harga,
                    qty: 1,
                    subtotal: harga
                });
            }

            // Simpan perubahan ke LocalStorage Browser
            localStorage.setItem('cafe_cart', JSON.stringify(cart));
            
            // Perbarui angka di Floating Cart bawah
            updateCartUI();
        }

        function updateCartUI() {
            const cartElement = document.getElementById('floating-cart');
            const countElement = document.getElementById('cart-count');
            const totalElement = document.getElementById('cart-total');

            let totalQty = 0;
            let totalPrice = 0;

            // Hitung total item dan uang
            cart.forEach(item => {
                totalQty += item.qty;
                totalPrice += item.subtotal;
            });

            countElement.innerText = totalQty;
            totalElement.innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');

            // Jika ada barang di keranjang, munculkan bar dari bawah
            if (totalQty > 0) {
                cartElement.classList.remove('translate-y-full');
            } else {
                cartElement.classList.add('translate-y-full');
            }
        }

        function goToCart() {
            // Mengarahkan ke rute keranjang PHP CodeIgniter
            window.location.href = "<?= base_url('keranjang') ?>";
        }

        // Jalankan fungsi update UI saat halaman pertama kali selesai dimuat
        document.addEventListener('DOMContentLoaded', () => {
            updateCartUI();
        });
    </script>
</body>
</html>