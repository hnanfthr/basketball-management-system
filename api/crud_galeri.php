<?php
session_start();
include 'koneksi.php';

// Validasi Admin (Harus login untuk upload/hapus)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    if ($_GET['action'] !== 'baca_publik') { 
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }
}

// Tentukan aksi
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// --- Lokasi Upload ---
$upload_dir = "../assets/uploads/galeri/"; 
// ---------------------

try {
    switch ($action) {
        
        // --- ADMIN: Upload Foto ---
        case 'upload':
            $id_admin = $_SESSION['admin_id'];
            $judul = $_POST['judul'] ?? '';
            $keterangan = $_POST['keterangan'] ?? null;
            
            if (empty($judul) || !isset($_FILES['foto']) || $_FILES['foto']['error'] != 0) {
                throw new Exception('Judul dan File foto wajib diisi.');
            }
            
            // (BARU) Cek & Buat Folder Otomatis jika belum ada
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    throw new Exception('Gagal membuat folder upload galeri otomatis.');
                }
            }
            
            // Cek file
            $file = $_FILES['foto'];
            $max_size = 2 * 1024 * 1024; // 2 MB
            $allowed_types = ['image/jpeg', 'image/png'];
            
            if ($file['size'] > $max_size) {
                throw new Exception('Ukuran file terlalu besar. Maksimal 2 MB.');
            }

            $file_type = mime_content_type($file['tmp_name']);
            if (!in_array($file_type, $allowed_types)) {
                throw new Exception('Tipe file tidak valid. Hanya JPG atau PNG.');
            }

            // Buat nama file unik
            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $nama_file_baru = uniqid('foto_') . time() . '.' . $ekstensi;
            $path_tujuan = $upload_dir . $nama_file_baru;

            // Pindahkan file
            if (!move_uploaded_file($file['tmp_name'], $path_tujuan)) {
                throw new Exception('Gagal memindahkan file. Pastikan folder assets/uploads/galeri ada dan permission-nya 755/777.');
            }

            // Simpan ke DB
            $sql_insert = "INSERT INTO galeri (nama_file, judul, keterangan, id_admin_uploader) 
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $koneksi->prepare($sql_insert);
            $stmt_insert->bind_param("sssi", $nama_file_baru, $judul, $keterangan, $id_admin);
            $stmt_insert->execute();

            echo json_encode(['success' => true, 'message' => 'Foto berhasil di-upload!']);
            break;

        // --- ADMIN: Baca Galeri ---
        case 'baca':
            $sql_baca = "SELECT * FROM galeri ORDER BY tanggal_upload DESC";
            $result_baca = $koneksi->query($sql_baca);
            $galeri = [];
            if ($result_baca) {
                while($row = $result_baca->fetch_assoc()) {
                    $galeri[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $galeri]);
            break;

        // --- PUBLIK: Baca Galeri ---
        case 'baca_publik':
            $sql_baca_p = "SELECT nama_file, judul, keterangan, tanggal_upload 
                           FROM galeri ORDER BY tanggal_upload DESC";
            $result_baca_p = $koneksi->query($sql_baca_p);
            $galeri_p = [];
            if ($result_baca_p) {
                while($row = $result_baca_p->fetch_assoc()) {
                    $galeri_p[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $galeri_p]);
            break;
            
        // --- ADMIN: Hapus Foto ---
        case 'hapus':
            $id_foto = $_POST['id_foto'] ?? '';
            $nama_file = $_POST['nama_file'] ?? '';

            if (empty($id_foto) || empty($nama_file)) {
                throw new Exception('ID atau Nama File tidak ada.');
            }

            // 1. Hapus file dari server
            $path_file = $upload_dir . $nama_file;
            if (file_exists($path_file)) {
                unlink($path_file); // Hapus file
            }

            // 2. Hapus data dari DB
            $sql_hapus = "DELETE FROM galeri WHERE id_foto = ?";
            $stmt_hapus = $koneksi->prepare($sql_hapus);
            $stmt_hapus->bind_param("i", $id_foto);
            $stmt_hapus->execute();

            echo json_encode(['success' => true, 'message' => 'Foto berhasil dihapus.']);
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