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
    <title>PlayAI | Story Roleplay</title>
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
            --primary: #A385FF;
            --secondary: #FF6B9D;
            --accent: #FEC84B;
            --dark: #070913;
            --card-bg: rgba(26, 31, 58, 0.6);
            --card-border: rgba(255, 255, 255, 0.1);
            --text: #e0e0e0;
            --danger: #ff4d6d;
        }

        /* === BASE LAYOUT === */
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--dark);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 20%;
            left: 20%;
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, rgba(163, 133, 255, 0.15) 0%, transparent 70%);
            z-index: -1;
        }

        .screen {
            width: 100%;
            max-width: 1200px;
            min-height: 80vh;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            animation: fadeInScreen 0.6s ease-out;
        }

        .hidden {
            display: none !important;
        }

        /* === HEADER === */
        .page-title {
            font-family: 'Righteous', cursive;
            font-size: 3.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #A385FF, #FF6B9D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 30px rgba(163, 133, 255, 0.2);
            text-align: center;
        }

        .page-subtitle {
            color: #a0a5bc;
            margin-bottom: 3rem;
            text-align: center;
            font-size: 1.1rem;
        }

        /* === CHARACTER SELECTOR GRID === */
        .character-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            width: 100%;
            max-width: 1000px;
        }

        .character-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .character-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: 0 15px 30px rgba(163, 133, 255, 0.15);
        }

        .character-name {
            font-family: 'Righteous', cursive;
            font-size: 1.4rem;
            margin: 1.5rem 0 0.5rem;
            color: #fff;
        }

        .character-lore {
            font-size: 0.9rem;
            color: #a0a5bc;
            line-height: 1.5;
        }

        /* Icon Wrapper */
        .icon-wrapper {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        /* === PIXEL SPRITES === */
        .pixel-sprite {
            width: 1px;
            height: 1px;
            position: relative;
            transform: scale(7) translate(-5px, -5px);
            transition: transform 0.3s ease;
        }

        .character-card:hover .pixel-sprite {
            transform: scale(8) translate(-5px, -5px);
        }

        /* KOORDINAT PIXEL ART */
        .naruto-sprite {
            box-shadow: 6px 0px 0 1px #FFD700, 7px 0px 0 1px #FFD700, 8px 0px 0 1px #FFD700, 9px 0px 0 1px #FFD700, 5px 1px 0 1px #FFD700, 6px 1px 0 1px #FFD700, 7px 1px 0 1px #FFD700, 8px 1px 0 1px #FFD700, 9px 1px 0 1px #FFD700, 10px 1px 0 1px #FFD700, 5px 2px 0 1px #FFD700, 6px 2px 0 1px #FFD700, 7px 2px 0 1px #FFD700, 8px 2px 0 1px #FFD700, 9px 2px 0 1px #FFD700, 10px 2px 0 1px #FFD700, 6px 3px 0 1px #FFD700, 7px 3px 0 1px #FFD700, 8px 3px 0 1px #FFD700, 9px 3px 0 1px #FFD700, 4px 4px 0 1px #0000FF, 5px 4px 0 1px #0000FF, 6px 4px 0 1px #0000FF, 7px 4px 0 1px #0000FF, 8px 4px 0 1px #0000FF, 9px 4px 0 1px #0000FF, 10px 4px 0 1px #0000FF, 11px 4px 0 1px #0000FF, 5px 5px 0 1px #FFE4B5, 6px 5px 0 1px #FFE4B5, 7px 5px 0 1px #FFE4B5, 8px 5px 0 1px #FFE4B5, 9px 5px 0 1px #FFE4B5, 10px 5px 0 1px #FFE4B5, 5px 6px 0 1px #FFE4B5, 6px 6px 0 1px #FFE4B5, 7px 6px 0 1px #000, 8px 6px 0 1px #000, 9px 6px 0 1px #FFE4B5, 10px 6px 0 1px #FFE4B5, 5px 7px 0 1px #FFE4B5, 6px 7px 0 1px #FFE4B5, 7px 7px 0 1px #FFE4B5, 8px 7px 0 1px #FFE4B5, 9px 7px 0 1px #FFE4B5, 10px 7px 0 1px #FFE4B5, 5px 8px 0 1px #FFA500, 6px 8px 0 1px #FFA500, 7px 8px 0 1px #FFA500, 8px 8px 0 1px #FFA500, 9px 8px 0 1px #FFA500, 10px 8px 0 1px #FFA500, 5px 9px 0 1px #FFA500, 6px 9px 0 1px #FFA500, 7px 9px 0 1px #FFA500, 8px 9px 0 1px #FFA500, 9px 9px 0 1px #FFA500, 10px 9px 0 1px #FFA500, 5px 10px 0 1px #FFA500, 6px 10px 0 1px #FFA500, 7px 10px 0 1px #FFA500, 8px 10px 0 1px #FFA500, 9px 10px 0 1px #FFA500, 10px 10px 0 1px #FFA500;
        }

        .luffy-sprite {
            box-shadow: 6px 0px 0 1px #FFD700, 7px 0px 0 1px #FFD700, 8px 0px 0 1px #FFD700, 9px 0px 0 1px #FFD700, 5px 1px 0 1px #FFD700, 6px 1px 0 1px #FFD700, 7px 1px 0 1px #FFD700, 8px 1px 0 1px #FFD700, 9px 1px 0 1px #FFD700, 10px 1px 0 1px #FFD700, 4px 2px 0 1px #FFD700, 5px 2px 0 1px #FFD700, 6px 2px 0 1px #FFD700, 7px 2px 0 1px #FFD700, 8px 2px 0 1px #FFD700, 9px 2px 0 1px #FFD700, 10px 2px 0 1px #FFD700, 11px 2px 0 1px #FFD700, 5px 3px 0 1px #FFE4B5, 6px 3px 0 1px #FFE4B5, 7px 3px 0 1px #FFE4B5, 8px 3px 0 1px #FFE4B5, 9px 3px 0 1px #FFE4B5, 10px 3px 0 1px #FFE4B5, 5px 4px 0 1px #FFE4B5, 6px 4px 0 1px #FFE4B5, 7px 4px 0 1px #000, 8px 4px 0 1px #000, 9px 4px 0 1px #FFE4B5, 10px 4px 0 1px #FFE4B5, 5px 5px 0 1px #FFE4B5, 6px 5px 0 1px #FFE4B5, 7px 5px 0 1px #FFE4B5, 8px 5px 0 1px #FFE4B5, 9px 5px 0 1px #FFE4B5, 10px 5px 0 1px #FFE4B5, 5px 6px 0 1px #FF0000, 6px 6px 0 1px #FF0000, 7px 6px 0 1px #FF0000, 8px 6px 0 1px #FF0000, 9px 6px 0 1px #FF0000, 10px 6px 0 1px #FF0000, 5px 7px 0 1px #FF0000, 6px 7px 0 1px #FF0000, 7px 7px 0 1px #FF0000, 8px 7px 0 1px #FF0000, 9px 7px 0 1px #FF0000, 10px 7px 0 1px #FF0000, 5px 8px 0 1px #FF0000, 6px 8px 0 1px #FF0000, 7px 8px 0 1px #FF0000, 8px 8px 0 1px #FF0000, 9px 8px 0 1px #FF0000, 10px 8px 0 1px #FF0000, 5px 9px 0 1px #0000FF, 6px 9px 0 1px #0000FF, 7px 9px 0 1px #0000FF, 8px 9px 0 1px #0000FF, 9px 9px 0 1px #0000FF, 10px 9px 0 1px #0000FF;
        }

        .tanjiro-sprite {
            box-shadow: 6px 0px 0 1px #000000, 7px 0px 0 1px #000000, 8px 0px 0 1px #000000, 9px 0px 0 1px #000000, 5px 1px 0 1px #000000, 6px 1px 0 1px #000000, 7px 1px 0 1px #000000, 8px 1px 0 1px #000000, 9px 1px 0 1px #000000, 10px 1px 0 1px #000000, 5px 2px 0 1px #000000, 6px 2px 0 1px #000000, 7px 2px 0 1px #000000, 8px 2px 0 1px #000000, 9px 2px 0 1px #000000, 10px 2px 0 1px #000000, 6px 3px 0 1px #000000, 7px 3px 0 1px #000000, 8px 3px 0 1px #000000, 9px 3px 0 1px #000000, 4px 3px 0 1px #FF0000, 11px 3px 0 1px #FF0000, 5px 4px 0 1px #FFE4B5, 6px 4px 0 1px #FFE4B5, 7px 4px 0 1px #FFE4B5, 8px 4px 0 1px #FFE4B5, 9px 4px 0 1px #FFE4B5, 10px 4px 0 1px #FFE4B5, 5px 5px 0 1px #FFE4B5, 6px 5px 0 1px #FFE4B5, 7px 5px 0 1px #000, 8px 5px 0 1px #000, 9px 5px 0 1px #FFE4B5, 10px 5px 0 1px #FFE4B5, 5px 6px 0 1px #FFE4B5, 6px 6px 0 1px #FFE4B5, 7px 6px 0 1px #FFE4B5, 8px 6px 0 1px #FFE4B5, 9px 6px 0 1px #FFE4B5, 10px 6px 0 1px #FFE4B5, 5px 7px 0 1px #006400, 6px 7px 0 1px #000000, 7px 7px 0 1px #006400, 8px 7px 0 1px #000000, 9px 7px 0 1px #006400, 10px 7px 0 1px #000000, 5px 8px 0 1px #000000, 6px 8px 0 1px #006400, 7px 8px 0 1px #000000, 8px 8px 0 1px #006400, 9px 8px 0 1px #000000, 10px 8px 0 1px #006400, 5px 9px 0 1px #006400, 6px 9px 0 1px #000000, 7px 9px 0 1px #006400, 8px 9px 0 1px #000000, 9px 9px 0 1px #006400, 10px 9px 0 1px #000000;
        }

        .eren-sprite {
            box-shadow: 6px 0px 0 1px #8B4513, 7px 0px 0 1px #8B4513, 8px 0px 0 1px #8B4513, 9px 0px 0 1px #8B4513, 5px 1px 0 1px #8B4513, 6px 1px 0 1px #8B4513, 7px 1px 0 1px #8B4513, 8px 1px 0 1px #8B4513, 9px 1px 0 1px #8B4513, 10px 1px 0 1px #8B4513, 5px 2px 0 1px #8B4513, 6px 2px 0 1px #8B4513, 7px 2px 0 1px #8B4513, 8px 2px 0 1px #8B4513, 9px 2px 0 1px #8B4513, 10px 2px 0 1px #8B4513, 6px 3px 0 1px #8B4513, 7px 3px 0 1px #8B4513, 8px 3px 0 1px #8B4513, 9px 3px 0 1px #8B4513, 4px 4px 0 1px #8B4513, 5px 4px 0 1px #8B4513, 6px 4px 0 1px #8B4513, 7px 4px 0 1px #8B4513, 8px 4px 0 1px #8B4513, 9px 4px 0 1px #8B4513, 10px 4px 0 1px #8B4513, 11px 4px 0 1px #8B4513, 5px 5px 0 1px #FFE4B5, 6px 5px 0 1px #FFE4B5, 7px 5px 0 1px #FFE4B5, 8px 5px 0 1px #FFE4B5, 9px 5px 0 1px #FFE4B5, 10px 5px 0 1px #FFE4B5, 5px 6px 0 1px #FFE4B5, 6px 6px 0 1px #FFE4B5, 7px 6px 0 1px #000, 8px 6px 0 1px #000, 9px 6px 0 1px #FFE4B5, 10px 6px 0 1px #FFE4B5, 5px 7px 0 1px #FFE4B5, 6px 7px 0 1px #FFE4B5, 7px 7px 0 1px #FFE4B5, 8px 7px 0 1px #FFE4B5, 9px 7px 0 1px #FFE4B5, 10px 7px 0 1px #FFE4B5, 5px 8px 0 1px #006400, 6px 8px 0 1px #006400, 7px 8px 0 1px #006400, 8px 8px 0 1px #006400, 9px 8px 0 1px #006400, 10px 8px 0 1px #006400, 5px 9px 0 1px #006400, 6px 9px 0 1px #006400, 7px 9px 0 1px #006400, 8px 9px 0 1px #006400, 9px 9px 0 1px #006400, 10px 9px 0 1px #006400;
        }

        .gojo-sprite {
            box-shadow: 6px 0px 0 1px #FFFFFF, 7px 0px 0 1px #FFFFFF, 8px 0px 0 1px #FFFFFF, 9px 0px 0 1px #FFFFFF, 5px 1px 0 1px #FFFFFF, 6px 1px 0 1px #FFFFFF, 7px 1px 0 1px #FFFFFF, 8px 1px 0 1px #FFFFFF, 9px 1px 0 1px #FFFFFF, 10px 1px 0 1px #FFFFFF, 5px 2px 0 1px #FFFFFF, 6px 2px 0 1px #FFFFFF, 7px 2px 0 1px #FFFFFF, 8px 2px 0 1px #FFFFFF, 9px 2px 0 1px #FFFFFF, 10px 2px 0 1px #FFFFFF, 6px 3px 0 1px #FFFFFF, 7px 3px 0 1px #FFFFFF, 8px 3px 0 1px #FFFFFF, 9px 3px 0 1px #FFFFFF, 5px 4px 0 1px #000000, 6px 4px 0 1px #000000, 7px 4px 0 1px #000000, 8px 4px 0 1px #000000, 9px 4px 0 1px #000000, 10px 4px 0 1px #000000, 5px 5px 0 1px #F5F5F5, 6px 5px 0 1px #F5F5F5, 7px 5px 0 1px #F5F5F5, 8px 5px 0 1px #F5F5F5, 9px 5px 0 1px #F5F5F5, 10px 5px 0 1px #F5F5F5, 5px 6px 0 1px #F5F5F5, 6px 6px 0 1px #F5F5F5, 7px 6px 0 1px #000, 8px 6px 0 1px #000, 9px 6px 0 1px #F5F5F5, 10px 6px 0 1px #F5F5F5, 5px 7px 0 1px #F5F5F5, 6px 7px 0 1px #F5F5F5, 7px 7px 0 1px #F5F5F5, 8px 7px 0 1px #F5F5F5, 9px 7px 0 1px #F5F5F5, 10px 7px 0 1px #F5F5F5, 5px 8px 0 1px #000000, 6px 8px 0 1px #000000, 7px 8px 0 1px #000000, 8px 8px 0 1px #000000, 9px 8px 0 1px #000000, 10px 8px 0 1px #000000, 5px 9px 0 1px #000000, 6px 9px 0 1px #000000, 7px 9px 0 1px #000000, 8px 9px 0 1px #000000, 9px 9px 0 1px #000000, 10px 9px 0 1px #000000;
        }

        .izuku-sprite {
            box-shadow: 6px 0px 0 1px #008000, 7px 0px 0 1px #008000, 8px 0px 0 1px #008000, 9px 0px 0 1px #008000, 5px 1px 0 1px #008000, 6px 1px 0 1px #008000, 7px 1px 0 1px #008000, 8px 1px 0 1px #008000, 9px 1px 0 1px #008000, 10px 1px 0 1px #008000, 5px 2px 0 1px #008000, 6px 2px 0 1px #008000, 7px 2px 0 1px #008000, 8px 2px 0 1px #008000, 9px 2px 0 1px #008000, 10px 2px 0 1px #008000, 6px 3px 0 1px #008000, 7px 3px 0 1px #008000, 8px 3px 0 1px #008000, 9px 3px 0 1px #008000, 5px 4px 0 1px #FFE4B5, 6px 4px 0 1px #FFE4B5, 7px 4px 0 1px #FFE4B5, 8px 4px 0 1px #FFE4B5, 9px 4px 0 1px #FFE4B5, 10px 4px 0 1px #FFE4B5, 5px 5px 0 1px #FFE4B5, 6px 5px 0 1px #FFE4B5, 7px 5px 0 1px #000, 8px 5px 0 1px #000, 9px 5px 0 1px #FFE4B5, 10px 5px 0 1px #FFE4B5, 5px 6px 0 1px #FFE4B5, 6px 6px 0 1px #FFE4B5, 7px 6px 0 1px #FFE4B5, 8px 6px 0 1px #FFE4B5, 9px 6px 0 1px #FFE4B5, 10px 6px 0 1px #FFE4B5, 5px 7px 0 1px #008000, 6px 7px 0 1px #000000, 7px 7px 0 1px #008000, 8px 7px 0 1px #000000, 9px 7px 0 1px #008000, 10px 7px 0 1px #000000, 5px 8px 0 1px #000000, 6px 8px 0 1px #008000, 7px 8px 0 1px #000000, 8px 8px 0 1px #008000, 9px 8px 0 1px #000000, 10px 8px 0 1px #008000, 5px 9px 0 1px #008000, 6px 9px 0 1px #000000, 7px 9px 0 1px #008000, 8px 9px 0 1px #000000, 9px 9px 0 1px #008000, 10px 9px 0 1px #000000;
        }

        .edward-sprite {
            box-shadow: 6px 0px 0 1px #FFD700, 7px 0px 0 1px #FFD700, 8px 0px 0 1px #FFD700, 9px 0px 0 1px #FFD700, 5px 1px 0 1px #FFD700, 6px 1px 0 1px #FFD700, 7px 1px 0 1px #FFD700, 8px 1px 0 1px #FFD700, 9px 1px 0 1px #FFD700, 10px 1px 0 1px #FFD700, 5px 2px 0 1px #FFD700, 6px 2px 0 1px #FFD700, 7px 2px 0 1px #FFD700, 8px 2px 0 1px #FFD700, 9px 2px 0 1px #FFD700, 10px 2px 0 1px #FFD700, 6px 3px 0 1px #FFD700, 7px 3px 0 1px #FFD700, 8px 3px 0 1px #FFD700, 9px 3px 0 1px #FFD700, 4px 4px 0 1px #FF0000, 5px 4px 0 1px #FF0000, 6px 4px 0 1px #FF0000, 7px 4px 0 1px #FF0000, 8px 4px 0 1px #FF0000, 9px 4px 0 1px #FF0000, 10px 4px 0 1px #FF0000, 11px 4px 0 1px #FF0000, 5px 5px 0 1px #FFE4B5, 6px 5px 0 1px #FFE4B5, 7px 5px 0 1px #FFE4B5, 8px 5px 0 1px #FFE4B5, 9px 5px 0 1px #FFE4B5, 10px 5px 0 1px #FFE4B5, 5px 6px 0 1px #FFE4B5, 6px 6px 0 1px #FFE4B5, 7px 6px 0 1px #000, 8px 6px 0 1px #000, 9px 6px 0 1px #FFE4B5, 10px 6px 0 1px #FFE4B5, 5px 7px 0 1px #FFE4B5, 6px 7px 0 1px #FFE4B5, 7px 7px 0 1px #FFE4B5, 8px 7px 0 1px #FFE4B5, 9px 7px 0 1px #FFE4B5, 10px 7px 0 1px #FFE4B5, 5px 8px 0 1px #000000, 6px 8px 0 1px #000000, 7px 8px 0 1px #000000, 8px 8px 0 1px #000000, 9px 8px 0 1px #000000, 10px 8px 0 1px #000000, 5px 9px 0 1px #000000, 6px 9px 0 1px #000000, 7px 9px 0 1px #000000, 8px 9px 0 1px #000000, 9px 9px 0 1px #000000, 10px 9px 0 1px #000000;
        }

        .denji-sprite {
            box-shadow: 6px 0px 0 1px #FFD700, 7px 0px 0 1px #FFD700, 8px 0px 0 1px #FFD700, 9px 0px 0 1px #FFD700, 5px 1px 0 1px #FFD700, 6px 1px 0 1px #FFD700, 7px 1px 0 1px #FFD700, 8px 1px 0 1px #FFD700, 9px 1px 0 1px #FFD700, 10px 1px 0 1px #FFD700, 5px 2px 0 1px #FFD700, 6px 2px 0 1px #FFD700, 7px 2px 0 1px #FFD700, 8px 2px 0 1px #FFD700, 9px 2px 0 1px #FFD700, 10px 2px 0 1px #FFD700, 6px 3px 0 1px #FFD700, 7px 3px 0 1px #FFD700, 8px 3px 0 1px #FFD700, 9px 3px 0 1px #FFD700, 5px 4px 0 1px #C0C0C0, 6px 4px 0 1px #C0C0C0, 7px 4px 0 1px #C0C0C0, 8px 4px 0 1px #C0C0C0, 9px 4px 0 1px #C0C0C0, 10px 4px 0 1px #C0C0C0, 5px 5px 0 1px #FFE4B5, 6px 5px 0 1px #FFE4B5, 7px 5px 0 1px #FFE4B5, 8px 5px 0 1px #FFE4B5, 9px 5px 0 1px #FFE4B5, 10px 5px 0 1px #FFE4B5, 5px 6px 0 1px #FFE4B5, 6px 6px 0 1px #FFE4B5, 7px 6px 0 1px #000, 8px 6px 0 1px #000, 9px 6px 0 1px #FFE4B5, 10px 6px 0 1px #FFE4B5, 5px 7px 0 1px #FFE4B5, 6px 7px 0 1px #FFE4B5, 7px 7px 0 1px #FFE4B5, 8px 7px 0 1px #FFE4B5, 9px 7px 0 1px #FFE4B5, 10px 7px 0 1px #FFE4B5, 5px 8px 0 1px #F5F5DC, 6px 8px 0 1px #F5F5DC, 7px 8px 0 1px #F5F5DC, 8px 8px 0 1px #F5F5DC, 9px 8px 0 1px #F5F5DC, 10px 8px 0 1px #F5F5DC, 5px 9px 0 1px #F5F5DC, 6px 9px 0 1px #F5F5DC, 7px 9px 0 1px #F5F5DC, 8px 9px 0 1px #F5F5DC, 9px 9px 0 1px #F5F5DC, 10px 9px 0 1px #F5F5DC;
        }

        /* === STORY SCREEN === */
        #story-screen {
            justify-content: flex-start;
            padding-top: 3rem;
        }

        .story-header {
            font-family: 'Righteous', cursive;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .story-container {
            width: 100%;
            max-width: 900px;
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            line-height: 1.8;
            font-size: 1.15rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
        }

        .choices-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            max-width: 900px;
        }

        .choice-btn {
            padding: 1.2rem 2rem;
            font-size: 1.1rem;
            font-family: 'DM Sans', sans-serif;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(163, 133, 255, 0.3);
            border-radius: 12px;
            color: #ffffff;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .choice-btn::after {
            content: '→';
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.2s;
            color: var(--primary);
            font-size: 1.2rem;
        }

        .choice-btn:hover:not(:disabled) {
            background: rgba(163, 133, 255, 0.1);
            border-color: var(--primary);
            transform: translateX(10px);
        }

        .choice-btn:hover::after {
            opacity: 1;
            transform: translateX(0);
        }

        .choice-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* === LOADING & ERROR === */
        .loading-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
            padding: 1rem 2rem;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 30px;
        }

        .pixel-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            font-size: 1rem;
            color: #a0a5bc;
            letter-spacing: 1px;
        }

        .story-error {
            padding: 1rem 2rem;
            background: rgba(255, 77, 109, 0.1);
            border: 1px solid var(--danger);
            border-radius: 12px;
            color: #fff;
            margin-top: 1rem;
            animation: fadeIn 0.3s;
        }

        .retry-btn {
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, #A385FF, #FF6B9D);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: transform 0.2s;
        }

        .retry-btn:hover {
            transform: translateY(-2px);
        }

        /* === DEATH SCREEN === */
        #death-screen {
            background: radial-gradient(circle at center, rgba(255, 77, 109, 0.15) 0%, var(--dark) 70%);
        }

        .death-title {
            font-family: 'Righteous', cursive;
            font-size: 4rem;
            color: var(--danger);
            text-shadow: 0 10px 30px rgba(255, 77, 109, 0.3);
            margin-bottom: 1.5rem;
            letter-spacing: 2px;
        }

        .death-story {
            width: 100%;
            max-width: 800px;
            padding: 2rem;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 77, 109, 0.3);
            border-radius: 16px;
            margin-bottom: 2.5rem;
            font-size: 1.2rem;
            line-height: 1.8;
            color: #e0e0e0;
        }

        .death-buttons {
            display: flex;
            gap: 1.5rem;
        }

        .death-btn {
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.2s;
        }

        .death-btn:hover {
            background: rgba(255, 77, 109, 0.2);
            border-color: var(--danger);
            transform: translateY(-3px);
        }

        /* === NAVIGASI KEMBALI === */
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

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeInScreen {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <a href="index.php" class="back-to-hub">← Kembali ke Hub</a>

    <div id="character-select-screen" class="screen">
        <h1 class="page-title">Pilih Karaktermu</h1>
        <p class="page-subtitle">Pilih avatar yang akan menjadi penentu takdir dunia anime ini.</p>
        <div class="character-grid">
            <div class="character-card" data-character-index="0">
                <div class="icon-wrapper">
                    <div class="pixel-sprite naruto-sprite"></div>
                </div>
                <h2 class="character-name">Naruto Uzumaki</h2>
                <p class="character-lore">Ninja muda dari desa Konoha yang bercita-cita menjadi Hokage dan memiliki
                    kuasa rubah ekor sembilan.</p>
            </div>
            <div class="character-card" data-character-index="1">
                <div class="icon-wrapper">
                    <div class="pixel-sprite luffy-sprite"></div>
                </div>
                <h2 class="character-name">Monkey D. Luffy</h2>
                <p class="character-lore">Kapten bajak laut topi jerami yang memiliki tubuh karet-karet dan bercita-cita
                    menjadi Raja Bajak Laut.</p>
            </div>
            <div class="character-card" data-character-index="2">
                <div class="icon-wrapper">
                    <div class="pixel-sprite tanjiro-sprite"></div>
                </div>
                <h2 class="character-name">Tanjiro Kamado</h2>
                <p class="character-lore">Pembasmi iblis yang berusaha mengubah adiknya Nezuko kembali menjadi manusia
                    setelah keluarganya dibantai iblis.</p>
            </div>
            <div class="character-card" data-character-index="3">
                <div class="icon-wrapper">
                    <div class="pixel-sprite eren-sprite"></div>
                </div>
                <h2 class="character-name">Eren Yeager</h2>
                <p class="character-lore">Prajurit dengan kemampuan berubah menjadi Titan yang bertekad memusnahkan
                    semua Titan agar umat manusia bebas.</p>
            </div>
            <div class="character-card" data-character-index="4">
                <div class="icon-wrapper">
                    <div class="pixel-sprite gojo-sprite"></div>
                </div>
                <h2 class="character-name">Satoru Gojo</h2>
                <p class="character-lore">Ahli sihir terkuat di dunia yang mengajar di sekolah jujutsu tinggi dan
                    memiliki mata enam batas tak terbatas.</p>
            </div>
            <div class="character-card" data-character-index="5">
                <div class="icon-wrapper">
                    <div class="pixel-sprite izuku-sprite"></div>
                </div>
                <h2 class="character-name">Izuku Midoriya</h2>
                <p class="character-lore">Siswa sekolah pahlawan yang mewarisi kekuatan One For All dari pahlawan nomor
                    satu All Might.</p>
            </div>
            <div class="character-card" data-character-index="6">
                <div class="icon-wrapper">
                    <div class="pixel-sprite edward-sprite"></div>
                </div>
                <h2 class="character-name">Edward Elric</h2>
                <p class="character-lore">Alkemis negara yang mencari batu bertuah untuk mengembalikan tubuh adiknya
                    Alphonse yang hilang akibat alkimi terlarang.</p>
            </div>
            <div class="character-card" data-character-index="7">
                <div class="icon-wrapper">
                    <div class="pixel-sprite denji-sprite"></div>
                </div>
                <h2 class="character-name">Denji</h2>
                <p class="character-lore">Pemuda miskin yang menjadi iblis gergaji mesin setelah bersatu dengan iblis
                    gergaji untuk melunasi hutang ayahnya.</p>
            </div>
        </div>
    </div>

    <div id="story-screen" class="screen hidden">
        <h2 class="story-header" id="story-character-name"></h2>
        <div class="story-container hidden" id="story-text"></div>
        <div class="loading-container hidden" id="loading-container">
            <div class="pixel-spinner"></div><span class="loading-text">AI sedang merajut cerita...</span>
        </div>
        <div class="choices-container hidden" id="choices-container"></div>
        <div class="story-error hidden" id="story-error"></div>
        <button class="retry-btn hidden" id="retry-btn">Coba Lagi</button>
    </div>

    <div id="death-screen" class="screen hidden">
        <h1 class="death-title">KARAKTER MATI.</h1>
        <div class="death-story" id="death-story-text"></div>
        <div class="death-buttons">
            <button class="death-btn" id="select-other-character-btn">Pilih Karakter Lain</button>
            <button class="death-btn" id="replay-btn"
                style="background: rgba(255, 77, 109, 0.2); border-color: var(--danger);">Mulai Dari Awal</button>
        </div>
    </div>

    <script>
        const characters = [{
                id: 0,
                name: 'Naruto Uzumaki',
                source: 'Naruto',
                lore: 'Ninja muda dari desa Konoha yang bercita-cita menjadi Hokage dan memiliki kuasa rubah ekor sembilan.',
                traits: ['Ninjutsu', 'Kurama Chakra', 'Tekad Kuat']
            },
            {
                id: 1,
                name: 'Monkey D. Luffy',
                source: 'One Piece',
                lore: 'Kapten bajak laut topi jerami yang memiliki tubuh karet-karet dan bercita-cita menjadi Raja Bajak Laut.',
                traits: ['Gear Transformations', 'Haki', 'Kekuatan Fisik Super']
            },
            {
                id: 2,
                name: 'Tanjiro Kamado',
                source: 'Demon Slayer',
                lore: 'Pembasmi iblis yang berusaha mengubah adiknya Nezuko kembali menjadi manusia setelah keluarganya dibantai iblis.',
                traits: ['Breath of Water', 'Indra Hanafuda', 'Penciuman Tajam']
            },
            {
                id: 3,
                name: 'Eren Yeager',
                source: 'Attack on Titan',
                lore: 'Prajurit dengan kemampuan berubah menjadi Titan yang bertekad memusnahkan semua Titan agar umat manusia bebas.',
                traits: ['Titan Shifting', 'Pengingat Masa Depan', 'Tekad Militan']
            },
            {
                id: 4,
                name: 'Satoru Gojo',
                source: 'Jujutsu Kaisen',
                lore: 'Ahli sihir terkuat di dunia yang mengajar di sekolah jujutsu tinggi dan memiliki mata enam batas tak terbatas.',
                traits: ['Limitless', 'Six Eyes', 'Domain Expansion']
            },
            {
                id: 5,
                name: 'Izuku Midoriya',
                source: 'My Hero Academia',
                lore: 'Siswa sekolah pahlawan yang mewarisi kekuatan One For All dari pahlawan nomor satu All Might.',
                traits: ['One For All', 'Full Cowl', 'Analisis Taktis']
            },
            {
                id: 6,
                name: 'Edward Elric',
                source: 'Fullmetal Alchemist',
                lore: 'Alkemis negara yang mencari batu bertuah untuk mengembalikan tubuh adiknya Alphonse yang hilang akibat alkimi terlarang.',
                traits: ['Alchemy', 'Metal Arm', 'Pengetahuan Alkemi']
            },
            {
                id: 7,
                name: 'Denji',
                source: 'Chainsaw Man',
                lore: 'Pemuda miskin yang menjadi iblis gergaji mesin setelah bersatu dengan iblis gergaji untuk melunasi hutang ayahnya.',
                traits: ['Chainsaw Transformation', 'Regeneration', 'Insting Bertahan Hidup']
            }
        ];

        const characterSelectScreen = document.getElementById('character-select-screen');
        const storyScreen = document.getElementById('story-screen');
        const deathScreen = document.getElementById('death-screen');
        const characterCards = document.querySelectorAll('.character-card');

        let currentCharacter = null;
        let storyHistory = [];
        let isContinueSession = false;

        characterCards.forEach(card => {
            card.addEventListener('click', () => {
                const index = parseInt(card.dataset.characterIndex);
                currentCharacter = characters[index];

                characterSelectScreen.classList.add('hidden');
                storyScreen.classList.remove('hidden');
                loadInitialStory();
            });
        });

        const API_PROXY = 'api-proxy.php';
        const HY3_MODEL = 'nvidia/nemotron-3-super-120b-a12b:free';

        // SISTEM PROMPT HARDCORE (Menghancurkan Plot Armor)
        const HY3_SYSTEM_PROMPT = `Anda adalah Game Master (GM) RPG survival hardcore yang logis, brutal, dan tidak kenal ampun.
ATURAN MUTLAK:
1. HILANGKAN PLOT ARMOR. Karakter TIDAK kebal. Setiap tindakan bodoh, ceroboh, atau berisiko tinggi saat terluka HARUS berakibat fatal.
2. Lacak status fisik (HP/Stamina/Luka) secara tersembunyi. Jika terluka parah dan tidak diobati, karakter akan mati.
3. Jika karakter mengambil keputusan yang menyebabkan kematian, ubah "is_dead" menjadi true, dan ceritakan detik-detik kematiannya secara dramatis. JANGAN BERIKAN KEAJAIBAN PENYELAMATAN.
4. SELALU kembalikan format JSON murni TANPA markdown/backticks di awal atau akhir JSON.
Struktur JSON:
{
  "story_segment": "string (bahasa Indonesia, 1-3 paragraf, sangat detail, deskriptif dan berbobot)",
  "choices": ["pilihan aman/logis", "pilihan berisiko tinggi", "pilihan bertahan/pengecut"], 
  "is_dead": boolean 
}`;

        const storyCharacterName = document.getElementById('story-character-name');
        const storyText = document.getElementById('story-text');
        const loadingContainer = document.getElementById('loading-container');
        const choicesContainer = document.getElementById('choices-container');
        const storyError = document.getElementById('story-error');
        const retryBtn = document.getElementById('retry-btn');
        const deathStoryText = document.getElementById('death-story-text');

        // Fungsi Auto-Save ke Database MySQL
        async function saveProgressToDatabase() {
            try {
                await fetch('save_story.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        character_name: currentCharacter.name,
                        chat_history: storyHistory
                    })
                });
            } catch (error) {
                console.error("Gagal melakukan auto-save", error);
            }
        }

        // Fungsi Memuat Data dari Database MySQL
        async function loadInitialStory() {
            storyHistory = [];
            isContinueSession = false;
            hideStoryContent();
            hideStoryError();
            showLoading();

            const charSpriteClass = `${currentCharacter.name.split(' ')[0].toLowerCase()}-sprite`;
            storyCharacterName.innerHTML =
                `<div style="width: 30px; height: 30px; margin-right:15px;"><div class="pixel-sprite ${charSpriteClass}" style="transform: scale(3) translate(0,0);"></div></div> ${currentCharacter.name}`;

            try {
                const response = await fetch(`load_story.php?character=${encodeURIComponent(currentCharacter.name)}`);
                const result = await response.json();

                if (result.status === 'success' && result.data) {
                    // Jika ada save data lama
                    storyHistory = typeof result.data === 'string' ? JSON.parse(result.data) : result.data;
                    isContinueSession = true;
                    fetchStorySegment(true); // Lanjutkan cerita
                } else {
                    // Jika belum pernah main
                    fetchStorySegment(false);
                }
            } catch (error) {
                console.error("Gagal memuat save data", error);
                fetchStorySegment(false); // Fallback ke game baru
            }
        }

        function buildUserPrompt(isContinue) {
            let promptBase = `Detail Karakter:
Nama: ${currentCharacter.name}
Anime Asal: ${currentCharacter.source}
Lore: ${currentCharacter.lore}
Sifat: ${currentCharacter.traits.join(', ')}\n\n`;

            if (isContinue) {
                promptBase +=
                    `Ini adalah sesi lanjutan dari Save Data pemain. Berikut adalah riwayat perjalanan sebelumnya:\n`;
            } else {
                promptBase += `Ini adalah awal permainan baru. Berikut riwayatnya (jika ada):\n`;
            }

            let historyText = storyHistory.length === 0 ? 'Belum ada riwayat.' : storyHistory.map(entry => {
                return entry.type === 'story' ? `GM: ${entry.content}` : `Pemain Memilih: ${entry.content}`;
            }).join('\n\n');

            return promptBase + historyText +
                `\n\nSilakan hasilkan segmen cerita (atau lanjutkan dari cerita terakhir) beserta konsekuensinya sesuai aturan JSON yang diberikan.`;
        }

        function renderChoices(choices) {
            choicesContainer.innerHTML = '';
            choices.forEach(choiceText => {
                const btn = document.createElement('button');
                btn.className = 'choice-btn';
                btn.textContent = choiceText;
                btn.addEventListener('click', () => {
                    storyHistory.push({
                        type: 'choice',
                        content: choiceText
                    });
                    fetchStorySegment(false);
                });
                choicesContainer.appendChild(btn);
            });
            choicesContainer.classList.remove('hidden');
        }

        function showDeathScreen(deathSegment) {
            storyScreen.classList.add('hidden');
            deathScreen.classList.remove('hidden');
            deathStoryText.textContent = deathSegment;
            // Jika mati, biarkan data tertimpa atau bisa diriset manual di database jika mau
        }

        function showLoading() {
            loadingContainer.classList.remove('hidden');
            document.querySelectorAll('.choice-btn').forEach(btn => btn.disabled = true);
        }

        function hideLoading() {
            loadingContainer.classList.add('hidden');
            document.querySelectorAll('.choice-btn').forEach(btn => btn.disabled = false);
        }

        function hideStoryContent() {
            storyText.classList.add('hidden');
            choicesContainer.classList.add('hidden');
        }

        document.getElementById('select-other-character-btn').addEventListener('click', () => {
            deathScreen.classList.add('hidden');
            characterSelectScreen.classList.remove('hidden');
            currentCharacter = null;
        });

        document.getElementById('replay-btn').addEventListener('click', () => {
            deathScreen.classList.add('hidden');
            storyScreen.classList.remove('hidden');
            storyHistory = []; // Hapus memori lama untuk mulai ulang bersih
            saveProgressToDatabase(); // Timpa database dengan data kosong
            fetchStorySegment(false);
        });

        function extractJsonFromResponse(text) {
            try {
                return JSON.parse(text);
            } catch (e) {
                const jsonMatch = text.match(/\{[\s\S]*\}/);
                if (jsonMatch) {
                    try {
                        return JSON.parse(jsonMatch[0]);
                    } catch (e2) {
                        throw new Error('INVALID_JSON');
                    }
                }
                throw new Error('INVALID_JSON');
            }
        }

        async function fetchStorySegment(isContinue = false) {
            showLoading();
            hideStoryContent();
            hideStoryError();

            try {
                const userPrompt = buildUserPrompt(isContinue);
                const response = await fetch(API_PROXY, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        model: HY3_MODEL,
                        messages: [{
                                role: 'system',
                                content: HY3_SYSTEM_PROMPT
                            },
                            {
                                role: 'user',
                                content: userPrompt
                            }
                        ],
                        temperature: 0.7,
                        max_tokens: 2000
                    })
                });

                if (!response.ok) throw new Error('API_ERROR');

                const data = await response.json();
                const hy3Response = (data.choices[0].message.content || '').trim();

                const parsedResponse = extractJsonFromResponse(hy3Response);

                storyHistory.push({
                    type: 'story',
                    content: parsedResponse.story_segment
                });

                // SIMPAN PROGRES SETELAH AI MENJAWAB
                saveProgressToDatabase();

                if (parsedResponse.is_dead) {
                    showDeathScreen(parsedResponse.story_segment);
                } else {
                    storyText.textContent = parsedResponse.story_segment;
                    storyText.classList.remove('hidden');
                    renderChoices(parsedResponse.choices);
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }

            } catch (error) {
                showStoryError("Gagal merajut takdir. Server AI sedang kelelahan. Silakan coba lagi.");
                retryBtn.classList.remove('hidden');
            } finally {
                hideLoading();
            }
        }

        retryBtn.addEventListener('click', () => fetchStorySegment());

        function showStoryError(message) {
            storyError.textContent = message;
            storyError.classList.remove('hidden');
        }

        function hideStoryError() {
            storyError.classList.add('hidden');
            retryBtn.classList.add('hidden');
        }
    </script>
</body>

</html>