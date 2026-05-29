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
        background: radial-gradient(circle, rgba(88,204,2,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }
    .lucky-bg::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: -60px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(28,176,246,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .wheel-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pointer-arrow {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 30;
        filter: drop-shadow(0 3px 6px rgba(0,0,0,0.2));
        transform-origin: center bottom;
    }
    @keyframes pointer-tick-left {
        0% { transform: translateX(-50%) rotate(0deg); }
        40% { transform: translateX(-50%) rotate(-12deg); }
        100% { transform: translateX(-50%) rotate(0deg); }
    }
    @keyframes pointer-tick-right {
        0% { transform: translateX(-50%) rotate(0deg); }
        40% { transform: translateX(-50%) rotate(12deg); }
        100% { transform: translateX(-50%) rotate(0deg); }
    }
    .spin-center-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff 0%, #f0fff0 100%);
        border: 5px solid #58CC02;
        box-shadow: 0 4px 0 #4BB200, inset 0 2px 4px rgba(255,255,255,0.8);
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
        box-shadow: 0 8px 0 #4BB200;
    }
    .chances-badge {
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #58CC02, #4BB200);
        color: white;
        padding: 8px 24px;
        border-radius: 50px;
        font-weight: 900;
        font-size: 13px;
        box-shadow: 0 4px 0 #3d9600;
        border: 3px solid white;
        white-space: nowrap;
        z-index: 25;
    }
    .buy-card {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(12px);
        border: 2px solid #E5E5E5;
    }
    @keyframes float-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
    .float-anim { animation: float-subtle 3s ease-in-out infinite; }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(88,204,2,0.2); }
        50% { box-shadow: 0 0 40px rgba(88,204,2,0.4); }
    }
    .glow-anim { animation: pulse-glow 2s ease-in-out infinite; }

    /* Sound toggle button */
    .sound-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 100;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: white;
        border: 2px solid #E5E5E5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .sound-toggle:hover { border-color: #58CC02; }
</style>

