<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['anggota_logged_in']) || $_SESSION['anggota_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

$id_anggota = $_SESSION['anggota_id'];

// --- (BARU) Tentukan Awal Semester Hari Ini ---
$bulan_ini = date('n'); // 1-12
$tahun_ini = date('Y');

if ($bulan_ini >= 7) {
    $tgl_awal_semester = "$tahun_ini-07-01"; // Semester Ganjil
} else {
    $tgl_awal_semester = "$tahun_ini-01-01"; // Semester Genap
}
// -----------------------------------------------

try {
    // 0. Ambil Profil
    $sql_profil = "SELECT nama_lengkap, kelas, posisi_main, foto_profil FROM anggota WHERE id_anggota = ?";
    $stmt_profil = $koneksi->prepare($sql_profil);
    $stmt_profil->bind_param("i", $id_anggota);
    $stmt_profil->execute();
    $profil = $stmt_profil->get_result()->fetch_assoc();
    $stmt_profil->close();

    // 1. Ambil Absensi (Tetap tampilkan semua history, atau mau dibatasi semester ini juga? 
    // Biasanya history absen enak dilihat semua. Kita biarkan semua history di tabel, 
    // tapi REKAP ANGKA-nya kita reset).
    $sql_list = "SELECT tanggal_latihan, status_kehadiran FROM absensi WHERE id_anggota = ? ORDER BY tanggal_latihan DESC";
    $stmt_list = $koneksi->prepare($sql_list);
    $stmt_list->bind_param("i", $id_anggota);
    $stmt_list->execute();
    $list_absensi = $stmt_list->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_list->close();

    // 2. Rekap Absensi (RESET PER SEMESTER)
    // (MODIFIKASI) Tambahkan WHERE tanggal_latihan >= tgl_awal_semester
    $sql_rekap = "SELECT 
            COUNT(CASE WHEN status_kehadiran = 'Hadir' THEN 1 END) AS Hadir,
            COUNT(CASE WHEN status_kehadiran = 'Izin' THEN 1 END) AS Izin,
            COUNT(CASE WHEN status_kehadiran = 'Sakit' THEN 1 END) AS Sakit,
            COUNT(CASE WHEN status_kehadiran = 'Alfa' THEN 1 END) AS Alfa
        FROM absensi 
        WHERE id_anggota = ? AND tanggal_latihan >= ?"; 
        
    $stmt_rekap = $koneksi->prepare($sql_rekap);
    $stmt_rekap->bind_param("is", $id_anggota, $tgl_awal_semester);
    $stmt_rekap->execute();
    $rekap = $stmt_rekap->get_result()->fetch_assoc();
    $stmt_rekap->close();

    // 3. Jadwal
    $sql_jadwal = "SELECT * FROM jadwal_latihan WHERE CONCAT(tanggal, ' ', waktu_selesai) >= NOW() ORDER BY tanggal ASC, waktu ASC LIMIT 5";
    $result_jadwal = $koneksi->query($sql_jadwal);
    $list_jadwal = $result_jadwal->fetch_all(MYSQLI_ASSOC);
    
    // 4. Galeri
    $sql_galeri = "SELECT nama_file, judul, keterangan, tanggal_upload FROM galeri ORDER BY tanggal_upload DESC LIMIT 9";
    $result_galeri = $koneksi->query($sql_galeri);
    $list_galeri = $result_galeri->fetch_all(MYSQLI_ASSOC);

    // 5. Kas
    $sql_kas = "SELECT j.tanggal, j.lokasi, k.status FROM kas_latihan k
                JOIN jadwal_latihan j ON k.id_jadwal = j.id_jadwal
                WHERE k.id_anggota = ? ORDER BY j.tanggal DESC";
    $stmt_kas = $koneksi->prepare($sql_kas);
    $stmt_kas->bind_param("i", $id_anggota);
    $stmt_kas->execute();
    $data_kas_sesi = $stmt_kas->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_kas->close();

    // 6. Denda
    $sql_denda = "SELECT * FROM denda WHERE id_anggota = ? ORDER BY id_denda DESC";
    $stmt_denda = $koneksi->prepare($sql_denda);
    $stmt_denda->bind_param("i", $id_anggota);
    $stmt_denda->execute();
    $list_denda = $stmt_denda->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_denda->close();

    echo json_encode([
        'success' => true, 
        'data_profil' => $profil,
        'data_list' => $list_absensi,
        'data_rekap' => $rekap, // Ini sekarang berisi data semester ini saja
        'data_jadwal' => $list_jadwal,
        'data_galeri' => $list_galeri,
        'data_kas' => $data_kas_sesi,
        'data_denda' => $list_denda
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$koneksi->close();
?>