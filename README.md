# The Amazing World of Gumball Theme

## 📖 Deskripsi Aplikasi

The Amazing World of Gumball Theme adalah website berbasis PHP Native yang berfungsi sebagai platform informasi dan dokumentasi mengenai Gumbal, ritual, artefak mistis, serta laporan masyarakat terkait penemuan atau legenda yang berhubungan dengannya.

Website ini dikembangkan sebagai proyek Responsi Praktikum Pemrograman Web dengan menerapkan HTML, CSS, JavaScript, PHP Native, Session, CRUD, dan MySQL.

---

## ✨ Fitur Utama

### 👤 Halaman User

#### 🔐 Registrasi & Login

* Membuat akun baru.
* Login menggunakan akun yang telah terdaftar.
* Logout dari sistem.

#### 🏠 Dashboard User

* Menampilkan informasi singkat website.
* Navigasi menuju seluruh fitur utama.

#### 🕯️ Data Gumbal

* Melihat daftar Gumbal.
* Melihat detail Gumbal.
* Mencari Gumbal berdasarkan nama atau kategori.

#### 📜 Ritual & Legenda

* Melihat informasi ritual dan legenda yang berkaitan dengan Gumbal.
* Menampilkan deskripsi dan sejarah singkat.

#### 📝 Laporan Temuan

* Mengirim laporan terkait Gumbal.
* Melihat status laporan yang telah dikirim.

---

### 🛡️ Halaman Admin

#### 📊 Dashboard Admin

* Menampilkan statistik website.
* Jumlah user, data Gumbal, ritual, dan laporan.

#### ⚙️ Manajemen Gumbal

* Menambah data Gumbal.
* Mengedit data Gumbal.
* Menghapus data Gumbal.
* Melihat seluruh data Gumbal.

#### 📜 Manajemen Ritual

* Menambah data ritual.
* Mengedit data ritual.
* Menghapus data ritual.

#### 📄 Manajemen Laporan

* Melihat laporan pengguna.
* Menyetujui laporan.
* Menolak laporan.
* Menghapus laporan.

#### 👥 Manajemen User

* Melihat daftar pengguna.
* Menghapus akun pengguna.

---

## 📂 Struktur Folder

```text
gumball_portal/
├── includes/              # Komponen backend & template
│   ├── config.php         # Koneksi DB + BASE_URL
│   ├── session.php        # Guard: require_login(), require_admin()
│   ├── functions.php      # db_query(), helpers, require_role()
│   ├── header.php         # Header frontend (FE)
│   └── footer.php         # Footer FE
│
├── pages/                 # Halaman autentikasi & detail publik
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── character-detail.php
│   └── episode-detail.php
│
├── admin/                 # Panel admin (akses terbatas)
│   ├── admin-sidebar.php
│   ├── dashboard.php
│   ├── manage-characters.php   # CRUD karakter
│   ├── manage-episodes.php     # CRUD episode
│   ├── manage-users.php        # Kelola user
│   └── manage-quotes.php       # Approve/reject quotes
│
├── assets/                # Aset statis
│   ├── css/
│   │   ├── style.css      # Frontend style
│   │   └── admin.css      # Admin panel style
│   ├── js/
│   │   ├── main.js        # Flash message, confirm delete
│   │   └── validation.js  # Validasi form
│   └── images/            # Gambar (karakter, episode, dll)
│
├── database/
│   └── schema.sql         # Struktur tabel + data awal (seed)
│
├── index.php              # Landing page
├── characters.php         # Daftar karakter publik
├── episodes.php           # Daftar episode publik
└── README.md              # File ini
```

---

## 🛠️ Teknologi yang Digunakan

### UI/UX Design

* Figma

### Frontend

* HTML5
* CSS3
* JavaScript

### Backend

* PHP Native

### Database

* MySQL

### Version Control

* GitHub

---

## 🗄️ Database

### users

Menyimpan data akun pengguna dan administrator.

### gumbal

Menyimpan informasi mengenai Gumbal.

### ritual

Menyimpan informasi ritual atau legenda.

### laporan

Menyimpan laporan yang dikirim oleh pengguna.

---

## 👥 Role Pengguna

### Admin

* CRUD Gumbal
* CRUD Ritual
* Kelola Laporan
* Kelola User
* Dashboard Statistik

### User

* Registrasi
* Login
* Melihat Data Gumbal
* Melihat Data Ritual
* Mengirim Laporan
* Melihat Profil

---

## 🚀 Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/gumbal.git
```

### 2. Pindahkan Project

Salin folder proyek ke dalam folder:

```text
htdocs/
```

### 3. Buat Database

```sql
CREATE DATABASE gumbal_db;
```

### 4. Import Database

Import file:

```text
database/schema.sql
```

dan

```text
database/seed.sql
```

### 5. Konfigurasi Database

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'gumbal_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 6. Jalankan Website

```bash
php -S localhost:8000
```

Akses:

```text
http://localhost:8000
```

---

## 🔒 Keamanan

* Password menggunakan password_hash()
* Login menggunakan Session PHP
* Prepared Statement PDO
* Validasi Client-side dan Server-side
* Proteksi halaman Admin
* Sanitasi output menggunakan htmlspecialchars()

---

## 📋 Fitur yang Memenuhi Ketentuan Responsi

✅ Login & Registrasi

✅ Session PHP

✅ Minimal 2 Role (Admin & User)

✅ Minimal 3 Tabel Database (Selain User)

✅ Implementasi CRUD

✅ Implementasi Function PHP

✅ Validasi Form

✅ GitHub Repository

✅ Figma Design

✅ Hosting Website

---

## 👨‍💻 Kontributor

| Nama              | Role               |Nim               |              
| ----------------- | ------------------ |------------------|
| Fathah Ikhwansyah | Backend Developer  |H1H024063         |
| Muhammad Aziz Ihza Fahriza Salam | Frontend Developer |H1H024050         |
| Mohammad Zulfan Ramadhan | UI/UX Designer     |H1H024008         |

---