<div class="lucky-bg min-h-screen px-4 py-6 flex flex-col items-center">

    <!-- Top Bar -->
    <div class="w-full max-w-md flex justify-between items-center mb-6 relative z-10">
        <a href="<?= base_url('profil') ?>" class="w-10 h-10 bg-white/60 rounded-full flex items-center justify-center border-2 border-[#E5E5E5] hover:bg-white/80 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#4B4B4B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <h2 class="text-lg font-extrabold text-[#100F3E] tracking-tight">Points Lucky Spin</h2>
        <div class="w-10 h-10"></div>
    </div>

    <!-- Title -->
    <div class="text-center mb-5 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tighter leading-none" style="color: #58CC02; text-shadow: 2px 2px 0 #fff, 0 4px 8px rgba(88,204,2,0.15);">
            POINTS<br>LUCKY SPIN
        </h1>
    </div>

    <!-- Poin Display -->
    <div class="mb-6 bg-white/70 backdrop-blur-md rounded-full px-5 py-2.5 inline-flex items-center gap-2 border-2 border-[#E5E5E5] relative z-10">
        <span class="text-lg">💰</span>
        <span class="text-sm font-bold text-[#4B4B4B]">Poin Anda: <span class="text-[#58CC02] font-extrabold" id="poin-display"><?= $user['poin_loyalitas'] ?></span></span>
    </div>

    <!-- Daily Streak Info -->
    <div class="mb-6 bg-white/70 backdrop-blur-md rounded-2xl px-5 py-3 inline-flex items-center gap-3 border-2 border-[#58CC02]/20 relative z-10 float-anim">
        <span class="text-2xl">🔥</span>
        <div>
            <p class="text-[10px] font-extrabold text-[#AFAFAF] uppercase tracking-widest">Daily Streak</p>
            <p class="text-sm font-extrabold text-[#100F3E]"><?= $user['streak_count'] ?> Hari berturut-turut</p>
        </div>
    </div>

    <!-- Wheel Area -->
    <div class="wheel-wrapper mb-12 relative z-10" style="width: 320px; height: 320px;">
        <div class="pedestal"></div>

        <!-- Pointer Arrow (at TOP / 12 o'clock) -->
        <div class="pointer-arrow" id="pointerArrow">
            <svg width="36" height="44" viewBox="0 0 36 44">
                <defs>
                    <linearGradient id="ptrGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#58CC02"/>
                        <stop offset="100%" stop-color="#4BB200"/>
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
        <button id="spinBtn" class="w-full bg-[#58CC02] hover:bg-[#4BB200] text-white font-extrabold py-4 rounded-2xl shadow-[0_6px_0_#4BB200] active:shadow-[0_2px_0_#4BB200] active:translate-y-1 transition-all text-xl tracking-wide">
            🎰 SPIN NOW
        </button>

        <div class="buy-card rounded-2xl p-5">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-extrabold text-[#58CC02] text-base">Beli Kesempatan Spin</h3>
                <span class="text-xl">✨</span>
            </div>
            <p class="text-xs text-[#777] font-bold mb-4">Tukar <span class="text-[#58CC02] font-extrabold">50 poin</span> untuk 1 kesempatan spin</p>
            <button id="buyBtn" class="w-full bg-[#1CB0F6] hover:bg-[#1899D6] text-white font-extrabold py-3.5 rounded-xl shadow-[0_4px_0_#1899D6] active:shadow-none active:translate-y-1 transition-all text-sm tracking-wide flex items-center justify-center gap-2">
                <span class="text-lg">💎</span> BELI 50 POIN = 1 SPIN
            </button>
        </div>
    </div>

    <div class="h-8"></div>
</div>

<!-- Sound Toggle -->
<button class="sound-toggle" id="soundToggle" title="Toggle Suara">🔊</button>

<script>
(function() {
    // ============================================
    // SOUND ENGINE (Web Audio API)
    // ============================================
    let audioCtx = null;
    let soundEnabled = true;

    function getAudioCtx() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        return audioCtx;
    }

    // Tick sound — short click when crossing slice boundary
    function playTick(volume = 0.3, pitch = 800) {
        if (!soundEnabled) return;
        try {
            const ctx = getAudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.type = 'sine';
            osc.frequency.setValueAtTime(pitch, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(pitch * 0.5, ctx.currentTime + 0.05);
            
            gain.gain.setValueAtTime(volume, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.06);
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.06);
        } catch(e) {}
    }

    // Spin start whoosh
    function playWhoosh() {
        if (!soundEnabled) return;
        try {
            const ctx = getAudioCtx();
            const bufferSize = ctx.sampleRate * 0.4;
            const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
            const data = buffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                data[i] = (Math.random() * 2 - 1) * (1 - i / bufferSize);
            }
            const source = ctx.createBufferSource();
            source.buffer = buffer;
            
            const filter = ctx.createBiquadFilter();
            filter.type = 'bandpass';
            filter.frequency.setValueAtTime(1000, ctx.currentTime);
            filter.frequency.exponentialRampToValueAtTime(200, ctx.currentTime + 0.4);
            filter.Q.value = 2;
            
            const gain = ctx.createGain();
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
            
            source.connect(filter);
            filter.connect(gain);
            gain.connect(ctx.destination);
            source.start();
        } catch(e) {}
    }

    // Win fanfare
    function playWinSound() {
        if (!soundEnabled) return;
        try {
            const ctx = getAudioCtx();
            const notes = [523.25, 659.25, 783.99, 1046.50]; // C5 E5 G5 C6
            notes.forEach((freq, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0, ctx.currentTime + i * 0.12);
                gain.gain.linearRampToValueAtTime(0.2, ctx.currentTime + i * 0.12 + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.12 + 0.4);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + i * 0.12);
                osc.stop(ctx.currentTime + i * 0.12 + 0.5);
            });
        } catch(e) {}
    }

    // Lose sound
    function playLoseSound() {
        if (!soundEnabled) return;
        try {
            const ctx = getAudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(400, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(200, ctx.currentTime + 0.5);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.5);
        } catch(e) {}
    }

    // Sound toggle
    const soundToggleBtn = document.getElementById('soundToggle');
    soundToggleBtn.addEventListener('click', () => {
        soundEnabled = !soundEnabled;
        soundToggleBtn.textContent = soundEnabled ? '🔊' : '🔇';
        // Initialize AudioContext on user interaction
        if (soundEnabled) getAudioCtx();
    });

    // ============================================
    // KONFIGURASI HADIAH (DINAMIS DARI DATABASE)
    // ============================================
    const prizes = <?php
        $prizesJs = array_values(array_map(fn($h) => [
            'id'        => (int)$h['id'],
            'label'     => strtoupper($h['nama_hadiah']),
            'color'     => $h['warna_bg'],
            'textColor' => $h['warna_text'],
            'emoji'     => $h['emoji'],
        ], $hadiah));
        echo json_encode($prizesJs);
    ?>;

    const NUM_SLICES = prizes.length;
    const SLICE_DEG = 360 / NUM_SLICES; // 60°

    const canvas = document.getElementById('wheelCanvas');
    const ctx = canvas.getContext('2d');
    const spinBtn = document.getElementById('spinBtn');
    const spinCenterBtn = document.getElementById('spinCenterBtn');
    const buyBtn = document.getElementById('buyBtn');
    const spinChancesEl = document.getElementById('spin-chances');
    const poinDisplay = document.getElementById('poin-display');
    const pointerArrow = document.getElementById('pointerArrow');

    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const outerRadius = canvas.width / 2 - 2;
    const innerRadius = outerRadius - 6;
    const sliceAngleRad = (2 * Math.PI) / NUM_SLICES;

    let isSpinning = false;
    let currentAngle = 0; // cumulative CSS rotation in degrees

    // ============================================
    // GAMBAR RODA DI CANVAS
    // ============================================
    function drawWheel() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Outer ring (green border)
        ctx.beginPath();
        ctx.arc(centerX, centerY, outerRadius, 0, 2 * Math.PI);
        ctx.fillStyle = '#58CC02';
        ctx.fill();

        // Draw each slice
        prizes.forEach((prize, i) => {
            // Slice i starts at top (12 o'clock) and goes CW
            // In canvas math: 12 o'clock = -PI/2
            const startAngle = i * sliceAngleRad - Math.PI / 2;
            const endAngle = startAngle + sliceAngleRad;

            // Fill slice
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, innerRadius, startAngle, endAngle);
            ctx.closePath();
            ctx.fillStyle = prize.color;
            ctx.fill();

            // Separator line
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.lineTo(
                centerX + Math.cos(startAngle) * innerRadius,
                centerY + Math.sin(startAngle) * innerRadius
            );
            ctx.strokeStyle = 'rgba(88,204,2,0.25)';
            ctx.lineWidth = 1.5;
            ctx.stroke();

            // Draw text + emoji at the mid-angle
            ctx.save();
            ctx.translate(centerX, centerY);
            const midAngle = startAngle + sliceAngleRad / 2;
            ctx.rotate(midAngle);

            // Emoji
            ctx.font = '20px serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(prize.emoji, innerRadius * 0.74, 0);

            // Label text
            ctx.font = 'bold 10px Nunito, sans-serif';
            ctx.fillStyle = prize.textColor;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            const lines = prize.label.split('\n');
            const lineHeight = 12;
            const textDist = innerRadius * 0.45;
            lines.forEach((line, li) => {
                const yOff = (li - (lines.length - 1) / 2) * lineHeight;
                ctx.fillText(line, textDist, yOff);
            });

            ctx.restore();
        });

        // Decorative dots
        const numDots = NUM_SLICES * 4;
        for (let i = 0; i < numDots; i++) {
            const dotAngle = (i / numDots) * 2 * Math.PI;
            const dx = centerX + Math.cos(dotAngle) * (outerRadius - 3);
            const dy = centerY + Math.sin(dotAngle) * (outerRadius - 3);
            ctx.beginPath();
            ctx.arc(dx, dy, 1.8, 0, 2 * Math.PI);
            ctx.fillStyle = i % 2 === 0 ? '#fff' : '#c8f7b0';
            ctx.fill();
        }
    }

    drawWheel();

    // ============================================
    // SPIN ANGLE CALCULATION
    // ============================================
    // 
    // GEOMETRY EXPLANATION:
    // --------------------
    // The pointer is FIXED at the top (12 o'clock).
    // The canvas is ROTATED via CSS rotate(X deg) which rotates CLOCKWISE.
    //
    // On the canvas, slices are drawn starting from 12 o'clock going CW:
    //   Slice 0 occupies 0°-60° CW from 12
    //   Slice 1 occupies 60°-120° CW from 12
    //   ...etc
    //
    // When CSS rotates the canvas CW by X°, the canvas point that
    // was at position (360 - X)° CW from 12 is now under the pointer.
    //
    // So: canvasPositionAtPointer = (360 - X%360) % 360
    // And: sliceAtPointer = floor(canvasPositionAtPointer / SLICE_DEG)
    //
    // To land on slice s (center):
    //   canvasPositionAtPointer = s * SLICE_DEG + SLICE_DEG/2
    //   (360 - X%360) % 360 = s * SLICE_DEG + SLICE_DEG/2
    //   X%360 = (360 - (s * SLICE_DEG + SLICE_DEG/2)) % 360
    //
    function computeFinalAngle(prizeId) {
        const sliceIndex = prizeId - 1;
        
        // Center of the target slice in canvas coordinates (degrees CW from 12 o'clock)
        const sliceCenter = sliceIndex * SLICE_DEG + SLICE_DEG / 2;
        
        // Required CSS rotation remainder to point at that slice center
        // X%360 = (360 - sliceCenter) % 360
        const targetRemainder = ((360 - sliceCenter) % 360 + 360) % 360;
        
        // Small jitter so it doesn't always land dead center (±35% of half-slice)
        const jitter = (Math.random() - 0.5) * SLICE_DEG * 0.35;
        
        // Multiple full rotations for dramatic effect
        const minSpins = 7;
        const extraSpins = Math.floor(Math.random() * 3); // 0-2 extra
        const totalFullRotation = (minSpins + extraSpins) * 360;
        
        // Calculate delta from current position to target
        const currentRemainder = ((currentAngle % 360) + 360) % 360;
        let delta = targetRemainder - currentRemainder + jitter;
        if (delta < 0) delta += 360;
        
        return currentAngle + totalFullRotation + delta;
    }

    // Verify function: what slice is at the pointer for a given rotation?
    function getSliceAtPointer(rotationDeg) {
        const canvasPos = ((360 - (rotationDeg % 360)) % 360 + 360) % 360;
        return Math.floor(canvasPos / SLICE_DEG) % NUM_SLICES;
    }

    // ============================================
    // ANIMATE SPIN — requestAnimationFrame with physics
    // ============================================
    function animateSpin(startAngle, endAngle, duration, onComplete) {
        const startTime = performance.now();
        const totalDelta = endAngle - startAngle;
        
        let prevSliceIndex = -1;
        let tickAlternate = false;

        // Custom easing: fast start, dramatic slowdown at end
        function easeOutQuint(t) {
            return 1 - Math.pow(1 - t, 5);
        }

        function frame(now) {
            const elapsed = now - startTime;
            let t = Math.min(elapsed / duration, 1);
            
            const easedT = easeOutQuint(t);
            const angle = startAngle + totalDelta * easedT;
            
            // Apply rotation
            canvas.style.transform = `rotate(${angle}deg)`;

            // Detect slice boundary crossing for tick sound + pointer animation
            const normalizedAngle = ((angle % 360) + 360) % 360;
            const currentSliceIndex = Math.floor(normalizedAngle / SLICE_DEG) % NUM_SLICES;
            
            if (currentSliceIndex !== prevSliceIndex && prevSliceIndex !== -1) {
                // Calculate current speed (0 at end, 1 at start)
                const speed = 1 - t;
                
                // Tick sound — gets louder & lower pitch as wheel slows
                const tickVolume = Math.min(0.1 + (1 - speed) * 0.35, 0.4);
                const tickPitch = 600 + speed * 600; // fast=1200Hz, slow=600Hz
                playTick(tickVolume, tickPitch);
                
                // Pointer tick animation — only visible when slow enough
                if (speed < 0.5) {
                    tickAlternate = !tickAlternate;
                    pointerArrow.style.animation = 'none';
                    void pointerArrow.offsetWidth; // force reflow
                    const dur = Math.max(0.08, 0.05 + (1 - speed) * 0.15);
                    pointerArrow.style.animation = `${tickAlternate ? 'pointer-tick-left' : 'pointer-tick-right'} ${dur}s ease-out`;
                }
            }
            prevSliceIndex = currentSliceIndex;

            if (t < 1) {
                requestAnimationFrame(frame);
            } else {
                // Ensure exact final position
                canvas.style.transform = `rotate(${endAngle}deg)`;
                currentAngle = endAngle;
                
                // One last strong tick
                playTick(0.5, 500);
                
                setTimeout(() => {
                    pointerArrow.style.animation = '';
                }, 300);
                
                if (onComplete) onComplete();
            }
        }

        // Start whoosh sound
        playWhoosh();
        requestAnimationFrame(frame);
    }

    // ============================================
    // DO SPIN
    // ============================================
    async function doSpin() {
        if (isSpinning) return;

        // Initialize audio context on user gesture
        getAudioCtx();

        let chances = parseInt(spinChancesEl.innerText);
        if (chances <= 0) {
            Swal.fire({ icon: 'warning', title: 'Tiket Habis!', text: 'Beli kesempatan spin dengan poin Anda.', confirmButtonColor: '#58CC02' });
            return;
        }

        isSpinning = true;
        spinBtn.classList.add('opacity-50', 'cursor-not-allowed');
        canvas.classList.remove('glow-anim');
        canvas.style.transition = 'none';

        try {
            const res = await fetch('<?= base_url('lucky_spin/proses') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.status === 'success') {
                spinChancesEl.innerText = chances - 1;

                const finalAngle = computeFinalAngle(data.prize_id);
                
                // DEBUG: verify the angle math
                const landedSlice = getSliceAtPointer(finalAngle);
                console.log(`Prize ID: ${data.prize_id}, Target Slice: ${data.prize_id - 1}, Landed Slice: ${landedSlice}, Angle: ${finalAngle.toFixed(1)}°`);
                
                // Spin duration: 5.5 - 7 seconds
                const spinDuration = 5500 + Math.random() * 1500;

                animateSpin(currentAngle, finalAngle, spinDuration, () => {
                    isSpinning = false;
                    spinBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    canvas.classList.add('glow-anim');

                    setTimeout(() => {
                        if (data.prize_type === 'zonk') {
                            playLoseSound();
                            Swal.fire({ icon: 'info', title: 'Yah, belum beruntung 😅', text: 'Terima kasih sudah mencoba. Coba lagi!', confirmButtonColor: '#58CC02' });
                        } else {
                            playWinSound();
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
                    }, 500);
                });

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
