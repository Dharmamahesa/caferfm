<?= $this->extend('pelanggan/layout_pelanggan') ?>

<?= $this->section('content') ?>

<!-- Header Section -->
<div class="bg-gradient-to-br from-orange-500 via-orange-500 to-red-500 text-white p-6 rounded-b-[2rem] shadow-lg shadow-orange-500/20 sticky top-0 z-40">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h1 class="text-3xl font-black tracking-tight drop-shadow-sm">Kafe</h1>
            <?php if(session()->get('isLoggedIn')): ?>
                <p class="text-orange-50 text-sm mt-1 font-medium opacity-90">Hai, <?= esc(session()->get('nama_pelanggan')) ?>! 👋</p>
            <?php else: ?>
                <p class="text-orange-50 text-sm mt-1 font-medium opacity-90">Selamat datang, Sahabat!</p>
            <?php endif; ?>
        </div>
        
        <div class="bg-white/20 backdrop-blur-md border border-white/30 text-white px-5 py-2.5 rounded-2xl text-center shadow-sm relative overflow-hidden group">
            <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mb-0.5 relative z-10">Meja</p>
            <p class="text-2xl font-black leading-none drop-shadow-md relative z-10" id="display_meja"><?= esc($no_meja) ?></p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="relative mb-5">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-orange-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <input type="text" id="searchInput" onkeyup="searchMenu()" placeholder="Cari makanan atau minuman..." class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white placeholder-orange-100 focus:bg-white focus:text-gray-800 focus:placeholder-gray-400 outline-none transition-all font-semibold shadow-inner">
    </div>

    <div class="flex">
        <?php if(session()->get('isLoggedIn')): ?>
            <a href="<?= base_url('profil') ?>" class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white text-center py-3 rounded-2xl text-sm font-semibold transition-all duration-300 border border-white/20 flex items-center justify-center gap-2 shadow-sm">
                <span class="text-lg">⭐</span> Profil & Poin
            </a>
        <?php else: ?>
            <a href="<?= base_url('auth/login') ?>" class="w-full bg-white text-orange-600 hover:bg-orange-50 text-center py-3 rounded-2xl text-sm font-bold transition-all duration-300 shadow-lg shadow-black/5 flex items-center justify-center gap-2">
                <span class="text-lg">🎁</span> Masuk / Daftar
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filter Categories -->
<div class="flex gap-3 p-5 mt-2 overflow-x-auto no-scrollbar snap-x">
    <button onclick="filterMenu('semua', this)" class="filter-btn active-filter snap-center bg-gradient-to-r from-gray-800 to-gray-900 text-white px-6 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-300 shadow-md transform scale-105">Semua</button>
    <button onclick="filterMenu('makanan', this)" class="filter-btn snap-center bg-white text-gray-600 px-6 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-300 shadow-sm border border-gray-100 hover:bg-gray-50 hover:shadow-md">Makanan</button>
    <button onclick="filterMenu('minuman', this)" class="filter-btn snap-center bg-white text-gray-600 px-6 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-300 shadow-sm border border-gray-100 hover:bg-gray-50 hover:shadow-md">Minuman</button>
    <button onclick="filterMenu('snack', this)" class="filter-btn snap-center bg-white text-gray-600 px-6 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all duration-300 shadow-sm border border-gray-100 hover:bg-gray-50 hover:shadow-md">Snack</button>
</div>

<!-- Menu Grid -->
<div class="px-5 pb-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5" id="menu-container">
    <?php 
    $semua_menu = array_merge($makanan ?? [], $minuman ?? [], $snack ?? []);
    
    if(empty($semua_menu)): ?>
        <div class="col-span-2 md:col-span-3 lg:col-span-4 flex flex-col items-center justify-center py-16 text-gray-400">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                <span class="text-3xl">🍽️</span>
            </div>
            <p class="font-medium text-gray-500">Menu belum tersedia.</p>
        </div>
    <?php else: ?>
        <?php foreach($semua_menu as $item): ?>
            <div class="menu-card bg-white rounded-[1.5rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 p-3.5 flex flex-col group" data-kategori="<?= esc($item['kategori']) ?>" data-nama="<?= strtolower(esc($item['nama_item'])) ?>">
                
                <div class="w-full h-36 bg-slate-100 rounded-2xl mb-4 flex items-center justify-center text-gray-400 text-xs overflow-hidden relative shadow-inner">
                    <?php if(!empty($item['foto'])): ?>
                        <img src="<?= base_url('uploads/menu/' . $item['foto']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out" alt="<?= esc($item['nama_item']) ?>">
                    <?php else: ?>
                        <span class="opacity-50 font-medium">No Image</span>
                    <?php endif; ?>
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                
                <div class="flex-grow px-1">
                    <h3 class="font-bold text-gray-800 leading-tight mb-1.5 line-clamp-2"><?= esc($item['nama_item']) ?></h3>
                    <p class="text-orange-600 font-black text-lg tracking-tight">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                </div>

                <button onclick="addToCart(<?= $item['id_menu'] ?>, '<?= addslashes($item['nama_item']) ?>', <?= $item['harga'] ?>)" class="mt-4 w-full bg-orange-50 text-orange-600 py-2.5 rounded-xl font-bold text-sm hover:bg-orange-500 hover:text-white transition-all duration-300 active:scale-95 hover:shadow-md hover:shadow-orange-500/20 flex justify-center items-center gap-1.5 group/btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/btn:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                    Tambah
                </button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div id="no-results" class="hidden px-5 py-10 flex-col items-center justify-center text-gray-400">
    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
        <span class="text-3xl">🔍</span>
    </div>
    <p class="font-bold text-gray-500">Menu tidak ditemukan.</p>
