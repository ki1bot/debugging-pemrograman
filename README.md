# BugHunt

**BugHunt** adalah platform pembelajaran debugging pemrograman berbasis web. Aplikasi ini menyediakan berbagai tantangan berupa potongan kode yang sengaja memiliki kesalahan.

Pengguna tidak hanya diminta memperbaiki kode, tetapi juga harus menemukan lokasi kesalahan dan menjelaskan penyebabnya. Dengan cara ini, pengguna dapat belajar memahami sebuah error, bukan sekadar menyalin jawaban yang benar.

## Tentang Proyek

Kemampuan debugging merupakan salah satu kemampuan penting dalam pemrograman. Seorang programmer perlu mampu membaca kode, menemukan sumber masalah, memahami penyebab kesalahan, dan menentukan solusi yang tepat.

BugHunt dibuat sebagai media latihan debugging yang interaktif. Setiap tantangan memiliki kode bermasalah, lokasi bug, solusi utama, alternatif solusi, hint, dan pembahasan.

Untuk menjaga keamanan server, BugHunt tidak menjalankan kode pengguna menggunakan `eval()`, perintah shell, compiler, atau terminal server. Jawaban pengguna diperiksa dengan membandingkannya terhadap solusi yang telah disiapkan oleh administrator.

## Tujuan

BugHunt dikembangkan dengan tujuan:

- Membantu pengguna meningkatkan kemampuan membaca kode.
- Melatih kemampuan menemukan lokasi bug.
- Melatih kemampuan memperbaiki kode.
- Membantu pengguna memahami penyebab sebuah error.
- Menyediakan latihan debugging yang lebih interaktif.
- Menyimpan perkembangan belajar melalui riwayat dan sistem poin.
- Menyediakan pengelolaan tantangan melalui halaman administrator.

## Kategori Tantangan

Versi awal BugHunt memiliki tiga kategori:

- JavaScript
- PHP
- SQL

Setiap kategori memiliki delapan tantangan dengan pembagian berikut:

| Tingkat kesulitan | Jumlah per kategori |
| ----------------- | ------------------: |
| Mudah             |                   3 |
| Menengah          |                   3 |
| Sulit             |                   2 |
| Total             |                   8 |

Total tantangan awal yang tersedia adalah **24 tantangan**.

## Cara Kerja Tantangan

Setiap tantangan memiliki tiga tahap utama.

### 1. Menemukan lokasi bug

Pengguna memilih nomor baris yang dianggap mengandung kesalahan.

### 2. Memperbaiki kode

Pengguna mengubah kode bermasalah melalui editor CodeMirror.

### 3. Menjelaskan penyebabnya

Pengguna menulis penjelasan mengenai penyebab bug dan alasan perbaikannya.

Setelah jawaban dikirim, sistem akan memeriksa lokasi baris, kode perbaikan, dan kata kunci pada penjelasan.

## Fitur Pengguna

Pengguna dapat:

- Melakukan registrasi.
- Login dan logout.
- Melihat dashboard pengguna.
- Melihat daftar tantangan.
- Mencari tantangan.
- Memfilter tantangan berdasarkan kategori.
- Memfilter tantangan berdasarkan tingkat kesulitan.
- Melihat detail tantangan.
- Memilih baris kode yang salah.
- Memperbaiki kode melalui CodeMirror.
- Menulis penjelasan penyebab bug.
- Membuka hint secara berurutan.
- Mengirim jawaban.
- Melihat hasil penilaian.
- Melihat skor setiap bagian jawaban.
- Melihat penalti hint.
- Melihat pembahasan setelah tantangan selesai.
- Melihat solusi utama dan alternatif solusi.
- Melihat riwayat pengerjaan.
- Melihat total poin.
- Melihat leaderboard.
- Mengubah profil.
- Mengubah password.

## Fitur Administrator

Administrator dapat:

