document.addEventListener('DOMContentLoaded', function() {

    const tbody = document.getElementById('tabel-jadwal-body');
    const modal = document.getElementById('modal-jadwal');
    const tombolTutupModal = document.getElementById('tombol-tutup-modal');
    const tombolBatal = document.getElementById('tombol-batal');
    const tombolTambah = document.getElementById('tombol-tambah-jadwal');
    const formJadwal = document.getElementById('form-jadwal');
    const modalTitle = document.getElementById('modal-title');
    
    // Input Form
    const inputIdJadwal = document.getElementById('id_jadwal');
    const inputAction = document.getElementById('action');
    const inputTanggal = document.getElementById('tanggal');
    const inputWaktu = document.getElementById('waktu');
    const inputWaktuSelesai = document.getElementById('waktu_selesai'); // Pastikan ID ini ada di HTML
    const inputLokasi = document.getElementById('lokasi');
    const inputKeterangan = document.getElementById('keterangan');

    function bukaModal() { modal.classList.remove('hidden'); }
    function tutupModal() { modal.classList.add('hidden'); }
    
    function formatTanggal(tglSQL) {
        const tgl = new Date(tglSQL + 'T00:00:00');
        return tgl.toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });
    }

    // Fungsi Memuat Data Jadwal
    async function muatJadwal() {
        try {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Memuat data...</td></tr>';
            
            const response = await fetch('../api/crud_jadwal.php?action=baca');
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            tbody.innerHTML = ''; 
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Tidak ada jadwal aktif.</td></tr>';
                return;
            }

            result.data.forEach((jadwal) => {
                const tr = document.createElement('tr');
                
                // --- LOGIKA TAMPILAN JAM ---
                const jamMulai = jadwal.waktu.substring(0, 5);
                // Cek apakah waktu_selesai ada/valid. Jika '00:00:00' atau null, anggap belum diatur
                let jamSelesai = '??';
                if (jadwal.waktu_selesai && jadwal.waktu_selesai !== '00:00:00') {
                    jamSelesai = jadwal.waktu_selesai.substring(0, 5);
                }

                tr.innerHTML = `
                    <td>${formatTanggal(jadwal.tanggal)}</td>
                    <td><span style="font-weight:bold; color:#007bff;">${jamMulai}</span> s/d <span style="font-weight:bold; color:#007bff;">${jamSelesai}</span></td>
                    <td>${jadwal.lokasi}</td>
                    <td>${jadwal.keterangan || '-'}</td>
                    <td class="kolom-aksi">
                        <button class="btn-edit" 
                            data-id="${jadwal.id_jadwal}" 
                            data-tanggal="${jadwal.tanggal}" 
                            data-waktu="${jadwal.waktu}" 
                            data-waktu-selesai="${jadwal.waktu_selesai}"
                            data-lokasi="${jadwal.lokasi}" 
                            data-keterangan="${jadwal.keterangan || ''}">Edit</button>
                        <button class="btn-hapus" data-id="${jadwal.id_jadwal}">Hapus</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align: center;">Gagal memuat data: ${error.message}</td></tr>`;
        }
    }

    tombolTambah.addEventListener('click', function() {
        formJadwal.reset(); 
        modalTitle.textContent = 'Tambah Jadwal Baru';
        inputAction.value = 'tambah';
        inputIdJadwal.value = '';
        bukaModal();
    });

    tombolTutupModal.addEventListener('click', tutupModal);
    tombolBatal.addEventListener('click', tutupModal);

    tbody.addEventListener('click', async function(e) {
        
        if (e.target.classList.contains('btn-edit')) {
            const data = e.target.dataset;
            
            modalTitle.textContent = 'Edit Data Jadwal';
            inputAction.value = 'edit';
            inputIdJadwal.value = data.id;
            inputTanggal.value = data.tanggal;
            inputWaktu.value = data.waktu;
            // Isi input waktu selesai dengan data dari tombol edit
            inputWaktuSelesai.value = data.waktuSelesai; 
            inputLokasi.value = data.lokasi;
            inputKeterangan.value = data.keterangan;
            
            bukaModal();
        }

        if (e.target.classList.contains('btn-hapus')) {
            const id = e.target.dataset.id;

            if (confirm(`Yakin ingin menghapus jadwal ini?`)) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'hapus');
                    formData.append('id_jadwal', id);

                    const response = await fetch('../api/crud_jadwal.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (result.success) {
                        alert(result.message);
                        muatJadwal(); 
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    alert(`Gagal menghapus: ${error.message}`);
                }
            }
        }
    });

    formJadwal.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(formJadwal);
        
        try {
            const response = await fetch('../api/crud_jadwal.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                alert(result.message);
                tutupModal();
                muatJadwal(); 
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            alert(`Gagal menyimpan: ${error.message}`);
        }
    });

    muatJadwal();

});