// Menjalankan kode setelah semua elemen HTML selesai dimuat
document.addEventListener('DOMContentLoaded', function() {

    // --- 1. MEMUAT DATA ANGGOTA SAAT HALAMAN DIBUKA ---
    
    const tbody = document.getElementById('daftar-anggota');
    
    // Fungsi untuk mengambil dan menampilkan data anggota
    async function muatAnggota() {
        try {
            // Panggil API untuk ambil data
            const response = await fetch('../api/ambil_anggota.php');
            const dataAnggota = await response.json();

            // Kosongkan isi tabel (tbody)
            tbody.innerHTML = '';

            if (dataAnggota.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4">Tidak ada data anggota. Harap tambahkan dulu.</td></tr>';
                return;
            }

            // Loop setiap data anggota dan buat baris tabel (tr)
            dataAnggota.forEach((anggota, index) => {
                const tr = document.createElement('tr');
                
                // Buat HTML untuk satu baris
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${anggota.nama_lengkap}</td>
                    <td>${anggota.kelas}</td>
                    <td>
                        <div class="radio-group">
                            <input type="radio" id="hadir_${anggota.id_anggota}" name="status[${anggota.id_anggota}]" value="Hadir" required>
                            <label for="hadir_${anggota.id_anggota}">H</label>
                            
                            <input type="radio" id="izin_${anggota.id_anggota}" name="status[${anggota.id_anggota}]" value="Izin">
                            <label for="izin_${anggota.id_anggota}">I</label>
                            
                            <input type="radio" id="sakit_${anggota.id_anggota}" name="status[${anggota.id_anggota}]" value="Sakit">
                            <label for="sakit_${anggota.id_anggota}">S</label>
                            
                            <input type="radio" id="alfa_${anggota.id_anggota}" name="status[${anggota.id_anggota}]" value="Alfa">
                            <label for="alfa_${anggota.id_anggota}">A</label>
                        </div>
                    </td>
                `;
                // Masukkan baris ke dalam tabel
                tbody.appendChild(tr);
            });

        } catch (error) {
            console.error('Error memuat data anggota:', error);
            tbody.innerHTML = '<tr><td colspan="4">Gagal memuat data. Cek konsol.</td></tr>';
        }
    }

    // Panggil fungsi untuk memuat anggota
    muatAnggota();

    
    // --- 2. MENYIMPAN DATA ABSENSI SAAT TOMBOL SUBMIT DIKLIK ---

    const formAbsensi = document.getElementById('form-absensi');

    formAbsensi.addEventListener('submit', async function(e) {
        // Mencegah form mengirim data secara default (reload halaman)
        e.preventDefault(); 

        const tanggal = document.getElementById('tanggal-latihan').value;

        // Validasi: Cek apakah tanggal sudah diisi
        if (!tanggal) {
            alert('Silakan pilih tanggal latihan terlebih dahulu!');
            return;
        }

        // Mengambil semua data dari form
        const formData = new FormData(formAbsensi);
        
        // Menambahkan tanggal ke dalam data yang akan dikirim
        formData.append('tanggal_latihan', tanggal);

        try {
            // Kirim data ke API simpan_absensi.php
            const response = await fetch('../api/simpan_absensi.php', {
                method: 'POST',
                body: formData
            });
            
            const hasil = await response.json();

            if (hasil.success) {
                alert(hasil.message); // Tampilkan pesan sukses
                // Opsional: reset form atau beri tanda visual
            } else {
                alert('Terjadi kesalahan: ' + hasil.message); // Tampilkan pesan error
            }

        } catch (error) {
            console.error('Error menyimpan absensi:', error);
            alert('Gagal menyimpan absensi. Cek konsol.');
        }
    });
});