- Login melalui akun administrator.
- Melihat dashboard administrator.
- Melihat statistik aplikasi.
- Mengelola kategori.
- Mengelola tingkat kesulitan.
- Menambahkan tantangan.
- Mengubah tantangan.
- Menonaktifkan atau mengarsipkan tantangan.
- Mengatur kode yang bermasalah.
- Mengatur lokasi baris bug.
- Mengatur pembahasan.
- Mengatur hint.
- Mengatur penalti hint.
- Mengatur solusi utama.
- Mengatur alternatif solusi.
- Mengatur kata kunci penjelasan.
- Mengatur poin tantangan.
- Mengatur status publikasi.
- Melihat data pengguna.
- Mengubah role pengguna.
- Melihat seluruh submission.
- Melihat detail hasil pengerjaan pengguna.

## Role Pengguna

BugHunt mempunyai dua role utama.

### User

User dapat mengerjakan tantangan, membuka hint, memperoleh poin, melihat riwayat, dan mengikuti leaderboard.

### Admin

Admin dapat mengelola seluruh data utama aplikasi, termasuk kategori, tingkat kesulitan, tantangan, hint, solusi, pengguna, submission, dan statistik.

## Sistem Penilaian

Setiap jawaban dinilai menggunakan tiga komponen:

| Komponen                   | Bobot |
| -------------------------- | ----: |
| Menemukan baris yang salah |   30% |
| Memperbaiki kode           |   50% |
| Menjelaskan penyebab bug   |   20% |
| Total                      |  100% |

Poin maksimum ditentukan berdasarkan tingkat kesulitan:

| Tingkat kesulitan | Poin maksimum |
| ----------------- | ------------: |
| Mudah             |            50 |
| Menengah          |           100 |
| Sulit             |           150 |

Penggunaan hint mengurangi skor akhir:

| Hint         | Penalti |
| ------------ | ------: |
| Hint pertama |     10% |
| Hint kedua   |     20% |

Total poin pengguna dihitung dari skor terbaik pada setiap tantangan. Mengirim jawaban berulang kali pada tantangan yang sama tidak menggandakan poin.

Apabila pengguna memperoleh skor yang lebih tinggi, sistem hanya menambahkan selisih antara skor baru dan skor terbaik sebelumnya.

## Status Jawaban

Submission dapat memiliki salah satu status berikut:

- `incorrect`
- `partially_correct`
- `completed`

Solusi utama, alternatif solusi, lokasi baris bug, dan pembahasan lengkap baru ditampilkan setelah challenge berhasil diselesaikan.

## Validasi Jawaban

BugHunt tidak menjalankan kode yang dikirim pengguna.

Validasi dilakukan dengan cara:

- Membandingkan nomor baris yang dipilih dengan lokasi bug.
- Menyamakan line ending.
- Menghapus baris kosong.
- Mengabaikan whitespace format yang tidak penting.
- Mempertahankan whitespace yang berada di dalam string.
- Membandingkan jawaban dengan solusi utama.
- Membandingkan jawaban dengan alternatif solusi.
- Memeriksa kata kunci pada penjelasan.
- Menghitung penalti berdasarkan hint yang telah dibuka.

Penilaian penjelasan menggunakan pencocokan kata kunci sederhana. Sistem tidak mengklaim dapat memahami seluruh jawaban pengguna secara semantik.

## Teknologi

### Backend

- PHP 8.3 atau lebih baru
- Laravel 13
- Laravel Breeze
- Inertia.js
- PostgreSQL

### Frontend

- React 18
- TypeScript
- Tailwind CSS 4
- CodeMirror
- Recharts
- Vite

### Peralatan Pengembangan

- Composer
- Node.js
- npm
- Git
- Visual Studio Code
- Browser modern

## Struktur Database

BugHunt menggunakan sembilan tabel utama:

| Tabel                     | Kegunaan                                   |
| ------------------------- | ------------------------------------------ |
| `users`                   | Menyimpan akun, role, dan total poin       |
| `categories`              | Menyimpan kategori bahasa                  |
| `difficulties`            | Menyimpan tingkat kesulitan dan poin dasar |
| `challenges`              | Menyimpan data utama tantangan             |
| `challenge_hints`         | Menyimpan hint dan penalti                 |
| `challenge_solutions`     | Menyimpan solusi dan kata kunci            |
| `submissions`             | Menyimpan jawaban pengguna                 |
| `submission_attempts`     | Menyimpan snapshot hasil penilaian         |
| `user_challenge_progress` | Menyimpan progres dan skor terbaik         |

