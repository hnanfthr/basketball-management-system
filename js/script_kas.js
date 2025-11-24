document.addEventListener('DOMContentLoaded', function() {

    const tbody = document.getElementById('tabel-kas-sesi-body');
    const filterJadwal = document.getElementById('filter-jadwal');
    let idJadwalTerpilih = null;

    // --- Fungsi Format Tanggal Dropdown ---
    function formatJadwalDropdown(tgl, waktu, lokasi) {
        const d = new Date(tgl + 'T' + waktu);
        const tglStr = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        const waktuStr = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        return `${tglStr} (${waktuStr} WIB) - ${lokasi}`;
    }

    // --- 1. Memuat Dropdown Jadwal ---
    async function muatJadwalDropdown() {
        try {
            const response = await fetch('../api/crud_jadwal.php?action=baca_semua');
            const result = await response.json();
            if (!result.success) throw new Error(result.message);

            filterJadwal.innerHTML = '<option value="">--- Pilih Sesi Latihan ---</option>';
            if (result.data.length === 0) {
                 filterJadwal.innerHTML = '<option value="">Belum ada jadwal latihan dibuat.</option>';
                 return;
            }

            result.data.forEach(jadwal => {
                const option = document.createElement('option');
                option.value = jadwal.id_jadwal;
                option.textContent = formatJadwalDropdown(jadwal.tanggal, jadwal.waktu, jadwal.lokasi);
                filterJadwal.appendChild(option);
            });
        } catch (error) {
            filterJadwal.innerHTML = `<option value="">Gagal memuat jadwal: ${error.message}</option>`;
        }
    }

    // --- 2. Memuat Status Kas untuk Sesi Terpilih ---
    async function muatStatusKasSesi(id_jadwal) {
        if (!id_jadwal) {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Silakan pilih sesi latihan di atas.</td></tr>';
            return;
        }
        
        idJadwalTerpilih = id_jadwal; // Simpan ID jadwal
        tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Memuat data kas...</td></tr>';
        
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

        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="3" style="text-align: center;">Gagal memuat data kas: ${error.message}</td></tr>`;
        }
    }

    // --- 3. Event Listener saat ganti jadwal ---
    filterJadwal.addEventListener('change', function() {
        muatStatusKasSesi(this.value);
    });

    // --- 4. Event Listener saat Checkbox di-klik (Simpan Perubahan) ---
    tbody.addEventListener('change', async function(e) {
        if (e.target.classList.contains('kas-checkbox')) {
            const checkbox = e.target;
            if (!idJadwalTerpilih) {
                alert('Pilih jadwal terlebih dahulu.');
                checkbox.checked = !checkbox.checked; // Batalkan
                return;
            }

            const idAnggota = checkbox.dataset.idAnggota;
            const status = checkbox.checked ? 'Lunas' : 'Belum';

            checkbox.disabled = true; // Nonaktifkan sementara
            
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
                
                // Sukses, aktifkan kembali
                checkbox.disabled = false;
                
            } catch (error) {
                alert(`Gagal menyimpan: ${error.message}`);
                checkbox.checked = !checkbox.checked; // Kembalikan
                checkbox.disabled = false;
            }
        }
    });

    // --- Muat dropdown saat halaman dibuka ---
    muatJadwalDropdown();
});