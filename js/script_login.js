document.addEventListener('DOMContentLoaded', function() {
    
    const loginForm = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');
    const inputUsername = document.getElementById('username');
    const roleAnggota = document.getElementById('role-anggota');
    const roleAdmin = document.getElementById('role-admin');
    
    // Ganti placeholder berdasarkan role
    function updatePlaceholder() {
        if (roleAnggota.checked) {
            inputUsername.placeholder = "Masukkan Email kamu";
            inputUsername.previousElementSibling.textContent = "Email";
        } else {
            inputUsername.placeholder = "Masukkan Username Admin";
            inputUsername.previousElementSibling.textContent = "Username";
        }
    }
    
    // Panggil saat load dan saat ganti role
    updatePlaceholder();
    roleAnggota.addEventListener('change', updatePlaceholder);
    roleAdmin.addEventListener('change', updatePlaceholder);


    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault(); 
        errorMessage.textContent = ''; 

        // Ambil data dari form, TERMASUK ROLE
        const formData = new FormData(loginForm);
        
        try {
            // Kirim data ke API cek_login.php
            const response = await fetch('../api/cek_login.php', {
                method: 'POST',
                body: formData
            });

            const hasil = await response.json();

            if (hasil.success) {
                // Jika login berhasil
                alert(hasil.message);
                
                // Redirect ke URL yang diberikan oleh API
                window.location.href = hasil.redirect_url; 

            } else {
                // Jika login gagal
                errorMessage.textContent = hasil.message;
            }

        } catch (error) {
            console.error('Error saat login:', error);
            errorMessage.textContent = 'Terjadi kesalahan jaringan. Coba lagi.';
        }
    });

});