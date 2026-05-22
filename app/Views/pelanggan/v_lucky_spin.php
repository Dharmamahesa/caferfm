<?= $this->extend('pelanggan/layout_pelanggan') ?>

<?= $this->section('content') ?>

<style>
    .lucky-bg {
        background: linear-gradient(180deg, #f0fdf0 0%, #f7fdf7 40%, #e8fce8 100%);
        position: relative;
        overflow: hidden;
    }
    .lucky-bg::before {
        content: '';
        position: absolute;
        top: -80px;
        left: -80px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,180,100,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .lucky-bg::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: -60px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(255,123,28,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .wheel-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #wheelCanvas {
        transition: transform 4.5s cubic-bezier(0.17, 0.67, 0.12, 0.99);
    }
    .pointer-arrow {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 30;
        filter: drop-shadow(0 3px 6px rgba(0,0,0,0.2));
    }
    .spin-center-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff 0%, #fff5eb 100%);
        border: 5px solid #58CC02;
        box-shadow: 0 4px 15px rgba(255,123,28,0.3), inset 0 2px 4px rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 20;
        transition: transform 0.15s;
    }
    .spin-center-btn:active { transform: translate(-50%, -50%) scale(0.92); }
    .spin-center-btn span {
        font-weight: 900;
        font-size: 16px;
        color: #58CC02;
        letter-spacing: 1px;
    }
    .pedestal {
        position: absolute;
        bottom: -24px;
        left: 50%;
        transform: translateX(-50%);
        width: 260px;
        height: 50px;
        background: linear-gradient(180deg, #a4e88a 0%, #7dd860 100%);
        border-radius: 50%;
        z-index: 0;
        box-shadow: 0 8px 0 #e8944d;
    }
    .chances-badge {
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #58CC02, #ff5500);
        color: white;
        padding: 8px 24px;
        border-radius: 50px;
        font-weight: 900;
        font-size: 13px;
        box-shadow: 0 4px 0 #3d9600, 0 6px 12px rgba(255,85,0,0.3);
        border: 3px solid white;
        white-space: nowrap;
        z-index: 25;
    }
    .buy-card {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.6);
    }
    @keyframes float-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
    .float-anim { animation: float-subtle 3s ease-in-out infinite; }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(255,123,28,0.3); }
        50% { box-shadow: 0 0 40px rgba(255,123,28,0.5); }
    }
    .glow-anim { animation: pulse-glow 2s ease-in-out infinite; }
</style>

