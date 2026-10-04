<?php
$host     = "127.0.0.1";
$username = "root";
$password = "aziz4212";
$database = "db_iot_socket";

$conn = mysqli_connect($host, $username, $password, $database);

// Proses update jika ada tombol yang ditekan
if (isset($_POST['toggle_id']) && isset($_POST['current_status'])) {
    $id = (int) $_POST['toggle_id'];
    $new_status = ($_POST['current_status'] == 'ON') ? 'OFF' : 'ON';
    $update = "UPDATE kontrol_relay SET status = '$new_status' WHERE id = $id";
    mysqli_query($conn, $update);
}

// Ambil semua data lampu
$query  = "SELECT * FROM kontrol_relay ORDER BY id ASC LIMIT 5";
$result = mysqli_query($conn, $query);
$lamps = array();
while($row = mysqli_fetch_assoc($result)){
    $lamps[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kontrol 5 Lampu IoT</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 30px; background-color: #f4f4f9; }
        .grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 200px; }
        .btn { padding: 12px 20px; font-size: 16px; cursor: pointer; color: white; border: none; border-radius: 5px; width: 100%; margin-top: 10px; }
        .btn-on { background-color: #28a745; }
        .btn-off { background-color: #dc3545; }
        .status-text { font-size: 18px; font-weight: bold; margin: 15px 0; }
        .on-text { color: #28a745; }
        .off-text { color: #dc3545; }
    </style>
</head>
<body>
    <h2>Dashboard Kontrol Lampu (Smart Home)</h2>
    
    <div class="grid">
        <?php foreach ($lamps as $lamp): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($lamp['nama_perangkat']); ?></h3>
                
                <div class="status-text <?php echo ($lamp['status'] == 'ON') ? 'on-text' : 'off-text'; ?>">
                    Status: <?php echo $lamp['status']; ?>
                </div>
                
                <form method="POST">
                    <input type="hidden" name="toggle_id" value="<?php echo $lamp['id']; ?>">
                    <input type="hidden" name="current_status" value="<?php echo $lamp['status']; ?>">
                    
                    <?php if ($lamp['status'] == 'OFF'): ?>
                        <button type="submit" class="btn btn-on">Nyalakan</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-off">Matikan</button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
