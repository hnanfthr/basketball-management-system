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

// --- Konfigurasi Upload ---
$upload_dir = "../assets/uploads/profil/";
$max_size = 1 * 1024 * 1024; // 1 MB
$allowed_types = ['image/jpeg', 'image/png'];
// ---------------------------

$posisi_main = $_POST['posisi_main'] ?? NULL;
if (empty($posisi_main)) {
    $posisi_main = NULL;
}

$nama_file_baru = null;
$path_file_baru = null;
$sql_foto_part = ""; 

try {
    // 1. Ambil data foto lama
    $stmt_cek = $koneksi->prepare("SELECT foto_profil FROM anggota WHERE id_anggota = ?");
    $stmt_cek->bind_param("i", $id_anggota);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();
    $foto_lama = $result_cek->fetch_assoc()['foto_profil'] ?? null;
    $stmt_cek->close();

    // 2. Proses jika ada file foto baru di-upload
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
        
        // (BARU) Cek & Buat Folder Otomatis
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                throw new Exception('Gagal membuat folder upload profil otomatis.');
            }
        }

        $file = $_FILES['foto_profil'];

        if ($file['size'] > $max_size) {
            throw new Exception('Ukuran file terlalu besar. Maksimal 1 MB.');
        }
        $file_type = mime_content_type($file['tmp_name']);
        if (!in_array($file_type, $allowed_types)) {
            throw new Exception('Tipe file tidak valid. Hanya JPG atau PNG.');
        }

        $ekstensi = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nama_file_baru = 'profil_' . $id_anggota . '_' . time() . '.' . $ekstensi;
        $path_file_baru = $upload_dir . $nama_file_baru;

        if (!move_uploaded_file($file['tmp_name'], $path_file_baru)) {
            throw new Exception('Gagal memindahkan file yang di-upload.');
        }

        $sql_foto_part = ", foto_profil = ?";
    }

    // 3. Update database
    $sql = "UPDATE anggota SET posisi_main = ? $sql_foto_part WHERE id_anggota = ?";
    $stmt_update = $koneksi->prepare($sql);

    if ($nama_file_baru) {
        $stmt_update->bind_param("ssi", $posisi_main, $nama_file_baru, $id_anggota);
    } else {
        $stmt_update->bind_param("si", $posisi_main, $id_anggota);
    }
    
    $stmt_update->execute();
    $stmt_update->close();

    // 4. Hapus foto lama
    if ($nama_file_baru && $foto_lama) {
        $path_foto_lama = $upload_dir . $foto_lama;
        if (file_exists($path_foto_lama)) {
            unlink($path_foto_lama);
        }
    }

    echo json_encode([
        'success' => true, 
        'message' => 'Profil berhasil diperbarui!',
        'new_posisi' => $posisi_main,
        'new_foto_nama' => $nama_file_baru 
    ]);

} catch (Exception $e) {
    if ($path_file_baru && file_exists($path_file_baru)) {
        unlink($path_file_baru);
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>