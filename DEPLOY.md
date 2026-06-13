# Panduan Deploy DompetKu di Shared Hosting (cPanel)

Karena shared hosting standar seringkali tidak memiliki akses SSH, ikuti langkah-langkah berikut untuk deploy aplikasi DompetKu.

## 1. Persiapan File
1. Kompres semua file project (kecuali folder `node_modules`, `tests`, dan `.git`) menjadi file `.zip`.
2. Pastikan Anda sudah menjalankan `composer install --no-dev` di komputer lokal sebelum kompres.

## 2. Upload ke cPanel
1. Masuk ke **File Manager** di cPanel.
2. Upload file `.zip` Anda ke folder root (di luar `public_html` sangat disarankan demi keamanan). Contoh: `/home/username/dompetku`.
3. Ekstrak file `.zip` tersebut.

## 3. Konfigurasi Folder Public
Ada dua cara, cara yang paling umum:
1. Pindahkan isi dari folder `/home/username/dompetku/public` ke folder `/home/username/public_html`.
2. Edit file `public_html/index.php`:
   - Cari baris `require __DIR__.'/../vendor/autoload.php';`
   - Ubah menjadi `require __DIR__.'/../dompetku/vendor/autoload.php';`
   - Cari baris `$app = require_once __DIR__.'/../bootstrap/app.php';`
   - Ubah menjadi `$app = require_once __DIR__.'/../dompetku/bootstrap/app.php';`

## 4. Konfigurasi Database
1. Buat Database MySQL dan User Database melalui menu **MySQL Databases** di cPanel.
2. Hubungkan User ke Database dengan hak akses penuh (All Privileges).
3. Edit file `/home/username/dompetku/.env`:
   - Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
   - Ubah `APP_ENV=production` dan `APP_DEBUG=false`.
   - Pastikan `APP_URL` sudah sesuai.

## 5. Menjalankan Migrasi Tanpa SSH
Karena tidak ada SSH, gunakan fitur **Web Artisan** yang sudah saya buat:
1. Buka browser dan akses URL berikut:
   `http://domain-anda.com/web-artisan/migrate?key=BASE64_APP_KEY_ANDA`
   *(Ganti `BASE64_APP_KEY_ANDA` dengan nilai `APP_KEY` yang ada di file .env)*
2. Setelah sukses, jalankan seeder untuk kategori default:
   `http://domain-anda.com/web-artisan/seed?key=BASE64_APP_KEY_ANDA`

## 6. Selesai
Aplikasi siap digunakan. Jangan lupa untuk menghapus folder `web-artisan` di routes atau mengamankan URL tersebut jika dirasa perlu setelah setup selesai.
