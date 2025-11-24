<?php
session_start();
include 'koneksi.php';

// Validasi Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

// Tentukan aksi (GET untuk 'baca_pending', POST untuk 'approve'/'reject')
$action = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
    exit;
}


try {
    switch ($action) {
        
        // --- BACA PENDAFTAR ---
        case 'baca_pending':
            // Kita juga ambil email untuk ditampilkan (nanti)
            $sql = "SELECT id_pendaftaran, nama_lengkap, kelas, email, tanggal_daftar 
                    FROM pendaftaran_pending 
                    ORDER BY tanggal_daftar ASC";
            $result = $koneksi->query($sql);
            $pendaftar = [];
            if ($result) {
                while($row = $result->fetch_assoc()) {
                    $pendaftar[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $pendaftar]);
            break;

        // --- SETUJUI (APPROVE) ---
        case 'approve':
            $id = $_POST['id_pendaftaran'] ?? '';
            if (empty($id)) {
                throw new Exception('ID Pendaftaran tidak ada.');
            }

            // Mulai transaksi
            $koneksi->begin_transaction();

            // 1. Ambil data LENGKAP dari tabel pending
            $sql_ambil = "SELECT nama_lengkap, kelas, email, password_hash 
                          FROM pendaftaran_pending WHERE id_pendaftaran = ?";
            $stmt_ambil = $koneksi->prepare($sql_ambil);
            $stmt_ambil->bind_param("i", $id);
            $stmt_ambil->execute();
            $result_ambil = $stmt_ambil->get_result();
            
            if ($result_ambil->num_rows === 0) {
                throw new Exception('Data pendaftar tidak ditemukan.');
            }
            $data = $result_ambil->fetch_assoc();
            $nama = $data['nama_lengkap'];
            $kelas = $data['kelas'];
            $email = $data['email'];
            $password_hash = $data['password_hash']; // Ambil hash-nya

            // 2. Masukkan ke tabel anggota
            $sql_masuk = "INSERT INTO anggota (nama_lengkap, kelas, email, password_hash) VALUES (?, ?, ?, ?)";
            $stmt_masuk = $koneksi->prepare($sql_masuk);
            // 'ssss' = string, string, string, string
            $stmt_masuk->bind_param("ssss", $nama, $kelas, $email, $password_hash);
            $stmt_masuk->execute();

            // 3. Hapus dari tabel pending
            $sql_hapus = "DELETE FROM pendaftaran_pending WHERE id_pendaftaran = ?";
            $stmt_hapus = $koneksi->prepare($sql_hapus);
            $stmt_hapus->bind_param("i", $id);
            $stmt_hapus->execute();

            // Commit transaksi
            $koneksi->commit();
            
            echo json_encode(['success' => true, 'message' => "Pendaftar '$nama' berhasil disetujui."]);
            break;

        // --- TOLAK (REJECT) ---
        case 'reject':
            $id = $_POST['id_pendaftaran'] ?? '';
            if (empty($id)) {
                throw new Exception('ID Pendaftaran tidak ada.');
            }
            
            // Hapus langsung dari tabel pending
            $sql = "DELETE FROM pendaftaran_pending WHERE id_pendaftaran = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'Pendaftaran berhasil ditolak.']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenal.']);
            break;
    }

} catch (Exception $e) {
    // Jika ada error (terutama saat transaksi), batalkan
    if ($koneksi->errno) {
        $koneksi->rollback();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>