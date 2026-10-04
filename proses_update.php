<?php
// Koneksi ke Database
$conn = mysqli_connect("localhost", "root", "", "nama_database_mu");

if (isset($_POST['update'])) {
    // Ambil data dari formulir POST
    $id    = mysqli_real_escape_string($conn, $_POST['id']);
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Query untuk memperbarui data
    $query = "UPDATE nama_tabel SET nama='$nama', email='$email' WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "Data berhasil diperbarui! <a href='index.php'>Lihat Data</a>";
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
