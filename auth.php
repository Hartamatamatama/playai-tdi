<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    // Mengamankan input dari serangan SQL Injection
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    if ($action === 'register') {
        $name = $conn->real_escape_string($_POST['name']);

        // Cek apakah email sudah pernah didaftarkan
        $check_query = "SELECT id FROM users WHERE email = '$email'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Email sudah terdaftar! Silakan gunakan fitur Masuk.'); window.location.href='index.php';</script>";
        } else {
            // Enkripsi password menggunakan BCRYPT standar industri
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

            if ($conn->query($insert_query)) {
                // Auto login setelah berhasil mendaftar
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['user_name'] = $name;
                echo "<script>alert('Pendaftaran berhasil! Selamat datang di PlayAI.'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan pada sistem. Silakan coba lagi.'); window.location.href='index.php';</script>";
            }
        }
    } elseif ($action === 'login') {
        $login_query = "SELECT * FROM users WHERE email = '$email'";
        $login_result = $conn->query($login_query);

        if ($login_result->num_rows > 0) {
            $user = $login_result->fetch_assoc();
            // Cocokkan password yang diketik dengan password acak di database
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: index.php");
            } else {
                echo "<script>alert('Password yang kamu masukkan salah!'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Email tidak ditemukan! Silakan Daftar terlebih dahulu.'); window.location.href='index.php';</script>";
        }
    }
}
