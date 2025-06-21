# 📝 PLANify - Aplikasi Manajemen Tugas Multi-User

## 📌 Deskripsi Proyek

**PLANify** adalah aplikasi web manajemen tugas berbasis PHP dan MySQL yang memungkinkan pengguna mengelola aktivitas harian secara efisien. Aplikasi ini mendukung fungsi **multi-user** yaitu pengguna dan admin, di mana setiap pengguna bisa membuat akun dan mengatur daftar tugas pribadi mereka. Dengan sistem prioritas, status, dan deadline, pengguna dapat lebih fokus menyelesaikan tugas tepat waktu. 

---

## ✨ Fitur Utama

1. **Fitur Umum (Digunakan oleh Semua Peran)**
    - **Login**: Semua pengguna/admin harus memiliki akun untuk menggunakan aplikasi.
    - **Desain responsif dengan Tailwind CSS**: Antarmuka dirancang agar nyaman diakses dari berbagai perangkat.

2. **Fitur Pengguna (Customer)**
    - **Registrasi**: Pengguna dapat melakukan registrasi terlebih dahulu sebelum melakukan login.
    - **CRUD (Create, Read, Update, Delete) tugas pribadi**: Pengguna dapat menambah, melihat, mengedit, dan menghapus tugas miliknya sendiri.
    - **Manajemen kategori dan prioritas**: Pengguna dapat menetapkan kategori (Kuliah, Kerja, Pribadi) dan tingkat prioritas (Tinggi, Sedang, Rendah) untuk tiap tugas.
    - **Deadline tugas**: Setiap tugas memiliki tenggat waktu untuk membantu pengguna mengelola waktu.
    - **Statistik tugas otomatis**: Tugas diklasifikasikan berdasarkan status (Selesai, Proses, Belum Selesai) secara otomatis.
    - **Filter dan pencarian**: Pengguna dapat mencari tugas berdasarkan judul, status, dan kategori.

3. **Fitur Admin**
    - **Hak Akses Berbasis Peran**: Admin memiliki akses untuk mengatur dan memonitor (melakukan CRUD) data pengguna dan seluruh tugas jika dibutuhkan.
    - **Manajemen sistem**: Admin dapat melihat dan melakukan filter kategori dan skala prioritas tugas.

---

## 🛠 Teknologi yang Digunakan

- **Front-end**: HTML, Tailwind CSS
- **Back-end**: PHP Native
- **Database**: MySQL
- **Server**: Apache (XAMPP / Laragon)

---

## 🚀 Cara Instalasi

1. **Cloning Repositori**:
   ```bash
   git clone https://github.com/Carissaokt/UAPWEB-Planify.git
   ```
2. **Masuk ke Direktori Proyek**:
   ```bash
   cd UAPWEB-Planify
   ```
3. **Konfigurasi Database**:
   - Buat database MySQL bernama `planify`.
   - Impor skema database dari file `planify.sql`.
   - Sesuaikan pengaturan koneksi database di `db/koneksi.php`.
4. **Siapkan Server**:
   - Salin proyek ke direktori server web (misalnya, `htdocs` untuk XAMPP atau `www` untuk Laragon).
   - Pastikan Apache dan MySQL berjalan.
5. **Akses Aplikasi**:
   - Buka browser dan kunjungi `http://localhost/UAPWEB-Planify/`.

---

## 🖥️ Cara Penggunaan

### 1. Akses Aplikasi
- Aktifkan XAMPP / Laragon
- Buka browser lalu kunjungi `http://localhost/UAPWEB-Planify/`.
- Login menggunakan akun yang telah terdaftar:
  - **Admin**: Akses penuh terhadap sistem (manajemen pengguna & data tugas).
  - **Pengguna**: Mengelola to-do list pribadi secara mandiri.

### 2. Sebagai Pengguna (Customer)
Pengguna merupakan peran utama dalam PLANify. Setelah berhasil login, Anda dapat:

#### — Kelola Tugas Pribadi
- Masuk ke **dashboard** pengguna (`beranda_user.php`).
- Klik tombol `+ Tambahkan Tugas` untuk membuat tugas baru.
- Masukkan judul tugas, kategori (Kuliah, Kerja, Pribadi), skala prioritas (Tinggi, Sedang, Rendah), status, dan deadline.

#### — Edit / Hapus Tugas
- Pada tabel daftar tugas, klik tombol `Edit` untuk memperbarui tugas Anda.
- Gunakan tombol `Hapus` untuk menghapus tugas jika tidak diperlukan lagi.

#### — Lihat Statistik Tugas
- Dashboard menampilkan total tugas dan jumlah yang telah **Selesai**, masih dalam **Proses**, atau **Belum Selesai** secara otomatis.

#### — Gunakan Filter & Pencarian
- Cari tugas berdasarkan judul.
- Filter berdasarkan kategori (Kuliah, Kerja, Pribadi) dan status (Selesai, Proses, Belum Selesai).
- Klik `Reset` untuk membersihkan filter dan menampilkan semua data.

#### — Update Status Cepat
- Langsung ubah status tugas dari daftar menggunakan dropdown pada kolom **Status**.

### 3. Sebagai Admin
Di dalam sistem ini, Admin dapat:
- Mengelola akun pengguna (menambahkan, moderasi, atau reset akun).
- Memiliki akses untuk melihat, mengedit, dan menghapus tugas pengguna.
- Melihat ringkasan statistik seluruh pengguna, total tugas.

---

## 💡 Tips Penggunaan

- Pastikan login dengan peran yang sesuai (pengguna/admin).
- Gunakan fitur pencarian dan filter untuk mengelola daftar tugas yang banyak.
- Tandai tugas penting dengan prioritas **Tinggi** agar tampil di urutan atas.
- Ubah status tugas secara berkala agar statistik lebih akurat.
- Gunakan browser terbaru untuk memastikan tampilan tetap optimal di semua perangkat.

---

## 👥 Kontributor

1. [Carissa Oktavia Sanjaya / 2317051005](https://github.com/Carissaokt)
2. [Zahra Agriphinna / 2357051022](https://github.com/kindlouve)
3. [Husnul Khotami / 2317051030](https://github.com/HusnulKhotami)
4. [Purwati Ayu Utami / 2357051007](https://github.com/puyuayu)
