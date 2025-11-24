<?php
// Mulai session untuk mengecek status login
session_start();

// --- KONEKSI DATABASE ---
// Sesuaikan detail di bawah ini dengan konfigurasi server/localhost Anda
$host = "localhost";
$username = "root";
$password = "";
$database = "db_basket48";
$koneksi = new mysqli($host, $username, $password, $database);
// ----------------------------------------------------

// Logika untuk tombol dinamis
$isLoggedIn = false;
$dashboardLink = "pages/login.html"; // Default
$dashboardText = "Login";

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $isLoggedIn = true;
    $dashboardLink = "pages/admin_dashboard.php"; 
    $dashboardText = "Dashboard Admin";
} elseif (isset($_SESSION['anggota_logged_in']) && $_SESSION['anggota_logged_in'] === true) {
    $isLoggedIn = true;
    $dashboardLink = "pages/dashboard_anggota.php";
    $dashboardText = "Dashboard Saya";
}

// --- AMBIL DATA DINAMIS ---
$jadwal = [];
$galeri = [];
$struktur = [];
$anggota = [];

if (!$koneksi->connect_error) {
    // 1. Ambil 3 Jadwal Terdekat
    $sql_jadwal = "SELECT * FROM jadwal_latihan WHERE CONCAT(tanggal, ' ', waktu_selesai) >= NOW() ORDER BY tanggal ASC, waktu ASC LIMIT 3";
    $result_jadwal = $koneksi->query($sql_jadwal);
    if ($result_jadwal) {
        while($row = $result_jadwal->fetch_assoc()) $jadwal[] = $row;
    }

    // 2. Ambil 6 Foto Galeri Terbaru
    $sql_galeri = "SELECT * FROM galeri ORDER BY tanggal_upload DESC LIMIT 6";
    $result_galeri = $koneksi->query($sql_galeri);
    if ($result_galeri) {
        while($row = $result_galeri->fetch_assoc()) $galeri[] = $row;
    }
    
    // 3. Ambil Struktur Organisasi
    $sql_struktur = "SELECT * FROM struktur_organisasi ORDER BY urutan ASC, nama ASC";
    $result_struktur = $koneksi->query($sql_struktur);
    if ($result_struktur) {
        while($row = $result_struktur->fetch_assoc()) $struktur[] = $row;
    }

    // 4. Ambil Daftar Anggota
    $sql_anggota = "SELECT id_anggota, nama_lengkap, kelas 
                    FROM anggota 
                    ORDER BY kelas ASC, nama_lengkap ASC";
    $result_anggota = $koneksi->query($sql_anggota);
    if ($result_anggota) {
        while($row = $result_anggota->fetch_assoc()) $anggota[] = $row;
    }
    
    $koneksi->close();
}
// (Opsional: Error handling di index bisa di-silent atau ditampilkan jika perlu)
// --- SELESAI AMBIL DATA ---

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eskul Basket SMKN 48 Jakarta Timur</title>
    
    <link rel="icon" href="assets/images/logo_basket48.png" type="image/png">
    
    <link rel="stylesheet" href="css/main.css?v=6">
    <link rel="stylesheet" href="css/landing.css?v=6">
