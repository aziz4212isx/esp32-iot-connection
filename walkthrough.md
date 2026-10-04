# Panduan Menjalankan Bot WhatsApp IoT

Fitur kontrol IoT melalui WhatsApp telah selesai diimplementasikan. Berikut adalah panduan langkah demi langkah untuk menyalakan bot dan mulai mengontrol lampu Anda melalui WhatsApp.

> [!IMPORTANT]
> **Pastikan MySQL / XAMPP Menyala**
> Sebelum menjalankan bot, pastikan servis MySQL di XAMPP/WAMP Anda dalam keadaan berjalan (`Start`), karena bot ini akan langsung mengakses database `db_iot_socket`.

## Langkah 1: Buka Terminal (CMD)
1. Buka Command Prompt (CMD) atau Terminal di laptop Anda.
2. Masuk ke dalam folder `wa-bot` yang baru saja kita buat:
   ```cmd
   cd "d:\sermestr 8\tertanam\koneksi iot\wa-bot"
   ```

## Langkah 2: Jalankan Bot
1. Di dalam terminal tersebut, ketikkan perintah berikut:
   ```cmd
   node index.js
   ```
2. Tunggu beberapa detik. Anda akan melihat tulisan:
   `✅ Terhubung ke Database MySQL db_iot_socket.`

## Langkah 3: Scan QR Code
1. Setelah tulisan database muncul, terminal akan menampilkan sebuah **QR Code**.
2. Buka aplikasi WhatsApp di HP Anda (sangat disarankan menggunakan nomor WhatsApp yang berbeda/nomor sekunder untuk bot).
3. Pilih **Perangkat Taut** (Linked Devices) > **Tautkan Perangkat** (Link a Device).
4. Scan QR Code yang ada di layar laptop Anda.
5. Jika berhasil, terminal akan menampilkan pesan:
   `✅ WhatsApp Bot sudah siap dan terhubung!`

## Langkah 4: Mulai Mengontrol
Sekarang Anda (atau dosen Anda) bisa mengirim pesan chat biasa ke nomor bot tersebut dengan format berikut:

- **Mengecek Status Semua Lampu**
  Ketik: `status`
  *Bot akan membalas dengan daftar 5 lampu beserta statusnya (ON/OFF).*

- **Menyalakan Lampu Tertentu**
  Ketik: `lampu 1 on` atau `lampu 2 on`
  *Bot akan menyalakan lampu ID tersebut dan database akan terupdate.*

- **Mematikan Lampu Tertentu**
  Ketik: `lampu 1 off` atau `lampu 3 off`
  *Bot akan mematikan lampu ID tersebut.*

- **Melihat Bantuan**
  Ketik: `help` atau `bantuan`

> [!TIP]
> **Integrasi dengan ESP8266/ESP32**
> Karena bot WA ini langsung memodifikasi nilai di dalam database `db_iot_socket`, perangkat ESP Anda yang melakukan *looping* ke `get_status.php` akan otomatis merespon perubahan yang Anda kirimkan lewat WhatsApp secara real-time. Tidak perlu mengubah kode C++ pada ESP Anda!
