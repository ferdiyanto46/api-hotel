# 🏨 Hotel Booking REST API

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/Lumen-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/JWT-Auth-000000?style=for-the-badge&logo=jsonwebtokens&logoColor=white" />
  <img src="https://img.shields.io/badge/Midtrans-Payment-003B6F?style=for-the-badge&logo=stripe&logoColor=white" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" />
</p>

RESTful API untuk sistem manajemen dan pemesanan hotel, dibangun menggunakan **Lumen PHP Framework** dengan autentikasi berbasis **JWT** dan integrasi pembayaran **Midtrans**.

---

## 📋 Daftar Isi

- [Fitur](#-fitur)
- [Tech Stack](#-tech-stack)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Dokumentasi API](#-dokumentasi-api)
- [Role & Hak Akses](#-role--hak-akses)
- [Struktur Database](#-struktur-database)
- [Struktur Proyek](#-struktur-proyek)
- [Lisensi](#-lisensi)

---

## ✨ Fitur

- 🔐 **Autentikasi JWT** — Register, Login, dan proteksi endpoint berbasis token
- 👥 **Role-Based Access Control** — Super Admin, Admin, dan Customer
- 🏨 **Manajemen Hotel** — CRUD hotel dengan fitur pencarian dan filter
- 🛏️ **Manajemen Kamar & Tipe Kamar** — Pengelolaan kamar beserta tipe dan ketersediaan
- 📅 **Sistem Pemesanan** — Checkout, riwayat, dan detail booking
- 💳 **Integrasi Midtrans** — Pembayaran online dengan webhook notifikasi otomatis
- 🔁 **Retry Payment** — Bayar ulang booking yang masih pending
- 📊 **Overview Dashboard** — Ringkasan data hotel untuk Super Admin

---

## 🛠️ Tech Stack

| Teknologi | Versi | Keterangan |
|-----------|-------|------------|
| PHP | ^8.1 | Bahasa pemrograman utama |
| Laravel Lumen | ^10.0 | PHP micro-framework |
| MySQL | 8.0 | Database relasional |
| tymon/jwt-auth | ^2.3 | Autentikasi JWT |
| midtrans/midtrans-php | ^2.6 | Payment gateway |
| PHPUnit | ^10.0 | Testing framework |

---

## ⚙️ Persyaratan Sistem

- **PHP** >= 8.1 (dengan ekstensi: `mbstring`, `openssl`, `pdo`, `pdo_mysql`)
- **Composer** >= 2.x
- **MySQL** >= 8.0
- **Akun Midtrans** (Sandbox untuk development)

---

## 🚀 Instalasi

### 1. Clone Repositori

```bash
git clone https://github.com/ferdiyanto46/api-hotel.git
cd api-hotel
```

### 2. Install Dependensi

```bash
composer install
```

### 3. Salin File Konfigurasi

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Generate JWT Secret

```bash
php artisan jwt:secret
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 7. (Opsional) Jalankan Seeder

```bash
php artisan db:seed
```

---

## 🔧 Konfigurasi

Buka file `.env` dan sesuaikan konfigurasi berikut:

```env
APP_NAME=HotelAPI
APP_ENV=local
APP_KEY=              # Di-generate otomatis oleh php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=Asia/Jakarta

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

# JWT
JWT_SECRET=           # Di-generate otomatis oleh php artisan jwt:secret
JWT_TTL=60            # Token berlaku (dalam menit)

# Midtrans
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false  # Gunakan true untuk production
```

> **Catatan:** Dapatkan Server Key dan Client Key dari [Dashboard Midtrans](https://dashboard.sandbox.midtrans.com).

---

## ▶️ Menjalankan Aplikasi

### Development (Laragon / Built-in Server)

```bash
php -S localhost:8000 -t public
```

atau dengan Laragon, akses otomatis di:

```
http://api-hotel.test
```

---

## 📖 Dokumentasi API

### Base URL
```
http://localhost:8000
```

---

### 🔑 Autentikasi

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| `POST` | `/auth/register` | Registrasi Customer baru | ❌ |
| `POST` | `/auth/login` | Login & dapatkan token JWT | ❌ |
| `POST` | `/auth/register-admin` | Daftarkan Admin baru | ✅ Super Admin |

#### Contoh Request Login

```json
POST /auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

#### Contoh Response

```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGci...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

> Sertakan token di header setiap request yang membutuhkan autentikasi:
> `Authorization: Bearer {token}`

---

### 🏨 Hotel

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| `GET` | `/hotels` | Daftar semua hotel (filter: `?search=&city=`) | ❌ |
| `GET` | `/hotels/{id}` | Detail hotel | ❌ |
| `GET` | `/hotels/overview` | Ringkasan semua hotel | ✅ Super Admin |
| `POST` | `/hotels` | Tambah hotel baru | ✅ Super Admin |
| `PUT` | `/hotels/{id}` | Update hotel | ✅ Admin / Super Admin |
| `DELETE` | `/hotels/{id}` | Hapus hotel | ✅ Admin / Super Admin |

---

### 🛏️ Tipe Kamar

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| `GET` | `/room-types` | Daftar semua tipe kamar | ❌ |
| `GET` | `/room-types/{id}` | Detail tipe kamar | ❌ |
| `POST` | `/room-types` | Tambah tipe kamar | ✅ Admin / Super Admin |
| `PUT` | `/room-types/{id}` | Update tipe kamar | ✅ Admin / Super Admin |
| `DELETE` | `/room-types/{id}` | Hapus tipe kamar | ✅ Admin / Super Admin |

---

### 🚪 Kamar

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| `GET` | `/rooms` | Daftar semua kamar (filter: `?status=available&room_type_id=1`) | ❌ |
| `GET` | `/rooms/{id}` | Detail kamar | ❌ |
| `POST` | `/rooms` | Tambah kamar baru | ✅ Admin / Super Admin |
| `PUT` | `/rooms/{id}` | Update kamar | ✅ Admin / Super Admin |
| `DELETE` | `/rooms/{id}` | Hapus kamar | ✅ Admin / Super Admin |

---

### 📅 Pemesanan

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| `GET` | `/bookings` | Riwayat booking | ✅ Customer |
| `GET` | `/bookings/{id}` | Detail booking | ✅ Customer |
| `POST` | `/bookings/checkout` | Buat booking baru & generate payment | ✅ Customer |
| `POST` | `/bookings/{id}/pay` | Bayar ulang booking pending | ✅ Customer |

---

### 💳 Pembayaran (Midtrans Webhook)

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| `POST` | `/midtrans/notification` | Notifikasi status pembayaran dari Midtrans | ❌ (Midtrans) |

---

### 👤 Manajemen Akun (Super Admin)

| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| `GET` | `/accounts` | Daftar semua akun pengguna | ✅ Super Admin |
| `GET` | `/accounts/{id}` | Detail akun | ✅ Super Admin |
| `PUT` | `/accounts/{id}` | Update akun | ✅ Super Admin |
| `DELETE` | `/accounts/{id}` | Hapus akun | ✅ Super Admin |

---

## 👥 Role & Hak Akses

| Role | Deskripsi |
|------|-----------|
| `super-admin` | Akses penuh: kelola hotel, akun, dan semua data |
| `admin` | Kelola kamar, tipe kamar, dan update hotel |
| `customer` | Buat booking, lihat riwayat, dan lakukan pembayaran |

---

## 🗄️ Struktur Database

```
users         — Data pengguna (id, name, email, password, role, hotel_id)
hotels        — Data hotel (id, name, city, address, description, dst.)
room_types    — Tipe kamar (id, name, hotel_id, price, capacity, dst.)
rooms         — Kamar (id, room_number, hotel_id, room_type_id, status)
bookings      — Pemesanan (id, user_id, room_id, check_in, check_out, status)
payments      — Pembayaran (id, booking_id, amount, status, midtrans_token, dst.)
```

---

## 📁 Struktur Proyek

```
api-hotel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── HotelController.php
│   │   │   ├── RoomController.php
│   │   │   ├── RoomTypeController.php
│   │   │   ├── BookingController.php
│   │   │   └── AccountController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Hotel.php
│   │   ├── Room.php
│   │   ├── RoomType.php
│   │   ├── Booking.php
│   │   └── Payment.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── web.php
├── .env.example
├── composer.json
└── README.md
```

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

---

<p align="center">
  Dibuat dengan ❤️ menggunakan <a href="https://lumen.laravel.com">Lumen PHP Framework</a>
</p>
