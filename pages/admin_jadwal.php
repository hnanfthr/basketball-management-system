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
    <title>Kelola Jadwal - Admin Basket SMKN 48</title>
    
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
            <h2>Manajemen Jadwal Latihan</h2>
            <button id="tombol-tambah-jadwal" class="btn-primary">Tambah Jadwal Baru</button>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Hari, Tanggal</th>
                        <th>Jam (Mulai - Selesai)</th>
                        <th>Lokasi</th>
                        <th>Keterangan</th>
                        <th class="kolom-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-jadwal-body">
                    <tr><td colspan="5" style="text-align: center;">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
        
    </main>
    <div id="modal-jadwal" class="modal-overlay hidden">
        <div class="modal-content" style="max-width: 500px;"> <div class="modal-header">
                <h3 id="modal-title">Tambah Jadwal Baru</h3>
                <span id="tombol-tutup-modal" class="tutup-modal">&times;</span>
            </div>
            <form id="form-jadwal">
                <div class="modal-body">
                    <input type="hidden" id="id_jadwal" name="id_jadwal">
                    <input type="hidden" id="action" name="action">
                    
                    <div class="input-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="input-full" required>
                    </div>
                    
                    <div style="display: flex; gap: 15px;">
                        <div class="input-group" style="flex:1;">
                            <label for="waktu">Jam Mulai</label>
                            <input type="time" id="waktu" name="waktu" class="input-full" required>
                        </div>
                        <div class="input-group" style="flex:1;">
                            <label for="waktu_selesai">Jam Selesai</label>
                            <input type="time" id="waktu_selesai" name="waktu_selesai" class="input-full" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Lapangan Indoor" required>
                    </div>
                    <div class="input-group">
                        <label for="keterangan">Keterangan (Opsional)</label>
                        <textarea id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Latihan fisik & sparing"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="tombol-batal" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary-green">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
    <script src="../js/script_jadwal.js"></script>
</body>
</html>