<?php
// Selalu mulai session di awal
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan pengguna kembali ke halaman login
header("Location: ../pages/login.html");
exit;
?>