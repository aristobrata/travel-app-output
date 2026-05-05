**Falles Travel** adalah sistem pemesanan tiket perjalanan bus dan travel yang dibangun menggunakan **PHP Native** dengan pola arsitektur **MVC (Model-View-Controller)** tanpa bergantung pada framework apapun. Aplikasi ini dirancang untuk perusahaan otobus dan travel lokal yang membutuhkan platform pemesanan tiket digital yang mudah dikelola.

Sistem ini mencakup dua bagian utama:
- **Panel Penumpang** — pencarian jadwal, pemilihan kursi interaktif, pemesanan, dan pembayaran online
- **Panel Admin** — manajemen armada kendaraan, rute, jadwal, pemesanan, pengguna, dan laporan keuangan

---

## ✨ Fitur Lengkap

### 👤 Fitur Penumpang (Frontend)

| Fitur | Keterangan |
|-------|-----------|
| 🔍 **Pencarian Jadwal** | Cari jadwal berdasarkan kota asal, tujuan, dan tanggal keberangkatan |
| 🪑 **Pemilihan Kursi Interaktif** | Denah kursi real-time dengan status tersedia/terisi/dikunci |
| 🔒 **Seat Locking** | Kursi terkunci sementara selama 15 menit saat proses pemesanan |
| 📝 **Form Data Penumpang** | Input nama dan nomor identitas per penumpang |
| 💳 **Pembayaran Online** | Integrasi Midtrans Snap (Transfer Bank, GoPay, OVO, QRIS, dll) |
| 🎫 **E-Tiket Digital** | Tiket elektronik yang bisa dicetak langsung dari browser |
| 📋 **Riwayat Pesanan** | Pantau semua pemesanan dan status pembayaran |
| 📱 **Responsive Design** | Tampilan optimal di desktop maupun smartphone |

### 🛠️ Fitur Admin Dashboard

#### 🚌 Manajemen Kendaraan
| Fitur | Keterangan |
|-------|-----------|
| ➕ **CRUD Kendaraan** | Tambah, edit, nonaktifkan kendaraan armada |
| 🔢 **Nomor Rangka** | Simpan dan tampilkan nomor rangka (VIN/Chassis) kendaraan |
| ⚙️ **Nomor Mesin** | Simpan dan tampilkan nomor mesin kendaraan |
| 📅 **Pajak Kendaraan** | Catat tanggal jatuh tempo pajak dengan peringatan otomatis |
| 🟠 **Indikator Pajak** | Warna merah/oranye/hijau berdasarkan sisa hari pajak |
| 📄 **Upload STNK** | Upload dan tampilkan dokumen STNK (JPG/PNG/PDF) |
| 📘 **Upload BPKB** | Upload dan tampilkan dokumen BPKB (JPG/PNG/PDF) |
| 👁️ **Detail Kendaraan** | Halaman detail lengkap dengan preview dokumen |
| ❄️🔌📶📺 **Fasilitas** | Tandai fasilitas: AC, WiFi, USB Charging, TV |

#### 🗺️ Manajemen Rute
- CRUD rute perjalanan (kota asal → kota tujuan)
- Toggle aktif/nonaktif rute

#### 📆 Manajemen Jadwal
- Buat jadwal keberangkatan dengan kendaraan dan rute tertentu
- Atur waktu berangkat, tiba, dan harga override
- Lacak ketersediaan kursi secara real-time

#### 🎟️ Manajemen Pemesanan
- Tampilkan semua booking dengan filter status (Pending/Terbayar/Dibatalkan/Selesai)
- Lihat detail lengkap: data penumpang, riwayat pembayaran, nomor kursi
- Update status booking secara manual (konfirmasi/batalkan/selesai)
- Otomatis kembalikan kursi ketika booking dibatalkan

#### 👥 Manajemen Pengguna
- Lihat semua pengguna terdaftar
- Aktifkan / nonaktifkan akun pengguna
- Ubah role pengguna (User ↔ Admin)

