<?php
// Selalu mulai session dan koneksi
session_start();
include 'koneksi.php';

// PERIKSA OTENTIKASI ADMIN
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak. Silakan login.']);
    exit;
}

// Tentukan aksi berdasarkan metode request
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
        
        // --- READ (BACA SEMUA ANGGOTA) ---
        case 'baca': // (FIX) Dikembalikan ke 'baca'
            // (FIX) Query HANYA ambil data yang ada di DB
            $sql = "SELECT id_anggota, nama_lengkap, kelas, email 
                    FROM anggota 
                    ORDER BY kelas ASC, nama_lengkap ASC";
            $result = $koneksi->query($sql);
            $anggota = [];
            if ($result) {
                while($row = $result->fetch_assoc()) {
                    $anggota[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $anggota]);
            break;

        // --- UPDATE (EDIT ANGGOTA) ---
        case 'edit':
            $id = $_POST['id_anggota'] ?? '';
            $nama = $_POST['nama_lengkap'] ?? '';
            $kelas = $_POST['kelas'] ?? '';

            if (empty($id) || empty($nama)) { // Kelas boleh kosong
                throw new Exception('Data tidak lengkap untuk proses edit.');
            }
            
            // (FIX) Query HANYA update nama dan kelas
            $sql = "UPDATE anggota SET nama_lengkap = ?, kelas = ? WHERE id_anggota = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssi", $nama, $kelas, $id);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Data anggota berhasil diperbarui.']);
            break;

        // --- DELETE (HAPUS ANGGOTA) ---
        case 'hapus':
            $id = $_POST['id_anggota'] ?? '';

            if (empty($id)) {
                throw new Exception('ID Anggota tidak ditemukan.');
            }

            $sql = "DELETE FROM anggota WHERE id_anggota = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Anggota berhasil dihapus.']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenal.']);
            break;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

if(isset($stmt)) {
    $stmt->close();
}
$koneksi->close();
?>