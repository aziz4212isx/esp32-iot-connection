CREATE DATABASE IF NOT EXISTS db_iot_socket;
USE db_iot_socket;

CREATE TABLE IF NOT EXISTS sensor_listrik (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tegangan FLOAT,
    arus FLOAT,
    daya FLOAT,
    energi FLOAT
);