#### 📊 Laporan & Analitik
| Laporan | Keterangan |
|---------|-----------|
| 📈 Pendapatan Harian | Grafik bar pendapatan per hari dalam periode tertentu |
| 🗺️ Per Rute | Total booking dan pendapatan per rute perjalanan |
| 🚌 Per Kendaraan | Performa tiap armada: jadwal, booking, pendapatan |
| 👑 Top Pelanggan | Ranking pelanggan berdasarkan total transaksi |
| 📄 Export PDF | Cetak laporan pendapatan, booking, rute, dan kendaraan |
| 🔍 Filter Periode | Filter data berdasarkan rentang tanggal |

---

## 🛠 Teknologi yang Digunakan

### Backend
| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| **PHP** | 8.1+ | Bahasa pemrograman utama |
| **MySQL / MariaDB** | 5.7+ / 10.4+ | Database relasional |
| **Apache** | 2.4+ | Web server dengan mod_rewrite |
| **PDO** | — | Koneksi database yang aman (prepared statements) |

### Arsitektur
| Komponen | Keterangan |
|----------|-----------|
| **MVC Pattern** | Model-View-Controller tanpa framework (PHP Native) |
| **Custom Router** | Router ringan dengan dukungan parameter dinamis `{id}` |
| **Middleware Chain** | Auth, Admin, CSRF protection |
| **PSR-4 Autoloading** | Autoloader kelas dengan `spl_autoload_register()` |
| **Environment Config** | Konfigurasi via file `.env` |

### Frontend
| Teknologi | Keterangan |
|-----------|-----------|
| **HTML5 & CSS3** | Markup dan styling dasar |
| **Vanilla JavaScript** | Interaktivitas tanpa library tambahan |
| **Font Awesome 6** | Ikon via CDN |
| **Google Fonts** | Plus Jakarta Sans + DM Serif Display |
| **CSS Custom Properties** | Design token (warna, shadow, radius) yang konsisten |
| **CSS Grid & Flexbox** | Layout responsif modern |

### Integrasi Eksternal
| Layanan | Keterangan |
|---------|-----------|
| **Midtrans Snap** | Payment gateway (VA, GoPay, OVO, QRIS, kartu kredit, dll) |
| **Font Awesome CDN** | Ikon UI |
| **Google Fonts CDN** | Tipografi |

### Keamanan
| Mekanisme | Keterangan |
|-----------|-----------|
| **Password Hashing** | `password_hash()` dengan algoritma Bcrypt (cost 12) |
| **CSRF Token** | Token unik per sesi untuk proteksi form |
| **Session Security** | `httponly`, `samesite=Lax`, tanpa expire |
| **SQL Injection Prevention** | PDO Prepared Statements di semua query |
| **XSS Prevention** | `htmlspecialchars()` pada semua output |
| **File Upload Security** | Validasi MIME type, ekstensi, dan direktori isolasi |
| **Directory Protection** | `.htaccess` memblokir akses ke folder `app/`, `config/`, `views/` |

---

## 📁 Struktur Proyek

