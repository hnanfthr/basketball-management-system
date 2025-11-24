document.addEventListener('DOMContentLoaded', function() {
    
    // --- Selektor untuk Modal Pendaftaran ---
    const tombolDaftar = document.getElementById('tombol-daftar-sekarang');
    const modalPendaftaran = document.getElementById('modal-pendaftaran');
    const tombolTutupModal = document.getElementById('tutup-modal-daftar');
    const tombolBatalDaftar = document.getElementById('tombol-batal-daftar');
    const formPendaftaran = document.getElementById('form-pendaftaran');
    const errorDaftar = document.getElementById('error-daftar');
    
    // --- Selektor untuk Modal Profil ---
    const rosterContainer = document.getElementById('roster-container');
    const modalProfil = document.getElementById('modal-profil-anggota');
    const modalProfilContent = document.getElementById('modal-profil-content');
    const tombolTutupProfil = document.getElementById('tombol-tutup-profil');
    const defaultFoto = 'assets/uploads/profil/default_avatar.png'; 

    // --- (BARU) Selektor untuk Modal Lightbox Galeri ---
    const modalLightbox = document.getElementById('modal-lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const lightboxDesc = document.getElementById('lightbox-desc');
    const tombolTutupLightbox = document.getElementById('tutup-lightbox');
    // Ambil semua item galeri
    const galeriItems = document.querySelectorAll('.galeri-item-clickable');


    // --- Fungsi Buka/Tutup Modal Pendaftaran ---
    function bukaModalDaftar() { modalPendaftaran.classList.remove('hidden'); }
    function tutupModalDaftar() { 
        modalPendaftaran.classList.add('hidden'); 
        errorDaftar.textContent = ''; 
        formPendaftaran.reset();
    }

    // --- Fungsi Buka/Tutup Modal Profil ---
    function bukaModalProfil() { modalProfil.classList.remove('hidden'); }
    function tutupModalProfil() { modalProfil.classList.add('hidden'); }

    // --- (BARU) Fungsi Buka/Tutup Modal Lightbox ---
    function tutupLightbox() { modalLightbox.classList.add('hidden'); }

    // --- Event Listeners Pendaftaran ---
    if (tombolDaftar) {
        tombolDaftar.addEventListener('click', bukaModalDaftar);
    }
    tombolTutupModal.addEventListener('click', tutupModalDaftar);
    tombolBatalDaftar.addEventListener('click', tutupModalDaftar);

    // --- Event Listener Modal Profil ---
    tombolTutupProfil.addEventListener('click', tutupModalProfil);

    // --- Event Listener Lightbox ---
    tombolTutupLightbox.addEventListener('click', tutupLightbox);
    
    // Tutup lightbox jika klik di area gelap (luar gambar)
    modalLightbox.addEventListener('click', function(e) {
        if (e.target === modalLightbox) {
            tutupLightbox();
        }
    });

    // Tambahkan listener klik ke SETIAP item galeri
    galeriItems.forEach(item => {
        item.addEventListener('click', function() {
            // Ambil data dari elemen yang diklik
            const imgElement = this.querySelector('img');
            const titleElement = this.querySelector('.galeri-judul');
            const descElement = this.querySelector('.galeri-keterangan-hidden');

            // Set konten lightbox
            lightboxImg.src = imgElement.src;
            lightboxCaption.textContent = titleElement.textContent;
            lightboxDesc.textContent = descElement ? descElement.textContent : '';

            // Tampilkan lightbox
            modalLightbox.classList.remove('hidden');
        });
    });


    // --- Event Listener untuk Roster Container (Klik Nama) ---
    if(rosterContainer) {
        rosterContainer.addEventListener('click', async function(e) {
            const item = e.target.closest('.anggota-item-clickable');
            if (!item) return; 

            const idAnggota = item.dataset.id;
            
            modalProfilContent.innerHTML = '<div style="padding: 40px; text-align: center;">Memuat profil...</div>';
            bukaModalProfil();

            try {
                const response = await fetch(`api/get_public_profile.php?id=${idAnggota}`);
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message);
                }

                const profil = result.data;
                const fotoUrl = profil.foto_profil ? `assets/uploads/profil/${profil.foto_profil}` : defaultFoto;
                
                modalProfilContent.innerHTML = `
                    <div style="background-color: var(--dark-blue); padding: 30px 20px 20px 20px; text-align: center; border-radius: 12px 12px 0 0;">
                        <img src="${fotoUrl}" alt="Foto Profil" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; margin-bottom: 10px;">
                        <h3 style="color: #fff; margin: 0; font-size: 1.4em;">${profil.nama_lengkap}</h3>
                        <p style="color: #eee; margin: 5px 0 0 0; font-size: 1.1em;">${profil.kelas || 'N/A'}</p>
                    </div>
                    <div style="padding: 25px; text-align: center; background-color: #fff;">
                        <strong style="font-size: 1.2em; color: var(--accent-orange); display: block;">
                            ${profil.posisi_main || 'Posisi Belum Diatur'}
                        </strong>
                    </div>
                `;

            } catch (error) {
                console.error('Gagal fetch profil:', error);
                modalProfilContent.innerHTML = `<div style="padding: 40px; text-align: center; color: var(--danger-red);">${error.message}</div>`;
            }
        });
    }

    // --- Submit Form Pendaftaran ---
    formPendaftaran.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorDaftar.textContent = ''; 
        
        const nama = document.getElementById('nama_lengkap_daftar').value;
        const kelas = document.getElementById('kelas_daftar').value;
        const email = document.getElementById('email_daftar').value;
        const password = document.getElementById('password_daftar').value;
        const konfirmasiPassword = document.getElementById('konfirmasi_password_daftar').value;

        if (password.length < 6) {
             errorDaftar.textContent = 'Password minimal harus 6 karakter.';
             return;
        }
        if (password !== konfirmasiPassword) {
            errorDaftar.textContent = 'Password dan Konfirmasi Password tidak cocok!';
            return;
        }

        try {
            const response = await fetch('api/kirim_pendaftaran.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nama_lengkap: nama,
                    kelas: kelas,
                    email: email,
                    password: password 
                })
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                tutupModalDaftar();
            } else {
                errorDaftar.textContent = 'Gagal: ' + result.message;
            }

        } catch (error) {
            console.error('Error:', error);
            errorDaftar.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
        }
    });

});