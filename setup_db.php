<?php
$host     = "127.0.0.1";
$username = "root";
$password = "aziz4212";

// 1. Koneksi tanpa database dulu
$conn = mysqli_connect($host, $username, $password);
if(!$conn) die("Koneksi gagal: " . mysqli_connect_error());

// 2. Buat database jika belum ada
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS db_iot_socket");

// 3. Pilih database tersebut
mysqli_select_db($conn, "db_iot_socket");

// 4. Buat tabel kontrol_relay
$sql = "CREATE TABLE IF NOT EXISTS kontrol_relay (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_perangkat VARCHAR(50),
    status VARCHAR(10)
)";
mysqli_query($conn, $sql);

// 5. Isi 5 data default jika belum ada
$sql = "INSERT INTO kontrol_relay (id, nama_perangkat, status) VALUES 
        (1, 'Lampu Ruang Tamu', 'OFF'),
        (2, 'Lampu Kamar Utama', 'OFF'),
        (3, 'Lampu Dapur', 'OFF'),
        (4, 'Lampu Kamar Mandi', 'OFF'),
        (5, 'Lampu Teras', 'OFF')
        ON DUPLICATE KEY UPDATE nama_perangkat=VALUES(nama_perangkat)";
mysqli_query($conn, $sql);

echo "Database dan Tabel kontrol_relay berhasil disiapkan!";
?>
