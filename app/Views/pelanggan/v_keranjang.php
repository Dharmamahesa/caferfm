<?= $this->extend('pelanggan/layout_pelanggan') ?>

<?= $this->section('content') ?>

<div class="bg-white/80 backdrop-blur-xl p-5 flex items-center shadow-sm sticky top-0 z-40 border-b border-[#E5E5E5]">
    <a href="<?= base_url('/') ?>" class="text-gray-600 font-extrabold mr-4 text-xl bg-[#f7f7f7] w-11 h-11 flex items-center justify-center rounded-2xl hover:bg-[#58CC02]/10 hover:text-[#58CC02] transition-colors shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    </a>
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-[#4B4B4B]">Keranjang</h1>
        <p class="text-[10px] uppercase font-bold tracking-widest text-[#AFAFAF]">Review Pesanan Anda</p>
    </div>
</div>

<div class="max-w-2xl mx-auto p-5 mt-2 animate-fade-in-up">
    
    <div id="cart-items-container" class="space-y-4 mb-8">
        <!-- Item keranjang dirender oleh JavaScript -->
    </div>

    <div id="checkout-form-container" class="bg-white p-6 rounded-2xl shadow-sm border border-[#E5E5E5] hidden relative overflow-hidden">
        <!-- Abstract gradient for visual flair -->
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#58CC02]/10 rounded-full blur-3xl pointer-events-none"></div>

        <h3 class="font-extrabold text-[#4B4B4B] mb-5 border-b border-[#E5E5E5] pb-4 text-lg flex items-center gap-2 relative z-10">
            <span class="bg-[#58CC02]/10 text-[#58CC02] w-8 h-8 rounded-full flex items-center justify-center text-sm">📝</span>
            Detail Pesanan
        </h3>
        
        <div class="space-y-5 relative z-10">
            <div>
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Nomor Meja</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-[#AFAFAF] font-extrabold">#</span>
                    </div>
                    <input type="number" id="no_meja" placeholder="Contoh: 12" required class="w-full pl-10 pr-4 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none text-xl font-extrabold text-[#4B4B4B] transition-all shadow-inner">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2">Metode Pembayaran</label>
                <div class="relative">
                    <select id="metode_bayar" class="w-full px-5 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-[#4B4B4B] appearance-none transition-all shadow-inner cursor-pointer">
                        <option value="Cash">💵 Bayar Tunai (Kasir)</option>
                        <option value="QRIS">📱 QRIS / E-Wallet</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-[#AFAFAF]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-2 flex items-center gap-2">
                    <span>Punya Kode Voucher?</span>
                    <span class="bg-[#58CC02]/10 text-[#58CC02] px-2 py-0.5 rounded-full text-[8px]">Opsional</span>
                </label>
                <div class="flex gap-2">
                    <input type="text" id="kode_voucher" placeholder="Contoh: VOUCHER50K" class="flex-1 pl-4 pr-4 py-4 rounded-xl bg-[#f7f7f7] border border-[#E5E5E5] focus:bg-white focus:border-[#58CC02] focus:ring-4 focus:ring-[#1CB0F6]/20 outline-none font-bold text-[#4B4B4B] uppercase transition-all shadow-inner placeholder:normal-case">
                    <button type="button" onclick="terapkanVoucher()" class="bg-gray-800 text-white px-6 rounded-xl font-extrabold text-sm hover:bg-gray-900 active:scale-95 transition-all shadow-md">
                        Cek
                    </button>
                </div>
                <p id="pesan_voucher" class="text-xs font-bold mt-2 hidden"></p>
            </div>
        </div>
    </div>

</div>

<!-- Floating Checkout Bar -->
<div id="checkout-bar" class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl border-t border-[#E5E5E5] p-5 shadow-[0_-10px_25px_-5px_rgba(0,0,0,0.08)] transform translate-y-full transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) z-50 rounded-t-[2rem]">
    <div class="max-w-md mx-auto flex justify-between items-center">
        <div>
            <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest mb-0.5">Total Tagihan</p>
            <p class="text-2xl font-extrabold text-[#58CC02] drop-shadow-sm" id="grand-total">Rp 0</p>
        </div>
        <button onclick="prosesCheckout()" id="btn-checkout" class="bg-gradient-to-r from-[#58CC02] to-[#4BB200] text-white px-8 py-4 rounded-2xl font-extrabold shadow-none shadow-[#58CC02]/25 hover:shadow-none hover:shadow-[#58CC02]/30 active:scale-95 transition-all flex items-center gap-2 group">
            Pesan Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </button>
    </div>
</div>

<script>
    let cart = JSON.parse(localStorage.getItem('cafe_cart')) || [];
    let grandTotal = 0;
    let voucherApplied = null;
    let discountAmount = 0;
    let idVoucherApplied = null;
    let idVoucherGlobalApplied = null;

    function loadCart() {
        const container = document.getElementById('cart-items-container');
        const formContainer = document.getElementById('checkout-form-container');
        const checkoutBar = document.getElementById('checkout-bar');
        const grandTotalEl = document.getElementById('grand-total');

        container.innerHTML = '';
        grandTotal = 0;

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-16 bg-white rounded-2xl border border-[#E5E5E5] shadow-sm animate-fade-in-up">
                    <div class="w-24 h-24 bg-[#f7f7f7] rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner text-5xl">🛒</div>
                    <h2 class="text-2xl font-extrabold text-[#4B4B4B] mb-2">Keranjang Kosong</h2>
                    <p class="text-[#777] font-medium mb-8">Yuk, pilih menu favoritmu dulu!</p>
                    <a href="<?= base_url('/') ?>" class="bg-[#58CC02]/10 text-[#58CC02] font-extrabold px-8 py-3.5 rounded-2xl hover:bg-[#4BB200] hover:text-white transition-colors shadow-sm inline-block">Mulai Pesan</a>
                </div>
            `;
            formContainer.classList.add('hidden');
            checkoutBar.classList.add('translate-y-full');
            return;
        }

        formContainer.classList.remove('hidden');
        checkoutBar.classList.remove('translate-y-full');

        // Autofill No Meja
        const savedMeja = localStorage.getItem('cafe_meja');
        if(savedMeja) {
            const inputMeja = document.getElementById('no_meja');
            if(inputMeja) {
                inputMeja.value = savedMeja;
                // Optional: make readonly if we want to lock it to the QR
                // inputMeja.setAttribute('readonly', 'true');
                // inputMeja.classList.add('bg-[#f7f7f7]');
            }
        }

        cart.forEach((item, index) => {
            grandTotal += item.subtotal;

            const itemHTML = `
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#E5E5E5] flex items-center justify-between group hover:shadow-md hover:border-[#58CC02]/20 transition-all">
                    <div class="flex-1 pr-4">
                        <h3 class="font-bold text-[#4B4B4B] leading-tight mb-1 text-base group-hover:text-[#58CC02] transition-colors">${item.nama}</h3>
                        <p class="text-[#58CC02] font-extrabold tracking-tight">Rp ${item.harga.toLocaleString('id-ID')}</p>
                    </div>
                    
                    <div class="flex items-center gap-2 bg-[#f7f7f7] p-1.5 rounded-xl border border-[#E5E5E5]">
                        <button onclick="updateQty(${index}, -1)" class="w-8 h-8 flex items-center justify-center bg-white text-gray-600 font-extrabold rounded-lg shadow-sm hover:bg-[#f7f7f7] active:scale-95 transition-all">-</button>
                        <span class="font-extrabold w-6 text-center text-[#4B4B4B]">${item.qty}</span>
                        <button onclick="updateQty(${index}, 1)" class="w-8 h-8 flex items-center justify-center bg-[#58CC02]/10 text-[#58CC02] font-extrabold rounded-lg shadow-sm hover:bg-[#58CC02] hover:text-white active:scale-95 transition-all">+</button>
                    </div>
                </div>
            `;
            container.innerHTML += itemHTML;
        });

        updateGrandTotalDisplay();
    }

    function updateQty(index, change) {
        if (change > 0 && cart[index].stok_max !== undefined) {
            if (cart[index].qty >= cart[index].stok_max) {
                alert('Maaf, stok item ' + cart[index].nama + ' hanya tersisa ' + cart[index].stok_max + '.');
                return;
            }
        }
        
        cart[index].qty += change;
        
        if (cart[index].qty <= 0) {
            cart.splice(index, 1);
        } else {
            cart[index].subtotal = cart[index].qty * cart[index].harga;
        }

        // Reset voucher if cart changes
        if(voucherApplied) {
            voucherApplied = null;
            discountAmount = 0;
            idVoucherApplied = null;
            idVoucherGlobalApplied = null;
            document.getElementById('kode_voucher').value = '';
            document.getElementById('pesan_voucher').classList.add('hidden');
        }

        localStorage.setItem('cafe_cart', JSON.stringify(cart));
        loadCart();
    }

    async function terapkanVoucher() {
        const kode = document.getElementById('kode_voucher').value.trim().toUpperCase();
        const pesanEl = document.getElementById('pesan_voucher');
        const btnCek = document.querySelector('button[onclick="terapkanVoucher()"]');
        
        if(!kode) {
            pesanEl.className = "text-xs font-bold mt-2 text-[#FF4B4B] animate-fade-in-up";
            pesanEl.innerText = "⚠️ Masukkan kode voucher dulu!";
            pesanEl.classList.remove('hidden');
            return;
        }

        if(cart.length === 0) return;

        // Visual loading
        const btnOriginalText = btnCek.innerText;
        btnCek.innerText = '...';
        btnCek.disabled = true;

        try {
            const response = await fetch('<?= base_url('checkout/cek_voucher') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ kode: kode, cart: cart })
            });

            const result = await response.json();

            if (result.status === 'success') {
                voucherApplied = kode;
                
                if (result.id_voucher_global) {
                    idVoucherGlobalApplied = result.id_voucher_global;
                    idVoucherApplied = null;
                } else {
                    idVoucherApplied = result.id_pelanggan_voucher;
                    idVoucherGlobalApplied = null;
                }
                
                if (result.exact_discount !== undefined && result.exact_discount !== null) {
                    discountAmount = result.exact_discount;
                } else if (result.tipe === 'persen') {
                    discountAmount = grandTotal * (result.diskon / 100);
                } else {
                    discountAmount = result.diskon;
                }

                pesanEl.className = "text-xs font-bold mt-2 text-green-500 animate-fade-in-up";
                pesanEl.innerText = "✓ " + result.message;
            } else {
                voucherApplied = null;
                discountAmount = 0;
                idVoucherApplied = null;
                idVoucherGlobalApplied = null;
                pesanEl.className = "text-xs font-bold mt-2 text-[#FF4B4B] animate-fade-in-up";
                pesanEl.innerText = "❌ " + result.message;
            }
            
            pesanEl.classList.remove('hidden');
            updateGrandTotalDisplay();

        } catch (error) {
            console.error(error);
            pesanEl.className = "text-xs font-bold mt-2 text-[#FF4B4B] animate-fade-in-up";
            pesanEl.innerText = "❌ Terjadi kesalahan saat mengecek voucher.";
            pesanEl.classList.remove('hidden');
        } finally {
            btnCek.innerText = btnOriginalText;
            btnCek.disabled = false;
        }
    }

    function updateGrandTotalDisplay() {
        if(voucherApplied && voucherApplied === 'DISKON10') {
            discountAmount = grandTotal * 0.1; // re-calculate percentage
        }

        let finalTotal = grandTotal - discountAmount;
        if(finalTotal < 0) finalTotal = 0;
        
        const grandTotalEl = document.getElementById('grand-total');
        
        if(discountAmount > 0) {
            grandTotalEl.innerHTML = `
                <span class="text-[#AFAFAF] line-through text-sm mr-2 font-semibold">Rp ${grandTotal.toLocaleString('id-ID')}</span>
                <span class="text-green-600 drop-shadow-sm">Rp ${finalTotal.toLocaleString('id-ID')}</span>
            `;
        } else {
            grandTotalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }
    }

    async function prosesCheckout() {
        const noMeja = document.getElementById('no_meja').value;
        const metodeBayar = document.getElementById('metode_bayar').value;

        if (!noMeja) {
            Swal.fire({ 
                icon: 'warning', 
                title: 'Oops...', 
                text: 'Nomor meja wajib diisi!',
                confirmButtonColor: '#ea580c',
                confirmButtonText: 'Baiklah'
            });
            return;
        }

        if (cart.length === 0) return;

        const btn = document.getElementById('btn-checkout');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
        btn.disabled = true;

        let finalTotal = grandTotal - discountAmount;
        if(finalTotal < 0) finalTotal = 0;

        const payload = {
            items: cart,
            no_meja: noMeja,
            metode_bayar: metodeBayar,
            total_bayar: finalTotal,
            voucher: voucherApplied,
            id_voucher: idVoucherApplied,
            id_voucher_global: idVoucherGlobalApplied
        };

        try {
            const response = await fetch('<?= base_url('checkout/proses') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.status === 'success') {
                localStorage.removeItem('cafe_cart');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Pesanan Berhasil!',
                    text: result.message,
                    confirmButtonColor: '#ea580c',
                    allowOutsideClick: false,
                    confirmButtonText: 'Luar Biasa!'
                }).then(() => {
                    window.location.href = '<?= base_url('/') ?>';
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, confirmButtonColor: '#ea580c' });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'Error Jaringan', text: 'Gagal terhubung ke server.', confirmButtonColor: '#ea580c' });
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    document.addEventListener('DOMContentLoaded', loadCart);
</script>

<?= $this->endSection() ?>