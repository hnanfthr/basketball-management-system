<?php
// Mulai session di paling atas file
session_start();

// Masukkan file koneksi
include 'koneksi.php';

// Ambil data dari form (method POST)
$username = $_POST['username'] ?? ''; // Bisa username (admin) or email (anggota)
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'anggota'; // Default ke 'anggota'

// Validasi input dasar
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username/Email dan Password tidak boleh kosong.']);
    exit;
}

try {
    if ($role === 'admin') {
        // --- PROSES LOGIN ADMIN ---
        $sql = "SELECT * FROM admin WHERE username = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password_hash'])) {
                // Password Admin Cocok!
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_nama'] = $admin['nama_lengkap'];

                echo json_encode([
                    'success' => true, 
                    'message' => 'Login Admin berhasil! Selamat datang, ' . $admin['nama_lengkap'],
                    'role' => 'admin',
                    // (MODIFIKASI) Ubah redirect_url ke dashboard baru
                    'redirect_url' => 'admin_dashboard.php' 
                ]);
            } else {
                throw new Exception('Username atau Password salah.');
            }
        } else {
            throw new Exception('Username atau Password salah.');
        }

    } else {
        // --- PROSES LOGIN ANGGOTA ---
        $sql = "SELECT * FROM anggota WHERE email = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $username); // Anggota login pakai email
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $anggota = $result->fetch_assoc();
            
            // Debugging: Cek hash dari DB
            // if ($anggota['password_hash'] == null) {
            //    throw new Exception('Hash password di DB kosong.');
            // }

            if (password_verify($password, $anggota['password_hash'])) {
                // Password Anggota Cocok!
                $_SESSION['anggota_logged_in'] = true;
                $_SESSION['anggota_id'] = $anggota['id_anggota'];
                $_SESSION['anggota_nama'] = $anggota['nama_lengkap'];

                echo json_encode([
                    'success' => true, 
                    'message' => 'Login berhasil! Selamat datang, ' . $anggota['nama_lengkap'],
                    'role' => 'anggota',
                    'redirect_url' => 'dashboard_anggota.php' // Halaman dashboard anggota
                ]);
            } else {
                throw new Exception('Email atau Password salah.');
            }
        } else {
            throw new Exception('Email atau Password salah.');
        }
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Pastikan $stmt ditutup hanya jika sudah di-set
if (isset($stmt)) {
    $stmt->close();
}
$koneksi->close();
?>