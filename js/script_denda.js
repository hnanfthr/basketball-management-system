document.addEventListener('DOMContentLoaded', function() {

    const tbody = document.getElementById('tabel-denda-body');

    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    async function muatDenda() {
        try {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Memuat data...</td></tr>';
            
            const response = await fetch('../api/crud_denda.php?action=baca');
            const result = await response.json();

            if (!result.success) throw new Error(result.message);

            tbody.innerHTML = ''; 
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Tidak ada data denda.</td></tr>';
                return;
            }

            result.data.forEach((denda, index) => {
                const tr = document.createElement('tr');
                const isLunas = denda.status === 'Lunas';
                
                let tombolAksi = '';
                if (!isLunas) {
                    tombolAksi = `<button class="btn-approve btn-bayar" data-id="${denda.id_denda}" data-nama="${denda.nama_lengkap}">Tandai Lunas</button>`;
                } else {
                    tombolAksi = `<span style="color:green; font-weight:bold;">✅ Selesai</span>`;
                }
                // Tambah tombol hapus untuk koreksi
                tombolAksi += ` <button class="btn-hapus btn-hapus-denda" data-id="${denda.id_denda}" style="margin-left:5px;">Hapus</button>`;

                const statusBadge = isLunas 
                    ? `<span class="status-label status-lunas">Lunas</span>`
                    : `<span class="status-label status-belum">Belum Lunas</span>`;

                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${denda.nama_lengkap}</td>
                    <td>${denda.kelas}</td>
                    <td>${denda.keterangan}</td>
                    <td style="color:red; font-weight:bold;">${formatRupiah(denda.jumlah)}</td>
                    <td>${statusBadge}</td>
                    <td class="kolom-aksi" style="width: 200px;">${tombolAksi}</td>
                `;
                tbody.appendChild(tr);
            });

        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align: center;">Gagal: ${error.message}</td></tr>`;
        }
    }

    tbody.addEventListener('click', async function(e) {
        // Tombol Bayar
        if (e.target.classList.contains('btn-bayar')) {
            const id = e.target.dataset.id;
            const nama = e.target.dataset.nama;
            
            if (confirm(`Tandai denda atas nama "${nama}" sebagai LUNAS?`)) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'bayar');
                    formData.append('id_denda', id);

                    const response = await fetch('../api/crud_denda.php', { method: 'POST', body: formData });
                    const result = await response.json();

                    if (result.success) {
                        alert(result.message);
                        muatDenda();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    alert(`Gagal: ${error.message}`);
                }
            }
        }

        // Tombol Hapus
        if (e.target.classList.contains('btn-hapus-denda')) {
            const id = e.target.dataset.id;
            if (confirm(`Hapus data denda ini secara permanen?`)) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'hapus');
                    formData.append('id_denda', id);

                    const response = await fetch('../api/crud_denda.php', { method: 'POST', body: formData });
                    const result = await response.json();

                    if (result.success) {
                        alert(result.message);
                        muatDenda();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    alert(`Gagal: ${error.message}`);
                }
            }
        }
    });

    muatDenda();
});