document.addEventListener('DOMContentLoaded', function() {

    const formUpload = document.getElementById('form-upload-struktur');
    const errorUpload = document.getElementById('error-upload-struktur');
    const galleryContainer = document.getElementById('struktur-admin-container');
    const inputFoto = document.getElementById('file_foto_pengurus');

    // Elemen Crop
    const modalCrop = document.getElementById('modal-crop-struktur');
    const imageToCrop = document.getElementById('image-to-crop');
    const btnBatalCrop = document.getElementById('btn-batal-crop');
    const btnPotongCrop = document.getElementById('btn-potong-crop');
    let cropper = null;
    let croppedBlob = null;

    // --- LOGIKA CROP ---
    inputFoto.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if(cropper) { cropper.destroy(); cropper = null; }
            
            const url = URL.createObjectURL(file);
            imageToCrop.src = url;
            modalCrop.classList.remove('hidden');

            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1, // Rasio 1:1 untuk foto profil pengurus
                viewMode: 1,
                autoCropArea: 1,
            });
        }
    });

    btnBatalCrop.addEventListener('click', function() {
        modalCrop.classList.add('hidden');
        inputFoto.value = ''; 
        croppedBlob = null;
    });

    btnPotongCrop.addEventListener('click', function() {
        if(cropper) {
            cropper.getCroppedCanvas({
                width: 400, // Resolusi kotak
                height: 400
            }).toBlob((blob) => {
                croppedBlob = blob;
                modalCrop.classList.add('hidden');
            }, 'image/jpeg', 0.9);
        }
    });

    // --- Fungsi Memuat Struktur ---
    async function muatStruktur() {
        try {
            galleryContainer.innerHTML = '<p>Memuat struktur...</p>';
            
            const response = await fetch('../api/crud_struktur.php?action=baca');
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            galleryContainer.innerHTML = ''; 
            if (result.data.length === 0) {
                galleryContainer.innerHTML = '<p>Belum ada data pengurus.</p>';
                return;
            }

            result.data.forEach((p) => {
                const item = document.createElement('div');
                item.className = 'gallery-item-admin'; 
                item.innerHTML = `
                    <img src="../assets/uploads/struktur/${p.foto}" alt="${p.nama}">
                    <div class="gallery-info">
                        <strong>${p.nama}</strong>
                        <small>${p.jabatan}</small>
                        <p>Urutan: ${p.urutan}</p>
                    </div>
                    <button class="btn-hapus btn-hapus-foto" data-id="${p.id_pengurus}" data-file="${p.foto}">Hapus</button>
                `;
                galleryContainer.appendChild(item);
            });

        } catch (error) {
            galleryContainer.innerHTML = `<p style="color: red;">Gagal memuat struktur: ${error.message}</p>`;
        }
    }

    // --- Event Listener Upload Form ---
    formUpload.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorUpload.textContent = '';

        if (!croppedBlob) {
            alert("Silakan pilih dan potong foto terlebih dahulu.");
            return;
        }

        const formData = new FormData(formUpload);
        formData.append('action', 'upload');
        formData.set('foto', croppedBlob, 'struktur_cropped.jpg');

        try {
            const response = await fetch('../api/crud_struktur.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                formUpload.reset();
                croppedBlob = null;
                muatStruktur(); 
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            errorUpload.textContent = `Gagal Upload: ${error.message}`;
        }
    });

    // --- Event Listener Hapus Pengurus ---
    galleryContainer.addEventListener('click', async function(e) {
        if (e.target.classList.contains('btn-hapus-foto')) {
            const id = e.target.dataset.id;
            const namaFile = e.target.dataset.file;
            
            if (confirm(`Yakin ingin menghapus pengurus ini?`)) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'hapus');
                    formData.append('id_pengurus', id);
                    formData.append('nama_file', namaFile);

                    const response = await fetch('../api/crud_struktur.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (result.success) {
                        alert(result.message);
                        muatStruktur(); 
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    alert(`Gagal menghapus: ${error.message}`);
                }
            }
        }
    });

    muatStruktur();
});