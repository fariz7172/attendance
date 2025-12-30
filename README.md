# Face Attendance System 📸

Sistem Absensi Wajah berbasis Web yang dibangun menggunakan **Laravel 10** dan **Face-API.js**. 
Aplikasi ini memungkinkan karyawan untuk melakukan absensi (Check In / Check Out) menggunakan pengenalan wajah real-time, serta membantu HRD dalam mengelola jadwal kerja, laporan kehadiran, dan perhitungan gaji (payroll).

![Dashboard Preview](https://via.placeholder.com/800x400?text=Dashboard+Face+Attendance)

## 🌟 Fitur Utama

- **Face Recognition Attendance**: Absensi menggunakan deteksi wajah dengan anti-spoofing sederhana (live detection via face-api.js).
- **Manajemen Karyawan**: Kelola data karyawan, jabatan, departemen, dan registrasi wajah.
- **Jadwal Kerja (Shift)**: Mendukung multiple shift (pagi, siang, malam, dll).
- **Laporan Kehadiran**: Rekapitulasi kehadiran harian dan bulanan (Tepat waktu, Terlambat, Pulang Awal).
- **Payroll System**: Perhitungan gaji otomatis berdasarkan kehadiran, tunjangan, dan potongan.
- **Admin Dashboard**: Statistik ringkas kehadiran hari ini.
- **Premium Design**: Antarmuka modern dengan konsep Glassmorphism.

## 🛠️ Teknologi

- **Backend**: Laravel 10 (PHP 8.1+)
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templates, Vanilla CSS (Custom Properties), JavaScript
- **AI Library**: [face-api.js](https://github.com/justadudewhohacks/face-api.js) (TensorFlow.js core)

---

## 🚀 Instalasi Lokal

Ikuti langkah ini untuk menjalankan project di komputer lokal:

1. **Clone Repository**
   ```bash
   git clone https://github.com/fariz7172/attendance.git
   cd attendance
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Atur koneksi database di file `.env`.

4. **Migrate & Seed**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Seeder akan membuat user admin default.*

5. **Jalankan Server**
   ```bash
   php artisan serve
   ```
   Akses di `http://127.0.0.1:8000`.

**Akun Default:**
- **Email:** `admin@gmail.com`
- **Password:** `password`

---

## 🌐 Panduan Deployment (Hostinger / Shared Hosting)

Aplikasi ini telah dimodifikasi agar kompatibel dengan Shared Hosting yang memiliki keterbatasan (seperti tidak support symlink atau document root kustom).

### 1. Upload File
Upload semua file project ke folder `public_html/attendance` (atau folder subdomain Anda).

### 2. Setup Database
Buat database di panel hosting, impor file SQL (jika ada) atau jalankan migrasi via SSH:
```bash
php artisan migrate --force
```
Jangan lupa sesuaikan `.env` dengan kredensial database hosting.

### 3. Konfigurasi Khusus (PENTING)

#### A. Penyimpanan Gambar (Zero Symlink)
Aplikasi ini **TIDAK** menggunakan `php artisan storage:link`.
Semua gambar (wajah & absensi) disimpan langsung di folder `public/faces` dan `public/attendances`.
- Pastikan folder `public/faces` dan `public/attendances` memiliki permission **755** atau **777** agar bisa ditulisi.

#### B. Redirection (.htaccess)
Jika Anda tidak bisa mengubah "Document Root" ke folder `public` (masalah umum di Shared Hosting), gunakan konfigurasi `.htaccess` berikut di **root folder project** (misal: `/public_html/attendance/.htaccess`):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L,QSA]
</IfModule>
```

Ini akan mengarahkan semua traffic ke folder `public` tanpa mengubah URL di browser.

---

## 📂 Struktur Folder Penting

- `app/Http/Controllers/`: Logika backend.
- `resources/views/`: Tampilan (Blade).
- `public/models/`: Model AI untuk face-api.js.
- `public/faces/`: Tempat penyimpanan foto registrasi wajah.
- `config/filesystems.php`: Konfigurasi disk `public_uploads`.

## 🤝 Kontribusi

Pull requests dipersilakan. Untuk perubahan besar, mohon buka issue terlebih dahulu untuk mendiskusikan apa yang ingin Anda ubah.

## 📄 Lisensi

[MIT](https://choosealicense.com/licenses/mit/)
