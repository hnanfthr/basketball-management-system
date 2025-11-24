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
    <title>Kelola Galeri - Admin Basket SMKN 48</title>
    
    <link rel="icon" href="../assets/images/logo_basket48.png" type="image/png">
    
    <link rel="stylesheet" href="../css/main.css?v=2">
    <link rel="stylesheet" href="../css/admin.css?v=2">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        .img-container {
            max-width: 100%;
            max-height: 400px;
            overflow: hidden;
        }
        .img-container img {
            width: 100%;
            display: block;
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
            <h2>Upload Foto Baru</h2>
        </div>
        
        <form id="form-upload-galeri" enctype="multipart/form-data" class="form-upload">
            <div class="input-group">
                <label for="judul_foto">Judul Foto</label>
                <input type="text" id="judul_foto" name="judul" required>
            </div>
            <div class="input-group">
                <label for="keterangan_foto">Keterangan (Opsional)</label>
                <textarea id="keterangan_foto" name="keterangan" rows="3"></textarea>
            </div>
            <div class="input-group">
                <label for="file_foto">Pilih File Gambar (Max 2MB)</label>
                <input type="file" id="file_foto" name="foto" accept="image/jpeg, image/png" class="input-full" required>
                <small>Akan otomatis membuka alat crop.</small>
            </div>
            <p id="error-upload" class="error-message"></p>
            <button type="submit" class="btn-primary-green">Upload Foto</button>
        </form>

        <hr class="divider">

        <div class="page-header">
            <h2>Galeri Terupload</h2>
        </div>
        <div id="gallery-admin-container" class="gallery-container">
            <p>Memuat galeri...</p>
        </div>
    </main>

    <div id="modal-crop-galeri" class="modal-overlay hidden" style="z-index: 2000;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Sesuaikan Foto Galeri</h3>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="image-to-crop" src="">
                </div>
                <p style="margin-top:10px; color:#666;">Sesuaikan area foto (Rasio 4:3).</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-batal-crop" class="btn-secondary">Batal</button>
                <button type="button" id="btn-potong-crop" class="btn-primary-green">Potong & Simpan</button>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="../js/script_galeri_admin.js"></script>
</body>
</html>