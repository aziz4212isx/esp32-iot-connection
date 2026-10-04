<?php
// 1. Establish Database Connection
$host = "localhost";
$user = "username";
$pass = "password";
$db   = "database_name";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Execute SQL Query
$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);

$data = array();

// 3. Fetch Data into a PHP Array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// 4. Output as JSON
header('Content-Type: application/json'); // Set the correct header for JSON
echo json_encode($data, JSON_PRETTY_PRINT);

// Optional: Save to a file
// file_put_contents('data.json', json_encode($data));

$conn->close();
