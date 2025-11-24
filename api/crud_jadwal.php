<?php
session_start();
include 'koneksi.php';

// Validasi Admin
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Izinkan akses publik hanya untuk 'baca' (untuk landing page/dashboard)
// Tapi landing page biasanya pakai file index.php sendiri, jadi ini aman
if (!$isAdmin && $action !== 'baca' && $action !== 'baca_semua') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

try {
    switch ($action) {
        
        // --- BACA JADWAL AKTIF (Hanya yang BELUM LEWAT) ---
        case 'baca':
            // Logika: Tampilkan jika (Tanggal & Jam Selesai) masih di masa depan
            $sql = "SELECT * FROM jadwal_latihan 
                    WHERE CONCAT(tanggal, ' ', waktu_selesai) >= NOW() 
                    ORDER BY tanggal ASC, waktu ASC";
            
            $result = $koneksi->query($sql);
            $jadwal = [];
            if ($result) {
                while($row = $result->fetch_assoc()) {
                    $jadwal[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $jadwal]);
            break;

        // --- BACA SEMUA (Untuk Iuran Kas / History) ---
        case 'baca_semua':
            $sql_semua = "SELECT * FROM jadwal_latihan 
                          ORDER BY tanggal DESC, waktu DESC";
            $result_semua = $koneksi->query($sql_semua);
            $jadwal_semua = [];
            if ($result_semua) {
                while($row = $result_semua->fetch_assoc()) {
                    $jadwal_semua[] = $row;
                }
            }
            echo json_encode(['success' => true, 'data' => $jadwal_semua]);
            break;

        // --- TAMBAH JADWAL ---
        case 'tambah':
            $tanggal = $_POST['tanggal'] ?? '';
            $waktu = $_POST['waktu'] ?? ''; // Waktu Mulai
            $waktu_selesai = $_POST['waktu_selesai'] ?? ''; // Waktu Selesai
            $lokasi = $_POST['lokasi'] ?? '';
            $keterangan = $_POST['keterangan'] ?? null;
            
            if (empty($tanggal) || empty($waktu) || empty($waktu_selesai) || empty($lokasi)) {
                throw new Exception('Tanggal, Jam Mulai, Jam Selesai, dan Lokasi wajib diisi.');
            }

            $sql = "INSERT INTO jadwal_latihan (tanggal, waktu, waktu_selesai, lokasi, keterangan) VALUES (?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("sssss", $tanggal, $waktu, $waktu_selesai, $lokasi, $keterangan);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'Jadwal baru berhasil ditambahkan.']);
            break;

        // --- EDIT JADWAL ---
        case 'edit':
            $id = $_POST['id_jadwal'] ?? '';
            $tanggal = $_POST['tanggal'] ?? '';
            $waktu = $_POST['waktu'] ?? '';
            $waktu_selesai = $_POST['waktu_selesai'] ?? '';
            $lokasi = $_POST['lokasi'] ?? '';
            $keterangan = $_POST['keterangan'] ?? null;

            if (empty($id) || empty($tanggal) || empty($waktu) || empty($waktu_selesai) || empty($lokasi)) {
                throw new Exception('Data tidak lengkap.');
            }

            $sql = "UPDATE jadwal_latihan SET tanggal = ?, waktu = ?, waktu_selesai = ?, lokasi = ?, keterangan = ? 
                    WHERE id_jadwal = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("sssssi", $tanggal, $waktu, $waktu_selesai, $lokasi, $keterangan, $id);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Data jadwal berhasil diperbarui.']);
            break;

        // --- HAPUS JADWAL ---
        case 'hapus':
            $id = $_POST['id_jadwal'] ?? '';

            if (empty($id)) {
                throw new Exception('ID Jadwal tidak ditemukan.');
            }

            $sql = "DELETE FROM jadwal_latihan WHERE id_jadwal = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Aksi tidak dikenal.']);
            break;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

if (isset($stmt)) {
    $stmt->close();
}
$koneksi->close();
?>