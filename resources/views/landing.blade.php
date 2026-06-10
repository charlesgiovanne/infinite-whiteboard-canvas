<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whiteboard Canvas – Infinite Creative Space</title>
    <meta name="description" content="An infinite, collaborative whiteboard canvas. Draw, sketch, and ideate without limits.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --c1: #6366f1;
            --c2: #8b5cf6;
            --c3: #ec4899;
            --c4: #06b6d4;
            --c5: #f59e0b;
        }

        html, body {
            width: 100%; height: 100%;
            overflow: hidden;
            background: #0a0a14;
            font-family: 'Inter', sans-serif;
            cursor: none;
        }

        /* ─── Blob gradient layer ─────────────────────────────────── */
        #blob-canvas {
            position: fixed;
            inset: 0;
            width: 100%; height: 100%;
            filter: blur(70px) saturate(1.6);
            opacity: 0.55;
            pointer-events: none;
            z-index: 0;
        }

        /* ─── Noise grain overlay ──────────────────────────────────── */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
            opacity: 0.045;
            pointer-events: none;
            z-index: 1;
        }

        /* ─── Mouse trail canvas ──────────────────────────────────── */
        #trail-canvas {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 2;
        }

        /* ─── Custom cursor dot ──────────────────────────────────── */
        #cursor {
            position: fixed;
            width: 12px; height: 12px;
            background: #fff;
            border-radius: 50%;
            pointer-events: none;
            z-index: 999;
            transform: translate(-50%, -50%);
            transition: transform 0.08s ease, opacity 0.15s;
            mix-blend-mode: difference;
        }

        /* ─── Main content ──────────────────────────────────────────── */
        #content {
            position: relative;
            z-index: 10;
            width: 100%; height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 24px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 14px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.65);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 28px;
            backdrop-filter: blur(8px);
        }
        .badge-dot {
            width: 7px; height: 7px;
            background: #22d3ee;
            border-radius: 50%;
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%,100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.7); }
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(3rem, 8vw, 7rem);
            font-weight: 900;
            line-height: 1.0;
            letter-spacing: -0.03em;
            color: #fff;
            margin-bottom: 12px;
        }
        h1 span {
            background: linear-gradient(135deg, #a78bfa 0%, #f472b6 50%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sub {
            font-size: clamp(1rem, 2.2vw, 1.2rem);
            color: rgba(255,255,255,0.5);
            font-weight: 400;
            line-height: 1.7;
            max-width: 520px;
            margin-bottom: 52px;
        }

        /* ─── CTA button ──────────────────────────────────────────── */
        #enter-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 18px 40px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 60%, #ec4899 100%);
            color: #fff;
            border: none;
            border-radius: 100px;
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 700;
            cursor: none;
            letter-spacing: 0.01em;
            box-shadow:
                0 0 0 0 rgba(139,92,246,0.6),
                0 20px 60px rgba(99,102,241,0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            overflow: hidden;
        }
        #enter-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 60%);
            border-radius: inherit;
        }
        #enter-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow:
                0 0 0 8px rgba(139,92,246,0.18),
                0 28px 70px rgba(99,102,241,0.55);
        }
        #enter-btn:active { transform: translateY(0) scale(0.99); }

        .btn-icon {
            display: flex;
            align-items: center;
            animation: nudge-right 1.8s ease-in-out infinite;
        }
        @keyframes nudge-right {
            0%,100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }

        /* ─── Bottom hint ─────────────────────────────────────────── */
        .hint {
            position: fixed;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            color: rgba(255,255,255,0.2);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            z-index: 10;
            font-weight: 500;
        }

        /* ─── Floating orbs decoration ───────────────────────────── */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 3;
            opacity: 0;
            animation: orb-in 1.2s ease forwards;
        }
        .orb-1 {
            width: 300px; height: 300px;
            top: -80px; right: -60px;
            background: radial-gradient(circle, rgba(139,92,246,0.25) 0%, transparent 70%);
            animation-delay: 0.3s;
        }
        .orb-2 {
            width: 220px; height: 220px;
            bottom: 60px; left: -40px;
            background: radial-gradient(circle, rgba(6,182,212,0.2) 0%, transparent 70%);
            animation-delay: 0.6s;
        }
        .orb-3 {
            width: 180px; height: 180px;
            bottom: 120px; right: 80px;
            background: radial-gradient(circle, rgba(236,72,153,0.18) 0%, transparent 70%);
            animation-delay: 0.9s;
        }
        @keyframes orb-in {
            to { opacity: 1; }
        }

        /* ─── Page entrance animation ────────────────────────────── */
        #content > * {
            opacity: 0;
            transform: translateY(24px);
            animation: fade-up 0.8s ease forwards;
        }
        #content .badge    { animation-delay: 0.15s; }
        #content h1        { animation-delay: 0.3s; }
        #content .sub      { animation-delay: 0.45s; }
        #content #enter-btn { animation-delay: 0.6s; }
        @keyframes fade-up {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Animated blob background -->
    <canvas id="blob-canvas"></canvas>

    <!-- Mouse trail -->
    <canvas id="trail-canvas"></canvas>

    <!-- Custom cursor -->
    <div id="cursor"></div>

    <!-- Floating orb decorations -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- Main content -->
    <div id="content">
        <div class="badge">
            <span class="badge-dot"></span>
            Infinite Canvas — Draw Without Limits
        </div>

        <h1>Whiteboard<br><span>Canvas</span></h1>

        <p class="sub">
            A limitless creative space for your ideas — sketch, plan,
            and collaborate freely with infinite canvas support.
        </p>

        <a id="enter-btn" href="{{ route('boards.index') }}">
            Enter My Whiteboards
            <span class="btn-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </span>
        </a>
    </div>

    <div class="hint">Move your mouse to explore</div>

<script>
// ══════════════════════════════════════════════════════════
//  BLOB GRADIENT ANIMATION
// ══════════════════════════════════════════════════════════
(function () {
    const canvas  = document.getElementById('blob-canvas');
    const ctx     = canvas.getContext('2d');
    let W, H;

    function resize() {
        W = canvas.width  = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    const blobs = [
        { x: 0.2, y: 0.3, r: 0.38, color: '#6366f1', vx: 0.00015, vy: 0.00018, phase: 0 },
        { x: 0.7, y: 0.2, r: 0.30, color: '#8b5cf6', vx: -0.00012, vy: 0.00020, phase: 1.1 },
        { x: 0.5, y: 0.7, r: 0.34, color: '#ec4899', vx: 0.00018, vy: -0.00014, phase: 2.3 },
        { x: 0.15, y: 0.75, r: 0.25, color: '#06b6d4', vx: -0.00016, vy: -0.00016, phase: 3.7 },
        { x: 0.85, y: 0.6, r: 0.28, color: '#f59e0b', vx: 0.00013, vy: 0.00022, phase: 5.1 },
    ];

    let t = 0;

    function drawBlobs() {
        ctx.clearRect(0, 0, W, H);
        t += 1;
        blobs.forEach(b => {
            const bx = (0.5 + 0.38 * Math.sin(t * b.vx * 1000 + b.phase)) * W;
            const by = (0.5 + 0.38 * Math.cos(t * b.vy * 1000 + b.phase * 0.7)) * H;
            const r  = b.r * Math.max(W, H);
            const g  = ctx.createRadialGradient(bx, by, 0, bx, by, r);
            g.addColorStop(0,   b.color + 'ee');
            g.addColorStop(0.5, b.color + '88');
            g.addColorStop(1,   b.color + '00');
            ctx.beginPath();
            ctx.arc(bx, by, r, 0, Math.PI * 2);
            ctx.fillStyle = g;
            ctx.fill();
        });
        requestAnimationFrame(drawBlobs);
    }
    drawBlobs();
})();

// ══════════════════════════════════════════════════════════
//  MOUSE TRAIL
// ══════════════════════════════════════════════════════════
(function () {
    const canvas = document.getElementById('trail-canvas');
    const ctx    = canvas.getContext('2d');
    let W, H;
    function resize() {
        W = canvas.width  = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    const MAX_POINTS = 80;
    let points = [];
    let mouse  = { x: W / 2, y: H / 2 };

    window.addEventListener('mousemove', e => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
        // Custom cursor
        const cur = document.getElementById('cursor');
        cur.style.left = e.clientX + 'px';
        cur.style.top  = e.clientY + 'px';
    });

    function addPoint() {
        points.push({ x: mouse.x, y: mouse.y, age: 0 });
        if (points.length > MAX_POINTS) points.shift();
    }

    // Colour stops cycling through gradient palette
    const palette = ['#a78bfa', '#f472b6', '#38bdf8', '#34d399', '#fbbf24'];

    function draw() {
        ctx.clearRect(0, 0, W, H);
        addPoint();

        points.forEach((p, i) => {
            p.age++;
        });

        // Draw smooth curve through points
        if (points.length < 3) { requestAnimationFrame(draw); return; }

        for (let i = 1; i < points.length; i++) {
            const alpha  = (i / points.length);           // 0 → 1 newest
            const radius = alpha * 6;                     // 0 → 6 px newest
            const colIdx = Math.floor((i / points.length) * (palette.length - 1));
            const colA   = palette[colIdx];
            const colB   = palette[Math.min(colIdx + 1, palette.length - 1)];

            // Interpolate colour
            const t    = (i / points.length) * (palette.length - 1) - colIdx;
            const lerp = (a, b, t) => a + (b - a) * t;
            const hex2rgb = h => [
                parseInt(h.slice(1,3),16),
                parseInt(h.slice(3,5),16),
                parseInt(h.slice(5,7),16)
            ];
            const [r1,g1,b1] = hex2rgb(colA);
            const [r2,g2,b2] = hex2rgb(colB);
            const r = Math.round(lerp(r1,r2,t));
            const g = Math.round(lerp(g1,g2,t));
            const b = Math.round(lerp(b1,b2,t));

            ctx.beginPath();
            ctx.arc(points[i].x, points[i].y, radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${r},${g},${b},${alpha * 0.75})`;
            ctx.fill();
        }

        // Glowing connector line
        if (points.length > 1) {
            ctx.beginPath();
            ctx.moveTo(points[0].x, points[0].y);
            for (let i = 1; i < points.length - 1; i++) {
                const mx = (points[i].x + points[i+1].x) / 2;
                const my = (points[i].y + points[i+1].y) / 2;
                ctx.quadraticCurveTo(points[i].x, points[i].y, mx, my);
            }
            const last = points[points.length - 1];
            ctx.lineTo(last.x, last.y);
            const grad = ctx.createLinearGradient(
                points[0].x, points[0].y,
                last.x, last.y
            );
            grad.addColorStop(0,   'rgba(167,139,250,0)');
            grad.addColorStop(0.5, 'rgba(244,114,182,0.35)');
            grad.addColorStop(1,   'rgba(56,189,248,0.7)');
            ctx.strokeStyle = grad;
            ctx.lineWidth   = 2;
            ctx.lineCap     = 'round';
            ctx.lineJoin    = 'round';
            ctx.stroke();
        }

        requestAnimationFrame(draw);
    }
    draw();
})();

// ══════════════════════════════════════════════════════════
//  ENTER BUTTON – cursor scale effect
// ══════════════════════════════════════════════════════════
const enterBtn = document.getElementById('enter-btn');
const cursor   = document.getElementById('cursor');

enterBtn.addEventListener('mouseenter', () => {
    cursor.style.transform = 'translate(-50%,-50%) scale(2.5)';
    cursor.style.opacity   = '0.6';
});
enterBtn.addEventListener('mouseleave', () => {
    cursor.style.transform = 'translate(-50%,-50%) scale(1)';
    cursor.style.opacity   = '1';
});
</script>
</body>
</html>
