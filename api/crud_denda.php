<?php
session_start();
include 'koneksi.php';

// Validasi Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        
        // Baca Semua Denda (Utamakan yang Belum Lunas)
        case 'baca':
            $sql = "SELECT d.*, a.nama_lengkap, a.kelas 
                    FROM denda d
                    JOIN anggota a ON d.id_anggota = a.id_anggota
                    ORDER BY d.status ASC, d.tanggal_dibuat DESC";
            $result = $koneksi->query($sql);
            $data = [];
            if ($result) {
                while($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        // Tandai Lunas
        case 'bayar':
            $id_denda = $_POST['id_denda'] ?? 0;
            
            $stmt = $koneksi->prepare("UPDATE denda SET status = 'Lunas' WHERE id_denda = ?");
            $stmt->bind_param("i", $id_denda);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Denda berhasil ditandai Lunas.']);
            } else {
                throw new Exception('Gagal update database.');
            }
            break;
            
        // Hapus Denda (Jika salah input)
        case 'hapus':
            $id_denda = $_POST['id_denda'] ?? 0;
            
            $stmt = $koneksi->prepare("DELETE FROM denda WHERE id_denda = ?");
            $stmt->bind_param("i", $id_denda);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Data denda dihapus.']);
            } else {
                throw new Exception('Gagal menghapus data.');
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenal.']);
            break;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>