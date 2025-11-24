<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Akses ditolak.");
}

$tgl_mulai = $_GET['tanggal_mulai'] ?? '';
$tgl_selesai = $_GET['tanggal_selesai'] ?? '';

if (empty($tgl_mulai) || empty($tgl_selesai)) {
    die("Tanggal wajib diisi.");
}

// --- (UBAH) Set Header jadi Excel (.xls) ---
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Absensi_" . $tgl_mulai . "_sd_" . $tgl_selesai . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #4a90e2; color: white; padding: 10px; border: 1px solid #000; }
        td { padding: 8px; border: 1px solid #000; }
    </style>
</head>
<body>
    <h3>LAPORAN REKAPITULASI ABSENSI</h3>
    <p><b>Periode:</b> <?php echo $tgl_mulai . ' s/d ' . $tgl_selesai; ?></p>

    <table border="1">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 250px;">Nama Anggota</th>
                <th style="width: 100px;">Kelas</th>
                <th style="width: 80px; background-color: #28a745;">Hadir</th>
                <th style="width: 80px; background-color: #ffc107; color: black;">Izin</th>
                <th style="width: 80px; background-color: #17a2b8;">Sakit</th>
                <th style="width: 80px; background-color: #dc3545;">Alfa</th>
                <th style="width: 100px; background-color: #6c757d;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT 
                        a.nama_lengkap,
                        a.kelas,
                        COUNT(CASE WHEN ab.status_kehadiran = 'Hadir' THEN 1 END) AS Hadir,
                        COUNT(CASE WHEN ab.status_kehadiran = 'Izin' THEN 1 END) AS Izin,
                        COUNT(CASE WHEN ab.status_kehadiran = 'Sakit' THEN 1 END) AS Sakit,
                        COUNT(CASE WHEN ab.status_kehadiran = 'Alfa' THEN 1 END) AS Alfa,
                        COUNT(ab.id_absensi) AS Total
                    FROM anggota a
                    LEFT JOIN absensi ab ON a.id_anggota = ab.id_anggota 
                                         AND ab.tanggal_latihan BETWEEN ? AND ?
                    GROUP BY a.id_anggota, a.nama_lengkap, a.kelas
                    ORDER BY a.kelas ASC, a.nama_lengkap ASC";

            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ss", $tgl_mulai, $tgl_selesai);
            $stmt->execute();
            $result = $stmt->get_result();

            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td align='center'>$no</td>";
                echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                echo "<td align='center'>" . htmlspecialchars($row['kelas']) . "</td>";
                echo "<td align='center' style='background-color:#e6ffec'>" . $row['Hadir'] . "</td>";
                echo "<td align='center'>" . $row['Izin'] . "</td>";
                echo "<td align='center'>" . $row['Sakit'] . "</td>";
                echo "<td align='center' style='color:red'>" . $row['Alfa'] . "</td>";
                echo "<td align='center'><b>" . $row['Total'] . "</b></td>";
                echo "</tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
$koneksi->close();
?>