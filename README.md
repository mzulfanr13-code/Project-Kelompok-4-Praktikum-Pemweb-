# The Amazing World of Gumball Fan Site

Fan site tidak resmi berbasis web untuk serial animasi *The Amazing World of Gumball* karya Ben Bocquelet. Aplikasi ini dibangun dengan PHP murni dan MySQL, menyediakan ensiklopedi karakter, panduan episode, serta sistem pengiriman dan moderasi quote dari para karakter serial tersebut.

---

## Daftar Isi

- [Gambaran Umum](#gambaran-umum)
- [Fitur](#fitur)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Struktur Direktori](#struktur-direktori)
- [Skema Database](#skema-database)
- [Sistem Autentikasi dan Peran](#sistem-autentikasi-dan-peran)
- [Modul Aplikasi](#modul-aplikasi)
- [Cara Instalasi](#cara-instalasi)
- [Akun Default](#akun-default)
- [Desain dan Antarmuka](#desain-dan-antarmuka)
- [Catatan Tambahan](#catatan-tambahan)

---

## Gambaran Umum

Aplikasi ini adalah fan portal multi-halaman yang memungkinkan pengguna terdaftar untuk menelusuri data karakter dan episode, serta mengirimkan quote favorit mereka. Admin memiliki kendali penuh atas konten melalui operasi CRUD dan sistem moderasi quote.

Proyek ini berjalan di atas server lokal (direkomendasikan: Laragon dengan PHP 8 dan MySQL) dan tidak bergantung pada framework PHP maupun CSS apapun.

---

## Fitur

**Untuk semua pengunjung:**
- Halaman beranda bertema hero dengan latar belakang bergambar dan deskripsi singkat serial
- Ensiklopedi karakter — menampilkan semua karakter dalam bentuk grid kartu beserta foto, nama, spesies, dan halaman detail
- Panduan episode — daftar episode dikelompokkan per season dengan badge format `S01E01`
- Quotes Wall — kumpulan quote yang telah disetujui admin, beserta informasi karakter, episode, dan pengirim

**Untuk pengguna terdaftar:**
- Registrasi akun baru dengan validasi input dan pengecekan duplikasi email
- Login dan logout berbasis sesi PHP
- Halaman profil pribadi: informasi akun, statistik quote (total, approved, pending), dan daftar 5 quote terbaru
- Pengiriman quote baru dengan pemilihan karakter dan episode, serta batas 500 karakter
- Quote yang dikirim user biasa akan berstatus `pending` hingga disetujui admin

**Untuk admin:**
- Semua fitur pengguna biasa, dengan quote yang dikirim langsung berstatus `approved`
- CRUD penuh untuk karakter: tambah, lihat detail, edit, dan hapus
- CRUD penuh untuk episode: tambah, lihat detail, edit, dan hapus
- Moderasi quote: approve, reject, edit, dan hapus; dengan tab filter (Approved / Pending / Rejected / Semua)

---

## Teknologi yang Digunakan

| Komponen       | Detail                                      |
|----------------|---------------------------------------------|
| Backend        | PHP 8 (vanilla, tanpa framework)            |
| Database       | MySQL dengan koneksi PDO                    |
| Frontend       | HTML5, CSS3 (vanilla, tanpa framework)      |
| Tipografi      | Google Fonts — Fredoka One, Nunito          |
| Ikon           | Font Awesome 6 Free (via CDN)               |
| Server Lokal   | Laragon (Apache + MySQL) — direkomendasikan |

---

## Struktur Direktori

```
gumball/
├── assets/
│   ├── css/
│   │   └── style.css          # Stylesheet utama
│   └── images/
│       ├── tawog-logo.png
│       ├── bg-home.jpg
│       ├── bg-login.jpg
│       ├── gumball.png
│       ├── darwin.png
│       ├── anais.png
│       ├── richard.png
│       └── nicole.png
├── characters/
│   ├── index.php              # Grid semua karakter
│   ├── detail.php             # Halaman detail karakter
│   ├── create.php             # Form tambah karakter (admin)
│   ├── edit.php               # Form edit karakter (admin)
│   └── delete.php             # Hapus karakter (admin)
├── episodes/
│   ├── index.php              # Daftar episode per season
│   ├── detail.php             # Halaman detail episode
│   ├── create.php             # Form tambah episode (admin)
│   ├── edit.php               # Form edit episode (admin)
│   └── delete.php             # Hapus episode (admin)
├── quotes/
│   ├── index.php              # Quotes Wall dengan filter
│   ├── create.php             # Form submit quote (login diperlukan)
│   ├── edit.php               # Edit quote (admin)
│   ├── approve.php            # Approve quote (admin)
│   ├── reject.php             # Reject quote (admin)
│   └── delete.php             # Hapus quote (admin)
├── config/
│   └── db.php                 # Konfigurasi koneksi PDO ke MySQL
├── database/
│   └── schema.sql             # DDL tabel dan data awal (seed)
├── includes/
│   ├── header.php             # DOCTYPE, head, navbar
│   ├── footer.php             # Tag penutup dan copyright
│   └── session.php            # Fungsi sesi: require_login, require_admin, is_logged_in, is_admin
├── index.php                  # Halaman beranda (hero section)
├── login.php                  # Halaman login
├── register.php               # Halaman registrasi
├── logout.php                 # Hapus sesi dan redirect ke login
└── profile.php                # Halaman profil pengguna
```

---

## Skema Database

Database bernama `gumball_db` terdiri dari empat tabel:

### Tabel `users`

| Kolom        | Tipe                        | Keterangan                      |
|--------------|-----------------------------|---------------------------------|
| `id`         | INT AUTO_INCREMENT PK       |                                 |
| `username`   | VARCHAR(50) NOT NULL        |                                 |
| `email`      | VARCHAR(100) UNIQUE NOT NULL|                                 |
| `password`   | VARCHAR(255) NOT NULL       | Di-hash dengan `PASSWORD_BCRYPT`|
| `role`       | ENUM('admin','user')        | Default: `user`                 |
| `created_at` | TIMESTAMP                   | Default: waktu saat ini         |

### Tabel `characters`

| Kolom              | Tipe             | Keterangan                        |
|--------------------|------------------|-----------------------------------|
| `id`               | INT PK           |                                   |
| `name`             | VARCHAR(100)     |                                   |
| `species`          | VARCHAR(100)     |                                   |
| `description`      | TEXT             |                                   |
| `image_url`        | VARCHAR(255)     | Path relatif ke folder `assets/`  |
| `first_appearance` | VARCHAR(100)     | Judul episode kemunculan pertama  |
| `created_at`       | TIMESTAMP        |                                   |

### Tabel `episodes`

| Kolom            | Tipe         | Keterangan |
|------------------|--------------|------------|
| `id`             | INT PK       |            |
| `title`          | VARCHAR(150) |            |
| `season`         | INT          |            |
| `episode_number` | INT          |            |
| `synopsis`       | TEXT         |            |
| `air_date`       | DATE         |            |
| `created_at`     | TIMESTAMP    |            |

### Tabel `quotes`

| Kolom          | Tipe                                  | Keterangan                                            |
|----------------|---------------------------------------|-------------------------------------------------------|
| `id`           | INT PK                                |                                                       |
| `character_id` | INT FK -> `characters(id)`            | CASCADE on delete                                     |
| `episode_id`   | INT FK -> `episodes(id)`              | CASCADE on delete                                     |
| `submitted_by` | INT FK -> `users(id)`                 | CASCADE on delete                                     |
| `content`      | TEXT                                  | Maksimal 500 karakter (diterapkan di sisi aplikasi)   |
| `status`       | ENUM('pending','approved','rejected') | Default: `pending`                                    |
| `created_at`   | TIMESTAMP                             |                                                       |

Relasi antar tabel bersifat `ON DELETE CASCADE`, artinya penghapusan karakter, episode, atau pengguna akan secara otomatis menghapus quote terkait.

---

## Sistem Autentikasi dan Peran

Autentikasi dikelola melalui sesi PHP yang diinisialisasi di `includes/session.php`. File ini menyediakan empat fungsi utama:

- `is_logged_in()` — mengembalikan `true` jika `$_SESSION['user_id']` terdefinisi
- `is_admin()` — mengembalikan `true` jika role sesi adalah `admin`
- `require_login()` — redirect ke `login.php` bila pengguna belum login
- `require_admin()` — memanggil `require_login()` terlebih dahulu, kemudian redirect ke `index.php` bila role bukan admin

Setiap halaman yang memerlukan autentikasi memanggil salah satu dari dua fungsi tersebut di baris paling awal. Password disimpan dalam bentuk hash menggunakan `password_hash()` dengan algoritma `PASSWORD_BCRYPT` dan diverifikasi dengan `password_verify()`.

Logout dilakukan di `logout.php` dengan memanggil `session_unset()` dan `session_destroy()` sebelum melakukan redirect ke halaman login.

---

## Modul Aplikasi

### Beranda (`index.php`)

Menampilkan halaman hero bertema dengan latar belakang gambar (`bg-home.jpg`), logo serial, judul sambutan, dan paragraf deskripsi singkat. Tidak memerlukan login.

### Karakter (`characters/`)

Halaman indeks menampilkan semua karakter dalam grid kartu yang dapat diklik. Setiap kartu menampilkan foto dan nama karakter. Admin melihat tombol tambah karakter (`+`) di pojok grid.

Halaman detail menampilkan foto besar, spesies, episode kemunculan pertama, tanggal ditambahkan, dan deskripsi. Admin melihat tombol edit dan hapus di halaman detail.

Penghapusan karakter dilakukan melalui `delete.php` yang menerima parameter `id` via GET, mengambil nama karakter untuk pesan konfirmasi flash, lalu mengeksekusi `DELETE` dan redirect ke indeks.

### Episode (`episodes/`)

Indeks episode mengelompokkan semua episode berdasarkan season menggunakan array `$byseason`. Setiap episode ditampilkan dalam kartu berisi badge season-episode, judul, tanggal tayang, dan cuplikan sinopsis (dipotong 100 karakter).

### Quotes (`quotes/`)

Halaman indeks default hanya menampilkan quote berstatus `approved`. Admin melihat tab filter tambahan untuk status `pending`, `rejected`, dan `all`. Setiap quote menampilkan isi kutipan, nama karakter, judul episode, dan pengirim.

Saat pengguna biasa mengirim quote, status diset ke `pending`. Saat admin yang mengirim, status langsung `approved`. Formulir pengiriman memiliki penghitung karakter secara real-time yang berubah warna merah saat mendekati batas 500 karakter.

### Profil (`profile.php`)

Menampilkan informasi akun (ID, username, email, role, tanggal bergabung) dan statistik quote (total, approved, pending). Bagian bawah menampilkan 5 quote terbaru pengguna dengan status masing-masing. Halaman ini hanya dapat diakses oleh pengguna yang sudah login.

---

## Cara Instalasi

**Prasyarat:** PHP 8.x, MySQL 5.7 atau lebih baru, Apache dengan `mod_rewrite` aktif (Laragon direkomendasikan untuk pengembangan lokal di Windows).

1. Salin seluruh folder proyek ke dalam direktori web root. Dengan Laragon, letakkan di dalam `C:/laragon/www/`, misalnya sebagai `C:/laragon/www/gumball/`.

2. Buat database baru dengan nama `gumball_db` melalui phpMyAdmin atau klien MySQL.

3. Import file `database/schema.sql` ke database tersebut. File ini akan membuat semua tabel dan mengisi data awal (karakter, episode, quote, serta dua akun pengguna).

4. Sesuaikan konfigurasi koneksi di `config/db.php` bila nama database, username, atau password MySQL berbeda dari nilai default:

   ```php
   $host   = "localhost";
   $dbname = "gumball_db";
   $user   = "root";
   $pass   = "";
   ```

5. Akses aplikasi melalui browser di `http://localhost/gumball/` atau sesuai nama folder yang digunakan.

---

## Akun Default

Dua akun berikut tersedia setelah menjalankan `schema.sql`:

| Role  | Username | Password  | Email               |
|-------|----------|-----------|---------------------|
| Admin | admin    | admin123  | admin@gumball.com   |
| User  | user     | user123   | user@gmail.com      |

Disarankan untuk mengubah password akun admin setelah instalasi pertama.

---

## Desain dan Antarmuka

Antarmuka menggunakan dua tipografi dari Google Fonts: **Fredoka One** untuk judul dan elemen display, serta **Nunito** untuk teks isi. Palet warna utama bertema biru cerah (`#4EA8DE`) dengan aksen kuning (`#F4A529`) yang mengacu pada estetika visual serial Gumball.

Semua komponen UI (kartu karakter, kartu episode, kartu quote, formulir, navbar, profil) didefinisikan dalam satu file stylesheet (`assets/css/style.css`, 975 baris) dengan variabel CSS untuk konsistensi warna, radius, bayangan, dan transisi di seluruh halaman.

Ikon antarmuka menggunakan Font Awesome 6 Free yang dimuat melalui CDN Cloudflare.

---

## Catatan Tambahan

- Proyek ini bersifat fan-made dan tidak berafiliasi dengan Cartoon Network maupun pencipta serial.
- Tidak ada mekanisme upload gambar karakter; URL gambar disimpan sebagai path teks relatif di kolom `image_url`.
- Tidak ada fitur pencarian atau pagination pada versi ini.
- File CSS ditulis tanpa preprocessor dan tanpa framework seperti Bootstrap atau Tailwind.