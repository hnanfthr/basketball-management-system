<?php

$host = "localhost";      
$username = "root";       
$password = "";           
$database = "db_basket48"; 

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error: Gagal terhubung ke server database.']);
    exit;
}

header('Content-Type: application/json');
?>