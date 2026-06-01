<?php
session_start();
// Jika user belum login, paksa tendang kembali ke halaman utama
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayAI | Sticker Studio</title>

    <script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=DM+Sans:wght@400;500;700&display=swap"
        rel="stylesheet">

    <style>
        /* === CSS RESET & VARIABLES === */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #FF6B9D;
            --secondary: #FEC84B;
            --tertiary: #A385FF;
            --dark: #070913;
            --card-bg: rgba(26, 31, 58, 0.5);
            --card-border: rgba(255, 255, 255, 0.1);
            --text: #e0e0e0;
        }

        /* === BASE LAYOUT === */
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--dark);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* Ubah agar halaman bisa di-scroll ke bawah untuk galeri */
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            overflow-x: hidden;
            position: relative;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -10%;
            right: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(255, 107, 157, 0.15) 0%, transparent 70%);
            z-index: -1;
            animation: pulseOrb 8s infinite alternate;
        }

        /* === NAVIGASI === */
        .back-to-hub {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: #a0a5bc;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
            transition: color 0.2s;
            z-index: 10;
        }

        .back-to-hub:hover {
            color: #fff;
        }

        /* === REACT ROOT CONTAINER === */
        #root {
            width: 100%;
            max-width: 1200px;
            padding: 4rem 2rem 4rem;
            animation: fadeInScreen 0.6s ease-out;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .title {
            font-family: 'Righteous', cursive;
            font-size: 4rem;
            background: linear-gradient(to right, #FF6B9D, #FEC84B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            text-shadow: 0 10px 30px rgba(255, 107, 157, 0.2);
        }

        .subtitle {
            font-size: 1.1rem;
            color: #a0a5bc;
            max-width: 600px;
            margin: 0 auto;
        }

        /* === MAIN GRID === */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
        }

        @media (max-width: 900px) {
            .main-grid {
                grid-template-columns: 1fr;
            }

            .title {
                font-size: 3rem;
            }
        }

        /* === GLASSMORPHISM PANELS === */
        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        /* === INPUT STYLES === */
        .section-label {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .prompt-input {
            width: 100%;
            min-height: 120px;
            padding: 1.2rem;
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 1.05rem;
            margin-bottom: 2rem;
            resize: vertical;
            transition: all 0.3s;
        }

        .prompt-input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px rgba(255, 107, 157, 0.2);
        }

        .prompt-input::placeholder {
            color: #6a718f;
        }

        /* === STYLE BUTTONS === */
        .style-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.8rem;
            margin-bottom: 2.5rem;
        }

        .style-btn {
            padding: 1rem 0.5rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            color: #a0a5bc;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
        }

        .style-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .style-btn.active {
            background: rgba(255, 107, 157, 0.15);
            border-color: var(--primary);
            color: #fff;
            box-shadow: inset 0 0 15px rgba(255, 107, 157, 0.2);
        }

        .style-icon {
            font-size: 1.5rem;
        }

        /* === GENERATE BUTTON === */
        .generate-btn {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #FF6B9D 0%, #FF8E53 100%);
            border: none;
            border-radius: 16px;
            color: white;
            font-family: 'Righteous', cursive;
            font-size: 1.3rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: auto;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .generate-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 107, 157, 0.4);
        }

        .generate-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .generate-btn:disabled {
            background: #2a304a;
            color: #6a718f;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* === OUTPUT AREA === */
        .output-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 450px;
            background: rgba(0, 0, 0, 0.3);
            border: 2px dashed rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .output-image {
            max-width: 85%;
            max-height: 350px;
            object-fit: contain;
            filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.5));
            animation: slideUp 0.5s ease-out;
        }

        .download-btn {
            margin-top: 2rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.8rem 2rem;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .download-btn:hover {
            background: var(--tertiary);
            border-color: var(--tertiary);
            box-shadow: 0 5px 15px rgba(163, 133, 255, 0.3);
        }

        .placeholder-text {
            color: #6a718f;
            margin-top: 1rem;
            font-size: 1.1rem;
        }

        /* === GALLERY SECTION === */
        .gallery-section {
            margin-top: 5rem;
            width: 100%;
            animation: slideUp 0.8s ease-out;
        }

        .gallery-title {
            font-family: 'Righteous', cursive;
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 1rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .gallery-card {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1rem;
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
            border-color: var(--tertiary);
            box-shadow: 0 10px 20px rgba(163, 133, 255, 0.15);
        }

        .gallery-img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 1rem;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.4));
        }

        .gallery-prompt {
            font-size: 0.85rem;
            color: #a0a5bc;
            text-align: center;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* === ANIMATIONS === */
        @keyframes fadeInScreen {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
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

        @keyframes bounceGlow {

            0%,
            100% {
                transform: translateY(0);
                filter: drop-shadow(0 0 10px rgba(255, 107, 157, 0.5));
            }

            50% {
                transform: translateY(-15px);
                filter: drop-shadow(0 0 25px rgba(255, 107, 157, 0.8));
            }
        }
    </style>
</head>

<body>
    <a href="index.php" class="back-to-hub">← Kembali ke Hub</a>
    <div id="root"></div>

    <script type="text/babel">
        const { useState, useEffect } = React;

        function App() {
            const [prompt, setPrompt] = useState('');
            const [style, setStyle] = useState('cartoon');
            const [loading, setLoading] = useState(false);
            const [imageSrc, setImageSrc] = useState(null);
            
            // State untuk Galeri Database
            const [gallery, setGallery] = useState([]);
            const [loadingGallery, setLoadingGallery] = useState(true);

            const styles = [
                { id: 'cartoon', icon: '🎨', label: 'Cartoon' },
                { id: '3d', icon: '🧊', label: '3D Clay' },
                { id: 'pixel', icon: '👾', label: 'Pixel Art' },
                { id: 'anime', icon: '✨', label: 'Anime' },
                { id: 'logo', icon: '💠', label: 'Mascot' },
                { id: 'neon', icon: '💡', label: 'Neon Glow' }
            ];

            // Fungsi mengambil data dari database saat halaman pertama kali dibuka
            const fetchGallery = async () => {
                setLoadingGallery(true);
                try {
                    const res = await fetch('load_stickers.php');
                    const data = await res.json();
                    if (data.status === 'success') {
                        setGallery(data.data);
                    }
                } catch (err) {
                    console.error('Gagal memuat galeri', err);
                } finally {
                    setLoadingGallery(false);
                }
            };

            // React Lifecycle: Panggil API load_stickers saat komponen mount
            useEffect(() => {
                fetchGallery();
            }, []);

            // Helper: Mengubah file gambar mentah (Blob) menjadi format Teks Base64 agar bisa masuk Database
            const blobToBase64 = (blob) => {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = () => resolve(reader.result);
                    reader.onerror = reject;
                });
            };

            const generateImage = async () => {
                if (!prompt) return;
                setLoading(true);
                setImageSrc(null);

                const stylePrompts = {
                    cartoon: 'vibrant vector cartoon sticker, bold outlines, flat colors, white border',
                    '3d': 'cute 3d isometric sticker, claymorphism style, glossy, white border',
                    pixel: 'pixel art sticker, 8-bit retro style, white border',
                    anime: 'chibi anime sticker, kawaii, detailed, white border',
                    logo: 'minimalist vector mascot logo sticker, geometric, white border',
                    neon: 'cyberpunk neon sticker, glowing, dark background, thick white border'
                };

                const fullPrompt = `${prompt}, ${stylePrompts[style]}, isolated on solid white background, die-cut style, high resolution`;

                try {
                    // API Call ke generator gambar (proxy.php lokalmu)
                    const response = await fetch('./proxy.php', {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ inputs: fullPrompt }),
                    });

                    if (!response.ok) {
                        const errorMsg = await response.text();
                        throw new Error(errorMsg || "Gagal menghubungi Server AI.");
                    }

                    const blob = await response.blob();
                    
                    // Tampilkan gambar langsung ke layar user
                    const localUrl = URL.createObjectURL(blob);
                    setImageSrc(localUrl);

                    // --- PROSES AUTO SAVE KE DATABASE ---
                    try {
                        const base64Data = await blobToBase64(blob);
                        const saveRes = await fetch('save_sticker.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                prompt: prompt,
                                image_url: base64Data
                            })
                        });
                        const saveResult = await saveRes.json();
                        
                        if (saveResult.status === 'success') {
                            fetchGallery(); // Refresh otomatis galeri di bawah jika berhasil simpan
                        }
                    } catch (saveErr) {
                        console.error("Gagal melakukan auto-save ke MySQL:", saveErr);
                    }

                } catch (error) {
                    alert("Info: " + error.message);
                } finally {
                    setLoading(false);
                }
            };

            return (
                <div>
                    <div className="header">
                        <h1 className="title">Sticker Studio</h1>
                        <p className="subtitle">Pabrik stiker pribadi bertenaga FLUX AI. Wujudkan imajinasi gilamu menjadi desain siap cetak.</p>
                    </div>

                    <div className="main-grid">
                        {/* PANEL KIRI: KONTROL */}
                        <div className="panel">
                            <label className="section-label">
                                <span>📝</span> Deskripsikan Idenya
                            </label>
                            <textarea
                                className="prompt-input"
                                value={prompt}
                                onChange={(e) => setPrompt(e.target.value)}
                                placeholder="Contoh: Kucing oren pakai kacamata hitam minum boba..."
                            />

                            <label className="section-label">
                                <span>🎭</span> Pilih Gaya Visual
                            </label>
                            <div className="style-options">
                                {styles.map(s => (
                                    <button
                                        key={s.id}
                                        className={`style-btn ${style === s.id ? 'active' : ''}`}
                                        onClick={() => setStyle(s.id)}
                                    >
                                        <span className="style-icon">{s.icon}</span>
                                        {s.label}
                                    </button>
                                ))}
                            </div>

                            <button
                                className="generate-btn"
                                onClick={generateImage}
                                disabled={loading || !prompt.trim()}
                            >
                                {loading ? '⏳ AI Sedang Melukis...' : '🚀 Generate Sticker'}
                            </button>
                        </div>

                        {/* PANEL KANAN: PREVIEW OUTPUT */}
                        <div className="panel output-panel">
                            {loading ? (
                                <div style={{ textAlign: 'center' }}>
                                    <div style={{ fontSize: '4rem', animation: 'bounceGlow 1.5s infinite' }}>✨</div>
                                    <p className="placeholder-text" style={{ marginTop: '2rem' }}>Memproses piksel...</p>
                                </div>
                            ) : imageSrc ? (
                                <div style={{ textAlign: 'center', width: '100%' }}>
                                    <img src={imageSrc} className="output-image" alt="Generated Sticker" />
                                    <br />
                                    <button
                                        className="download-btn"
                                        style={{ margin: '2rem auto 0' }}
                                        onClick={() => {
                                            const a = document.createElement('a');
                                            a.href = imageSrc;
                                            a.download = `sticker-${style}-${Date.now()}.jpg`;
                                            a.click();
                                        }}
                                    >
                                        ⬇️ Simpan Gambar
                                    </button>
                                </div>
                            ) : (
                                <div style={{ textAlign: 'center' }}>
                                    <div style={{ fontSize: '4rem', opacity: 0.5, filter: 'grayscale(1)' }}>🖼️</div>
                                    <p className="placeholder-text">Kanvas masih kosong.<br />Stiker buatanmu akan muncul di sini.</p>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* GALERI KOLEKSI PRIBADI (Terhubung dengan Database) */}
                    <div className="gallery-section">
                        <h2 className="gallery-title">🎨 Galeri Koleksimu</h2>
                        
                        {loadingGallery ? (
                            <p style={{textAlign: 'center', color: '#a0a5bc'}}>Memuat koleksi dari brankas data...</p>
                        ) : gallery.length > 0 ? (
                            <div className="gallery-grid">
                                {gallery.map((item, index) => (
                                    <div key={index} className="gallery-card">
                                        <img src={item.image_url} alt={item.prompt} className="gallery-img" />
                                        <p className="gallery-prompt" title={item.prompt}>{item.prompt}</p>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p style={{textAlign: 'center', color: '#6a718f'}}>Kamu belum membuat stiker apapun. Yuk mulai imajinasimu!</p>
                        )}
                    </div>
                </div>
            );
        }

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<App />);
    </script>
</body>

</html>