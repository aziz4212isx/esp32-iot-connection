<?php
// 1. Koneksi ke Database
$host     = "localhost";
$username = "root";
$password = "";
$database = "api-st";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Ambil ID dari URL menggunakan metode GET
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']); // Amankan data

    // 3. Query untuk membaca data spesifik
    $query  = "SELECT * FROM data_rumah WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    // Query untuk log history
    $query = "insert history(keterangan) value('Pembacaan Tabel data_rumah dengan id=$id')";

    if (mysqli_query($conn, $query)) {
        echo "Log Berhasil Ditambahkan !";
    } else {
        echo "Log Gagal Ditambahkan : " . mysqli_error($conn);
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $keterangan = $row['keterangan']; // Ganti sesuai nama kolom tabelmu
        $status = $row['status'];
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

        <label>Keterangan:</label><br>
        <input type="text" name="keterangan" value="<?php echo $keterangan; ?>"><br><br>

        <label>Status:</label><br>
        <input type="text" name="status" value="<?php echo $status; ?>"><br><br>

        <button type="submit" name="update">Simpan Perubahan</button>
    </form>
</body>

</html>