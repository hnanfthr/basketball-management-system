<?php
session_start();
include 'koneksi.php';

// Tentukan aksi
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Validasi Admin
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if ($action !== 'baca' && !$isAdmin) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

// --- Lokasi Upload ---
$upload_dir = "../assets/uploads/struktur/"; 
// ---------------------

try {
    switch ($action) {
        
        // --- ADMIN: Upload Foto ---
        case 'upload':
            $nama = $_POST['nama'] ?? '';
            $jabatan = $_POST['jabatan'] ?? '';
            $urutan = $_POST['urutan'] ?? 10;
            
            if (empty($nama) || empty($jabatan) || !isset($_FILES['foto']) || $_FILES['foto']['error'] != 0) {
                throw new Exception('Nama, Jabatan, dan File foto wajib diisi.');
            }
            
            // (BARU) Cek & Buat Folder Otomatis
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    throw new Exception('Gagal membuat folder upload struktur otomatis.');
                }
            }

            // Cek file
            $file = $_FILES['foto'];
            $max_size = 1 * 1024 * 1024; // 1 MB
            $allowed_types = ['image/jpeg', 'image/png'];
            
            if ($file['size'] > $max_size) {
                throw new Exception('Ukuran file terlalu besar. Maksimal 1 MB.');
            }

            $file_type = mime_content_type($file['tmp_name']);
            if (!in_array($file_type, $allowed_types)) {
                throw new Exception('Tipe file tidak valid. Hanya JPG atau PNG.');
            }

            // Buat nama file unik
            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $nama_file_baru = uniqid('struktur_') . time() . '.' . $ekstensi;
            $path_tujuan = $upload_dir . $nama_file_baru;

            // Pindahkan file
            if (!move_uploaded_file($file['tmp_name'], $path_tujuan)) {
                throw new Exception('Gagal memindahkan file yang di-upload.');
            }

            // Simpan ke DB
            $sql_insert = "INSERT INTO struktur_organisasi (nama, jabatan, foto, urutan) 
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $koneksi->prepare($sql_insert);
            $stmt_insert->bind_param("sssi", $nama, $jabatan, $nama_file_baru, $urutan);
            $stmt_insert->execute();

            echo json_encode(['success' => true, 'message' => 'Data pengurus berhasil di-upload!']);
            break;

        // --- PUBLIK/ADMIN: Baca Struktur ---
        case 'baca':
            $sql_baca = "SELECT * FROM struktur_organisasi ORDER BY urutan ASC, nama ASC";
            $result_baca = $koneksi->query($sql_baca);
            $struktur = [];
            if ($result_baca) {
                while($row = $result_baca->fetch_assoc()) {
                    $struktur[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $struktur]);
            break;
            
        // --- ADMIN: Hapus Pengurus ---
        case 'hapus':
            $id_pengurus = $_POST['id_pengurus'] ?? '';
            $nama_file = $_POST['nama_file'] ?? '';

            if (empty($id_pengurus) || empty($nama_file)) {
                throw new Exception('ID atau Nama File tidak ada.');
            }

            $path_file = $upload_dir . $nama_file;
            if (file_exists($path_file)) {
                unlink($path_file); 
            }

            $sql_hapus = "DELETE FROM struktur_organisasi WHERE id_pengurus = ?";
            $stmt_hapus = $koneksi->prepare($sql_hapus);
            $stmt_hapus->bind_param("i", $id_pengurus);
            $stmt_hapus->execute();

            echo json_encode(['success' => true, 'message' => 'Data pengurus berhasil dihapus.']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenal.']);
            break;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

if (isset($stmt_insert)) $stmt_insert->close();
if (isset($stmt_hapus)) $stmt_hapus->close();
$koneksi->close();
?>