```
travel-app-output/
│
├── 📄 index.php                    # Entry point utama (akses via localhost/travel-app-output/)
├── 📄 .env                         # Konfigurasi environment (DB, Midtrans, dll)
├── 📄 .env.example                 # Template konfigurasi
├── 📄 .htaccess                    # URL rewriting ke index.php
├── 📄 INSTALL.md                   # Panduan instalasi singkat
│
├── 📂 app/
│   ├── 📂 Controllers/             # Logika request handler (5 controller)
│   │   ├── AdminController.php     # Dashboard, kendaraan, rute, jadwal, user
│   │   ├── AuthController.php      # Login, register, logout
│   │   ├── BookingController.php   # Pencarian, kursi, pemesanan, detail
│   │   ├── PaymentController.php   # Midtrans initiate, callback, finish
│   │   └── ReportController.php    # Laporan & export PDF
│   │
│   ├── 📂 Core/                    # Framework mini buatan sendiri
│   │   ├── Database.php            # PDO wrapper (query, fetchAll, fetchOne, insert)
│   │   ├── Request.php             # Helper GET/POST/validate/sanitize
│   │   ├── Response.php            # redirect(), json(), back()
│   │   └── Router.php              # Custom router dengan pattern matching
│   │
│   ├── 📂 Middleware/              # Layer keamanan
│   │   ├── AdminMiddleware.php     # Cek role = admin
│   │   ├── AuthMiddleware.php      # Cek sudah login
│   │   └── CsrfMiddleware.php      # Validasi CSRF token
│   │
│   ├── 📂 Models/                  # Abstraksi database (8 model)
│   │   ├── Booking.php             # Pemesanan & kursi penumpang
│   │   ├── Payment.php             # Data transaksi pembayaran
│   │   ├── Report.php              # Query laporan & analitik
│   │   ├── Route.php               # Rute perjalanan
│   │   ├── Schedule.php            # Jadwal keberangkatan
│   │   ├── SeatLock.php            # Kunci kursi sementara
│   │   ├── User.php                # Akun pengguna
│   │   └── Vehicle.php             # Data armada kendaraan
│   │
│   └── 📂 Services/                # Layanan eksternal
│       ├── PaymentService.php      # Midtrans Snap API
│       ├── MapsService.php         # Google Maps Distance Matrix
│       └── SeatService.php         # Helper layout denah kursi
│
├── 📂 config/
│   ├── app.php                     # Konfigurasi aplikasi & load .env
│   ├── database.php                # Koneksi PDO MySQL
│   └── payment.php                 # Konfigurasi Midtrans & Google Maps
│
├── 📂 database/
│   ├── schema.sql                  # Struktur tabel lengkap + data awal
│   ├── schema_fix.sql              # Patch perbaikan schema
│   └── migration_vehicle_docs.sql  # Migrasi kolom dokumen kendaraan
│
├── 📂 public/
│   ├── index.php                   # Routing entry point (56 routes)
│   ├── .htaccess                   # Rewrite rules untuk public/
│   └── 📂 assets/
│       ├── css/app.css             # Stylesheet utama
│       ├── js/                     # JavaScript files
│       ├── img/                    # Gambar statis
│       └── uploads/                # File upload STNK & BPKB
│
└── 📂 views/                       # Template HTML/PHP (30 view)
    ├── 📂 layouts/
    │   ├── admin.php               # Layout header + sidebar admin
    │   └── admin-footer.php        # Layout footer + script admin
    │
    ├── 📂 admin/
    │   ├── dashboard.php           # Halaman utama admin
    │   ├── vehicles.php            # Daftar kendaraan
    │   ├── vehicle-form.php        # Form tambah/edit kendaraan
    │   ├── vehicle-detail.php      # Detail kendaraan & dokumen
    │   ├── routes.php              # Daftar rute
    │   ├── route-form.php          # Form tambah/edit rute
    │   ├── schedules.php           # Daftar jadwal
    │   ├── schedule-form.php       # Form tambah/edit jadwal
    │   ├── bookings.php            # Daftar pemesanan
    │   ├── booking-detail.php      # Detail pemesanan
    │   ├── users.php               # Manajemen pengguna
    │   ├── reports.php             # Halaman laporan
    │   └── 📂 pdf/                 # Template cetak PDF
    │       ├── revenue-pdf.php
    │       ├── booking-pdf.php
    │       ├── route-pdf.php
    │       └── vehicle-pdf.php
    │
    ├── 📂 booking/
    │   ├── search.php              # Homepage & form pencarian
    │   ├── results.php             # Hasil pencarian jadwal
    │   ├── seat-select.php         # Pilih kursi interaktif
    │   ├── passenger.php           # Form data penumpang
    │   ├── payment.php             # Halaman pembayaran Midtrans
    │   ├── detail.php              # E-tiket digital
    │   ├── my-bookings.php         # Riwayat pesanan
    │   └── success.php             # Konfirmasi pembayaran sukses
    │
    ├── 📂 auth/
    │   ├── login.php               # Halaman login
    │   └── register.php            # Halaman registrasi
    │
    └── 📂 errors/
        ├── 404.php                 # Halaman tidak ditemukan
        └── 403.php                 # Akses ditolak
```

---

## 🗄️ Skema Database

### Tabel Utama

