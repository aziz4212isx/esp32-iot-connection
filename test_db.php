<?php
try {
    $conn = mysqli_connect('127.0.0.1', 'root', '');
    echo "Koneksi sukses!";
} catch (Exception $e) {
    echo "Gagal: " . $e->getMessage();
}
?>
