#include <WiFi.h>
#include <HTTPClient.h>
#include <PZEM004Tv30.h> // Pastikan library PZEM sudah terinstall

// ============================================
// 1. PENGATURAN WIFI
// ============================================
const char* ssid = "AZIZ5G "; // Ada spasi di akhir nama WiFi!
const char* password = "ISX4212*)";

// ============================================
// 2. PENGATURAN SERVER DATABASE (PHP)
// ============================================
// Ganti 192.168.1.10 dengan IP Address komputer/laptop Anda
// Pastikan folder koneksi_iot ada di dalam folder htdocs XAMPP
String serverName = "http://192.168.1.7:8000/insert_sensor.php";

// ============================================
// 3. PENGATURAN SENSOR PZEM
// ============================================
HardwareSerial serialPZEM(2);
PZEM004Tv30 pzem(serialPZEM, 16, 17); // Pin RX=16, TX=17 (Sesuaikan dengan wiring Anda)

void setup() {
  Serial.begin(115200);
  
  // Memulai komunikasi ke PZEM
  serialPZEM.begin(9600, SERIAL_8N1, 16, 17);

  // Menghubungkan ke WiFi
  Serial.println();
  Serial.print("Menghubungkan ke WiFi: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nBerhasil terhubung ke WiFi!");
  Serial.print("IP Address ESP32: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  // 1. BACA DATA DARI SENSOR PZEM
  float v = pzem.voltage();
  float i = pzem.current();
  float p = pzem.power();
  float e = pzem.energy();

  // Cek apakah pembacaan valid
  if(isnan(v) || isnan(i) || isnan(p) || isnan(e)) {
    Serial.println("Gagal membaca dari sensor PZEM");
  } else {
    Serial.println("-----------------------------------");
    Serial.printf("Tegangan: %.1f V | Arus: %.3f A | Daya: %.1f W | Energi: %.3f kWh\n", v, i, p, e);

    // 2. KIRIM DATA KE DATABASE MYSQL VIA PHP
    if(WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      
      // Merakit URL dengan data sensor
      // Hasil akhir misal: http://192.168.1.10/.../insert_sensor.php?v=220.5&i=1.2&p=264&e=1.5
      String urlLengkap = serverName + "?v=" + String(v, 1) + 
                                       "&i=" + String(i, 3) + 
                                       "&p=" + String(p, 1) + 
                                       "&e=" + String(e, 3);
      
      Serial.println("Mengeksekusi Link: " + urlLengkap);
      http.begin(urlLengkap);
      
      int httpResponseCode = http.GET();
      
      if (httpResponseCode > 0) {
        String payload = http.getString();
        Serial.println("Balasan Server: " + payload);
      } else {
        Serial.print("Gagal mengirim data. Kode Error: ");
        Serial.println(httpResponseCode);
      }
      
      http.end(); // Tutup koneksi memori
    } else {
      Serial.println("Koneksi WiFi Terputus...");
    }
  }

  // Jeda 10 detik sebelum mengulang (jangan terlalu cepat agar server tidak hang)
  delay(10000);
}
