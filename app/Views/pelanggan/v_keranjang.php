<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan - Kafe Gamified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 pb-32">

    <div class="bg-white p-4 flex items-center shadow-sm sticky top-0 z-40">
        <a href="<?= base_url('/') ?>" class="text-orange-600 font-bold mr-4 text-xl">←</a>
        <h1 class="text-xl font-bold tracking-tight text-gray-800">Keranjang Saya</h1>
    </div>

    <div class="p-4 max-w-md mx-auto">
        <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 mb-4 flex justify-between items-center">
            <span class="font-semibold text-gray-700">Nomor Meja:</span>
            <span class="text-xl font-black text-orange-600"><?= esc(session()->get('no_meja') ?? '-') ?></span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
            <h2 class="font-bold text-gray-800 mb-3 border-b pb-2">Rincian Pesanan</h2>
            <div id="cart-items-container" class="space-y-4">
                </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
            <h2 class="font-bold text-gray-800 mb-3">Metode Pembayaran</h2>
            <div class="space-y-2">
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="metode_bayar" value="tunai" class="w-5 h-5 text-orange-600" checked>
                    <span class="ml-3 font-medium text-gray-700">Tunai (Bayar di Kasir)</span>
                </label>
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="metode_bayar" value="qris" class="w-5 h-5 text-orange-600">
                    <span class="ml-3 font-medium text-gray-700">QRIS (E-Wallet/M-Banking)</span>
                </label>
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] z-50">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pembayaran</p>
                <p class="text-2xl font-black text-orange-600" id="grand-total">Rp 0</p>
            </div>
            <button onclick="prosesCheckout()" id="btn-checkout" class="bg-orange-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-orange-200 hover:bg-orange-700 active:scale-95 transition-all disabled:opacity-50">
                Pesan Sekarang
            </button>
        </div>
    </div>

    <script>
        // Ambil data keranjang dari Local Storage
        let cart = JSON.parse(localStorage.getItem('cafe_cart')) || [];
        let grandTotal = 0;

        // Format angka ke Rupiah
        const formatRp = (angka) => 'Rp ' + angka.toLocaleString('id-ID');

        // Fungsi merender isi keranjang ke HTML
        function renderCart() {
            const container = document.getElementById('cart-items-container');
            const totalEl = document.getElementById('grand-total');
            const btnCheckout = document.getElementById('btn-checkout');
            
            container.innerHTML = '';
            grandTotal = 0;

            if (cart.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-400 py-4">Keranjang masih kosong.</p>';
                totalEl.innerText = formatRp(0);
                btnCheckout.disabled = true;
                return;
            }

            btnCheckout.disabled = false;

            cart.forEach((item, index) => {
                grandTotal += item.subtotal;
                
                // Desain per item
                const itemHTML = `
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 leading-tight">${item.nama}</h3>
                            <p class="text-sm text-gray-500">${item.qty} x ${formatRp(item.harga)}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="font-bold text-gray-800">${formatRp(item.subtotal)}</p>
                            <button onclick="hapusItem(${index})" class="text-xs text-red-500 hover:underline mt-1">Hapus</button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHTML);
            });

            totalEl.innerText = formatRp(grandTotal);
        }

        // Fungsi menghapus item dari keranjang
        function hapusItem(index) {
            cart.splice(index, 1); // Hapus item dari array
            localStorage.setItem('cafe_cart', JSON.stringify(cart)); // Simpan ulang ke memori
            renderCart(); // Refresh tampilan
        }

        // --- INI ADALAH JEMBATAN KE BACKEND (FETCH API) ---
        async function prosesCheckout() {
            if (cart.length === 0) return alert("Keranjang kosong!");

            // Ambil metode bayar yang dipilih
            const metodeBayar = document.querySelector('input[name="metode_bayar"]:checked').value;
            const btnCheckout = document.getElementById('btn-checkout');

            // 1. Siapkan paket data (Payload)
            const payload = {
                no_meja: "<?= esc(session()->get('no_meja')) ?>",
                metode_bayar: metodeBayar,
                total_bayar: grandTotal,
                items: cart // Array berisi id_menu, qty, dll
            };

            // Ubah tombol jadi loading
            btnCheckout.innerText = "Memproses...";
            btnCheckout.disabled = true;

            try {
                // 2. Kirim data ke Controller CI4 menggunakan method POST
                const response = await fetch("<?= base_url('checkout/proses') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                // 3. Tangani respon dari Server
                if (result.status === 'success') {
                    alert("Pesanan berhasil dibuat! Silakan tunggu di meja.");
                    localStorage.removeItem('cafe_cart'); // Kosongkan keranjang
                    window.location.href = "<?= base_url('katalog') ?>"; // Kembali ke awal (nanti bisa diarahkan ke halaman status)
                } else {
                    alert("Gagal memproses pesanan: " + result.message);
                    btnCheckout.innerText = "Pesan Sekarang";
                    btnCheckout.disabled = false;
                }
            } catch (error) {
                alert("Terjadi kesalahan koneksi ke server.");
                btnCheckout.innerText = "Pesan Sekarang";
                btnCheckout.disabled = false;
            }
        }

        // Jalankan render saat halaman dibuka
        document.addEventListener('DOMContentLoaded', renderCart);
    </script>
</body>
</html>