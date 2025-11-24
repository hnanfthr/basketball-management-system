<?php
// Password yang kita inginkan untuk semua anggota
$password_polos = 'anggota123';

// Hasilkan hash-nya
$hash_yang_benar = password_hash($password_polos, PASSWORD_DEFAULT);

// Tampilkan hash itu di layar
echo "Ini adalah HASH yang 100% benar untuk server kamu:";
echo "<br><br>";
echo "<b>" . $hash_yang_benar . "</b>";
?>