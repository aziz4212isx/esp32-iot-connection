<?php
$host     = "127.0.0.1";
$username = "root";
$password = "aziz4212";
$database = "db_iot_socket";

$conn = mysqli_connect($host, $username, $password, $database);

if ($conn) {
    $query  = "SELECT status FROM kontrol_relay ORDER BY id ASC LIMIT 5";
    $result = mysqli_query($conn, $query);
    
    $statuses = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $statuses[] = trim($row['status']);
    }
    
    if (count($statuses) > 0) {
        echo implode(",", $statuses); // Akan mengeluarkan teks seperti "ON,OFF,ON,OFF,OFF"
    } else {
        echo "OFF,OFF,OFF,OFF,OFF";
    }
} else {
    echo "ERROR_DB";
}
?>
