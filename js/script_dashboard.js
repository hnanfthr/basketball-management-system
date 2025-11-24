document.addEventListener('DOMContentLoaded', function() {

    // Variabel global
    let dataProfilSaatIni = null;
    const defaultFoto = '../assets/uploads/profil/default_avatar.png';

    // Elemen Profil
    const profilFoto = document.getElementById('profil-foto');
    const profilNama = document.getElementById('profil-nama');
    const profilKelas = document.getElementById('profil-kelas');
    const profilPosisi = document.getElementById('profil-posisi');

    // (BARU) Elemen Denda
    const dendaContainer = document.getElementById('denda-container');
    const dendaList = document.getElementById('denda-list');

    // Elemen Lain
    const tbodyKas = document.getElementById('tabel-kas-anggota-body');
    const galeriContainer = document.getElementById('gallery-anggota-container');
    const jadwalContainer = document.getElementById('jadwal-list-container');
    const tbodyAbsensi = document.getElementById('tabel-absensi-anggota');
    const totalHadir = document.getElementById('total-hadir');
    const totalIzin = document.getElementById('total-izin');
    const totalSakit = document.getElementById('total-sakit');
    const totalAlfa = document.getElementById('total-alfa');
    const formGantiPassword = document.getElementById('form-ganti-password');
    const errorPassword = document.getElementById('error-password');

    // Elemen Modal Edit & Crop
    const tombolEditProfil = document.getElementById('tombol-edit-profil');
    const modalEditProfil = document.getElementById('modal-edit-profil');
    const tombolTutupEdit = document.getElementById('tombol-tutup-edit');
    const tombolBatalEdit = document.getElementById('tombol-batal-edit');
    const formEditProfil = document.getElementById('form-edit-profil');
    const errorEditProfil = document.getElementById('error-edit-profil');
    const previewFotoProfil = document.getElementById('preview-foto-profil');
    const inputFotoProfil = document.getElementById('input_foto_profil');
    const selectPosisiMain = document.getElementById('select_posisi_main');
    const modalCrop = document.getElementById('modal-crop-tool');
    const imageToCrop = document.getElementById('image-to-crop');
    const btnBatalCrop = document.getElementById('btn-batal-crop');
    const btnPotongCrop = document.getElementById('btn-potong-crop');
    let cropper = null;

    // --- Fungsi Format Tanggal ---
    function formatTanggal(tglSQL, waktuSQL = null) {
        const dateTimeStr = waktuSQL ? `${tglSQL}T${waktuSQL}` : `${tglSQL}T00:00:00`;
        const tgl = new Date(dateTimeStr);
        
        if (waktuSQL) {
            return tgl.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', 
                hour: '2-digit', minute: '2-digit'
            }) + ' WIB';
        } else {
            return tgl.toLocaleDateString('id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
        }
    }

    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    // --- FUNGSI UTAMA ---
    async function muatDataDashboard() {
        try {
            const response = await fetch('../api/ambil_data_anggota.php');
            const result = await response.json();

            if (!result.success) throw new Error(result.message);
            
            const profil = result.data_profil;
            if (profil) {
                dataProfilSaatIni = profil; 
                profilNama.textContent = profil.nama_lengkap;
                profilKelas.textContent = profil.kelas || 'Kelas belum diatur';
                profilPosisi.textContent = `Posisi: ${profil.posisi_main || 'Belum diatur'}`;
                
                if (profil.foto_profil) {
                    profilFoto.src = `../assets/uploads/profil/${profil.foto_profil}`;
                } else {
                    profilFoto.src = defaultFoto;
                }
            }

            // (BARU) Tampilkan Denda
            dendaList.innerHTML = '';
            if (result.data_denda && result.data_denda.length > 0) {
                let adaDendaBelumLunas = false;
                result.data_denda.forEach(denda => {
                    if(denda.status === 'Belum Lunas') {
                        adaDendaBelumLunas = true;
                        const div = document.createElement('div');
                        div.className = 'denda-item';
                        div.innerHTML = `
                            <span><strong>${denda.keterangan}</strong></span>
                            <span style="color:red; font-weight:bold;">${formatRupiah(denda.jumlah)}</span>
                        `;
                        dendaList.appendChild(div);
                    }
                });

                if(adaDendaBelumLunas) {
                    dendaContainer.style.display = 'block';
                } else {
                    dendaContainer.style.display = 'none';
                }
            } else {
                dendaContainer.style.display = 'none';
            }
            
            tbodyKas.innerHTML = '';
            if (result.data_kas.length === 0) {
                tbodyKas.innerHTML = '<tr><td colspan="3" style="text-align: center;">Belum ada riwayat pembayaran kas.</td></tr>';
            } else {
                result.data_kas.forEach(kas => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${formatTanggal(kas.tanggal)}</td>
                        <td>${kas.lokasi}</td>
                        <td><span class="status-label status-${kas.status.toLowerCase()}">${kas.status}</span></td>
                    `;
                    tbodyKas.appendChild(tr);
                });
            }

            galeriContainer.innerHTML = ''; 
            if (result.data_galeri.length === 0) {
                galeriContainer.innerHTML = '<p>Belum ada foto di galeri.</p>';
            } else {
                result.data_galeri.forEach(foto => {
                    const item = document.createElement('div');
                    item.className = 'gallery-item';
                    item.innerHTML = `
                        <img src="../assets/uploads/galeri/${foto.nama_file}" alt="${foto.judul}">
                        <div class="gallery-caption">${foto.judul}</div>
                    `;
                    galeriContainer.appendChild(item);
                });
            }

            jadwalContainer.innerHTML = ''; 
            if (result.data_jadwal.length === 0) {
                jadwalContainer.innerHTML = '<p>Belum ada jadwal latihan yang akan datang.</p>';
            } else {
                const ul = document.createElement('ul');
                ul.className = 'jadwal-list';
                result.data_jadwal.forEach(jadwal => {
                    const li = document.createElement('li');
                    li.className = 'jadwal-item';
                    li.innerHTML = `
                        <div class="jadwal-waktu">${formatTanggal(jadwal.tanggal, jadwal.waktu)}</div>
                        <div class="jadwal-lokasi">${jadwal.lokasi}</div>
                        <div class="jadwal-keterangan">${jadwal.keterangan || 'Latihan rutin'}</div>
                    `;
                    ul.appendChild(li);
                });
                jadwalContainer.appendChild(ul);
            }

            tbodyAbsensi.innerHTML = ''; 
            if (result.data_list.length === 0) {
                tbodyAbsensi.innerHTML = '<tr><td colspan="3" style="text-align: center;">Kamu belum memiliki data absensi.</td></tr>';
            } else {
                result.data_list.forEach((row, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${formatTanggal(row.tanggal_latihan)}</td>
                        <td><span class="status-label status-${row.status_kehadiran.toLowerCase()}">${row.status_kehadiran}</span></td>
                    `;
                    tbodyAbsensi.appendChild(tr);
                });
            }

            const rekap = result.data_rekap;
            totalHadir.textContent = rekap.Hadir || 0;
            totalIzin.textContent = rekap.Izin || 0;
            totalSakit.textContent = rekap.Sakit || 0;
            totalAlfa.textContent = rekap.Alfa || 0;

        } catch (error) {
            const errorMsg = `<p style="color: red;">Gagal memuat data: ${error.message}</p>`;
            profilNama.textContent = 'Gagal memuat profil';
            tbodyKas.innerHTML = `<tr><td colspan="3" style="text-align: center; color: red;">Gagal memuat kas.</td></tr>`;
            galeriContainer.innerHTML = errorMsg;
            jadwalContainer.innerHTML = errorMsg;
            tbodyAbsensi.innerHTML = `<tr><td colspan="3" style="text-align: center; color: red;">Gagal memuat absensi.</td></tr>`;
        }
    }
    
    // --- GANTI PASSWORD ---
    if(formGantiPassword) {
        formGantiPassword.addEventListener('submit', async function(e) {
            e.preventDefault();
            errorPassword.textContent = '';
            const passwordLama = document.getElementById('password_lama').value;
            const passwordBaru = document.getElementById('password_baru').value;
            const konfirmasiPassword = document.getElementById('konfirmasi_password_baru').value;

            if (passwordBaru.length < 6) {
                errorPassword.textContent = 'Password baru minimal harus 6 karakter.';
                return;
            }
            if (passwordBaru !== konfirmasiPassword) {
                errorPassword.textContent = 'Password baru dan konfirmasi tidak cocok.';
                return;
            }
            try {
                const formData = new FormData();
                formData.append('password_lama', passwordLama);
                formData.append('password_baru', passwordBaru);
                const response = await fetch('../api/ganti_password.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    formGantiPassword.reset();
                } else {
                    errorPassword.textContent = 'Gagal: ' + result.message;
                }
            } catch (error) {
                errorPassword.textContent = 'Terjadi kesalahan jaringan.';
            }
        });
    }

    // --- EDIT PROFIL & CROP (LOGIKA SAMA SEPERTI SEBELUMNYA) ---
    function bukaModalEdit() {
        if (!dataProfilSaatIni) return;
        selectPosisiMain.value = dataProfilSaatIni.posisi_main || "";
        if (dataProfilSaatIni.foto_profil) {
            previewFotoProfil.src = `../assets/uploads/profil/${dataProfilSaatIni.foto_profil}`;
        } else {
            previewFotoProfil.src = defaultFoto;
        }
        inputFotoProfil.value = null; 
        errorEditProfil.textContent = '';
        modalEditProfil.classList.remove('hidden');
    }
    function tutupModalEdit() { modalEditProfil.classList.add('hidden'); }

    tombolEditProfil.addEventListener('click', bukaModalEdit);
    tombolTutupEdit.addEventListener('click', tutupModalEdit);
    tombolBatalEdit.addEventListener('click', tutupModalEdit);

    inputFotoProfil.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if(cropper) { cropper.destroy(); cropper = null; }
            const url = URL.createObjectURL(file);
            imageToCrop.src = url;
            modalCrop.classList.remove('hidden');
            cropper = new Cropper(imageToCrop, { aspectRatio: 1, viewMode: 1, autoCropArea: 1 });
        }
    });

    btnBatalCrop.addEventListener('click', function() {
        modalCrop.classList.add('hidden');
        inputFotoProfil.value = ''; 
        if (dataProfilSaatIni.foto_profil) {
            previewFotoProfil.src = `../assets/uploads/profil/${dataProfilSaatIni.foto_profil}`;
        } else {
            previewFotoProfil.src = defaultFoto;
        }
    });

    btnPotongCrop.addEventListener('click', function() {
        if(cropper) {
            cropper.getCroppedCanvas({ width: 300, height: 300 }).toBlob((blob) => {
                const croppedFile = new File([blob], "profil_cropped.jpg", { type: "image/jpeg" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                inputFotoProfil.files = dataTransfer.files;
                const url = URL.createObjectURL(croppedFile);
                previewFotoProfil.src = url;
                modalCrop.classList.add('hidden');
            }, 'image/jpeg', 0.9);
        }
    });

    formEditProfil.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorEditProfil.textContent = 'Menyimpan...';
        const formData = new FormData(formEditProfil);
        try {
            const response = await fetch('../api/update_profil.php', { method: 'POST', body: formData });
            const result = await response.json();
            if (result.success) {
                alert(result.message);
                tutupModalEdit();
                const posisiBaru = result.new_posisi;
                profilPosisi.textContent = `Posisi: ${posisiBaru || 'Belum diatur'}`;
                dataProfilSaatIni.posisi_main = posisiBaru;
                if (result.new_foto_nama) {
                    const newFotoUrl = `../assets/uploads/profil/${result.new_foto_nama}`;
                    profilFoto.src = newFotoUrl;
                    dataProfilSaatIni.foto_profil = result.new_foto_nama;
                }
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            errorEditProfil.textContent = 'Gagal: ' + error.message;
        }
    });

    muatDataDashboard();
});