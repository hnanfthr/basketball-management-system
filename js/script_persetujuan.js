document.addEventListener('DOMContentLoaded', function() {

    const tbody = document.getElementById('tabel-pending-body');

    // --- Fungsi Memuat Data Pendaftar ---
    async function muatPendaftar() {
        try {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">Memuat data...</td></tr>';
            
            // Panggil API (GET action=baca_pending)
            const response = await fetch('../api/crud_persetujuan.php?action=baca_pending');
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            tbody.innerHTML = ''; // Kosongkan tabel
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">Tidak ada pendaftar baru.</td></tr>';
                return;
            }

            // Isi tabel dengan data
            result.data.forEach((pendaftar, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${pendaftar.nama_lengkap}</td>
                    <td>${pendaftar.kelas}</td>
                    <td>${pendaftar.email}</td>
                    <td>${new Date(pendaftar.tanggal_daftar).toLocaleDateString('id-ID')}</td>
                    <td class="kolom-aksi" style="width: 200px;">
                        <button class="btn-approve" data-id="${pendaftar.id_pendaftaran}">Approve</button>
                        <button class="btn-reject" data-id="${pendaftar.id_pendaftaran}" data-nama="${pendaftar.nama_lengkap}">Reject</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align: center;">Gagal memuat data: ${error.message}</td></tr>`;
            console.error('Error muatPendaftar:', error);
        }
    }

    // --- Event Listener untuk Tombol Approve dan Reject ---
    tbody.addEventListener('click', async function(e) {
        
        const action = e.target.classList.contains('btn-approve') ? 'approve' : (e.target.classList.contains('btn-reject') ? 'reject' : null);
        if (!action) return; // Jika yang diklik bukan tombol

        const id = e.target.dataset.id;
        let konfirmasi = true;

        if (action === 'reject') {
            const nama = e.target.dataset.nama;
            konfirmasi = confirm(`Yakin ingin MENOLAK pendaftaran "${nama}"?`);
        }
        
        if (konfirmasi) {
            try {
                const formData = new FormData();
                formData.append('action', action);
                formData.append('id_pendaftaran', id);

                const response = await fetch('../api/crud_persetujuan.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    muatPendaftar(); // Muat ulang data tabel
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                alert(`Gagal: ${error.message}`);
            }
        }
    });

    // --- Muat data saat halaman pertama kali dibuka ---
    muatPendaftar();

});