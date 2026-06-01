<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayAI | AI Playground Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=DM+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        /* CSS RESET & VARIABLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #FF6B9D;
            --secondary: #FEC84B;
            --tertiary: #845EC2;
            --dark: #070913;
            --card-bg: rgba(26, 31, 58, 0.4);
            --card-border: rgba(255, 255, 255, 0.1);
            --text: #e0e0e0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--dark);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            position: relative;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            background-position: center center;
        }

        body::before,
        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.4;
            animation: pulseOrb 8s infinite alternate;
        }

        body::before {
            background: var(--tertiary);
            top: -100px;
            left: -100px;
        }

        body::after {
            background: var(--primary);
            bottom: -100px;
            right: -100px;
            animation-delay: -4s;
        }

        /* TOP NAV (AUTH BUTTONS) */
        .top-nav {
            position: absolute;
            top: 2rem;
            right: 2rem;
            z-index: 10;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .auth-btn {
            padding: 0.6rem 1.5rem;
            border-radius: 30px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .btn-login {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-login:hover {
            background: rgba(255, 107, 157, 0.1);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: #fff;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
        }

        .user-greeting {
            color: var(--secondary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-logout:hover {
            background: rgba(255, 77, 109, 0.2);
            border-color: #ff4d6d;
        }

        /* MAIN CONTAINER */
        .container {
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
            text-align: center;
            z-index: 1;
            margin-top: 3rem;
        }

        .header {
            margin-bottom: 4rem;
        }

        .badge-hub {
            display: inline-block;
            padding: 0.4rem 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            font-size: 0.9rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--secondary);
            margin-bottom: 1rem;
            animation: slideDown 0.8s ease-out;
        }

        .title {
            font-family: 'Righteous', cursive;
            font-size: 4.5rem;
            background: linear-gradient(to right, #FF6B9D, #FEC84B, #845EC2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            animation: fadeIn 1s ease-in;
            text-shadow: 0 10px 30px rgba(255, 107, 157, 0.2);
        }

        .subtitle {
            font-size: 1.2rem;
            color: #a0a5bc;
            animation: fadeIn 1.2s ease-in;
            max-width: 600px;
            margin: 0 auto;
        }

        /* GRID CARDS */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 3.5rem 2rem 2.5rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
            transform: skewX(-20deg);
            transition: left 0.7s ease;
        }

        .card:hover::before {
            left: 150%;
        }

        .card:hover {
            transform: translateY(-15px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .card:nth-child(1):hover {
            box-shadow: 0 20px 50px rgba(132, 94, 194, 0.3), inset 0 0 20px rgba(132, 94, 194, 0.1);
        }

        .card:nth-child(2):hover {
            box-shadow: 0 20px 50px rgba(255, 107, 157, 0.3), inset 0 0 20px rgba(255, 107, 157, 0.1);
        }

        .icon-wrapper {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            animation: float 4s ease-in-out infinite;
        }

        .card-title {
            font-family: 'Righteous', cursive;
            font-size: 2.2rem;
            margin-bottom: 1rem;
            color: #ffffff;
            letter-spacing: 1px;
        }

        .card-desc {
            font-size: 1.05rem;
            line-height: 1.6;
            color: #a0a5bc;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        .badge-container {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .card-badge {
            display: inline-block;
            padding: 0.5rem 1.2rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 700;
            background: rgba(0, 0, 0, 0.4);
        }

        .card:nth-child(1) .card-badge {
            color: #A385FF;
            border: 1px solid rgba(132, 94, 194, 0.4);
        }

        .card:nth-child(2) .card-badge {
            color: var(--primary);
            border: 1px solid rgba(255, 107, 157, 0.4);
        }

        .cta-text {
            font-weight: bold;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.3s ease;
        }

        .card:nth-child(1) .cta-text {
            color: #A385FF;
        }

        .card:nth-child(2) .cta-text {
            color: var(--primary);
        }

        .card:hover .cta-text {
            gap: 15px;
        }

        .pixel-sprite-small {
            width: 1px;
            height: 1px;
            position: relative;
            transform: scale(10) translate(-7.5px, -4px);
        }

        .naruto-mini {
            box-shadow: 6px 0px 0 2px #FFD700, 7px 0px 0 2px #FFD700, 8px 0px 0 2px #FFD700, 9px 0px 0 2px #FFD700, 5px 1px 0 2px #FFD700, 6px 1px 0 2px #FFD700, 7px 1px 0 2px #FFD700, 8px 1px 0 2px #FFD700, 9px 1px 0 2px #FFD700, 10px 1px 0 2px #FFD700, 5px 2px 0 2px #FFD700, 6px 2px 0 2px #FFD700, 7px 2px 0 2px #FFD700, 8px 2px 0 2px #FFD700, 9px 2px 0 2px #FFD700, 10px 2px 0 2px #FFD700, 6px 3px 0 2px #FFD700, 7px 3px 0 2px #FFD700, 8px 3px 0 2px #FFD700, 9px 3px 0 2px #FFD700, 4px 4px 0 2px #0000FF, 5px 4px 0 2px #0000FF, 6px 4px 0 2px #0000FF, 7px 4px 0 2px #0000FF, 8px 4px 0 2px #0000FF, 9px 4px 0 2px #0000FF, 10px 4px 0 2px #0000FF, 11px 4px 0 2px #0000FF, 5px 5px 0 2px #FFE4B5, 6px 5px 0 2px #FFE4B5, 7px 5px 0 2px #FFE4B5, 8px 5px 0 2px #FFE4B5, 9px 5px 0 2px #FFE4B5, 10px 5px 0 2px #FFE4B5, 5px 6px 0 2px #FFE4B5, 6px 6px 0 2px #FFE4B5, 7px 6px 0 2px #000, 8px 6px 0 2px #000, 9px 6px 0 2px #FFE4B5, 10px 6px 0 2px #FFE4B5, 5px 7px 0 2px #FFE4B5, 6px 7px 0 2px #FFE4B5, 7px 7px 0 2px #FFE4B5, 8px 7px 0 2px #FFE4B5, 9px 7px 0 2px #FFE4B5, 10px 7px 0 2px #FFE4B5, 5px 8px 0 2px #FFA500, 6px 8px 0 2px #FFA500, 7px 8px 0 2px #FFA500, 8px 8px 0 2px #FFA500, 9px 8px 0 2px #FFA500, 10px 8px 0 2px #FFA500;
        }

        .sticker-preview {
            font-size: 5rem;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.5));
        }

        /* MODAL AUTHENTICATION */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            transform: translateY(20px);
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .modal-overlay.active .auth-card {
            transform: translateY(0);
        }

        .close-modal {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            color: #a0a5bc;
            cursor: pointer;
            font-size: 1.5rem;
            transition: color 0.2s;
        }

        .close-modal:hover {
            color: #fff;
        }

        .auth-title {
            font-family: 'Righteous', cursive;
            font-size: 2rem;
            color: #fff;
            margin-bottom: 2rem;
            text-align: center;
        }

        .input-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 0.9rem;
            color: #a0a5bc;
            margin-bottom: 0.5rem;
        }

        .input-group input {
            width: 100%;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-group input:focus {
            border-color: var(--primary);
        }

        .auth-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            margin-top: 1rem;
            transition: transform 0.2s;
        }

        .auth-submit:hover {
            transform: translateY(-2px);
        }

        .auth-switch {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #a0a5bc;
        }

        .auth-switch a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .auth-switch a:hover {
            text-decoration: underline;
        }

        .hidden-element {
            display: none !important;
        }

        /* ANIMATIONS */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes pulseOrb {
            0% {
                transform: scale(1);
                opacity: 0.3;
            }

            100% {
                transform: scale(1.2);
                opacity: 0.5;
            }
        }

        .footer {
            margin-top: 4rem;
            padding: 2rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            width: 100%;
        }

        .footer-text {
            font-size: 0.95rem;
            color: #6a718f;
            text-align: center;
        }

        @media (max-width: 768px) {
            .cards-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .title {
                font-size: 3rem;
            }
        }
    </style>
</head>

<body>

    <div class="top-nav">
        <?php if (isset($_SESSION['user_name'])): ?>
            <span class="user-greeting">Halo,
                <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
            </span>
            <button class="auth-btn btn-logout" onclick="location.href='logout.php'">Logout</button>
        <?php else: ?>
            <button class="auth-btn btn-login" onclick="openAuthModal('login')">Masuk</button>
            <button class="auth-btn btn-register" onclick="openAuthModal('register')">Daftar</button>
        <?php endif; ?>
    </div>

    <div class="container">
        <div class="header">
            <span class="badge-hub">Project Hub</span>
            <h1 class="title">PlayAI.</h1>
            <p class="subtitle">Satu portal, dua dunia tak terbatas. Pilih mesin AI mana yang ingin kamu kendalikan hari
                ini.</p>
        </div>

        <div class="cards-container">
            <div class="card" onclick="checkAuthAndRedirect('storygen.php')">
                <div class="icon-wrapper">
                    <div class="pixel-sprite-small naruto-mini"></div>
                </div>
                <h2 class="card-title">Story Roleplay</h2>
                <p class="card-desc">Ambil alih kendali cerita. Buat keputusan krusial, kembangkan karaktermu, dan
                    saksikan AI merajut skenario epik secara real-time.</p>
                <div class="badge-container">
                    <span class="card-badge">🧠 Nemotron-120B</span>
                </div>
                <span class="cta-text">Mulai Petualangan <span style="font-size: 1.2em;">→</span></span>
            </div>

            <div class="card" onclick="checkAuthAndRedirect('stickergen.php')">
                <div class="icon-wrapper">
                    <span class="sticker-preview">🎨</span>
                </div>
                <h2 class="card-title">Sticker Studio</h2>
                <p class="card-desc">Pabrik stiker pribadi di genggamanmu. Ubah ide liar menjadi stiker vektor, 3D, atau
                    pixel art instan dengan kekuatan FLUX AI.</p>
                <div class="badge-container">
                    <span class="card-badge">✨ FLUX.1 Schnell</span>
                </div>
                <span class="cta-text">Buka Generator <span style="font-size: 1.2em;">→</span></span>
            </div>
        </div>
    </div>

    <div id="authModal" class="modal-overlay">
        <div class="auth-card">
            <span class="close-modal" onclick="closeAuthModal()">✖</span>
            <h2 id="modalTitle" class="auth-title">Masuk</h2>

            <form id="authForm" action="auth.php" method="POST">
                <input type="hidden" name="action" id="authAction" value="login">

                <div class="input-group hidden-element" id="nameGroup">
                    <label>Nama Panggilan</label>
                    <input type="text" name="name" id="nameInput" placeholder="Contoh: Lili">
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="email@contoh.com">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>

                <button type="submit" id="submitBtn" class="auth-submit">Masuk</button>
            </form>

            <div class="auth-switch" id="switchText">
                Belum punya akun? <a href="#" onclick="toggleAuthMode('register')">Daftar sekarang</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <p class="footer-text">© 2026 PlayAI Hub. Coded with 💻 & AI Magic.</p>
    </div>

    <script>
        // Data session dari PHP dioper ke Javascript
        const isLoggedIn = <?php echo isset($_SESSION['user_name']) ? 'true' : 'false'; ?>;
        const modal = document.getElementById('authModal');
        const modalTitle = document.getElementById('modalTitle');
        const authAction = document.getElementById('authAction');
        const nameGroup = document.getElementById('nameGroup');
        const nameInput = document.getElementById('nameInput');
        const submitBtn = document.getElementById('submitBtn');
        const switchText = document.getElementById('switchText');

        // Fungsi Membuka Modal
        function openAuthModal(mode) {
            modal.classList.add('active');
            toggleAuthMode(mode);
        }

        // Fungsi Menutup Modal
        function closeAuthModal() {
            modal.classList.remove('active');
        }

        // Ganti tampilan antara Login & Register
        function toggleAuthMode(mode) {
            if (mode === 'register') {
                modalTitle.textContent = 'Buat Akun';
                authAction.value = 'register';
                nameGroup.classList.remove('hidden-element');
                nameInput.setAttribute('required', 'true');
                submitBtn.textContent = 'Daftar';
                switchText.innerHTML =
                    'Sudah punya akun? <a href="#" onclick="toggleAuthMode(\'login\')">Masuk di sini</a>';
            } else {
                modalTitle.textContent = 'Selamat Datang';
                authAction.value = 'login';
                nameGroup.classList.add('hidden-element');
                nameInput.removeAttribute('required');
                submitBtn.textContent = 'Masuk';
                switchText.innerHTML =
                    'Belum punya akun? <a href="#" onclick="toggleAuthMode(\'register\')">Daftar sekarang</a>';
            }
        }

        // Fungsi Pencegah Akses Jika Belum Login
        function checkAuthAndRedirect(targetUrl) {
            if (isLoggedIn) {
                location.href = targetUrl;
            } else {
                // Jika belum login, paksa buka modal
                openAuthModal('login');
                // Sedikit sentuhan interaktif
                modalTitle.textContent = 'Login Dulu Yuk!';
            }
        }
    </script>
</body>

</html>