<?php
session_start();
include 'koneksi.php';

// Validasi Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

// Ambil tanggal dari parameter GET
$tanggal_mulai = $_GET['tanggal_mulai'] ?? null;
$tanggal_selesai = $_GET['tanggal_selesai'] ?? null;

if (empty($tanggal_mulai) || empty($tanggal_selesai)) {
    echo json_encode(['success' => false, 'message' => 'Tanggal mulai dan selesai wajib diisi.']);
    exit;
}

try {
    $sql = "
        SELECT 
            a.id_anggota,
            a.nama_lengkap,
            a.kelas,
            COUNT(CASE WHEN ab.status_kehadiran = 'Hadir' THEN 1 END) AS Hadir,
            COUNT(CASE WHEN ab.status_kehadiran = 'Izin' THEN 1 END) AS Izin,
            COUNT(CASE WHEN ab.status_kehadiran = 'Sakit' THEN 1 END) AS Sakit,
            COUNT(CASE WHEN ab.status_kehadiran = 'Alfa' THEN 1 END) AS Alfa,
            COUNT(ab.id_absensi) AS Total
        FROM 
            anggota a
        LEFT JOIN 
            absensi ab ON a.id_anggota = ab.id_anggota 
                         AND ab.tanggal_latihan BETWEEN ? AND ?
        GROUP BY 
            a.id_anggota, a.nama_lengkap, a.kelas
        -- (FIX) Query diubah: Urutkan berdasarkan KELAS dulu, baru NAMA
        ORDER BY 
            a.kelas ASC, a.nama_lengkap ASC
    ";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $tanggal_mulai, $tanggal_selesai);
    $stmt->execute();
    $result = $stmt->get_result();

    $laporan = [];
    while ($row = $result->fetch_assoc()) {
        $laporan[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $laporan]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>