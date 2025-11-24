<?php
// API ini TIDAK perlu session admin, karena ini untuk publik
include 'koneksi.php';

// Ambil data mentah dari body request (karena kita kirim JSON)
$data = json_decode(file_get_contents('php://input'), true);

$nama = $data['nama_lengkap'] ?? '';
$kelas = $data['kelas'] ?? '';
$email = $data['email'] ?? '';
$password_polos = $data['password'] ?? '';

// Validasi
if (empty($nama) || empty($kelas) || empty($email) || empty($password_polos)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Semua field (Nama, Kelas, Email, Password) tidak boleh kosong.'
    ]);
    exit;
}

if (strlen($password_polos) < 6) {
    echo json_encode([
        'success' => false, 
        'message' => 'Password minimal harus 6 karakter.'
    ]);
    exit;
}

// Enkripsi password
$password_hash = password_hash($password_polos, PASSWORD_DEFAULT);

try {
    // Cek dulu apakah email sudah dipakai di tabel pending atau anggota
    $sql_cek = "SELECT 1 FROM pendaftaran_pending WHERE email = ? 
                UNION 
                SELECT 1 FROM anggota WHERE email = ?";
    $stmt_cek = $koneksi->prepare($sql_cek);
    $stmt_cek->bind_param("ss", $email, $email);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();

    if ($result_cek->num_rows > 0) {
        throw new Exception('Email ini sudah terdaftar. Gunakan email lain.');
    }

    // Simpan ke tabel pendaftaran_pending
    $sql_simpan = "INSERT INTO pendaftaran_pending (nama_lengkap, kelas, email, password_hash) VALUES (?, ?, ?, ?)";
    $stmt_simpan = $koneksi->prepare($sql_simpan);
    $stmt_simpan->bind_param("ssss", $nama, $kelas, $email, $password_hash);
    $stmt_simpan->execute();

    echo json_encode([
        'success' => true, 
        'message' => 'Pendaftaran berhasil! Akun kamu akan segera ditinjau oleh Admin.'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Gagal: ' . $e->getMessage()
    ]);
}

$koneksi->close();
?>