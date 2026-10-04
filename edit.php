<?php
// 1. Koneksi ke Database
$host     = "localhost";
$username = "root";
$password = "";
$database = "db_iot_socket";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Ambil ID dari URL menggunakan metode GET
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']); // Amankan data

    // 3. Query untuk membaca data spesifik
    $query  = "SELECT * FROM sensor_listrik WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $tegangan = $row['tegangan']; 
        $arus = $row['arus'];
        $daya = $row['daya'];
        $energi = $row['energi'];
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
} else {
    echo "ID tidak ditentukan.";
    exit;
}
?>

<!-- 4. Tampilkan data ke dalam Formulir HTML -->
<!DOCTYPE html>
<html>

<head>
    <title>Edit Data</title>
</head>

<body>
    <h2>Formulir Edit Data</h2>
    <!-- Formulir dikirim ke proses_update.php menggunakan POST demi keamanan -->
    <form action="proses_update.php" method="POST">
        <!-- Input ID disembunyikan (hidden) agar bisa dikirim saat update -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label>Tegangan (V):</label><br>
        <input type="text" name="tegangan" value="<?php echo $tegangan; ?>"><br><br>

        <label>Arus (A):</label><br>
        <input type="text" name="arus" value="<?php echo $arus; ?>"><br><br>

        <label>Daya (W):</label><br>
        <input type="text" name="daya" value="<?php echo $daya; ?>"><br><br>

        <label>Energi (kWh):</label><br>
        <input type="text" name="energi" value="<?php echo $energi; ?>"><br><br>

        <button type="submit" name="update">Simpan Perubahan</button>
    </form>
</body>

</html>