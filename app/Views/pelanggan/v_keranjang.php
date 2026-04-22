<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan - Kafe Gamified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Inter', sans-serif; background-color: #f9fafb; } </style>
</head>
<body class="pb-24">

    <div class="bg-white p-4 flex items-center shadow-sm sticky top-0 z-40 border-b border-gray-100">
        <a href="<?= base_url('/') ?>" class="text-gray-800 font-bold mr-4 text-xl bg-gray-100 w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors">←</a>
        <div>
            <h1 class="text-xl font-black tracking-tight text-gray-800">Keranjang Saya</h1>
            <p class="text-xs text-gray-500 font-medium">Review pesanan sebelum bayar</p>
        </div>
    </div>

    <div class="max-w-md mx-auto p-4 mt-2">
        
        <div id="cart-items-container" class="space-y-4 mb-6">
            </div>

        <div id="checkout-form-container" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hidden">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Informasi Pesanan</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nomor Meja</label>
                    <input type="number" id="no_meja" placeholder="Contoh: 12" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none text-lg font-bold text-gray-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Metode Pembayaran</label>
                    <select id="metode_bayar" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 outline-none font-semibold text-gray-800">
                        <option value="Cash">Bayar Tunai di Kasir (Cash)</option>
                        <option value="QRIS">QRIS / E-Wallet</option>
                    </select>
                </div>
            </div>
        </div>

    </div>

    <div id="checkout-bar" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] hidden z-50">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Tagihan</p>
                <p class="text-2xl font-black text-orange-600" id="grand-total">Rp 0</p>
            </div>
            <button onclick="prosesCheckout()" id="btn-checkout" class="bg-orange-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-orange-200 hover:bg-orange-700 active:scale-95 transition-all flex items-center gap-2">
                Pesan Sekarang
            </button>
        </div>
    </div>

    <script>
        // Ambil memori keranjang dari browser
        let cart = JSON.parse(localStorage.getItem('cafe_cart')) || [];
        let grandTotal = 0;

        function loadCart() {
            const container = document.getElementById('cart-items-container');
            const formContainer = document.getElementById('checkout-form-container');
            const checkoutBar = document.getElementById('checkout-bar');
            const grandTotalEl = document.getElementById('grand-total');

            container.innerHTML = '';
            grandTotal = 0;

            // Jika keranjang kosong
            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <div class="text-6xl mb-4">🛒</div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Keranjang Masih Kosong</h2>
                        <p class="text-gray-500 text-sm mb-6">Yuk, pilih menu favoritmu dulu!</p>
                        <a href="<?= base_url('/') ?>" class="bg-orange-100 text-orange-700 font-bold px-6 py-2 rounded-full hover:bg-orange-200">Lihat Menu</a>
                    </div>
                `;
                formContainer.classList.add('hidden');
                checkoutBar.classList.add('hidden');
                return;
            }

            // Jika ada isinya, munculkan form dan baris bawah
            formContainer.classList.remove('hidden');
            checkoutBar.classList.remove('hidden');

            // Render setiap item makanan
            cart.forEach((item, index) => {
                grandTotal += item.subtotal;

                const itemHTML = `
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 leading-tight mb-1">${item.nama}</h3>
                            <p class="text-orange-600 font-black text-sm">Rp ${item.harga.toLocaleString('id-ID')}</p>
                        </div>
                        
                        <div class="flex items-center gap-3 bg-gray-50 p-1 rounded-xl border border-gray-100 ml-4">
                            <button onclick="updateQty(${index}, -1)" class="w-8 h-8 flex items-center justify-center bg-white text-gray-600 font-bold rounded-lg shadow-sm hover:bg-gray-100">-</button>
                            <span class="font-bold w-4 text-center text-gray-800">${item.qty}</span>
                            <button onclick="updateQty(${index}, 1)" class="w-8 h-8 flex items-center justify-center bg-white text-orange-600 font-bold rounded-lg shadow-sm hover:bg-orange-50">+</button>
                        </div>
                    </div>
                `;
                container.innerHTML += itemHTML;
            });

            // Update Total Uang
            grandTotalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // Fungsi menambah/mengurangi jumlah makanan
        function updateQty(index, change) {
            cart[index].qty += change;
            
            // Jika qty jadi 0, hapus dari array
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            } else {
                // Update subtotal
                cart[index].subtotal = cart[index].qty * cart[index].harga;
            }

            // Simpan ke memory dan render ulang
            localStorage.setItem('cafe_cart', JSON.stringify(cart));
            loadCart();
        }

        // ==========================================
        // FUNGSI CHECKOUT AJAX KE CODEIGNITER 4
        // ==========================================
        async function prosesCheckout() {
            const noMeja = document.getElementById('no_meja').value;
            const metodeBayar = document.getElementById('metode_bayar').value;

            // Validasi Sederhana
            if (!noMeja) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Nomor meja wajib diisi!' });
                return;
            }

            if (cart.length === 0) return;

            // Kunci tombol agar tidak di-klik 2 kali (Double Submit)
            const btn = document.getElementById('btn-checkout');
            btn.innerHTML = 'Memproses...';
            btn.disabled = true;

            // Siapkan Paket JSON untuk dikirim ke Controller
            const payload = {
                items: cart,
                no_meja: noMeja,
                metode_bayar: metodeBayar,
                total_bayar: grandTotal
            };

            try {
                // Fetch API (AJAX) ke Controller Checkout
                const response = await fetch('<?= base_url('checkout/proses') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // Penting untuk CI4 isAJAX()
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Bersihkan memori keranjang
                    localStorage.removeItem('cafe_cart');
                    
                    // Tampilkan Animasi Sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Pesanan Berhasil!',
                        text: result.message, // Pesan ini memuat info Poin Loyalitas RFM
                        confirmButtonColor: '#ea580c',
                        allowOutsideClick: false
                    }).then(() => {
                        // Kembali ke halaman katalog atau profil
                        window.location.href = '<?= base_url('/') ?>';
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: result.message });
                    btn.innerHTML = 'Pesan Sekarang';
                    btn.disabled = false;
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'Error Jaringan', text: 'Gagal terhubung ke server.' });
                btn.innerHTML = 'Pesan Sekarang';
                btn.disabled = false;
            }
        }

        // Jalankan saat halaman pertama dibuka
        document.addEventListener('DOMContentLoaded', loadCart);
    </script>
</body>
</html>