#include <WiFi.h>
#include <HTTPClient.h>

// 1. Pengaturan WiFi
const char* ssid = "AZIZ5G ";         // Ganti dengan nama WiFi Anda (ada spasi di akhir!)
const char* password = "ISX4212*)"; // Ganti dengan kata sandi WiFi Anda

// 2. Pengaturan URL API untuk mengambil status
String serverName = "http://192.168.1.7:8000/get_status.php"; // Sesuaikan port (8000 atau hilangkan jika port default 80)

// 3. Deklarasi Pin Relay / Lampu
const int pinLampu[5] = {2, 4, 5, 18, 19}; 
// Indeks 0 -> Pin 2  (Ruang Tamu)
// Indeks 1 -> Pin 4  (Kamar Utama)
// Indeks 2 -> Pin 5  (Dapur)
// Indeks 3 -> Pin 18 (Kamar Mandi)
// Indeks 4 -> Pin 19 (Teras)

void setup() {
  Serial.begin(115200); 
  
  // Inisialisasi semua pin sebagai OUTPUT dan matikan awalnya
  for(int i = 0; i < 5; i++) {
    pinMode(pinLampu[i], OUTPUT);
    digitalWrite(pinLampu[i], LOW);
  }
  
  // Menghubungkan ESP32 ke WiFi
  Serial.println();
  Serial.print("Menghubungkan ke WiFi: ");
  Serial.println(ssid);
  
  WiFi.mode(WIFI_STA);
  WiFi.disconnect();
  delay(100);
  WiFi.begin(ssid, password);
  
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nBerhasil terhubung ke WiFi!");
  Serial.print("IP Address ESP32: ");
  Serial.println(WiFi.localIP());
}

// Fungsi untuk memecah string berdasarkan koma (CSV)
String getValue(String data, char separator, int index) {
  int found = 0;
  int strIndex[] = {0, -1};
  int maxIndex = data.length() - 1;

  for (int i = 0; i <= maxIndex && found <= index; i++) {
    if (data.charAt(i) == separator || i == maxIndex) {
      found++;
      strIndex[0] = strIndex[1] + 1;
      strIndex[1] = (i == maxIndex) ? i + 1 : i;
    }
  }
  return found > index ? data.substring(strIndex[0], strIndex[1]) : "";
}

void loop() {
  if(WiFi.status() == WL_CONNECTED){
    HTTPClient http;
    http.begin(serverName);
    int httpResponseCode = http.GET();
    
    if (httpResponseCode > 0) {
      String payload = http.getString();
      payload.trim(); // Menghapus spasi/enter tambahan dari PHP
      
      Serial.print("Data Diterima: ");
      Serial.println(payload); // Contoh output: "ON,OFF,OFF,ON,OFF"
      
      // Memecah teks dan mengatur pin satu per satu
      for (int i = 0; i < 5; i++) {
        String statusLampu = getValue(payload, ',', i);
        
        if (statusLampu == "ON") {
          digitalWrite(pinLampu[i], HIGH);
        } else if (statusLampu == "OFF") {
          digitalWrite(pinLampu[i], LOW);
        }
      }
      
    } else {
      Serial.print("Gagal mengambil data, Kode Error: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("Koneksi WiFi Terputus...");
  }
  
  // Tunggu 2 detik sebelum mengecek database lagi
  delay(2000);
}