| Tabel | Kolom Utama | Fungsi |
|-------|------------|--------|
| `users` | id, name, email, phone, password_hash, role, is_active | Akun pengguna & admin |
| `vehicles` | id, name, type, plate_number, **chassis_number**, **engine_number**, capacity, facilities (JSON), **tax_due_date**, **stnk_file**, **bpkb_file**, status | Data armada kendaraan |
| `routes` | id, origin, destination, distance_km, duration_min, base_price, is_active | Rute perjalanan |
| `schedules` | id, vehicle_id, route_id, depart_at, arrive_at, available_seats, price_override, status | Jadwal keberangkatan |
| `bookings` | id, user_id, schedule_id, booking_code, contact_name/phone/email, passenger_count, total_price, status, notes | Data pemesanan |
| `booking_seats` | id, booking_id, seat_number, passenger_name, passenger_id_no | Data kursi per penumpang |
| `payments` | id, booking_id, gateway, gateway_trx_id, payment_type, amount, status, paid_at, expired_at, raw_response | Transaksi pembayaran |
| `seat_locks` | id, schedule_id, seat_number, user_id, locked_until | Kunci kursi sementara |

> **Kolom baru** (bold) ditambahkan via `database/migration_vehicle_docs.sql`

### Relasi Database
```
users ──< bookings >── schedules >── vehicles
                  └──< booking_seats      └──> routes
bookings ──< payments
schedules ──< seat_locks
```

---

## 🌐 API Endpoint

### Public (tanpa auth)
| Method | URL | Fungsi |
|--------|-----|--------|
| `GET` | `/` | Homepage + form pencarian |
| `GET` | `/search` | Hasil pencarian jadwal |
| `GET` | `/login` | Halaman login |
| `POST` | `/login` | Proses login |
| `GET` | `/register` | Halaman registrasi |
| `POST` | `/register` | Proses registrasi |
| `GET` | `/logout` | Logout |
| `POST` | `/payment/callback` | Webhook Midtrans |

### Authenticated (perlu login)
| Method | URL | Fungsi |
|--------|-----|--------|
| `GET` | `/seat/{scheduleId}` | Pilih kursi |
| `GET` | `/api/seats/{scheduleId}` | Status kursi (JSON) |
| `POST` | `/api/seat/lock` | Kunci kursi (JSON) |
| `POST` | `/booking/passenger` | Proses data penumpang |
| `POST` | `/booking/confirm` | Konfirmasi & simpan booking |
| `GET` | `/booking/{id}/payment` | Halaman pembayaran |
| `GET` | `/booking/{id}` | Detail booking / e-tiket |
| `GET` | `/my-bookings` | Riwayat pesanan |

### Admin (perlu role admin)
| Method | URL | Fungsi |
|--------|-----|--------|
| `GET` | `/admin` | Dashboard |
| `GET/POST` | `/admin/vehicles/create` | Tambah kendaraan |
| `GET/POST` | `/admin/vehicles/{id}/edit` | Edit kendaraan |
| `GET` | `/admin/vehicles/{id}` | Detail kendaraan |
| `GET/POST` | `/admin/routes/create` | Tambah rute |
| `GET/POST` | `/admin/schedules/create` | Tambah jadwal |
| `GET` | `/admin/bookings` | Daftar booking |
| `GET` | `/admin/bookings/{id}` | Detail booking |
| `POST` | `/admin/bookings/{id}/status` | Update status booking |
| `GET` | `/admin/users` | Manajemen pengguna |
| `GET` | `/admin/reports` | Laporan & analitik |
| `GET` | `/admin/reports/pdf/revenue` | Export PDF pendapatan |
| `GET` | `/admin/reports/pdf/booking` | Export PDF booking |

---

## 🚀 Cara Instalasi

### Prasyarat
- XAMPP / Laragon (PHP 8.1+, MySQL/MariaDB, Apache)
- Browser modern (Chrome, Firefox, Edge)

### Langkah-langkah

#### 1. Clone / Ekstrak Project
```bash
# Ekstrak ZIP ke folder htdocs
C:\xampp\htdocs\travel-app-output\
```

#### 2. Buat Database
```sql
CREATE DATABASE travel_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 3. Import SQL
Jalankan **berurutan** di phpMyAdmin:
```
1. database/schema.sql
2. database/migration_vehicle_docs.sql
```

#### 4. Konfigurasi Environment
Salin `.env.example` menjadi `.env` dan sesuaikan:
```env
APP_NAME="Nama Travel Anda"
APP_URL=http://localhost/travel-app-output

DB_HOST=localhost
DB_NAME=travel_app
DB_USER=root
DB_PASS=

