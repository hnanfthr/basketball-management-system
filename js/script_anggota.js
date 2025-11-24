document.addEventListener('DOMContentLoaded', function() {

    // --- Selektor Elemen ---
    const tbody = document.getElementById('tabel-anggota-body');
    const modal = document.getElementById('modal-anggota');
    const tombolTutupModal = document.getElementById('tombol-tutup-modal');
    const tombolBatal = document.getElementById('tombol-batal');
    const formAnggota = document.getElementById('form-anggota');
    const modalTitle = document.getElementById('modal-title');
    
    // Input Form
    const inputIdAnggota = document.getElementById('id_anggota');
    const inputAction = document.getElementById('action');
    const inputNama = document.getElementById('nama_lengkap');
    const inputKelas = document.getElementById('kelas');
    const inputEmail = document.getElementById('email');

    // --- Fungsi Buka/Tutup Modal ---
    function bukaModal() { modal.classList.remove('hidden'); }
    function tutupModal() { modal.classList.add('hidden'); }

    // --- Fungsi Memuat Data Anggota ---
    async function muatAnggota() {
        try {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Memuat data...</td></tr>';
            
            // (FIX) Panggil API (action=baca)
            const response = await fetch('../api/crud_anggota.php?action=baca');
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            tbody.innerHTML = ''; // Kosongkan tabel
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">Belum ada data anggota.</td></tr>';
                return;
            }

            // Isi tabel dengan data
            result.data.forEach((anggota, index) => {
                const tr = document.createElement('tr');
                
                // (MODIFIKASI) Tambahkan tombol Reset Pass
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${anggota.nama_lengkap}</td>
                    <td>${anggota.kelas || '-'}</td>
                    <td>${anggota.email || 'N/A'}</td>
                    <td class="kolom-aksi" style="width: 250px;">
                        <button class="btn-edit" 
                            data-id="${anggota.id_anggota}" 
                            data-nama="${anggota.nama_lengkap}" 
                            data-kelas="${anggota.kelas || ''}" 
                            data-email="${anggota.email || ''}">Edit</button>
                        <button class="btn-reset btn-reset-pass"
                            data-id="${anggota.id_anggota}"
                            data-nama="${anggota.nama_lengkap}">Reset Pass</button>
                        <button class="btn-hapus" 
                            data-id="${anggota.id_anggota}" 
                            data-nama="${anggota.nama_lengkap}">Hapus</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align: center;">Gagal memuat data: ${error.message}</td></tr>`;
        }
    }

    // --- Tombol "Batal" dan "Tutup" ---
    tombolTutupModal.addEventListener('click', tutupModal);
    tombolBatal.addEventListener('click', tutupModal);

    // --- Event Listener untuk Tombol Edit dan Hapus (Event Delegation) ---
    tbody.addEventListener('click', async function(e) {
        
        // --- Tombol EDIT ---
        if (e.target.classList.contains('btn-edit')) {
            const data = e.target.dataset;
            
            // (FIX) Isi semua field di modal
            modalTitle.textContent = 'Edit Data Anggota';
            inputAction.value = 'edit';
            inputIdAnggota.value = data.id;
            inputNama.value = data.nama;
            inputKelas.value = data.kelas;
            inputEmail.value = data.email || 'N/A';
            
            bukaModal(); // Buka modalnya
        }

        // --- (BARU) Tombol RESET PASSWORD ---
        else if (e.target.classList.contains('btn-reset-pass')) {
            const id = e.target.dataset.id;
            const nama = e.target.dataset.nama;
            
            if (confirm(`Yakin ingin me-reset password untuk "${nama}"?\nPassword akan diubah ke "anggota123".`)) {
                try {
                    const formData = new FormData();
                    formData.append('id_anggota', id);
                    
                    const response = await fetch('../api/admin_reset_password.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        alert(result.message);
                        // Tidak perlu muat ulang tabel, karena datanya tidak berubah
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    alert(`Gagal me-reset password: ${error.message}`);
                }
            }
        }

        // --- Tombol HAPUS ---
        else if (e.target.classList.contains('btn-hapus')) {
            const id = e.target.dataset.id;
            const nama = e.target.dataset.nama;

            if (confirm(`Yakin ingin menghapus anggota "${nama}"?\n\Tindakan ini akan menghapus SEMUA data absensi, kas, dll terkait anggota ini.`)) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'hapus');
                    formData.append('id_anggota', id);
                    const response = await fetch('../api/crud_anggota.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(result.message);
                        muatAnggota();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    alert(`Gagal menghapus: ${error.message}`);
                }
            }
        }
    });

    // --- Event Listener Submit Form (HANYA UNTUK EDIT) ---
    formAnggota.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(formAnggota);
        try {
            const response = await fetch('../api/crud_anggota.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                alert(result.message);
                tutupModal();
                muatAnggota();
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            alert(`Gagal menyimpan: ${error.message}`);
        }
    });

    // --- Muat data saat halaman pertama kali dibuka ---
    muatAnggota();
});