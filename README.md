# 🚌 Falles Travel — Sistem Manajemen Agen Travel

Aplikasi web manajemen agen travel berbasis PHP native (tanpa framework) untuk mengelola pemesanan tiket, armada kendaraan, jadwal keberangkatan, pengeluaran operasional, dan laporan keuangan secara terpadu.

---

## 📋 Daftar Isi

- [Tentang Aplikasi](#tentang-aplikasi)
- [Fitur Lengkap](#fitur-lengkap)
- [Teknologi](#teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Struktur Direktori](#struktur-direktori)
- [Struktur Database](#struktur-database)
- [URL & Routing](#url--routing)
- [Akun Default](#akun-default)
- [Integrasi Pihak Ketiga](#integrasi-pihak-ketiga)

---

## Tentang Aplikasi

Falles Travel adalah sistem informasi agen travel yang dirancang untuk melayani pemesanan tiket perjalanan antarkota. Aplikasi ini memiliki dua sisi utama:

- **Sisi Penumpang** — pencarian jadwal, pemilihan kursi interaktif, pengisian data penumpang, dan pembayaran online via Midtrans.
- **Sisi Admin** — dashboard operasional lengkap untuk mengelola kendaraan, rute, jadwal, pemesanan, pengeluaran, supir, dan laporan keuangan dengan ekspor PDF.

Aplikasi dibangun dengan arsitektur MVC ringan menggunakan PHP 8.1+ murni tanpa framework eksternal, sehingga mudah di-deploy di shared hosting maupun XAMPP.

---

## Fitur Lengkap

### 👤 Fitur Penumpang (User)

#### Autentikasi
- Registrasi akun baru dengan validasi email unik
- Login dengan email dan password (hash bcrypt)
- Proteksi CSRF pada semua form

#### Pencarian & Pemesanan Tiket
- Pencarian jadwal berdasarkan **asal**, **tujuan**, dan **tanggal keberangkatan**
- Tampilan hasil pencarian dengan detail harga, waktu tempuh, dan kapasitas kursi tersisa
- Estimasi jarak dan durasi perjalanan via **Google Maps Distance Matrix API** (dengan cache 24 jam di database)
- **Pemilihan kursi secara interaktif** — denah kursi real-time dengan sistem *seat locking* sementara (mencegah double-booking saat pengguna sedang memilih)
- Pengisian data penumpang per kursi (nama & nomor identitas)
- Pengisian data kontak pemesan (nama, telepon, email)
- Halaman konfirmasi pemesanan sebelum pembayaran

#### Pembayaran
- Integrasi **Midtrans SNAP** — mendukung transfer bank, virtual account, kartu kredit, dompet digital, dll.
- Mode sandbox untuk pengujian tanpa transaksi nyata
- Halaman status pembayaran (sukses / gagal / pending)

#### Manajemen Pesanan
- Halaman **Riwayat Pesanan** (`/my-bookings`) menampilkan semua histori booking
- Detail setiap booking: kode booking, jadwal, kursi, status, dan total harga
- Status booking: `pending` → `paid` → `completed` / `cancelled`

---

### 🔧 Fitur Admin

#### Dashboard Utama
- Ringkasan statistik: total pengguna, kendaraan, jadwal, booking (pending/paid/cancelled)
- Pendapatan terkini
- Notifikasi **peringatan pajak kendaraan** yang mendekati jatuh tempo (30 hari ke depan)
- Tabel 10 pemesanan terbaru

#### Manajemen Kendaraan
- CRUD kendaraan (Mobil, Minibus, Bus)
- Data lengkap: nama, plat nomor, **nomor rangka**, **nomor mesin**, kapasitas penumpang
- Fasilitas kendaraan: AC, WiFi, USB Charger, TV
- **Upload dokumen**: STNK dan BPKB (file gambar)
- Pencatatan **tanggal jatuh tempo pajak** dengan peringatan otomatis
- Status kendaraan: aktif / dalam perawatan / tidak aktif
- Halaman detail kendaraan dengan histori servis terintegrasi
- Ekspor daftar kendaraan ke **PDF**

#### Manajemen Rute
- CRUD rute perjalanan (asal → tujuan)
- Pencatatan jarak (km), estimasi durasi, dan harga dasar tiket
- Dukungan data polyline untuk peta
- Ekspor daftar rute ke **PDF**

#### Manajemen Jadwal
- Pembuatan jadwal keberangkatan: kendaraan, rute, waktu berangkat & tiba
- Pengaturan jumlah kursi tersedia dan opsi override harga
- Status jadwal: aktif / dibatalkan / selesai

#### Manajemen Pemesanan
- Tabel semua pemesanan dengan filter status
- Detail booking: informasi penumpang, kursi, pembayaran
- Pembaruan status pemesanan oleh admin
- Cetak/ekspor detail booking ke **PDF**
- Ekspor laporan pemesanan ke **PDF**

#### Manajemen Pengguna
- Daftar semua akun pengguna terdaftar
- Informasi: nama, email, telepon, role, status aktif

---

### 💰 Modul Pengeluaran Operasional

#### Dashboard Pengeluaran
- Grafik pengeluaran bulanan: BBM, servis kendaraan, dan gaji supir
- Ringkasan total pengeluaran bulan ini per kategori
- **Alert otomatis**: servis kendaraan yang jadwalnya dalam 14 hari ke depan
- **Alert SIM**: notifikasi supir yang masa berlaku SIM-nya habis dalam 30 hari

#### Manajemen Supir
- CRUD data supir: nama, NIK, telepon, alamat
- Pencatatan nomor SIM dan **tanggal kadaluarsa SIM**
- Gaji pokok supir
- Status supir: aktif / tidak aktif
- Tanggal bergabung

#### Pengeluaran BBM (Bahan Bakar)
- Pencatatan pengeluaran BBM per trip
- Relasi opsional ke jadwal keberangkatan dan data supir
- Input: tanggal, kendaraan, liter BBM, total harga, odometer
- Rute perjalanan (asal–tujuan)
- Ekspor laporan BBM ke **PDF**

#### Servis & Perawatan Kendaraan
- Pencatatan histori servis per kendaraan
- Kategori servis: oli, tune-up, ban, rem, AC, mesin, bodi, kaki-kaki, lainnya
- Data lengkap: tanggal, bengkel, biaya, odometer saat servis
- Penjadwalan servis berikutnya (berdasarkan km atau tanggal)
- Upload bukti/struk servis
- Status: selesai / dalam servis / dijadwalkan
- Ekspor laporan servis ke **PDF**

#### Gaji Supir
- Pencatatan pembayaran gaji per periode (bulan/tahun)
- Komponen gaji: gaji pokok + bonus + potongan
- Catatan / keterangan pembayaran
- Status pembayaran: lunas / belum dibayar
- Ekspor slip/laporan gaji ke **PDF**

#### Laporan Pengeluaran Gabungan
- Ringkasan total seluruh pengeluaran (BBM + servis + gaji) per periode
- Ekspor laporan ringkasan ke **PDF**

---

### 📊 Laporan & Analitik

- Laporan **pendapatan** (revenue) berdasarkan periode
- Laporan **pemesanan** dengan breakdown status
- Laporan **per rute** — performa tiap rute perjalanan
- Laporan **per kendaraan** — pendapatan dan utilisasi armada
- Semua laporan dapat diekspor ke **PDF** langsung dari browser

---

## Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | PHP 8.1+ (MVC Native) |
| Database | MySQL 5.7+ / MariaDB 10.4+ |
| Frontend | HTML5, CSS3, JavaScript (Vanilla) |
| Web Server | Apache dengan `mod_rewrite` |
| Payment Gateway | Midtrans SNAP |
| Maps API | Google Maps Distance Matrix API |
| PDF Generation | PHP (render langsung via browser print) |
| Keamanan | CSRF Token, Password Bcrypt, Middleware Auth |

---

## Persyaratan Sistem

- **PHP** 8.1 atau lebih baru
- **MySQL** 5.7+ atau **MariaDB** 10.4+
- **Apache** dengan modul `mod_rewrite` aktif
- **XAMPP**, **Laragon**, atau web server setara
- Browser modern (Chrome, Firefox, Edge, Safari)

---

## Instalasi

### 1. Letakkan Folder Project

Ekstrak ZIP ke folder `htdocs` XAMPP (atau `www` Laragon):

```
C:\xampp\htdocs\travel-app-output\
```

### 2. Buat Database

Buka **phpMyAdmin** → buat database baru bernama:

```
travel_app
```

### 3. Import SQL

Jalankan file SQL berikut **secara berurutan** di phpMyAdmin:

1. `database/schema.sql` — skema utama + data seed awal
2. `database/migration_expenses.sql` — tabel modul pengeluaran (supir, BBM, servis, gaji)
3. `database/migration_vehicle_docs.sql` — kolom tambahan dokumen kendaraan (STNK, BPKB, nomor rangka/mesin)
4. `database/schema_fix.sql` — perbaikan/patch skema tambahan

### 4. Konfigurasi `.env`

Salin `.env.example` menjadi `.env` dan sesuaikan:

```env
APP_NAME="Falles Travel"
APP_URL=http://localhost/travel-app-output
APP_ENV=development
APP_DEBUG=true
APP_KEY=isi-dengan-random-string-32-karakter

DB_HOST=localhost
DB_PORT=3306
DB_NAME=travel_app
DB_USER=root
DB_PASS=

GOOGLE_MAPS_KEY=YOUR_GOOGLE_MAPS_API_KEY_HERE

MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_IS_PRODUCTION=false
```

### 5. Aktifkan `mod_rewrite` Apache

Edit `C:\xampp\apache\conf\httpd.conf`:

1. Pastikan baris berikut tidak ter-comment:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
2. Cari blok `<Directory "C:/xampp/htdocs">` dan ubah:
   ```
   AllowOverride None  →  AllowOverride All
   ```
3. Restart Apache.

### 6. Akses Aplikasi

```
http://localhost/travel-app-output/
```

---

## Konfigurasi

### File Konfigurasi

| File | Keterangan |
|---|---|
| `.env` | Konfigurasi utama: database, URL, API key |
| `config/app.php` | Nama aplikasi, timezone, mode debug |
| `config/database.php` | Koneksi database (membaca dari `.env`) |
| `config/payment.php` | Konfigurasi Midtrans & Google Maps |

### Konfigurasi Production

Saat deploy ke server production, ubah di `.env`:

```env
APP_ENV=production
APP_DEBUG=false
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_CLIENT_KEY=Mid-client-xxxx   # key production
MIDTRANS_SERVER_KEY=Mid-server-xxxx   # key production
```

---

## Struktur Direktori

```
travel-app-output/
├── app/
│   ├── Controllers/          # Logic handler request
│   │   ├── AdminController.php       # Dashboard, kendaraan, rute, jadwal, user
│   │   ├── AuthController.php        # Login & register
│   │   ├── BookingController.php     # Pencarian, pemesanan, seat lock
│   │   ├── ExpenseController.php     # BBM, servis, supir, gaji
│   │   ├── PaymentController.php     # Integrasi Midtrans
│   │   └── ReportController.php      # Laporan & ekspor PDF
│   ├── Core/
│   │   ├── Database.php      # PDO wrapper (singleton)
│   │   ├── Request.php       # Input sanitasi & validasi
│   │   ├── Response.php      # Redirect, JSON, dll.
│   │   └── Router.php        # URL routing
│   ├── Middleware/
│   │   ├── AuthMiddleware.php        # Proteksi halaman login
│   │   ├── AdminMiddleware.php       # Proteksi halaman admin
│   │   ├── CsrfMiddleware.php        # Validasi CSRF token
│   │   └── Middleware.php            # Base middleware
│   ├── Models/               # Query database per entitas
│   │   ├── Booking.php, Driver.php, DriverSalary.php
│   │   ├── FuelExpense.php, Payment.php, Report.php
│   │   ├── Route.php, Schedule.php, SeatLock.php
│   │   ├── ServiceRecord.php, User.php, Vehicle.php
│   └── Services/
│       ├── MapsService.php           # Google Maps API + cache
│       ├── PaymentService.php        # Midtrans SNAP
│       └── SeatService.php           # Logika seat lock
├── config/                   # Konfigurasi aplikasi
├── database/                 # File migrasi SQL
├── public/
│   ├── assets/css/app.css    # Stylesheet utama
│   ├── assets/img/           # Gambar statis
│   ├── assets/uploads/       # Upload dokumen kendaraan
│   └── index.php             # Entry point publik
├── views/
│   ├── admin/                # Template halaman admin
│   │   ├── expenses/         # Halaman modul pengeluaran
│   │   └── pdf/              # Template cetak PDF admin
│   ├── auth/                 # Login & register
│   ├── booking/              # Pencarian, kursi, pembayaran
│   ├── errors/               # Halaman 403 & 404
│   └── layouts/              # Layout admin (sidebar, header)
├── .env                      # Konfigurasi environment
├── .htaccess                 # Redirect semua ke index.php
├── index.php                 # Bootstrap aplikasi
└── INSTALL.md                # Panduan instalasi singkat
```

---

## Struktur Database

| Tabel | Keterangan |
|---|---|
| `users` | Akun pengguna (penumpang & admin) |
| `vehicles` | Data armada kendaraan |
| `routes` | Rute perjalanan asal–tujuan |
| `schedules` | Jadwal keberangkatan |
| `bookings` | Data pemesanan tiket |
| `booking_seats` | Detail kursi per penumpang |
| `seat_locks` | Kunci sementara kursi saat proses pemesanan |
| `payments` | Rekam pembayaran via Midtrans |
| `route_cache` | Cache hasil Google Maps API |
| `drivers` | Data supir |
| `fuel_expenses` | Pengeluaran BBM per trip |
| `service_records` | Histori servis kendaraan |
| `driver_salaries` | Pembayaran gaji supir |

---

## URL & Routing

### Halaman Penumpang

| URL | Keterangan |
|---|---|
| `/` | Halaman pencarian tiket |
| `/login` | Login |
| `/register` | Registrasi akun baru |
| `/search` | Hasil pencarian jadwal |
| `/seat/{scheduleId}` | Pilih kursi |
| `/passenger/{scheduleId}` | Isi data penumpang |
| `/booking/payment/{id}` | Halaman pembayaran |
| `/booking/success/{id}` | Konfirmasi pembayaran berhasil |
| `/my-bookings` | Riwayat pesanan saya |
| `/booking/{id}` | Detail pesanan |

### Halaman Admin

| URL | Keterangan |
|---|---|
| `/admin` | Dashboard admin |
| `/admin/vehicles` | Daftar kendaraan |
| `/admin/vehicles/create` | Tambah kendaraan |
| `/admin/vehicles/{id}` | Detail kendaraan |
| `/admin/routes` | Daftar rute |
| `/admin/schedules` | Daftar jadwal |
| `/admin/bookings` | Daftar pemesanan |
| `/admin/bookings/{id}` | Detail pemesanan |
| `/admin/users` | Daftar pengguna |
| `/admin/reports` | Laporan & analitik |
| `/admin/expenses` | Dashboard pengeluaran |
| `/admin/expenses/drivers` | Daftar supir |
| `/admin/expenses/fuel` | Pengeluaran BBM |
| `/admin/expenses/service` | Servis kendaraan |
| `/admin/expenses/salaries` | Gaji supir |

---

## Akun Default

Setelah import `schema.sql`, akun admin tersedia:

| Field | Value |
|---|---|
| Email | `admin@travelku.id` |
| Password | `password` |
| Role | `admin` |

> ⚠️ **Penting:** Ganti password admin segera setelah instalasi pertama.

Untuk menambah akun admin secara manual via SQL:

```sql
INSERT INTO users (name, email, phone, password_hash, role, is_active)
VALUES (
  'Admin Baru',
  'admin2@travel.com',
  '08123456789',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
  'admin',
  1
);
```

---

## Integrasi Pihak Ketiga

### Midtrans (Payment Gateway)

1. Daftar di [https://midtrans.com](https://midtrans.com)
2. Dapatkan **Client Key** dan **Server Key** dari dashboard Midtrans
3. Isi di `.env`:
   ```env
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
   MIDTRANS_IS_PRODUCTION=false  # ubah ke true di production
   ```

Jika key tidak diisi, aplikasi otomatis menggunakan token dummy untuk pengujian.

## Keamanan

- Password di-hash menggunakan **bcrypt** (`password_hash` PHP)
- Semua form dilindungi **CSRF Token**
- Input pengguna di-sanitasi sebelum diproses
- Halaman admin dilindungi middleware autentikasi & role
- File upload dibatasi oleh `.htaccess` agar tidak dapat dieksekusi langsung
- Mode debug (`APP_DEBUG`) harus dimatikan di environment production

---

*Dikembangkan untuk kebutuhan manajemen operasional agen travel lokal.*
