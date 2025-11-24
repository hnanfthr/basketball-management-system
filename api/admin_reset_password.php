<?php
// api/admin_reset_password.php

session_start();
include 'koneksi.php';

// Validasi Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

// Password default yang baru
$password_default_polos = 'anggota123';
$password_hash_baru = password_hash($password_default_polos, PASSWORD_DEFAULT);

// Ambil ID dari POST
$id_anggota = $_POST['id_anggota'] ?? 0;

if (empty($id_anggota)) {
    echo json_encode(['success' => false, 'message' => 'ID Anggota tidak ada.']);
    exit;
}

try {
    // Update password di database
    $sql = "UPDATE anggota SET password_hash = ? WHERE id_anggota = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("si", $password_hash_baru, $id_anggota);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Password anggota berhasil di-reset ke: ' . $password_default_polos
        ]);
    } else {
        throw new Exception('Anggota tidak ditemukan.');
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

if(isset($stmt)) {
    $stmt->close();
}
$koneksi->close();
?>