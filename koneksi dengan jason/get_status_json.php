<?php
$host     = "127.0.0.1";
$username = "root";
$password = "aziz4212";
$database = "db_iot_socket";

$conn = mysqli_connect($host, $username, $password, $database);

// Set header agar output dikenali sebagai JSON
header('Content-Type: application/json');

if ($conn) {
    $query  = "SELECT id, status FROM kontrol_relay ORDER BY id ASC LIMIT 5";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $statuses = array();
        while ($row = mysqli_fetch_assoc($result)) {
            // Memasukkan setiap baris ke dalam array
            $statuses[] = array(
                "id" => $row['id'],
                "status" => trim($row['status'])
            );
        }
        
        if (count($statuses) > 0) {
            // Mengubah array PHP menjadi format JSON
            echo json_encode($statuses, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(array("error" => "Data kosong"));
        }
    } else {
        echo json_encode(array("error" => "ERROR_QUERY: " . mysqli_error($conn)));
    }
} else {
    echo json_encode(array("error" => "ERROR_DB: " . mysqli_connect_error()));
}
?>