Diagram database dan diagram sistem tersedia pada folder:

```text
database/diagram/
```

## Persyaratan Sistem

Pastikan perangkat telah memiliki:

- PHP 8.3 atau lebih baru
- Composer
- Node.js
- npm
- PostgreSQL
- Git
- Browser modern

Ekstensi PHP yang diperlukan:

```text
ctype
fileinfo
json
mbstring
openssl
pdo_pgsql
tokenizer
xml
```

## Instalasi

### 1. Clone repository

```bash
git clone https://github.com/ki1bot/debugging-pemrograman.git
cd debugging-pemrograman
```

### 2. Instal dependency Laravel

```bash
composer install
```

### 3. Instal dependency frontend

```bash
npm install
```

### 4. Buat file environment

Untuk Git Bash atau Linux:

```bash
cp .env.example .env
```

Untuk PowerShell:

```powershell
Copy-Item .env.example .env
```

Lewati langkah ini apabila file `.env` sudah tersedia.

### 5. Generate application key

```bash
php artisan key:generate
```

## Konfigurasi Environment

Buka file `.env`, kemudian sesuaikan konfigurasi aplikasi dan PostgreSQL.

Contoh:

```env
APP_NAME=BugHunt
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=debugging_pemrograman
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, dan password dengan PostgreSQL yang digunakan pada perangkat masing-masing.

File `.env` berisi konfigurasi lokal dan informasi sensitif. Jangan mengunggah file tersebut ke repository publik.

## Migration dan Seeder

Jalankan migration sekaligus seeder:

```bash
php artisan migrate --seed
```

Seeder akan membuat:

- 3 kategori bahasa.
- 3 tingkat kesulitan.
- 24 tantangan.
- 1 akun administrator.
- 1 akun user demo.

Untuk menghapus seluruh data dan membuat database dari awal:

```bash
php artisan migrate:fresh --seed
```

Perintah `migrate:fresh` akan menghapus seluruh tabel dan data yang sudah ada.

## Menjalankan Aplikasi

Jalankan Laravel dan Vite secara bersamaan:

```bash
composer run dev
```

Aplikasi dapat dibuka melalui:

```text
http://127.0.0.1:8000
```

Untuk menghentikan server, tekan:

```text
Ctrl + C
```

## Akun Demo

Seeder menyediakan dua akun demo.

### Administrator

```text
Email    : admin@bughunt.test
Password : password
```

### User

```text
Email    : user@bughunt.test
Password : password
```

Akun tersebut hanya digunakan untuk development dan demonstrasi. Jangan menggunakan password demo pada aplikasi produksi.

## Automated Test

Jalankan seluruh test Laravel:

```bash
php artisan test
```

Atau gunakan script Composer:

```bash
composer test
```

Menjalankan test submission:

```bash
php artisan test --filter=ChallengeSubmissionTest
```

Menjalankan test seeder:

```bash
php artisan test --filter=BugHuntSeederTest
```

Menjalankan test authorization:

```bash
php artisan test --filter=BugHuntAuthorizationTest
```

Test yang tersedia mencakup:

- Validasi seeder.
- Jumlah dan distribusi tantangan.
- Authorization user dan admin.
- Evaluasi jawaban.
- Penyimpanan submission.
- Penalti hint.
- Perhitungan skor terbaik.
- Pencegahan penggandaan poin.
- Penguncian solusi sebelum challenge selesai.
- Pembukaan solusi setelah challenge selesai.

## Production Build

Buat frontend production build dengan perintah:

```bash
npm run build
```

Hasil build akan disimpan pada:

```text
public/build/
```

## Continuous Integration

Repository menggunakan GitHub Actions melalui file:

```text
.github/workflows/ci.yml
```

Workflow akan menjalankan:

- Instalasi dependency Composer.
- Instalasi dependency npm.
- Automated test Laravel.
- Production build Vite.

Workflow dijalankan ketika terdapat push ke branch `main` atau pull request.

## Diagram dan Dokumentasi

Dokumentasi lengkap tersedia pada:

- [Dokumentasi Pendukung](docs/DOKUMENTASI.md)
- [Use Case Diagram](database/diagram/USE%20CASE%20DIAGRAM.png)
- [Activity Diagram](database/diagram/ACTIVITY%20DIAGRAM.png)
- [ERD](database/diagram/ERD.png)
- [File ERD draw.io](database/diagram/ERD.drawio)
- [DFD Level 0](database/diagram/DFD%20LEVEL%200.png)
- [DFD Level 1](database/diagram/DFD%20LEVEL%201.png)

## Screenshot

Screenshot aplikasi akan disimpan pada:

```text
docs/screenshots/
```

Screenshot yang akan ditampilkan antara lain:

- Landing page.
- Halaman login.
- Dashboard user.
- Daftar tantangan.
- Halaman pengerjaan.
- Hasil jawaban yang belum selesai.
- Hasil jawaban yang selesai.
- Riwayat pengerjaan.
- Leaderboard.
- Dashboard administrator.
- Statistik administrator.
- Form pengelolaan tantangan.

Screenshot belum ditampilkan pada README sampai pengambilan gambar aplikasi final selesai dilakukan.

## Wireframe

Wireframe aplikasi akan disimpan pada:

```text
docs/wireframes/
```

Wireframe yang disiapkan meliputi:

- Landing page.
- Dashboard user.
- Daftar tantangan.
- Halaman pengerjaan.
- Halaman hasil.
- Leaderboard.
- Dashboard admin.
- Form tambah atau edit tantangan.

## Video Demonstrasi

Video demonstrasi akan menunjukkan:

1. Tampilan landing page.
2. Login sebagai user.
3. Memilih tantangan.
4. Membuka hint.
5. Mengirim jawaban yang belum benar.
6. Menunjukkan bahwa solusi masih terkunci.
7. Mengirim jawaban yang benar.
8. Melihat skor dan pembahasan.
9. Melihat riwayat dan leaderboard.
10. Login sebagai administrator.
11. Mengelola tantangan.
12. Melihat statistik.

Link video demonstrasi:

```text
Belum tersedia
```

## Deployment

Status deployment:

```text
Belum tersedia
```

Setelah aplikasi berhasil di-hosting, bagian ini akan diperbarui dengan URL aplikasi yang aktif.

## Keamanan

BugHunt menerapkan beberapa aturan keamanan:

- Tidak menggunakan `eval()`.
- Tidak menjalankan kode pengguna melalui shell.
- Tidak menjalankan compiler terhadap jawaban pengguna.
- Password disimpan menggunakan hashing Laravel.
- Request divalidasi di backend.
- Halaman administrator dilindungi middleware role.
- Menggunakan CSRF protection.
- Membatasi panjang kode.
- Membatasi panjang penjelasan.
- Menampilkan kode sebagai teks.
- Tidak merender kode pengguna sebagai HTML.
- Memberikan rate limit pada submission.
- Memberikan rate limit pada pembukaan hint.
- Menyembunyikan solusi sebelum challenge selesai.
- Menggunakan Eloquent dan Query Builder untuk akses database.

## Struktur Dokumentasi

```text
database/
└── diagram/
    ├── ACTIVITY DIAGRAM.png
    ├── DFD LEVEL 0.png
    ├── DFD LEVEL 1.png
    ├── ERD.drawio
    ├── ERD.png
    └── USE CASE DIAGRAM.png

docs/
├── DOKUMENTASI.md
├── screenshots/
└── wireframes/
```

## Lisensi

Proyek ini menggunakan **MIT License**.

Informasi lengkap tersedia pada file:

```text
LICENSE
```

## Pengembang

**Rifqi**

GitHub:

```text
https://github.com/ki1bot
```

Repository BugHunt:

```text
https://github.com/ki1bot/debugging-pemrograman
```