</head>
<body class="landing-body">

    <header class="public-header">
        <div class="logo-container-public">
            <img src="assets/images/logo_basket48.png" alt="Logo Basket" class="logo-public">
            <span class="logo-text">Eskul Basket SMKN 48</span>
        </div>
        <a href="<?php echo $dashboardLink; ?>" class="top-login-button">
            <?php echo $dashboardText; ?>
        </a>
    </header>

    <main class="landing-main">
        <div class="hero-section">
            <h1>Selamat Datang di Website</h1>
            <h2>Eskul Basket SMKN 48 Jakarta Timur</h2>
            <p>Tempat untuk mengelola data absensi dan jadwal kegiatan ekskul.</p>
            <div class="hero-buttons-container">
                <?php if (!$isLoggedIn): ?>
                    <button id="tombol-daftar-sekarang" class="hero-button">Daftar Eskul</button>
                <?php else: ?>
                    <a href="<?php echo $dashboardLink; ?>" class="hero-button">Lihat Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($struktur)): ?>
        <section class="landing-section">
            <h2 class="section-title">Struktur Organisasi</h2>
            <div class="struktur-container">
                <?php foreach ($struktur as $p): ?>
                    <div class="struktur-item">
                        <img src="assets/uploads/struktur/<?php echo $p['foto']; ?>" alt="<?php echo htmlspecialchars($p['nama']); ?>">
                        <h3><?php echo htmlspecialchars($p['nama']); ?></h3>
                        <p><?php echo htmlspecialchars($p['jabatan']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <?php if (!empty($anggota)): ?>
        <section class="landing-section">
            <h2 class="section-title">Current Rosters</h2>
            <div class="anggota-container" id="roster-container">
                <?php foreach ($anggota as $a): ?>
                    <div class="anggota-item anggota-item-clickable" data-id="<?php echo $a['id_anggota']; ?>">
                        <h3 class="anggota-nama"><?php echo htmlspecialchars($a['nama_lengkap']); ?></h3>
                        <p class="anggota-kelas"><?php echo htmlspecialchars($a['kelas'] ?: 'N/A'); ?></p> 
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <div class="info-section">
            <h3>Tentang Kami</h3>
            <p>Ini adalah sistem internal yang digunakan oleh pengurus untuk mendata kehadiran anggota, mengelola jadwal latihan, dan menyimpan rekapitulasi data.</p>
        </div>

        <?php if (!empty($jadwal)): ?>
        <section class="landing-section">
            <h2 class="section-title">Jadwal Terdekat</h2>
            <div class="jadwal-landing-container">
                <?php foreach ($jadwal as $j): ?>
                    <div class="jadwal-landing-item">
                        <span class="jadwal-tanggal"><?php echo date('D, d M Y', strtotime($j['tanggal'])); ?></span>
                        <span class="jadwal-waktu"><?php echo date('H:i', strtotime($j['waktu'])); ?> WIB</span>
                        <h3 class="jadwal-lokasi"><?php echo htmlspecialchars($j['lokasi']); ?></h3>
                        <p><?php echo htmlspecialchars($j['keterangan'] ?: 'Latihan rutin'); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <?php if (!empty($galeri)): ?>
        <section class="landing-section">
            <h2 class="section-title">Galeri Terbaru</h2>
            <div class="galeri-landing-container">
                <?php foreach ($galeri as $g): ?>
                    <div class="galeri-landing-item galeri-item-clickable">
                        <img src="assets/uploads/galeri/<?php echo $g['nama_file']; ?>" alt="<?php echo htmlspecialchars($g['judul']); ?>">
                        <div class="galeri-judul"><?php echo htmlspecialchars($g['judul']); ?></div>
                        <div class="galeri-keterangan-hidden" style="display:none;"><?php echo htmlspecialchars($g['keterangan']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <footer class="landing-footer">
        <p>&copy; 2025 - Eskul Basket SMKN 48 Jakarta Timur. Dibuat untuk manajemen internal.</p>
    </footer>

    <div id="modal-pendaftaran" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Daftar Eskul Basket</h3>
                <span id="tutup-modal-daftar" class="tutup-modal">&times;</span>
            </div>
            <form id="form-pendaftaran">
                <div class="modal-body">
                    <p>Isi data di bawah ini untuk mendaftar. Pendaftaranmu akan ditinjau oleh Admin.</p>
                    <div class="input-group">
                        <label for="nama_lengkap_daftar">Nama Lengkap Kamu</label>
                        <input type="text" id="nama_lengkap_daftar" required>
                    </div>
                    <div class="input-group">
                        <label for="kelas_daftar">Kelas Kamu</label>
                        <input type="text" id="kelas_daftar" placeholder="Contoh: 10 DKV 1" required>
                    </div>
                    <div class="input-group">
                        <label for="email_daftar">Email Kamu (Untuk Login)</label>
                        <input type="email" id="email_daftar" required>
                    </div>
                    <div class="input-group">
                        <label for="password_daftar">Buat Password</label>
                        <input type="password" id="password_daftar" required>
                    </div>
                    <div class="input-group">
                        <label for="konfirmasi_password_daftar">Konfirmasi Password</label>
                        <input type="password" id="konfirmasi_password_daftar" required>
                    </div>
                    <p id="error-daftar" class="error-message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="tombol-batal-daftar" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary-green">Kirim Pendaftaran</button>
                </div>
            </form>
        </div> 
    </div>
    
    <div id="modal-profil-anggota" class="modal-overlay hidden">
        <div class="modal-content" style="max-width: 450px; padding: 0; overflow: hidden;">
            <div id="modal-profil-content">
                <div style="padding: 40px; text-align: center;">Memuat profil...</div>
            </div>
            <div class="modal-footer" style="justify-content: center; background-color: #f4f7f6;">
                <button type="button" id="tombol-tutup-profil" class="btn-secondary">Tutup</button>
            </div>
        </div> 
    </div>

    <div id="modal-lightbox" class="modal-overlay hidden" style="background-color: rgba(0,0,0,0.9);">
        <div style="position: relative; max-width: 90%; max-height: 90%;">
            <span id="tutup-lightbox" style="position: absolute; top: -40px; right: 0; color: #fff; font-size: 40px; cursor: pointer; font-weight: bold;">&times;</span>
            <img id="lightbox-img" src="" style="max-width: 100%; max-height: 80vh; border-radius: 8px; display: block; margin: 0 auto; box-shadow: 0 0 20px rgba(0,0,0,0.5);">
            <div id="lightbox-caption" style="color: #fff; text-align: center; margin-top: 15px; font-size: 1.2em; font-weight: 500;"></div>
            <div id="lightbox-desc" style="color: #ccc; text-align: center; margin-top: 5px; font-size: 0.9em;"></div>
        </div>
    </div>

    <script src="js/script_landing.js"></script>

</body>
</html>