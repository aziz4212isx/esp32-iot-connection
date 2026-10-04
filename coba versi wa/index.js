const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const mysql = require('mysql2');

// Konfigurasi Database (Sama seperti toggle.php)
const db = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: 'aziz4212',
    database: 'db_iot_socket'
});

// Koneksi ke Database
db.connect((err) => {
    if (err) {
        console.error('Gagal koneksi ke MySQL: ' + err.stack);
        return;
    }
    console.log('✅ Terhubung ke Database MySQL db_iot_socket.');
});

// Inisialisasi WhatsApp Client
// LocalAuth akan menyimpan sesi agar Anda tidak perlu scan QR tiap kali bot direstart
const client = new Client({
    authStrategy: new LocalAuth()
});

// Event ketika QR Code harus di-scan
client.on('qr', (qr) => {
    qrcode.generate(qr, { small: true });
    console.log('Scan QR code di atas menggunakan WhatsApp di HP Anda.');
});

// Event ketika bot sudah berhasil login dan siap digunakan
client.on('ready', () => {
    console.log('✅ WhatsApp Bot sudah siap dan terhubung!');
});

// Event ketika menerima atau mengirim pesan (supaya bisa test chat ke diri sendiri)
client.on('message_create', async (message) => {
    // Ubah pesan menjadi huruf kecil semua dan hapus spasi berlebih
    const text = message.body.toLowerCase().trim();

    // 1. Perintah: "status"
    if (text === 'status') {
        db.query("SELECT * FROM kontrol_relay ORDER BY id ASC LIMIT 5", (err, results) => {
            if (err) {
                message.reply('Terjadi kesalahan saat mengambil data dari database.');
                return;
            }
            let replyText = '*Status Lampu Saat Ini:*\n\n';
            results.forEach((row) => {
                let emoji = row.status === 'ON' ? '🟢' : '🔴';
                replyText += `${emoji} ${row.nama_perangkat} (ID ${row.id}): *${row.status}*\n`;
            });
            message.reply(replyText);
        });
    }
    
    // 2. Perintah: "lampu X on" atau "lampu X off"
    // Regex ini akan menangkap pola "lampu 1 on", "lampu 2 off", dst.
    const regex = /^lampu\s+(\d+)\s+(on|off)$/;
    const match = text.match(regex);
    
    if (match) {
        const id = parseInt(match[1]);
        const status = match[2].toUpperCase(); // "ON" atau "OFF"
        
        if (id >= 1 && id <= 5) {
            db.query("UPDATE kontrol_relay SET status = ? WHERE id = ?", [status, id], (err, result) => {
                if (err) {
                    message.reply('Gagal memperbarui status lampu.');
                    return;
                }
                
                // Ambil nama lampu yang baru diupdate untuk pesan balasan
                db.query("SELECT nama_perangkat FROM kontrol_relay WHERE id = ?", [id], (err, rows) => {
                    if (!err && rows.length > 0) {
                        message.reply(`✅ Berhasil! *${rows[0].nama_perangkat}* sekarang dalam keadaan *${status}*.`);
                    } else {
                        message.reply(`✅ Lampu dengan ID ${id} berhasil diatur menjadi ${status}.`);
                    }
                });
            });
        } else {
            message.reply('ID Lampu tidak valid. Gunakan ID 1 sampai 5.\nContoh: *lampu 1 on*');
        }
    }

    // 3. Perintah: "help" atau "bantuan"
    if (text === 'help' || text === 'bantuan') {
        message.reply(
            '*Daftar Perintah Bot IoT:*\n\n' +
            '1. *status* -> Melihat status semua lampu.\n' +
            '2. *lampu [id] on* -> Menyalakan lampu (contoh: lampu 1 on).\n' +
            '3. *lampu [id] off* -> Mematikan lampu (contoh: lampu 1 off).\n'
        );
    }
});

// Menjalankan client WA
client.initialize();
