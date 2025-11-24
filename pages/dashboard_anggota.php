<?php
session_start();
if (!isset($_SESSION['anggota_logged_in']) || $_SESSION['anggota_logged_in'] !== true) {
    header("Location: login.html");
    exit;
}
$nama_anggota = $_SESSION['anggota_nama'];
$id_anggota = $_SESSION['anggota_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggota - Basket SMKN 48</title>
    
    <link rel="icon" href="../assets/images/logo_basket48.png" type="image/png">
    
    <link rel="stylesheet" href="../css/main.css?v=2">
    <link rel="stylesheet" href="../css/admin.css?v=2"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <style>
        /* Style Profil */
        .profile-card {
            background-color: #f4f7f6;
            padding: 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            flex-wrap: wrap; 
            gap: 20px;
            margin-bottom: 30px;
        }
        .profile-card-foto {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary-blue);
            flex-shrink: 0;
        }
        .profile-card-info { flex: 1; }
        .profile-card-info h2 { margin: 0; color: var(--dark-blue); }
        .profile-card-info p { margin: 5px 0; font-size: 1.1em; color: #555; }
        .profile-card-info .posisi { font-weight: 600; color: var(--accent-orange); }
        .profile-card-action { margin-left: auto; }
        
        .modal-body .profile-preview { text-align: center; margin-bottom: 15px; }
        .modal-body .profile-preview img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #ddd; }
        .img-container { max-width: 100%; max-height: 400px; overflow: hidden; }
        .img-container img { width: 100%; display: block; }

        /* (BARU) Style Kotak Denda */
        .denda-alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
            display: none; /* Sembunyi default */
        }
        .denda-alert h3 { margin: 0 0 10px 0; font-size: 1.2em; }
        .denda-item {
            background: rgba(255,255,255,0.5);
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-top">
            <div class="welcome-message">
                Selamat Datang, <strong><?php echo htmlspecialchars($nama_anggota); ?>!</strong>
            </div>
            <a href="../api/logout.php" class="logout-button">Logout</a>
        </div>
        <div class="logo-container">
            <a href="../index.php" title="Kembali ke Landing Page">
                <img src="../assets/images/logo_basket48.png" alt="Logo Eskul Basket SMKN 48" class="logo">
            </a>
        </div>
        <h1>Dashboard Anggota</h1>
        <h2>Eskul Basket SMKN 48 Jakarta Timur</h2>
    </header>

    <main>
        <div class="profile-card">
            <img id="profil-foto" src="../assets/uploads/profil/default_avatar.png" alt="Foto Profil" class="profile-card-foto">
            <div class="profile-card-info">
                <h2 id="profil-nama">Memuat Nama...</h2>
                <p id="profil-kelas">Memuat Kelas...</p>
                <p id="profil-posisi" class="posisi">Memuat Posisi...</p>
            </div>
            <div class="profile-card-action">
                <button id="tombol-edit-profil" class="btn-primary">Edit Profil</button>
            </div>
        </div>

        <div id="denda-container" class="denda-alert">
            <h3>⚠️ Peringatan Punishment</h3>
            <div id="denda-list"></div>
            <p style="margin-top:10px; font-size:0.9em;">*Silakan hubungi Bendahara untuk pembayaran.</p>
        </div>

        <div class="page-header">
            <h2>Riwayat Iuran Kas Kamu</h2>
        </div>
        <div class="table-container">
            <table style="width: 100%; min-width: 0;">
                <thead>
                    <tr>
                        <th>Tanggal Latihan</th>
                        <th>Lokasi</th>
                        <th>Status Bayar</th>
                    </tr>
                </thead>
                <tbody id="tabel-kas-anggota-body">
                    <tr><td colspan="3" style="text-align: center;">Memuat riwayat kas...</td></tr>
                </tbody>
            </table>
        </div>
        <hr class="divider">
        
        <div class="page-header">
            <h2>Galeri Kegiatan</h2>
        </div>
        <div id="gallery-anggota-container" class="gallery-container">
            <p>Memuat galeri...</p>
        </div>
        <hr class="divider">
        
        <div class="page-header">
            <h2>Jadwal Latihan Akan Datang</h2>
        </div>
        <div id="jadwal-list-container">
            <p>Memuat jadwal...</p>
        </div>
        <hr class="divider">

        <div class="page-header">
            <h2>Rekap Absensi Kamu</h2>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Latihan</th>
                        <th>Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody id="tabel-absensi-anggota">
                    <tr>
                        <td colspan="3" style="text-align: center;">Memuat data absensi...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div id="rekap-total-container" class="rekap-container">
            <h3>Total Rekapitulasi</h3>
            <div class="rekap-grid">
                <div class="rekap-box rekap-hadir">
                    <span id="total-hadir">0</span> Hadir
                </div>
                <div class="rekap-box rekap-izin">
                    <span id="total-izin">0</span> Izin
                </div>
                <div class="rekap-box rekap-sakit">
                    <span id="total-sakit">0</span> Sakit
                </div>
                <div class="rekap-box rekap-alfa">
                    <span id="total-alfa">0</span> Alfa
                </div>
            </div>
        </div>

        <hr class="divider">
        
        <div class="page-header">
            <h2>Ubah Password</h2>
        </div>
        <form id="form-ganti-password">
            <div class="input-group">
                <label for="password_lama">Password Lama</label>
                <input type="password" id="password_lama" name="password_lama" required>
            </div>
            <div class="input-group">
                <label for="password_baru">Password Baru (Min. 6 karakter)</label>
                <input type="password" id="password_baru" name="password_baru" required>
            </div>
            <div class="input-group">
                <label for="konfirmasi_password_baru">Konfirmasi Password Baru</label>
                <input type="password" id="konfirmasi_password_baru" name="konfirmasi_password_baru" required>
            </div>
            <p id="error-password" class="error-message"></p>
            <button type="submit" class="btn-primary" style="margin: 10px 0;">Ganti Password</button>
        </form>
    </main>

    <div id="modal-edit-profil" class="modal-overlay hidden">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Edit Profil Saya</h3>
                <span id="tombol-tutup-edit" class="tutup-modal">&times;</span>
            </div>
            <form id="form-edit-profil" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="profile-preview">
                        <img id="preview-foto-profil" src="../assets/uploads/profil/default_avatar.png" alt="Preview Foto Profil">
                    </div>
                    <div class="input-group">
                        <label for="input_foto_profil">Ubah Foto Profil (Max 1MB)</label>
                        <input type="file" id="input_foto_profil" name="foto_profil" class="input-full" accept="image/jpeg, image/png">
                        <small>Kosongkan jika tidak ingin mengubah foto.</small>
                    </div>
                    <div class="input-group">
                        <label for="select_posisi_main">Posisi Main</label>
                        <select id="select_posisi_main" name="posisi_main" class="input-full">
                            <option value="">-- Pilih Posisi --</option>
                            <option value="PG">Point Guard (PG)</option>
                            <option value="SG">Shooting Guard (SG)</option>
                            <option value="SF">Small Forward (SF)</option>
                            <option value="PF">Power Forward (PF)</option>
                            <option value="C">Center (C)</option>
                        </select>
                    </div>
                    <p id="error-edit-profil" class="error-message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="tombol-batal-edit" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary-green">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-crop-tool" class="modal-overlay hidden" style="z-index: 2000;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header"><h3>Potong Foto</h3></div>
            <div class="modal-body">
                <div class="img-container"><img id="image-to-crop" src=""></div>
                <p style="margin-top:10px; color:#666;">Geser dan cubit untuk menyesuaikan area foto.</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-batal-crop" class="btn-secondary">Batal</button>
                <button type="button" id="btn-potong-crop" class="btn-primary-green">Potong & Gunakan</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="../js/script_dashboard.js"></script>
</body>
</html>