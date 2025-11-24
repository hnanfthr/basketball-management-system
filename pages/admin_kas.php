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
    <title>Kelola Iuran Kas - Admin Basket SMKN 48</title>
    
    <link rel="icon" href="../assets/images/logo_basket48.png" type="image/png">
    
    <link rel="stylesheet" href="../css/main.css?v=5">
    <link rel="stylesheet" href="../css/admin.css?v=5">
    <style>
        .kas-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
            display: none; 
        }
        .kas-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .kas-card h3 { margin: 0 0 10px 0; color: #666; font-size: 1em; }
        .kas-card .angka { font-size: 1.8em; font-weight: 700; color: var(--dark-blue); }
        .kas-card .angka.uang { color: var(--success-green); }
        .kas-card .angka.tunggakan { color: var(--danger-red); }

        /* Style Filter & Action */
        .kas-controls {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .kas-inputs-group {
            display: flex;
            gap: 20px;
            flex: 1;
            flex-wrap: wrap;
        }
        .control-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 200px;
        }
        .control-item label {
            font-weight: 600;
            font-size: 0.95em;
            color: var(--dark-blue);
        }
        .control-item select, .control-item input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            outline: none;
            transition: border 0.2s;
        }
        .input-group-rp {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .prefix-rp {
            background-color: #f1f3f5;
            color: #555;
            padding: 12px 15px;
            font-weight: 600;
            border-right: 1px solid #ccc;
        }
        .input-group-rp input {
            border: none !important;
            border-radius: 0 !important;
            width: 100%;
            box-shadow: none !important;
        }
        .btn-download-large {
            padding: 12px 25px;
            background-color: #218838;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            height: 46px;
            transition: all 0.2s;
        }
        .btn-download-large:hover {
            background-color: #1e7e34;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(33, 136, 56, 0.3);
        }
        .btn-download-large:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        @media (max-width: 768px) {
            .kas-controls { flex-direction: column; align-items: stretch; }
            .kas-inputs-group { flex-direction: column; }
            .btn-download-large { width: 100%; justify-content: center; }
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
            <h2>Manajemen Iuran Kas</h2>
        </div>
        
        <div class="kas-controls">
            <div class="kas-inputs-group">
                <div class="control-item" style="flex-grow: 2;">
                    <label for="filter-jadwal">Pilih Sesi Latihan</label>
                    <select id="filter-jadwal">
                        <option value="">--- Memuat Jadwal ---</option>
                    </select>
                </div>

                <div class="control-item" style="flex-grow: 1;">
                    <label for="input-nominal">Nominal Kas</label>
                    <div class="input-group-rp">
                        <div class="prefix-rp">Rp</div>
                        <input type="number" id="input-nominal" placeholder="0" min="0" step="500">
                    </div>
                </div>
            </div>

            <div class="control-item">
                <label style="visibility: hidden;">Aksi</label>
                <button id="btn-download-excel" class="btn-download-large" disabled>
                    📥 Download Excel
                </button>
            </div>
        </div>

        <div id="kas-summary-box" class="kas-summary">
            <div class="kas-card">
                <h3>Total Terkumpul</h3>
                <div id="total-terkumpul" class="angka uang">Rp 0</div>
            </div>
            <div class="kas-card">
                <h3>Potensi / Belum Lunas</h3>
                <div id="total-tunggakan" class="angka tunggakan">Rp 0</div>
            </div>
            <div class="kas-card">
                <h3>Anggota Bayar</h3>
                <div id="persentase-bayar" class="angka">0 / 0</div>
            </div>
        </div>

        <div class="table-container">
            <table class="tabel-kas">
                <thead>
                    <tr>
                        <th class="sticky-col">Nama Anggota</th>
                        <th>Kelas</th>
                        <th style="width: 150px;">Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody id="tabel-kas-sesi-body">
                    <tr><td colspan="3" style="text-align: center;">Silakan pilih sesi latihan di atas.</td></tr>
                </tbody>
            </table>
        </div>
    </main>

    <div id="toast-container"></div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        const tbody = document.getElementById('tabel-kas-sesi-body');
        const filterJadwal = document.getElementById('filter-jadwal');
        const inputNominal = document.getElementById('input-nominal');
        const summaryBox = document.getElementById('kas-summary-box');
        const elTerkumpul = document.getElementById('total-terkumpul');
        const elTunggakan = document.getElementById('total-tunggakan');
        const elPersentase = document.getElementById('persentase-bayar');
        const btnDownload = document.getElementById('btn-download-excel');
        const toastContainer = document.getElementById('toast-container');
        
        let idJadwalTerpilih = null;

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `<span>${type === 'success' ? '✅' : '❌'}</span> ${message}`;
            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.5s forwards';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function hitungSummary() {
            const checkboxes = document.querySelectorAll('.kas-checkbox');
            let lunasCount = 0;
            let totalAnggota = checkboxes.length;
            let nominalKas = parseInt(inputNominal.value);
            
            if (isNaN(nominalKas)) nominalKas = 0; 

            checkboxes.forEach(cb => {
                if (cb.checked) lunasCount++;
            });

            let totalUang = lunasCount * nominalKas;
            let totalTunggakan = (totalAnggota - lunasCount) * nominalKas;

            elTerkumpul.textContent = formatRupiah(totalUang);
            elTunggakan.textContent = formatRupiah(totalTunggakan);
            elPersentase.textContent = `${lunasCount} / ${totalAnggota}`;
        }

        function formatJadwalDropdown(tgl, waktu, lokasi) {
            const d = new Date(tgl + 'T' + waktu);
            const tglStr = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            const waktuStr = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            return `${tglStr} (${waktuStr} WIB) - ${lokasi}`;
        }

        async function muatJadwalDropdown() {
            try {
                const response = await fetch('../api/crud_jadwal.php?action=baca_semua');
                const result = await response.json();
                if (!result.success) throw new Error(result.message);

                filterJadwal.innerHTML = '<option value="">--- Pilih Sesi Latihan ---</option>';
                result.data.forEach(jadwal => {
                    const option = document.createElement('option');
                    option.value = jadwal.id_jadwal;
                    option.textContent = formatJadwalDropdown(jadwal.tanggal, jadwal.waktu, jadwal.lokasi);
                    filterJadwal.appendChild(option);
                });
            } catch (error) {
                filterJadwal.innerHTML = `<option value="">Gagal: ${error.message}</option>`;
            }
        }

        async function muatStatusKasSesi(id_jadwal) {
            if (!id_jadwal) {
                tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Silakan pilih sesi latihan.</td></tr>';
                summaryBox.style.display = 'none';
                btnDownload.disabled = true; 
                return;
            }
            
            idJadwalTerpilih = id_jadwal; 
            btnDownload.disabled = false; 
            tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Memuat data...</td></tr>';
            summaryBox.style.display = 'none';
            
            try {
                const response = await fetch(`../api/crud_kas.php?action=baca_sesi&id_jadwal=${id_jadwal}`);
                const result = await response.json();
                if (!result.success) throw new Error(result.message);

                tbody.innerHTML = '';
                if (result.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Tidak ada anggota.</td></tr>';
                    return;
                }

                result.data.forEach(anggota => {
                    const tr = document.createElement('tr');
                    const isLunas = anggota.status_bayar === 'Lunas';
                    
                    tr.innerHTML = `
                        <td class="sticky-col">${anggota.nama_lengkap}</td>
                        <td>${anggota.kelas || 'N/A'}</td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="kas-checkbox" 
                                   data-id-anggota="${anggota.id_anggota}"
                                   ${isLunas ? 'checked' : ''}>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                summaryBox.style.display = 'grid';
                hitungSummary();

            } catch (error) {
                tbody.innerHTML = `<tr><td colspan="3" style="text-align: center;">Gagal: ${error.message}</td></tr>`;
            }
        }

        filterJadwal.addEventListener('change', function() {
            muatStatusKasSesi(this.value);
        });

        inputNominal.addEventListener('input', hitungSummary);

        // Kirim Nominal ke URL saat download
        btnDownload.addEventListener('click', function() {
            if(idJadwalTerpilih) {
                const nominal = inputNominal.value || 0;
                window.location.href = `../api/export_kas.php?id_jadwal=${idJadwalTerpilih}&nominal=${nominal}`;
            }
        });

        tbody.addEventListener('change', async function(e) {
            if (e.target.classList.contains('kas-checkbox')) {
                const checkbox = e.target;
                
                if (!idJadwalTerpilih) {
                    alert('Pilih jadwal terlebih dahulu.');
                    checkbox.checked = !checkbox.checked; 
                    return;
                }

                const nominal = inputNominal.value;
                if (!nominal || nominal <= 0) {
                    alert('Harap isi NOMINAL KAS terlebih dahulu!');
                    checkbox.checked = !checkbox.checked; 
                    inputNominal.focus();
                    return;
                }

                const idAnggota = checkbox.dataset.idAnggota;
                const status = checkbox.checked ? 'Lunas' : 'Belum';

                checkbox.disabled = true; 
                
                try {
                    const formData = new FormData();
                    formData.append('action', 'update_sesi');
                    formData.append('id_anggota', idAnggota);
                    formData.append('id_jadwal', idJadwalTerpilih);
                    formData.append('status', status);

                    const response = await fetch('../api/crud_kas.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (!result.success) throw new Error(result.message);
                    
                    checkbox.disabled = false;
                    hitungSummary();
                    showToast(status === 'Lunas' ? 'Pembayaran dicatat ✅' : 'Pembayaran dibatalkan ↩️');
                    
                } catch (error) {
                    alert(`Gagal menyimpan: ${error.message}`);
                    checkbox.checked = !checkbox.checked; 
                    checkbox.disabled = false;
                }
            }
        });

        muatJadwalDropdown();
    });
    </script>
</body>
</html>