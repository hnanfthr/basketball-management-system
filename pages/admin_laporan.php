<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.html");
    exit;
}
$nama_admin = $_SESSION['admin_nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi - Admin Basket SMKN 48</title>
    
    <link rel="icon" href="../assets/images/logo_basket48.png" type="image/png">
    
    <link rel="stylesheet" href="../css/main.css?v=2">
    <link rel="stylesheet" href="../css/admin.css?v=2">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="welcome-message">
                Selamat Datang, <strong><?php echo htmlspecialchars($nama_admin); ?>!</strong>
            </div>
            <a href="../api/logout.php" class="logout-button">Logout</a>
        </div>
        <div class="logo-container">
            <a href="../index.php" title="Kembali ke Landing Page">
                <img src="../assets/images/logo_basket48.png" alt="Logo Eskul Basket SMKN 48" class="logo">
            </a>
        </div>
        <h1>Sistem Absensi Eskul Basket</h1>
        <h2>SMKN 48 Jakarta Timur</h2>
    </header>
    <nav class="admin-nav">
        <ul>
            <li><a href="admin_dashboard.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') ? 'class="active"' : ''; ?>>Dashboard</a></li>
            <li><a href="admin_persetujuan.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_persetujuan.php') ? 'class="active"' : ''; ?>>Persetujuan Anggota</a></li>
            <li><a href="admin_absensi.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_absensi.php') ? 'class="active"' : ''; ?>>Input Absensi</a></li>
            <li><a href="admin_anggota.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_anggota.php') ? 'class="active"' : ''; ?>>Kelola Anggota</a></li>
            <li><a href="admin_laporan.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_laporan.php') ? 'class="active"' : ''; ?>>Lihat Laporan</a></li>
            <li><a href="admin_jadwal.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_jadwal.php') ? 'class="active"' : ''; ?>>Kelola Jadwal</a></li>
            <li><a href="admin_galeri.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_galeri.php') ? 'class="active"' : ''; ?>>Kelola Galeri</a></li>
            <li><a href="admin_kas.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_kas.php') ? 'class="active"' : ''; ?>>Iuran Kas</a></li>
            
            <li><a href="admin_denda.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_denda.php') ? 'class="active"' : ''; ?>>Denda & Sanksi</a></li>
            
            <li><a href="admin_struktur.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_struktur.php') ? 'class="active"' : ''; ?>>Kelola Struktur</a></li>
        </ul>
    </nav>
    <main>
        <div class="page-header">
            <h2>Laporan Rekapitulasi Absensi</h2>
        </div>
        
        <form id="form-laporan" class="absensi-header" style="display: flex; align-items: flex-end; gap: 15px;">
            <div style="flex: 1;">
                <label for="tanggal_mulai">Dari Tanggal:</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" required style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label for="tanggal_selesai">Sampai Tanggal:</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" required style="width: 100%;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-primary">Tampilkan</button>
                <button type="button" id="btn-download-laporan" class="btn-primary-green">Download Excel</button>
            </div>
        </form>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Kelas</th>
                        <th class="rekap-hadir">Hadir</th>
                        <th class="rekap-izin">Izin</th>
                        <th class="rekap-sakit">Sakit</th>
                        <th class="rekap-alfa">Alfa</th>
                        <th class="rekap-total">Total Latihan</th>
                    </tr>
                </thead>
                <tbody id="tabel-laporan-body">
                    <tr>
                        <td colspan="8" style="text-align: center;">Silakan pilih rentang tanggal dan klik "Tampilkan".</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    
    <script src="../js/script_laporan.js"></script>
    
    <script>
        document.getElementById('btn-download-laporan').addEventListener('click', function() {
            const tglMulai = document.getElementById('tanggal_mulai').value;
            const tglSelesai = document.getElementById('tanggal_selesai').value;
            
            if (!tglMulai || !tglSelesai) {
                alert("Pilih rentang tanggal terlebih dahulu.");
                return;
            }
            
            window.location.href = `../api/export_laporan.php?tanggal_mulai=${tglMulai}&tanggal_selesai=${tglSelesai}`;
        });
    </script>
</body>
</html>