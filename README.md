# The Amazing World of Gumball Theme

## 🕯️ Deskripsi Aplikasi

The Amazing World of Gumball Theme adalah aplikasi web berbasis PHP Native yang bertemakan mitologi, misteri, dan benda pusaka nusantara. Website ini memungkinkan pengguna untuk menjelajahi informasi mengenai karun (harta atau pusaka mistis), gumbal (media ritual), serta melaporkan temuan atau lokasi yang berkaitan dengan legenda tersebut.

Proyek ini dibuat untuk memenuhi tugas Responsi Praktikum Pemrograman Web dengan menerapkan konsep HTML, CSS, JavaScript, PHP Native, Session, CRUD, dan MySQL.

---

## ✨ Fitur Utama

### 👤 Halaman User (Pengguna)

#### 🔐 Sistem Registrasi & Login

* Pengguna dapat membuat akun baru.
* Pengguna dapat login menggunakan akun yang telah dibuat.

#### 🏠 Dashboard User

* Menampilkan ringkasan informasi.
* Navigasi menuju seluruh fitur website.

#### 🗺️ Data Karun

* Melihat daftar karun atau pusaka yang tersedia.
* Melihat detail lokasi dan deskripsi karun.

#### 🕯️ Data Gumbal

* Melihat daftar gumbal beserta jenis dan keterangannya.

#### 📝 Laporan Temuan

* Mengirim laporan terkait penemuan karun atau gumbal.
* Riwayat laporan yang pernah dikirim.

---

### 🛡️ Halaman Admin

#### 📊 Dashboard Admin

* Menampilkan statistik data website.
* Ringkasan jumlah user, karun, gumbal, dan laporan.

#### ⚙️ Manajemen Karun (CRUD)

* Menambah data karun.
* Melihat data karun.
* Mengubah data karun.
* Menghapus data karun.

#### 🕯️ Manajemen Gumbal

* Menambah data gumbal.
* Mengubah data gumbal.
* Menghapus data gumbal.

#### 👥 Manajemen User

* Melihat seluruh akun pengguna.
* Menghapus akun pengguna jika diperlukan.

#### 📄 Manajemen Laporan

* Melihat laporan yang dikirim oleh pengguna.
* Memverifikasi atau menghapus laporan.

---

## 📂 Struktur Folder

```text
/ │ ├── database/ │ ├── schema.sql │ └── seed.sql │ ├── assets/ │ ├── css/ │ ├── js/ │ └── images/ │ ├── includes/ │ ├── config.php │ ├── functions.php │ ├── session.php │ ├── header.php │ └── footer.php │ ├── pages/ │ ├── login.php │ ├── register.php │ ├── dashboard.php │ ├── karun.php │ ├── karun-detail.php │ ├── gumbal.php │ ├── gumbal-detail.php │ ├── laporan.php │ ├── profile.php │ └── logout.php │ ├── admin/ │ ├── dashboard.php │ ├── manage-karun.php │ ├── manage-gumbal.php │ ├── manage-laporan.php │ └── manage-users.php │ ├── index.php ├── README.md └── .gitignore
```

---

## 🗄️ Database

### Tabel Users

Menyimpan data akun pengguna.

### Tabel Karun

Menyimpan informasi mengenai karun atau pusaka.

### Tabel Gumbal

Menyimpan informasi mengenai gumbal.

### Tabel Laporan

Menyimpan laporan yang dikirim oleh pengguna.

---

## 🔧 Teknologi yang Digunakan

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

* Git & GitHub

---

## 🚀 Cara Instalasi

### 1. Clone Repository

```bash
git clone [url-repository]
```

### 2. Pindahkan Project

Pindahkan folder proyek ke dalam folder server lokal seperti:

```text
htdocs/ (XAMPP)
```

### 3. Buat Database

Buka phpMyAdmin dan buat database:

```sql
karun_gumbal
```

### 4. Import Database

Import file `.sql` yang tersedia pada folder:

```text
db/
```

### 5. Konfigurasi Database

Buka file:

```text
config/conn.php
```

Sesuaikan konfigurasi:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "karun_gumbal";
```

### 6. Jalankan Aplikasi

Buka browser dan akses:

```text
http://localhost/karun-gumbal
```

---

## 👷‍♂️ Pembagian Tugas

### UI/UX Designer

* Membuat wireframe dan desain antarmuka menggunakan Figma.

### Frontend Developer

* Mengimplementasikan desain menjadi halaman web menggunakan HTML, CSS, dan JavaScript.

### Backend Developer

* Membuat database MySQL.
* Implementasi Session Login.
* Implementasi CRUD.
* Integrasi database dengan PHP Native.
* Implementasi Function dan validasi data.

---

## 📌 Fitur yang Memenuhi Ketentuan Responsi

✅ Login & Registrasi

✅ Session PHP

✅ Minimal 2 Role (Admin & User)

✅ Minimal 3 Tabel Database (selain User)

✅ Implementasi CRUD

✅ Implementasi Function PHP

✅ Website Dinamis

✅ Menggunakan GitHub

✅ Menggunakan Figma

✅ Hosting Website

---

## 👨‍💻 Kontributor

* Fathah Ikhwansyah - H1H024063 — Backend Developer
* Muhammad Aziz Ihza Fahriza Salam - H1H024050 — Frontend Developer
* Mohammad Zulfan Ramadhan - H1H024008 — UI/UX Designer