# Daftar di https://dashboard.midtrans.com
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_IS_PRODUCTION=false
```

#### 5. Aktifkan mod_rewrite (XAMPP)
Edit `C:\xampp\apache\conf\httpd.conf`:
```apache
# Pastikan baris ini aktif (tidak ada # di depan)
LoadModule rewrite_module modules/mod_rewrite.so

# Cari blok <Directory "C:/xampp/htdocs"> dan ubah:
AllowOverride All   # (dari AllowOverride None)
```
Lalu **restart Apache**.

#### 6. Akses Aplikasi
```
http://localhost/travel-app-output/
```

### Akun Admin Default
Tambahkan akun admin via phpMyAdmin atau command line MySQL:
```sql
INSERT INTO users (name, email, phone, password_hash, role, is_active, created_at)
VALUES (
  'Admin',
  'admin@travel.com',
  '08123456789',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'admin',
  1,
  NOW()
);
-- Password default: password
```

---

## 🔧 Konfigurasi Lanjutan

### Midtrans Payment Gateway
1. Daftar di [dashboard.midtrans.com](https://dashboard.midtrans.com)
2. Ambil **Client Key** dan **Server Key** dari menu *Settings → Access Keys*
3. Masukkan ke file `.env`
4. Untuk testing gunakan mode Sandbox (`MIDTRANS_IS_PRODUCTION=false`)
5. Metode pembayaran yang didukung:
   - Transfer Bank Virtual Account (BCA, BNI, BRI, Mandiri, Permata)
   - E-Wallet (GoPay, OVO, Dana, ShopeePay)
   - QRIS
   - Kartu Kredit/Debit
---

## 📸 Halaman-halaman Utama

| Halaman | URL | Deskripsi |
|---------|-----|-----------|
| **Beranda** | `/` | Pencarian jadwal & rute populer |
| **Hasil Pencarian** | `/search` | Daftar jadwal dengan detail armada |
| **Pilih Kursi** | `/seat/{id}` | Denah kursi interaktif real-time |
| **Data Penumpang** | `/booking/passenger` | Form data kontak & penumpang |
| **Pembayaran** | `/booking/{id}/payment` | Midtrans Snap popup |
| **E-Tiket** | `/booking/{id}` | Tiket digital yang bisa dicetak |
| **Dashboard Admin** | `/admin` | Ringkasan statistik & peringatan |
| **Kendaraan Admin** | `/admin/vehicles` | CRUD + dokumen + pajak kendaraan |
| **Laporan** | `/admin/reports` | Grafik & tabel laporan + export PDF |

---

## 👥 Role & Hak Akses

| Role | Hak Akses |
|------|----------|
| **Guest** (belum login) | Lihat beranda, cari jadwal, login, register |
| **User** (sudah login) | Semua guest + pilih kursi, pesan tiket, bayar, lihat e-tiket |
| **Admin** | Semua user + akses penuh panel admin |

---

## 📦 Dependency

Aplikasi ini **tidak menggunakan Composer** dan **tidak membutuhkan instalasi package apapun**. Semua library eksternal di-load via CDN:

| Library | Versi | Sumber |
|---------|-------|--------|
| Font Awesome | 6.5.0 | cdnjs.cloudflare.com |
| Google Fonts | — | fonts.googleapis.com |
| Midtrans Snap.js | — | app.sandbox.midtrans.com |

---

## 🤝 Kontribusi

Pull request sangat diterima. Untuk perubahan besar, buka issue terlebih dahulu.

1. Fork repository ini
2. Buat branch fitur: `git checkout -b fitur/nama-fitur`
3. Commit: `git commit -m 'Tambah fitur X'`
4. Push: `git push origin fitur/nama-fitur`
5. Buat Pull Request

---

## 📝 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 📞 Dukungan

Jika mengalami masalah instalasi atau bug, silakan:
- Buka **Issues** di repository ini
- Cek file **INSTALL.md** untuk panduan lengkap
- Pastikan `mod_rewrite` Apache sudah aktif dan `AllowOverride All` sudah dikonfigurasi

---

<div align="center">

Dibuat dengan ❤️ menggunakan **PHP Native MVC**

**Falles Travel** — *Solusi Perjalanan Bus & Travel Digital*

</div>
