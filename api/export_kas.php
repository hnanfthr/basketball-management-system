<?php
session_start();
include 'koneksi.php';

// Validasi Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Akses ditolak.");
}

$id_jadwal = $_GET['id_jadwal'] ?? 0;
// (BARU) Tangkap nominal dari URL
$nominal = isset($_GET['nominal']) ? intval($_GET['nominal']) : 0;

if (empty($id_jadwal)) {
    die("Pilih jadwal terlebih dahulu.");
}

// Ambil Info Jadwal
$sql_jadwal = "SELECT tanggal, lokasi FROM jadwal_latihan WHERE id_jadwal = ?";
$stmt = $koneksi->prepare($sql_jadwal);
$stmt->bind_param("i", $id_jadwal);
$stmt->execute();
$jadwal = $stmt->get_result()->fetch_assoc();
$tgl = date('d-m-Y', strtotime($jadwal['tanggal']));

// Set Header jadi Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Kas_$tgl.xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #4a90e2; color: white; padding: 10px; }
        td { padding: 8px; }
    </style>
</head>
<body>
    <h3>LAPORAN IURAN KAS BASKET</h3>
    <p>
        <b>Tanggal:</b> <?php echo $tgl; ?><br>
        <b>Lokasi:</b> <?php echo $jadwal['lokasi']; ?><br>
        <b>Tarif Kas:</b> Rp <?php echo number_format($nominal, 0, ',', '.'); ?>
    </p>

    <table border="1">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 250px;">Nama Anggota</th>
                <th style="width: 100px;">Kelas</th>
                <th style="width: 150px;">Status Bayar</th>
                <th style="width: 150px;">Uang Masuk (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT a.nama_lengkap, a.kelas, COALESCE(k.status, 'Belum') AS status
                    FROM anggota a
                    LEFT JOIN kas_latihan k ON a.id_anggota = k.id_anggota AND k.id_jadwal = ?
                    ORDER BY a.kelas ASC, a.nama_lengkap ASC";

            $stmt_data = $koneksi->prepare($sql);
            $stmt_data->bind_param("i", $id_jadwal);
            $stmt_data->execute();
            $result = $stmt_data->get_result();

            $no = 1;
            $total_uang = 0; // Variabel penampung total
            
            while ($row = $result->fetch_assoc()) {
                // Tentukan nominal per orang (hanya jika Lunas)
                $uang_masuk = ($row['status'] == 'Lunas') ? $nominal : 0;
                $total_uang += $uang_masuk;
                
                $style_status = ($row['status'] == 'Lunas') 
                    ? 'background-color: #d4edda; color: #155724; font-weight:bold;' 
                    : 'background-color: #f8d7da; color: #721c24;';
                
                echo "<tr>";
                echo "<td align='center'>$no</td>";
                echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                echo "<td align='center'>" . htmlspecialchars($row['kelas']) . "</td>";
                echo "<td align='center' style='$style_status'>" . $row['status'] . "</td>";
                // Tampilkan Uang Masuk
                echo "<td align='right'>" . number_format($uang_masuk, 0, ',', '.') . "</td>";
                echo "</tr>";
                $no++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="right"><b>TOTAL TERKUMPUL:</b></td>
                <td align="right" style="background-color: yellow;"><b>Rp <?php echo number_format($total_uang, 0, ',', '.'); ?></b></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
<?php
$koneksi->close();
?>