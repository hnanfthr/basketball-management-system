document.addEventListener('DOMContentLoaded', function() {

    const formUpload = document.getElementById('form-upload-galeri');
    const errorUpload = document.getElementById('error-upload');
    const galleryContainer = document.getElementById('gallery-admin-container');
    const inputFoto = document.getElementById('file_foto');

    // Elemen Crop
    const modalCrop = document.getElementById('modal-crop-galeri');
    const imageToCrop = document.getElementById('image-to-crop');
    const btnBatalCrop = document.getElementById('btn-batal-crop');
    const btnPotongCrop = document.getElementById('btn-potong-crop');
    let cropper = null;
    let croppedBlob = null; // Menyimpan file hasil crop

    // --- Fungsi Format Tanggal ---
    function formatTanggal(tglSQL) {
        const tgl = new Date(tglSQL);
        return tgl.toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });
    }

    // --- LOGIKA CROP ---
    
    inputFoto.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if(cropper) { cropper.destroy(); cropper = null; }
            
            const url = URL.createObjectURL(file);
            imageToCrop.src = url;
            modalCrop.classList.remove('hidden');

            cropper = new Cropper(imageToCrop, {
                aspectRatio: 4 / 3, // Rasio Galeri
                viewMode: 1,
                autoCropArea: 1,
            });
            
            // Reset value input agar bisa pilih file yg sama jika batal
            // inputFoto.value = ''; // Jangan di-reset dulu, nanti error validation
        }
    });

    btnBatalCrop.addEventListener('click', function() {
        modalCrop.classList.add('hidden');
        inputFoto.value = ''; // Reset input karena batal
        croppedBlob = null;
    });

    btnPotongCrop.addEventListener('click', function() {
        if(cropper) {
            cropper.getCroppedCanvas({
                width: 800, // Resolusi cukup tinggi untuk galeri
                height: 600
            }).toBlob((blob) => {
                croppedBlob = blob;
                modalCrop.classList.add('hidden');
                // Kita tidak perlu ganti input file, kita pakai variable 'croppedBlob' saat submit
            }, 'image/jpeg', 0.9);
        }
    });


    // --- Fungsi Memuat Galeri ---
    async function muatGaleri() {
        try {
            galleryContainer.innerHTML = '<p>Memuat galeri...</p>';
            
            const response = await fetch('../api/crud_galeri.php?action=baca');
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            galleryContainer.innerHTML = ''; 
            if (result.data.length === 0) {
                galleryContainer.innerHTML = '<p>Belum ada foto di galeri.</p>';
                return;
            }

            result.data.forEach((foto) => {
                const item = document.createElement('div');
                item.className = 'gallery-item-admin';
                item.innerHTML = `
                    <img src="../assets/uploads/galeri/${foto.nama_file}" alt="${foto.judul}">
                    <div class="gallery-info">
                        <strong>${foto.judul}</strong>
                        <small>Di-upload: ${formatTanggal(foto.tanggal_upload)}</small>
                        <p>${foto.keterangan || ''}</p>
                    </div>
                    <button class="btn-hapus btn-hapus-foto" data-id="${foto.id_foto}" data-file="${foto.nama_file}">Hapus</button>
                `;
                galleryContainer.appendChild(item);
            });

        } catch (error) {
            galleryContainer.innerHTML = `<p style="color: red;">Gagal memuat galeri: ${error.message}</p>`;
        }
    }

    // --- Event Listener Upload Form ---
    formUpload.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorUpload.textContent = '';

        // Cek apakah sudah di-crop
        if (!croppedBlob) {
            alert("Silakan pilih dan potong foto terlebih dahulu.");
            return;
        }

        const formData = new FormData(formUpload);
        formData.append('action', 'upload');
        
        // Ganti file asli dengan file hasil crop
        formData.set('foto', croppedBlob, 'galeri_cropped.jpg');

        try {
            const response = await fetch('../api/crud_galeri.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                formUpload.reset();
                croppedBlob = null;
                muatGaleri();
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            errorUpload.textContent = `Gagal Upload: ${error.message}`;
        }
    });

    // --- Event Listener Hapus Foto ---
    galleryContainer.addEventListener('click', async function(e) {
        if (e.target.classList.contains('btn-hapus-foto')) {
            const id = e.target.dataset.id;
            const namaFile = e.target.dataset.file;
            
            if (confirm(`Yakin ingin menghapus foto "${namaFile}"?`)) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'hapus');
                    formData.append('id_foto', id);
                    formData.append('nama_file', namaFile);

                    const response = await fetch('../api/crud_galeri.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (result.success) {
                        alert(result.message);
                        muatGaleri();
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    alert(`Gagal menghapus: ${error.message}`);
                }
            }
        }
    });

    muatGaleri();
});