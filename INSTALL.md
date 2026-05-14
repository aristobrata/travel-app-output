# 🚌 Falles Travel — Panduan Instalasi

## Persyaratan
- XAMPP / Laragon (PHP 8.1+, MySQL 5.7+ / MariaDB 10.4+)
- Apache dengan `mod_rewrite` aktif
- Browser modern

---

## Langkah Instalasi

### 1. Letakkan Folder Project
Ekstrak zip ke folder htdocs XAMPP:
```
C:\xampp\htdocs\travel-app-output\
```

### 2. Buat Database
Buka phpMyAdmin → Buat database baru bernama `travel_app`

### 3. Import SQL
Jalankan file SQL berikut **secara berurutan** di phpMyAdmin:
1. `database/travel_app.sql` ← data utama (import dari file SQL yang sudah ada)
2. `database/migration_vehicle_docs.sql` ← tambahan kolom kendaraan

### 4. Konfigurasi .env
Edit file `.env` di root project:
```env
APP_NAME="Nama Perusahaan Travel"
APP_URL=http://localhost/travel-app-output
APP_DEBUG=true

DB_HOST=localhost
DB_NAME=travel_app
DB_USER=root
DB_PASS=

# Isi dengan key Midtrans Anda (opsional untuk testing)
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
```

### 5. Aktifkan mod_rewrite
Edit `C:\xampp\apache\conf\httpd.conf`:
- Pastikan `LoadModule rewrite_module modules/mod_rewrite.so` tidak ter-comment
- Cari `<Directory "C:/xampp/htdocs">` dan ubah `AllowOverride None` → `AllowOverride All`
- Restart Apache

### 6. Akses Aplikasi
```
http://localhost/travel-app-output/
```

---

## Login Admin
Cek tabel `users` di database, atau tambah akun admin manual:
```sql
INSERT INTO users (name, email, phone, password_hash, role, is_active)
VALUES (
  'Admin',
  'admin@travel.com',
  '08123456789',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
  'admin',
  1
);
```

---

## Fitur Admin Dashboard
- **Kendaraan**: CRUD + nomor rangka, nomor mesin, pajak, upload STNK & BPKB
- **Rute**: CRUD rute perjalanan
- **Jadwal**: Buat jadwal keberangkatan
- **Pemesanan**: Pantau & update status booking
- **Pengguna**: Kelola akun pengguna
- **Laporan**: Pendapatan, booking, per rute & kendaraan + export PDF

## Struktur URL
| URL | Keterangan |
|-----|-----------|
| `/` | Halaman pencarian tiket |
| `/login` | Login |
| `/register` | Daftar akun |
| `/search` | Hasil pencarian |
| `/seat/{id}` | Pilih kursi |
| `/my-bookings` | Riwayat pesanan |
| `/admin` | Dashboard admin |
| `/admin/vehicles` | Manajemen kendaraan |
| `/admin/bookings` | Manajemen pemesanan |
| `/admin/reports` | Laporan |

