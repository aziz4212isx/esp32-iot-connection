#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h> // Pastikan Anda sudah menginstall library ArduinoJson di Arduino IDE

// 1. Pengaturan WiFi
const char* ssid = "AZIZ5G ";         // Ganti dengan nama WiFi Anda (ada spasi di akhir!)
const char* password = "ISX4212*)";   // Ganti dengan kata sandi WiFi Anda

// 2. Pengaturan URL API untuk mengambil status JSON
// Pastikan path-nya sesuai dengan lokasi file get_status_json.php Anda
String serverName = "http://192.168.1.7:8000/koneksi%20dengan%20jason/get_status_json.php"; 

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

void loop() {
  if(WiFi.status() == WL_CONNECTED){
    HTTPClient http;
    http.begin(serverName);
    int httpResponseCode = http.GET();
    
    if (httpResponseCode > 0) {
      String payload = http.getString();
      
      Serial.println("=================================");
      Serial.print("Data JSON Diterima: ");
      Serial.println(payload);
      
      // Parse JSON menggunakan ArduinoJson
      // Untuk ArduinoJson versi 6, gunakan DynamicJsonDocument doc(1024);
      // Untuk ArduinoJson versi 7, cukup gunakan JsonDocument doc;
      DynamicJsonDocument doc(1024);
      
      DeserializationError error = deserializeJson(doc, payload);

      if (error) {
        Serial.print("Gagal memparsing JSON: ");
        Serial.println(error.c_str());
      } else {
        // Karena respons JSON berbentuk Array [{id, status}, {id, status}]
        for (int i = 0; i < doc.size() && i < 5; i++) {
          JsonObject item = doc[i];
          const char* statusLampu = item["status"];
          
          if (String(statusLampu) == "ON") {
            digitalWrite(pinLampu[i], HIGH);
            Serial.printf("Lampu %d (Pin %d) -> ON\n", i + 1, pinLampu[i]);
          } else if (String(statusLampu) == "OFF") {
            digitalWrite(pinLampu[i], LOW);
            Serial.printf("Lampu %d (Pin %d) -> OFF\n", i + 1, pinLampu[i]);
          }
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
