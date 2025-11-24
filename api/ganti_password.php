<?php
session_start();
include 'koneksi.php';

// Validasi Anggota
if (!isset($_SESSION['anggota_logged_in']) || $_SESSION['anggota_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak. Anda harus login.']);
    exit;
}

// Ambil ID anggota dari session
$id_anggota = $_SESSION['anggota_id'];

// Ambil data dari form
$password_lama = $_POST['password_lama'] ?? '';
$password_baru = $_POST['password_baru'] ?? '';

// Validasi input
if (empty($password_lama) || empty($password_baru)) {
    echo json_encode(['success' => false, 'message' => 'Password lama dan baru tidak boleh kosong.']);
    exit;
}

if (strlen($password_baru) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password baru minimal 6 karakter.']);
    exit;
}

try {
    // 1. Ambil hash password saat ini dari database
    $sql_cek = "SELECT password_hash FROM anggota WHERE id_anggota = ?";
    $stmt_cek = $koneksi->prepare($sql_cek);
    $stmt_cek->bind_param("i", $id_anggota);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();
    
    if ($result_cek->num_rows === 0) {
        throw new Exception('Anggota tidak ditemukan.');
    }
    
    $anggota = $result_cek->fetch_assoc();
    $hash_lama = $anggota['password_hash'];

    // 2. Verifikasi password lama
    if (password_verify($password_lama, $hash_lama)) {
        // Password lama benar, lanjutkan ganti password
        
        // 3. Buat hash baru untuk password baru
        $hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

        // 4. Update password di database
        $sql_update = "UPDATE anggota SET password_hash = ? WHERE id_anggota = ?";
        $stmt_update = $koneksi->prepare($sql_update);
        $stmt_update->bind_param("si", $hash_baru, $id_anggota);
        $stmt_update->execute();

        echo json_encode(['success' => true, 'message' => 'Password berhasil diperbarui!']);

    } else {
        // Password lama salah
        throw new Exception('Password lama yang Anda masukkan salah.');
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>