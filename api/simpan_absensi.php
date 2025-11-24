<?php
session_start();
include 'koneksi.php';

// Validasi Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

$tanggal = $_POST['tanggal_latihan'] ?? null;
$status_list = $_POST['status'] ?? null;

if (empty($tanggal) || empty($status_list) || !is_array($status_list)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
    exit;
}

// --- (BARU) Tentukan Awal Semester Berdasarkan Tanggal Latihan ---
// Kita ambil bulan dan tahun dari tanggal latihan yang sedang diinput
$time_latihan = strtotime($tanggal);
$bulan = date('n', $time_latihan); // 1-12
$tahun = date('Y', $time_latihan);

if ($bulan >= 7) {
    // Semester Ganjil (Juli - Desember)
    $tgl_awal_semester = "$tahun-07-01";
} else {
    // Semester Genap (Januari - Juni)
    $tgl_awal_semester = "$tahun-01-01";
}
// ------------------------------------------------------------------

$koneksi->begin_transaction();
$gagal = false;
$pesan_tambahan = "";

try {
    // 1. Simpan Absensi
    $sql_insert = "INSERT INTO absensi (id_anggota, tanggal_latihan, status_kehadiran) VALUES (?, ?, ?)
                   ON DUPLICATE KEY UPDATE status_kehadiran = VALUES(status_kehadiran)";
    $stmt = $koneksi->prepare($sql_insert);

    foreach ($status_list as $id_anggota => $status_kehadiran) {
        $stmt->bind_param("iss", $id_anggota, $tanggal, $status_kehadiran);
        if (!$stmt->execute()) {
            $gagal = true; break;
        }

        // --- LOGIKA PUNISHMENT (DIPERBARUI: RESET PER SEMESTER) ---
        if ($status_kehadiran === 'Alfa') {
            
            // Hitung total Alfa HANYA DI SEMESTER INI
            $sql_count = "SELECT COUNT(*) as total FROM absensi 
                          WHERE id_anggota = ? 
                          AND status_kehadiran = 'Alfa'
                          AND tanggal_latihan >= ?"; // Cek mulai dari awal semester
            
            $stmt_count = $koneksi->prepare($sql_count);
            $stmt_count->bind_param("is", $id_anggota, $tgl_awal_semester);
            $stmt_count->execute();
            $total_alfa = $stmt_count->get_result()->fetch_assoc()['total'];
            $stmt_count->close();

            // Cek kelipatan 4 (4, 8, 12...)
            if ($total_alfa > 0 && $total_alfa % 4 == 0) {
                
                $denda = 25000;
                $ket = "Denda Punishment (Alfa ke-$total_alfa di Semester ini)";
                
                // Cek apakah denda untuk alfa ke-sekian ini sudah pernah dibuat (biar gak dobel kalau admin edit)
                // Opsional tapi bagus untuk keamanan data
                
                // Masukkan ke tabel denda
                $sql_denda = "INSERT INTO denda (id_anggota, jumlah, keterangan) VALUES (?, ?, ?)";
                $stmt_denda = $koneksi->prepare($sql_denda);
                $stmt_denda->bind_param("iis", $id_anggota, $denda, $ket);
                $stmt_denda->execute();
                $stmt_denda->close();
                
                $pesan_tambahan .= " Ada yang kena denda (Alfa ke-$total_alfa)!";
            }
        }
    }

    if ($gagal) {
        $koneksi->rollback();
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data.']);
    } else {
        $koneksi->commit();
        echo json_encode(['success' => true, 'message' => 'Absensi berhasil disimpan.' . $pesan_tambahan]);
    }

} catch (Exception $e) {
    $koneksi->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

if (isset($stmt)) $stmt->close();
$koneksi->close();
?>