</div>

<!-- Floating Cart -->
<div id="floating-cart" class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl border-t border-gray-100 p-5 shadow-[0_-10px_25px_-5px_rgba(0,0,0,0.08)] transform translate-y-full transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) z-50 rounded-t-[2rem]">
    <div class="max-w-md mx-auto flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="bg-orange-100 text-orange-600 w-12 h-12 rounded-2xl flex items-center justify-center relative shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span id="cart-count-badge" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white shadow-sm">0</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Total Harga</p>
                <p class="text-xl font-black text-gray-800 leading-none" id="cart-total">Rp 0</p>
            </div>
        </div>
        <button onclick="goToCart()" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-7 py-3.5 rounded-2xl font-bold shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 active:scale-95 transition-all flex items-center gap-2 group">
            Checkout
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </button>
    </div>
</div>

<style>
    .hidden-item { display: none !important; }
</style>

<script>
    // --- 0. Smart QR Table Handling ---
    // Cek parameter ?meja=X dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const urlMeja = urlParams.get('meja');
    if (urlMeja) {
        localStorage.setItem('cafe_meja', urlMeja);
        const displayMeja = document.getElementById('display_meja');
        if(displayMeja && displayMeja.innerText.trim() === '0' || displayMeja.innerText.trim() === '') {
            displayMeja.innerText = urlMeja;
        }
    } else {
        const storedMeja = localStorage.getItem('cafe_meja');
        const displayMeja = document.getElementById('display_meja');
        if (storedMeja && displayMeja && (displayMeja.innerText.trim() === '0' || displayMeja.innerText.trim() === '')) {
            displayMeja.innerText = storedMeja;
        }
    }

    let currentCategory = 'semua';

    function filterMenu(kategori, btnElement) {
        currentCategory = kategori;
        const activeClasses = ['bg-gradient-to-r', 'from-gray-800', 'to-gray-900', 'text-white', 'shadow-md', 'transform', 'scale-105'];
        const inactiveClasses = ['bg-white', 'text-gray-600', 'shadow-sm', 'border', 'border-gray-100', 'hover:bg-gray-50', 'hover:shadow-md'];

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove(...activeClasses);
            btn.classList.add(...inactiveClasses);
            btn.classList.remove('active-filter');
        });
        
        btnElement.classList.remove(...inactiveClasses);
        btnElement.classList.add(...activeClasses, 'active-filter');

        applyFilters();
    }

    function searchMenu() {
        applyFilters();
    }

    function applyFilters() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.menu-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const matchCategory = currentCategory === 'semua' || card.getAttribute('data-kategori') === currentCategory;
            const matchSearch = card.getAttribute('data-nama').includes(query);

            if (matchCategory && matchSearch) {
                card.classList.remove('hidden-item');
                visibleCount++;
            } else {
                card.classList.add('hidden-item');
            }
        });

        const noResults = document.getElementById('no-results');
        if (visibleCount === 0 && cards.length > 0) {
            noResults.classList.replace('hidden', 'flex');
        } else {
            noResults.classList.replace('flex', 'hidden');
        }
    }

    // --- 2. Logika Keranjang (Local Storage) ---
    let cart = JSON.parse(localStorage.getItem('cafe_cart')) || [];

    function addToCart(id, nama, harga) {
        let existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.qty += 1;
            existingItem.subtotal = existingItem.qty * harga;
        } else {
            cart.push({ id: id, nama: nama, harga: harga, qty: 1, subtotal: harga });
        }
        localStorage.setItem('cafe_cart', JSON.stringify(cart));
        updateCartUI();
        
        const countBadge = document.getElementById('cart-count-badge');
        countBadge.animate([{ transform: 'scale(1)' }, { transform: 'scale(1.4)' }, { transform: 'scale(1)' }], { duration: 300 });
    }

    function updateCartUI() {
        const cartElement = document.getElementById('floating-cart');
        const countBadge = document.getElementById('cart-count-badge');
        const totalElement = document.getElementById('cart-total');

        let totalQty = 0;
        let totalPrice = 0;
        cart.forEach(item => {
            totalQty += item.qty;
            totalPrice += item.subtotal;
        });

        countBadge.innerText = totalQty;
        totalElement.innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');

        if (totalQty > 0) {
            cartElement.classList.remove('translate-y-full');
        } else {
            cartElement.classList.add('translate-y-full');
        }
    }

    function goToCart() {
        window.location.href = "<?= base_url('keranjang') ?>";
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateCartUI();
    });
</script>

<?= $this->endSection() ?>