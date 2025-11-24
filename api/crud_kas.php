<?php
session_start();
include 'koneksi.php';

// Validasi Login (Admin atau Anggota)
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$isAnggota = isset($_SESSION['anggota_logged_in']) && $_SESSION['anggota_logged_in'] === true;

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!$isAdmin && !$isAnggota) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

// Data khusus dari session
$id_anggota_login = $_SESSION['anggota_id'] ?? 0;

try {
    switch ($action) {
        
        // --- ADMIN: Baca status kas per sesi latihan ---
        case 'baca_sesi':
            if (!$isAdmin) throw new Exception('Akses Admin ditolak.');
            $id_jadwal = $_GET['id_jadwal'] ?? 0;
            if (empty($id_jadwal)) throw new Exception('ID Jadwal tidak ada.');

            // Ambil SEMUA anggota, dan LEFT JOIN status kas mereka untuk jadwal ini
            $sql = "
                SELECT 
                    a.id_anggota, 
                    a.nama_lengkap, 
                    a.kelas, 
                    COALESCE(k.status, 'Belum') AS status_bayar
                FROM 
                    anggota a
                LEFT JOIN 
                    kas_latihan k ON a.id_anggota = k.id_anggota AND k.id_jadwal = ?
                ORDER BY 
                    a.kelas ASC, a.nama_lengkap ASC
            ";
            
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("i", $id_jadwal);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        // --- ADMIN: Update status bayar per sesi ---
        case 'update_sesi':
            if (!$isAdmin) throw new Exception('Akses Admin ditolak.');
            
            $id_anggota = $_POST['id_anggota'] ?? 0;
            $id_jadwal_post = $_POST['id_jadwal'] ?? 0;
            $status = $_POST['status'] ?? 'Belum';

            if (empty($id_anggota) || empty($id_jadwal_post)) {
                throw new Exception('Data tidak lengkap.');
            }

            // Gunakan "INSERT ... ON DUPLICATE KEY UPDATE"
            $sql_update = "INSERT INTO kas_latihan (id_anggota, id_jadwal, status) 
                           VALUES (?, ?, ?)
                           ON DUPLICATE KEY UPDATE status = VALUES(status)";
            
            $stmt_update = $koneksi->prepare($sql_update);
            $stmt_update->bind_param("iis", $id_anggota, $id_jadwal_post, $status);
            $stmt_update->execute();

            echo json_encode(['success' => true, 'message' => 'Status kas diperbarui.']);
            break;

        // --- ANGGOTA: Baca data kas sendiri ---
        case 'baca_anggota':
            if (!$isAnggota) throw new Exception('Akses anggota ditolak.');
            
            $sql_anggota = "
                SELECT 
                    j.tanggal, 
                    j.lokasi, 
                    k.status 
                FROM kas_latihan k
                JOIN jadwal_latihan j ON k.id_jadwal = j.id_jadwal
                WHERE k.id_anggota = ?
                ORDER BY j.tanggal DESC
            ";
            $stmt_anggota = $koneksi->prepare($sql_anggota);
            $stmt_anggota->bind_param("i", $id_anggota_login);
            $stmt_anggota->execute();
            $result_anggota = $stmt_anggota->get_result();
            $data_kas = $result_anggota->fetch_all(MYSQLI_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $data_kas]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Aksi kas tidak dikenal.']);
            break;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>