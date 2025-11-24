<?php
// api/get_dashboard_stats.php

session_start();
include 'koneksi.php';

// Validasi Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

try {
    $stats = [];

    // 1. Total Anggota Aktif
    $result_anggota = $koneksi->query("SELECT COUNT(*) AS total FROM anggota");
    $stats['total_anggota'] = $result_anggota->fetch_assoc()['total'] ?? 0;

    // 2. Total Pendaftar Pending
    $result_pending = $koneksi->query("SELECT COUNT(*) AS total FROM pendaftaran_pending");
    $stats['total_pending'] = $result_pending->fetch_assoc()['total'] ?? 0;

    // 3. Rekap Absensi (30 Hari Terakhir)
    $sql_absensi = "SELECT status_kehadiran, COUNT(*) AS jumlah 
                    FROM absensi 
                    WHERE tanggal_latihan >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                    GROUP BY status_kehadiran";
    $result_absensi = $koneksi->query($sql_absensi);
    
    $rekap_absensi = [
        'Hadir' => 0,
        'Izin' => 0,
        'Sakit' => 0,
        'Alfa' => 0
    ];
    while($row = $result_absensi->fetch_assoc()) {
        if (isset($rekap_absensi[$row['status_kehadiran']])) {
            $rekap_absensi[$row['status_kehadiran']] = (int)$row['jumlah'];
        }
    }
    $stats['rekap_absensi_30hari'] = $rekap_absensi;

    // 4. Pendaftar Baru (6 Bulan Terakhir)
    $sql_pendaftar = "SELECT DATE_FORMAT(tanggal_daftar, '%Y-%m') AS bulan, COUNT(*) AS jumlah 
                      FROM anggota 
                      WHERE tanggal_bergabung >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
                      GROUP BY bulan 
                      ORDER BY bulan ASC";
    // Fallback jika 'tanggal_bergabung' tidak ada (meski seharusnya ada dari persetujuan)
    // Jika Anda belum punya 'tanggal_bergabung' di tabel 'anggota', query ini perlu diubah
    // Untuk sementara, kita pakai 'pendaftaran_pending' untuk simulasikan pendaftar
    
    $sql_pendaftar_simulasi = "SELECT DATE_FORMAT(tanggal_daftar, '%Y-%m') AS bulan, COUNT(*) AS jumlah 
                               FROM pendaftaran_pending 
                               WHERE tanggal_daftar >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) 
                               GROUP BY bulan 
                               ORDER BY bulan ASC";

    $result_pendaftar = $koneksi->query($sql_pendaftar_simulasi);
    $pendaftar_per_bulan = [];
    $labels_bulan = [];
    $data_jumlah = [];
    
    while($row = $result_pendaftar->fetch_assoc()) {
        $labels_bulan[] = date("M Y", strtotime($row['bulan'] . "-01"));
        $data_jumlah[] = (int)$row['jumlah'];
    }
    $stats['pendaftar_6bulan'] = [
        'labels' => $labels_bulan,
        'data' => $data_jumlah
    ];

    // Kirim semua statistik
    echo json_encode(['success' => true, 'data' => $stats]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>