<div class="lucky-bg min-h-screen px-4 py-6 flex flex-col items-center">

    <!-- Top Bar -->
    <div class="w-full max-w-md flex justify-between items-center mb-6 relative z-10">
        <a href="<?= base_url('profil') ?>" class="w-10 h-10 bg-white/60 rounded-full flex items-center justify-center shadow-sm border border-[#58CC02]/20 backdrop-blur-sm hover:bg-white/80 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <h2 class="text-lg font-extrabold text-[#4B4B4B] tracking-tight">Points Lucky Spin</h2>
        <div class="w-10 h-10"></div>
    </div>

    <!-- Title -->
    <div class="text-center mb-5 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tighter leading-none" style="color: #58CC02; text-shadow: 2px 2px 0 #fff, 0 4px 8px rgba(255,123,28,0.15);">
            POINTS<br>LUCKY SPIN
        </h1>
    </div>

    <!-- Poin Display -->
    <div class="mb-6 bg-white/70 backdrop-blur-md rounded-full px-5 py-2.5 inline-flex items-center gap-2 border border-white/50 shadow-sm relative z-10">
        <span class="text-lg">💰</span>
        <span class="text-sm font-bold text-gray-700">Poin Anda: <span class="text-[#58CC02] font-extrabold" id="poin-display"><?= $user['poin_loyalitas'] ?></span></span>
    </div>

    <!-- Daily Streak Info -->
    <div class="mb-6 bg-white/70 backdrop-blur-md rounded-2xl px-5 py-3 inline-flex items-center gap-3 border border-[#58CC02]/20 shadow-sm relative z-10 float-anim">
        <span class="text-2xl">🔥</span>
        <div>
            <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Daily Streak</p>
            <p class="text-sm font-extrabold text-[#4B4B4B]"><?= $user['streak_count'] ?> Hari berturut-turut</p>
        </div>
    </div>

    <!-- Wheel Area -->
    <div class="wheel-wrapper mb-12 relative z-10" style="width: 320px; height: 320px;">
        <div class="pedestal"></div>

        <!-- Pointer Arrow -->
        <div class="pointer-arrow">
            <svg width="36" height="44" viewBox="0 0 36 44">
                <defs>
                    <linearGradient id="ptrGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#58CC02"/>
                        <stop offset="100%" stop-color="#e55500"/>
                    </linearGradient>
                </defs>
                <path d="M18 44L2 18C2 9.16 9.16 2 18 2C26.84 2 34 9.16 34 18L18 44Z" fill="url(#ptrGrad)" stroke="white" stroke-width="2"/>
                <circle cx="18" cy="17" r="5" fill="white"/>
            </svg>
        </div>

        <!-- Canvas Wheel -->
        <canvas id="wheelCanvas" width="300" height="300" class="glow-anim" style="border-radius: 50%;"></canvas>

        <!-- Center Button -->
        <div class="spin-center-btn" id="spinCenterBtn">
            <span>SPIN</span>
        </div>

        <!-- Chances Badge -->
        <div class="chances-badge">
            🎟️ <span id="spin-chances"><?= $user['spin_chances'] ?></span> Spin Tersisa
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="w-full max-w-sm space-y-4 relative z-10">
        <button id="spinBtn" class="w-full bg-gradient-to-r from-yellow-300 via-yellow-400 to-amber-400 hover:from-yellow-400 hover:to-amber-500 text-amber-900 font-extrabold py-4 rounded-3xl shadow-[0_6px_0_#b45309] active:shadow-[0_2px_0_#b45309] active:translate-y-1 transition-all text-xl border-4 border-white/80 tracking-wide">
            🎰 SPIN NOW
        </button>

        <div class="buy-card rounded-3xl p-5 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-extrabold text-[#58CC02] text-base">Beli Kesempatan Spin</h3>
                <span class="text-xl">✨</span>
            </div>
            <p class="text-xs text-[#777] font-bold mb-4">Tukar <span class="text-[#58CC02] font-extrabold">50 poin</span> untuk 1 kesempatan spin</p>
            <button id="buyBtn" class="w-full bg-gradient-to-r from-[#58CC02] to-[#ff5500] hover:from-[#4BB200] hover:to-[#cc4400] text-white font-extrabold py-3.5 rounded-2xl shadow-none shadow-[#58CC02]/20 active:scale-95 transition-all text-sm tracking-wide flex items-center justify-center gap-2">
                <span class="text-lg">💎</span> BELI 50 POIN = 1 SPIN
            </button>
        </div>
    </div>

    <div class="h-8"></div>
</div>

<script>
(function() {
    // ============================================
    // KONFIGURASI HADIAH (SINKRON DENGAN BACKEND)
    // ============================================
    const prizes = [
        { id: 1, label: 'FREE AREN\nLATTE',  color: '#f7fdf7', textColor: '#4BB200', emoji: '☕' },
        { id: 2, label: 'DISC 6K',           color: '#dcfadc', textColor: '#3d9600', emoji: '🎫' },
        { id: 3, label: '100\nPOINTS',        color: '#f7fdf7', textColor: '#4BB200', emoji: '💰' },
        { id: 4, label: '50\nPOINTS',         color: '#dcfadc', textColor: '#3d9600', emoji: '🪙' },
        { id: 5, label: '30\nPOINTS',         color: '#f7fdf7', textColor: '#4BB200', emoji: '🪙' },
        { id: 6, label: 'THANKS',            color: '#dcfadc', textColor: '#3d9600', emoji: '😭' },
    ];

    const canvas = document.getElementById('wheelCanvas');
    const ctx = canvas.getContext('2d');
    const spinBtn = document.getElementById('spinBtn');
    const spinCenterBtn = document.getElementById('spinCenterBtn');
    const buyBtn = document.getElementById('buyBtn');
    const spinChancesEl = document.getElementById('spin-chances');
    const poinDisplay = document.getElementById('poin-display');

    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = canvas.width / 2 - 4;
    const sliceAngle = (2 * Math.PI) / prizes.length;

    let isSpinning = false;
    let currentDeg = 0;

    // ============================================
    // GAMBAR RODA DI CANVAS
    // ============================================
    function drawWheel() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Outer ring
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.fillStyle = '#58CC02';
        ctx.fill();

        // Inner circle (slightly smaller)
        const innerRadius = radius - 6;

        prizes.forEach((prize, i) => {
            const startAngle = i * sliceAngle - Math.PI / 2;
            const endAngle = startAngle + sliceAngle;

            // Draw slice
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, innerRadius, startAngle, endAngle);
            ctx.closePath();
            ctx.fillStyle = prize.color;
            ctx.fill();

            // Separator lines
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, innerRadius, startAngle, startAngle);
            ctx.lineTo(centerX + Math.cos(startAngle) * innerRadius, centerY + Math.sin(startAngle) * innerRadius);
            ctx.strokeStyle = 'rgba(255,123,28,0.15)';
            ctx.lineWidth = 1.5;
            ctx.stroke();

            // Draw text
            ctx.save();
            ctx.translate(centerX, centerY);
            const textAngle = startAngle + sliceAngle / 2;
            ctx.rotate(textAngle);

            // Emoji (closer to edge)
            ctx.font = '20px serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(prize.emoji, innerRadius * 0.72, 0);

            // Label text (closer to center)
            ctx.font = 'bold 10px Inter, sans-serif';
            ctx.fillStyle = prize.textColor;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            const lines = prize.label.split('\n');
            const lineHeight = 12;
            const textDist = innerRadius * 0.46;
            lines.forEach((line, li) => {
                const yOff = (li - (lines.length - 1) / 2) * lineHeight;
                ctx.fillText(line, textDist, yOff);
            });

            ctx.restore();
        });

        // Small decorative dots on outer ring
        for (let i = 0; i < prizes.length * 3; i++) {
            const dotAngle = (i / (prizes.length * 3)) * 2 * Math.PI;
            const dx = centerX + Math.cos(dotAngle) * (radius - 3);
            const dy = centerY + Math.sin(dotAngle) * (radius - 3);
            ctx.beginPath();
            ctx.arc(dx, dy, 2, 0, 2 * Math.PI);
            ctx.fillStyle = i % 2 === 0 ? '#fff' : '#ffe0c0';
            ctx.fill();
        }
    }

    drawWheel();

    // ============================================
    // LOGIKA SPIN
    // ============================================

    // Pointer ada di ATAS (jam 12). 
    // Slice 0 dimulai dari jam 12 (karena kita offset -PI/2 saat menggambar).
    // Jadi jika roda tidak diputar (0°), pointer menunjuk ke tengah slice 0.
    // 
    // Untuk menunjuk ke slice i, kita perlu memutar roda sebesar:
    //   -(i * sliceAngle_deg + sliceAngle_deg/2)
    // ditambah kelipatan 360 untuk banyak putaran.
    //
    // sliceAngle_deg = 360 / 6 = 60°
    // 
    // Mapping:
    //   Prize id=1 (slice 0): target = 0° (sudah di atas)
    //   Prize id=2 (slice 1): target = -60°  → equivalent to 300°
    //   Prize id=3 (slice 2): target = -120° → equivalent to 240°
    //   Prize id=4 (slice 3): target = -180° → equivalent to 180°
    //   Prize id=5 (slice 4): target = -240° → equivalent to 120°
    //   Prize id=6 (slice 5): target = -300° → equivalent to 60°

    function getTargetDeg(prizeId) {
        const sliceIndex = prizeId - 1;
        const sliceDeg = 360 / prizes.length;
        // Negative rotation brings slice under pointer
        return 360 - (sliceIndex * sliceDeg);
    }

    async function doSpin() {
        if (isSpinning) return;

        let chances = parseInt(spinChancesEl.innerText);
        if (chances <= 0) {
            Swal.fire({ icon: 'warning', title: 'Tiket Habis!', text: 'Beli kesempatan spin dengan poin Anda.', confirmButtonColor: '#58CC02' });
            return;
        }

        isSpinning = true;
        spinBtn.classList.add('opacity-50', 'cursor-not-allowed');
        canvas.classList.remove('glow-anim');

        try {
            const res = await fetch('<?= base_url('lucky_spin/proses') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.status === 'success') {
                spinChancesEl.innerText = chances - 1;

                const targetBase = getTargetDeg(data.prize_id);
                // Add random offset within half-slice so it doesn't always land dead center
                const halfSlice = (360 / prizes.length) / 2;
                const randomOffset = (Math.random() - 0.5) * halfSlice * 0.6;
                const fullSpins = 360 * 6; // 6 full rotations
                const finalDeg = currentDeg + fullSpins + (targetBase - (currentDeg % 360)) + randomOffset;

                canvas.style.transform = `rotate(${finalDeg}deg)`;
                currentDeg = finalDeg;

                setTimeout(() => {
                    isSpinning = false;
                    spinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    canvas.classList.add('glow-anim');

                    if (data.prize_type === 'zonk') {
                        Swal.fire({ icon: 'info', title: 'Yah, belum beruntung 😅', text: 'Terima kasih sudah mencoba. Coba lagi!', confirmButtonColor: '#58CC02' });
                    } else {
                        let desc = '';
                        if (data.prize_type === 'poin') {
                            const addPoin = parseInt(data.prize_name.replace(/[^0-9]/g, ''));
                            poinDisplay.innerText = parseInt(poinDisplay.innerText) + addPoin;
                            desc = `+${addPoin} Poin telah ditambahkan ke akun Anda!`;
                        } else {
                            desc = `Voucher "${data.prize_name}" sudah masuk ke daftar Voucher Anda!`;
                        }
                        Swal.fire({
                            icon: 'success',
                            title: '🎉 Selamat!',
                            html: `<div class="font-bold text-lg text-[#58CC02] mb-1">${data.prize_name}</div><p class="text-[#777] text-sm">${desc}</p>`,
                            confirmButtonColor: '#58CC02'
                        });
                    }
                }, 4800);

            } else {
                isSpinning = false;
                spinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                canvas.classList.add('glow-anim');
                Swal.fire('Oops!', data.message, 'error');
            }
        } catch (err) {
            isSpinning = false;
            spinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            canvas.classList.add('glow-anim');
            Swal.fire('Oops!', 'Gagal menghubungi server.', 'error');
        }
    }

    spinBtn.addEventListener('click', doSpin);
    spinCenterBtn.addEventListener('click', doSpin);

    // ============================================
    // BELI SPIN
    // ============================================
    buyBtn.addEventListener('click', async () => {
        const confirm = await Swal.fire({
            icon: 'question',
            title: 'Beli Spin?',
            text: 'Tukar 50 poin loyalitas untuk 1 kesempatan spin?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Beli!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#58CC02'
        });
        if (!confirm.isConfirmed) return;

        try {
            const res = await fetch('<?= base_url('lucky_spin/beli') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.status === 'success') {
                let currentChances = parseInt(spinChancesEl.innerText);
                let currentPoin = parseInt(poinDisplay.innerText);
                spinChancesEl.innerText = currentChances + 1;
                poinDisplay.innerText = currentPoin - 50;

                Swal.fire({ icon: 'success', title: '+1 Spin Chance! 🎟️', text: 'Berhasil menukar 50 poin.', timer: 1800, showConfirmButton: false });
            } else {
                Swal.fire('Oops!', data.message, 'error');
            }
        } catch (err) {
            Swal.fire('Oops!', 'Terjadi kesalahan sistem.', 'error');
        }
    });
})();
</script>

<?= $this->endSection() ?>
