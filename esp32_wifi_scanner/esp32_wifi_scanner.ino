#include "WiFi.h"

void setup() {
  Serial.begin(115200);
  Serial.println("Memulai WiFi Scanner...");

  // Set WiFi ke mode station agar bisa melakukan scan
  WiFi.mode(WIFI_STA);
  WiFi.disconnect();
  delay(100);

  Serial.println("Setup selesai. Memulai pemindaian...");
}

void loop() {
  Serial.println("Scan dimulai...");

  // WiFi.scanNetworks() akan mencari jaringan di sekitar ESP32
  int n = WiFi.scanNetworks();
  Serial.println("Scan selesai!");
  
  if (n == 0) {
    Serial.println("TIDAK ADA jaringan WiFi yang ditemukan.");
  } else {
    Serial.print(n);
    Serial.println(" jaringan ditemukan di sekitar ESP32:");
    
    for (int i = 0; i < n; ++i) {
      // Print SSID (Nama WiFi) dan RSSI (Kekuatan Sinyal)
      Serial.print(i + 1);
      Serial.print(": ");
      Serial.print(WiFi.SSID(i));
      Serial.print(" (Sinyal: ");
      Serial.print(WiFi.RSSI(i));
      Serial.print(" dBm)");
      
      // Kasih bintang jika WiFi dikunci (ada password-nya)
      Serial.println((WiFi.encryptionType(i) == WIFI_AUTH_OPEN)?" ":" *");
      delay(10);
    }
  }
  Serial.println("----------------------------------------");

  // Tunggu 5 detik sebelum melakukan scan lagi
  delay(5000);
}
