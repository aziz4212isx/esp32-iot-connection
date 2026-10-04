<?php
// Koneksi ke Database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_iot_socket"; // Nama database yang baru kita buat

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah data V, I, P, dan E dikirim dari ESP32
if (isset($_GET['v']) && isset($_GET['i']) && isset($_GET['p']) && isset($_GET['e'])) {
    
    // Mengamankan data dari injeksi
    $v = mysqli_real_escape_string($conn, $_GET['v']); // Tegangan
    $i = mysqli_real_escape_string($conn, $_GET['i']); // Arus
    $p = mysqli_real_escape_string($conn, $_GET['p']); // Daya
    $e = mysqli_real_escape_string($conn, $_GET['e']); // Energi (kWh)

    // Perintah memasukkan data ke tabel
    $query = "INSERT INTO sensor_listrik (tegangan, arus, daya, energi) VALUES ('$v', '$i', '$p', '$e')";
    
    if (mysqli_query($conn, $query)) {
        echo "Berhasil menyimpan data sensor!";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
} else {
    echo "Menunggu pengiriman data dari ESP32...";
}

mysqli_close($conn);
?>
