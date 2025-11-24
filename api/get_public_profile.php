<?php
// api/get_public_profile.php

// API Publik, tidak perlu session
include 'koneksi.php';

// Ambil ID dari URL (misal: ...?id=5)
$id = $_GET['id'] ?? 0;

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID Anggota tidak ada.']);
    exit;
}

try {
    // Ambil data profil publik
    $sql = "SELECT nama_lengkap, kelas, posisi_main, foto_profil 
            FROM anggota 
            WHERE id_anggota = ?";
    
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $profil = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $profil]);
    } else {
        throw new Exception('Anggota tidak ditemukan.');
    }
    
    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>