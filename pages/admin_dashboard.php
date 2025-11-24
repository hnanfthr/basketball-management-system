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
    <title>Dashboard Admin - Basket SMKN 48</title>
    
    <link rel="icon" href="../assets/images/logo_basket48.png" type="image/png">
    
    <link rel="stylesheet" href="../css/main.css?v=2">
    <link rel="stylesheet" href="../css/admin.css?v=2">
    <style>
        /* Style untuk kartu statistik */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: var(--bg-grey);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid var(--border-color);
            text-align: center;
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: var(--dark-blue);
            font-size: 1.1em;
        }
        .stat-card span {
            font-size: 2.5em;
            font-weight: 700;
            color: var(--primary-blue);
        }
        /* Style untuk container chart */
        .chart-container {
            background-color: var(--bg-light);
            padding: 25px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
        }
        .chart-container h2 {
            margin-top: 0;
            color: var(--dark-blue);
            text-align: center;
        }
    </style>
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
            <h2>Dashboard Utama</h2>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Anggota Aktif</h3>
                <span id="stat-total-anggota">...</span>
            </div>
            <div class="stat-card">
                <h3>Pendaftar Pending</h3>
                <span id="stat-total-pending">...</span>
            </div>
            </div>

        <div class="chart-container">
            <h2>Rekap Absensi (30 Hari Terakhir)</h2>
            <canvas id="chartAbsensi"></canvas>
        </div>

        <div class="chart-container">
            <h2>Pendaftar Baru (6 Bulan Terakhir)</h2>
            <canvas id="chartPendaftar"></canvas>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="../js/script_dashboard_admin.js"></script>
</body>
</html>