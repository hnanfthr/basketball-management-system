document.addEventListener('DOMContentLoaded', function() {

    const formLaporan = document.getElementById('form-laporan');
    const tbody = document.getElementById('tabel-laporan-body');
    const tglMulai = document.getElementById('tanggal_mulai');
    const tglSelesai = document.getElementById('tanggal_selesai');

    // Set tanggal default (misal 1 bulan terakhir)
    const today = new Date();
    const oneMonthAgo = new Date(new Date().setMonth(today.getMonth() - 1));
    tglSelesai.valueAsDate = today;
    tglMulai.valueAsDate = oneMonthAgo;


    formLaporan.addEventListener('submit', async function(e) {
        e.preventDefault(); // Mencegah reload halaman

        const tanggalMulai = tglMulai.value;
        const tanggalSelesai = tglSelesai.value;

        // Validasi
        if (!tanggalMulai || !tanggalSelesai) {
            alert('Silakan isi kedua tanggal.');
            return;
        }
        if (tanggalMulai > tanggalSelesai) {
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
            return;
        }

        // Tampilkan loading
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">Memuat data laporan...</td></tr>';

        try {
            // Panggil API
            const response = await fetch(`../api/ambil_laporan.php?tanggal_mulai=${tanggalMulai}&tanggal_selesai=${tanggalSelesai}`);
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            // Kosongkan tabel
            tbody.innerHTML = '';

            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">Tidak ada data absensi di rentang tanggal ini.</td></tr>';
                return;
            }

            // Isi tabel dengan data
            result.data.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${row.nama_lengkap}</td>
                    <td>${row.kelas}</td>
                    <td>${row.Hadir}</td>
                    <td>${row.Izin}</td>
                    <td>${row.Sakit}</td>
                    <td>${row.Alfa}</td>
                    <td>${row.Total}</td>
                `;
                tbody.appendChild(tr);
            });

        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align: center;">Gagal memuat laporan: ${error.message}</td></tr>`;
            console.error('Error ambil laporan:', error);
        }
    });

});