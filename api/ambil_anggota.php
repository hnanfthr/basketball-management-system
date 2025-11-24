<?php
// Memasukkan file koneksi database
include 'koneksi.php';

// Menyiapkan array untuk menampung data anggota
$anggota_list = [];

// (FIX) Query diubah: Urutkan berdasarkan KELAS dulu, baru NAMA
$sql = "SELECT id_anggota, nama_lengkap, kelas 
        FROM anggota 
        ORDER BY kelas ASC, nama_lengkap ASC";

// Menjalankan query
$result = $koneksi->query($sql);

// Cek apakah query berhasil dan ada datanya
if ($result && $result->num_rows > 0) {
    // Loop melalui setiap baris data
    while($row = $result->fetch_assoc()) {
        // Masukkan data ke array $anggota_list
        $anggota_list[] = $row;
    }
    // Mengirim data dalam format JSON
    echo json_encode($anggota_list);
} else {
    // Jika tidak ada data, kirim array kosong
    echo json_encode([]);
}

// Menutup koneksi database
$koneksi->close();
?>