<?php
// Koneksi ke Database
$conn = mysqli_connect("localhost", "root", "", "api-st");

if (isset($_POST['update'])) {
    // Ambil data dari formulir POST
    $id    = mysqli_real_escape_string($conn, $_POST['id']);
    $keterangan  = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Query untuk memperbarui data
    $query = "UPDATE data_rumah SET keterangan='$keterangan', status='$status' WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        echo "Data berhasil diperbarui! <a href='edit.php?id=$id'>Lihat Data</a>";
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }

    // Query untuk log history
    $query = "insert history(keterangan) value('Perubahan Tabel data_rumah dengan id=$id,keterangan=$keterangan,status=$status')";

    if (mysqli_query($conn, $query)) {
        echo "Log Berhasil Ditambahkan !";
    } else {
        echo "Log Gagal Ditambahkan : " . mysqli_error($conn);
    }
}

mysqli_close($conn);
