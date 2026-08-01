# Debugging Pemrograman

**Debugging Pemrograman** adalah aplikasi web untuk melatih kemampuan menemukan, memahami, dan memperbaiki kesalahan pada kode program.

Pengguna tidak hanya diminta memberikan kode yang benar. Dalam setiap tantangan, pengguna harus menentukan baris yang bermasalah, memperbaiki kode, menjalankan kode untuk menguji hasilnya, dan menjelaskan penyebab kesalahan tersebut.

Aplikasi ini dibuat agar proses belajar debugging tidak berhenti pada menyalin jawaban, tetapi membantu pengguna memahami mengapa sebuah kesalahan terjadi dan bagaimana cara memperbaikinya.

## Fitur Utama

BugHunt memiliki fitur untuk pengunjung, pengguna terdaftar, dan administrator.

### Fitur Publik

Pengunjung dapat:

- Membuka halaman utama.
- Membaca informasi tentang aplikasi.
- Melihat daftar tantangan yang tersedia.
- Mencari tantangan berdasarkan judul atau deskripsi.
- Memfilter tantangan berdasarkan bahasa pemrograman.
- Memfilter tantangan berdasarkan tingkat kesulitan.
- Melihat leaderboard pengguna.

### Fitur Pengguna

Pengguna yang telah login dapat:

- Melihat dashboard pribadi.
- Membuka detail tantangan.
- Memilih baris kode yang dianggap memiliki bug.
- Mengedit kode melalui editor CodeMirror.
- Menjalankan kode melalui layanan Judge0.
- Menggunakan input standar atau `stdin` ketika menjalankan kode.
- Membuka hint secara berurutan.
- Mengirimkan kode hasil perbaikan.
- Menjelaskan penyebab bug.
- Melihat nilai setiap bagian jawaban.
- Melihat penalti penggunaan hint.
- Melihat solusi dan pembahasan setelah tantangan diselesaikan.
- Melihat riwayat pengerjaan.
- Melihat skor terbaik untuk setiap tantangan.
- Mengumpulkan poin.
- Mengikuti leaderboard.
- Mengubah profil.
- Mengubah password.
- Menghapus akun.
- Melakukan reset password.

### Fitur Administrator

Administrator dapat:

- Membuka dashboard administrator.
- Melihat statistik aplikasi.
- Mengelola kategori bahasa pemrograman.
- Mengelola tingkat kesulitan.
- Menambahkan tantangan.
- Mengubah tantangan.
- Menghapus tantangan menggunakan mekanisme soft delete.
- Mengatur status tantangan menjadi `draft`, `published`, atau `inactive`.
- Mengatur kode yang memiliki bug.
- Menentukan nomor baris yang bermasalah.
- Mengatur pembahasan tantangan.
- Mengatur poin dasar tantangan.
- Menambahkan hingga lima hint.
- Mengatur penalti setiap hint.
- Menentukan satu solusi utama.
- Menambahkan beberapa solusi alternatif.
- Mengatur kata kunci jawaban penjelasan.
- Melihat daftar pengguna.
- Mengubah role pengguna.
- Melihat seluruh submission.
- Melihat hasil pengerjaan setiap pengguna.

## Bahasa Pemrograman

Seeder utama menyediakan delapan kategori bahasa pemrograman:

| Bahasa     |
| ---------- |
| C          |
| C++        |
| Go         |
| Java       |
| JavaScript |
| PHP        |
| Python     |
| SQL        |

Setiap bahasa memiliki delapan tantangan dengan pembagian:

| Tingkat kesulitan | Jumlah per bahasa | Poin dasar |
| ----------------- | ----------------: | ---------: |
| Mudah             |                 3 |         50 |
| Menengah          |                 3 |        100 |
| Sulit             |                 2 |        150 |
| **Total**         |             **8** |          — |

Total tantangan yang dibuat oleh seeder adalah:

```text
8 bahasa × 8 tantangan = 64 tantangan
```

Setiap tantangan memiliki kode bermasalah, nomor baris bug, pembahasan, hint, solusi utama, dan dapat memiliki solusi alternatif.

## Cara Kerja Tantangan

Setiap tantangan memiliki beberapa tahapan.

### 1. Menentukan lokasi bug

Pengguna memilih nomor baris yang dianggap menjadi sumber kesalahan.

### 2. Memperbaiki kode

Pengguna memperbaiki kode melalui editor CodeMirror yang telah menyesuaikan syntax highlighting berdasarkan bahasa tantangan.

### 3. Menjalankan kode

Kode dapat dijalankan melalui Judge0 sebelum dikirim sebagai jawaban.

Hasil eksekusi dapat menampilkan:

- Standard output.
- Standard error.
- Compile output.
- Status eksekusi.
- Waktu eksekusi.
- Penggunaan memori.

### 4. Menjelaskan penyebab bug

Pengguna harus menulis penjelasan mengenai kesalahan yang ditemukan dan alasan dari perbaikan yang dilakukan.

Penjelasan harus memiliki panjang minimal 20 karakter dan maksimal 3.000 karakter.

### 5. Mengirim jawaban

Sistem akan memeriksa:

- Nomor baris yang dipilih.
- Kode hasil perbaikan.
- Kata kunci pada penjelasan.
- Penalti dari hint yang telah dibuka.

## Sistem Penilaian

Nilai setiap jawaban dibagi menjadi tiga komponen:

| Komponen                 |    Bobot |
| ------------------------ | -------: |
| Menentukan baris bug     |      30% |
| Memperbaiki kode         |      50% |
| Menjelaskan penyebab bug |      20% |
| **Total**                | **100%** |

Kode pengguna dibandingkan dengan solusi utama dan solusi alternatif yang tersedia.

Sebelum dibandingkan, sistem melakukan normalisasi kode dengan cara:

- Menyamakan line ending.
- Menghapus baris kosong.
- Menghapus whitespace format yang tidak penting.
- Mempertahankan isi whitespace di dalam string.
- Membandingkan hasil normalisasi menggunakan hash.

Penilaian penjelasan menggunakan pencocokan kata kunci. Tantangan dianggap selesai apabila:

- Nomor baris bug benar.
- Kode hasil perbaikan benar.
- Penjelasan memuat minimal 60% kata kunci yang diperlukan.

Status submission terdiri dari:

| Status              | Keterangan                         |
| ------------------- | ---------------------------------- |
| `incorrect`         | Seluruh bagian jawaban belum benar |
| `partially_correct` | Sebagian jawaban sudah benar       |
| `completed`         | Tantangan berhasil diselesaikan    |

## Hint dan Penalti

Seeder menyediakan dua hint untuk setiap tantangan:

| Hint         | Penalti |
| ------------ | ------: |
| Hint pertama |     10% |
| Hint kedua   |     20% |

Hint harus dibuka secara berurutan.

Administrator dapat mengatur hingga lima hint untuk setiap tantangan dan menentukan sendiri nilai penalti masing-masing hint.

Penalti diterapkan terhadap nilai jawaban setelah seluruh komponen nilai dihitung.

## Poin dan Progres Pengguna

Sistem hanya menggunakan skor terbaik pengguna pada setiap tantangan.

Apabila pengguna mengerjakan tantangan yang sama beberapa kali:

- Poin tidak digandakan.
- Sistem membandingkan skor baru dengan skor terbaik sebelumnya.
- Apabila skor baru lebih tinggi, hanya selisih nilainya yang ditambahkan ke total poin.
- Apabila skor baru lebih rendah, total poin tidak berkurang.

Mekanisme ini mencegah pengguna mendapatkan poin berulang kali dari tantangan yang sama.

## Eksekusi Kode dengan Judge0

BugHunt menggunakan layanan **Judge0** untuk menjalankan kode.

Bahasa yang dapat dijalankan:

- C
- C++
- Go
- Java
- JavaScript
- PHP
- Python
- SQL

Eksekusi kode memiliki batasan:

| Batasan                |           Nilai |
| ---------------------- | --------------: |
| Panjang source code    | 20.000 karakter |
| Panjang `stdin`        |  5.000 karakter |
| CPU time               |         2 detik |
| Wall time              |         5 detik |
| Memori                 |          128 MB |
| Ukuran file maksimum   |            1 MB |
| Akses jaringan program |   Dinonaktifkan |

Token hasil eksekusi hanya disimpan pada session pengguna selama lima menit.

Endpoint eksekusi juga dilindungi menggunakan rate limiting.

Perlu diperhatikan bahwa fitur menjalankan kode dan fitur penilaian jawaban adalah dua proses yang berbeda.

Judge0 digunakan agar pengguna dapat menguji kode. Penilaian akhir tetap dilakukan oleh aplikasi dengan membandingkan jawaban terhadap solusi yang telah disimpan.

## Teknologi

### Backend

- PHP 8.3 atau lebih baru.
- Laravel 13.
- Laravel Breeze.
- Laravel Sanctum.
- Inertia.js 2.
- PostgreSQL.
- PHPUnit 12.

### Frontend

- React 18.
- TypeScript 5.
- Tailwind CSS 4.
- Vite 8.
- CodeMirror 6.
- Recharts.
- Headless UI.
- Base UI.
- shadcn.
- Lucide React.
- Hugeicons.

### Layanan Eksternal

- Judge0 untuk menjalankan kode.

## Persyaratan Sistem

Pastikan perangkat telah memiliki:

- PHP 8.3 atau lebih baru.
- Composer 2.
- Node.js 22 atau lebih baru.
- npm.
- PostgreSQL.
- Git.
- Browser modern.
- Akses ke server Judge0.

Ekstensi PHP yang diperlukan:

```text
ctype
curl
dom
fileinfo
filter
mbstring
openssl
pdo
pdo_pgsql
tokenizer
xml
```

## Instalasi

Seluruh perintah berikut dapat dijalankan melalui Git Bash.

### 1. Clone repository

```bash
git clone https://github.com/ki1bot/debugging-pemrograman.git
cd debugging-pemrograman
```

### 2. Instal dependency backend

```bash
composer install
```

### 3. Instal dependency frontend

```bash
npm install
```

Untuk instalasi yang mengikuti versi dependency pada `package-lock.json`, gunakan:

```bash
npm ci
```

### 4. Buat database PostgreSQL

Buat database PostgreSQL baru, misalnya:

```text
debuggingpemrograman
```

Nama database, username, dan password dapat disesuaikan dengan konfigurasi PostgreSQL pada perangkat masing-masing.

### 5. Konfigurasi environment

Buka file `.env`, kemudian sesuaikan konfigurasi berikut:

```env
APP_NAME="Debugging Pemrograman"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=debuggingpemrograman
DB_USERNAME=postgres
DB_PASSWORD=
```

Jangan mengunggah file `.env` ke repository karena file tersebut dapat berisi password, token, dan informasi sensitif lainnya.

### 6. Konfigurasi Judge0

Secara default, aplikasi menggunakan:

```env
JUDGE0_BASE_URL=https://ce.judge0.com
```

Apabila layanan Judge0 membutuhkan API key atau token, tambahkan:

```env
JUDGE0_API_KEY=
JUDGE0_RAPIDAPI_HOST=
JUDGE0_AUTH_TOKEN=
```

ID runtime setiap bahasa juga dapat disesuaikan:

```env
JUDGE0_LANGUAGE_C=103
JUDGE0_LANGUAGE_CPP=105
JUDGE0_LANGUAGE_GO=107
JUDGE0_LANGUAGE_JAVA=91
JUDGE0_LANGUAGE_JAVASCRIPT=102
JUDGE0_LANGUAGE_PHP=98
JUDGE0_LANGUAGE_PYTHON=100
JUDGE0_LANGUAGE_SQL=82
```

### 7. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

Seeder akan membuat:

- 8 kategori bahasa.
- 3 tingkat kesulitan.
- 64 tantangan.
- 1 akun administrator.
- 1 akun pengguna demo ketika aplikasi berjalan pada environment `local` atau `testing`.

Untuk menghapus seluruh tabel dan membuat ulang data dari awal:

```bash
php artisan migrate:fresh --seed
```

Perintah tersebut akan menghapus seluruh data yang sudah tersimpan.

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

Laravel dan Vite juga dapat dijalankan secara terpisah.

Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
npm run dev
```

## Administrator Production

Pada environment selain `local` dan `testing`, data administrator harus dikonfigurasi melalui `.env`:

```env
BUGHUNT_ADMIN_EMAIL=admin@example.com
BUGHUNT_ADMIN_PASSWORD=password-yang-kuat
```

Password administrator production harus memiliki panjang minimal 16 karakter.

Seeder akan menolak dijalankan apabila email administrator tidak valid atau password production terlalu pendek.

## Automated Test

Jalankan seluruh pengujian:

```bash
php artisan test
```

Pengujian juga dapat dijalankan melalui Composer:

```bash
composer test
```

Pengujian yang tersedia mencakup:

- Registrasi dan login.
- Reset password.
- Verifikasi email.
- Perubahan password.
- Pengelolaan profil.
- Authorization pengguna dan administrator.
- Validasi seeder.
- Jumlah kategori dan tantangan.
- Distribusi tingkat kesulitan.
- Evaluasi jawaban.
- Penyimpanan submission.
- Penalti hint.
- Perhitungan skor terbaik.
- Pencegahan penggandaan poin.
- Penguncian solusi sebelum tantangan selesai.

## Production Build

Periksa TypeScript dan buat frontend production build:

```bash
npm run build
```

## Continuous Integration

Repository menggunakan GitHub Actions melalui:

```text
.github/workflows/ci.yml
```

Workflow dijalankan ketika:

- Terdapat push ke branch `main`.
- Terdapat pull request.
- Workflow dijalankan secara manual.

CI menggunakan:

- Ubuntu versi terbaru.
- PHP 8.4.
- Node.js 22.
- SQLite in-memory untuk pengujian.
- Composer.
- npm.

Tahapan CI meliputi:

1. Menginstal dependency Composer.
2. Menyiapkan aplikasi Laravel.
3. Menginstal dependency frontend.
4. Memeriksa TypeScript.
5. Membuat frontend production build.
6. Menjalankan seluruh pengujian Laravel.

## Struktur Database

Tabel utama aplikasi terdiri dari:

| Tabel                     | Kegunaan                                   |
| ------------------------- | ------------------------------------------ |
| `users`                   | Menyimpan akun, role, dan total poin       |
| `categories`              | Menyimpan kategori bahasa pemrograman      |
| `difficulties`            | Menyimpan tingkat kesulitan dan poin dasar |
| `challenges`              | Menyimpan data utama tantangan             |
| `challenge_hints`         | Menyimpan hint dan penalti                 |
| `challenge_solutions`     | Menyimpan solusi dan kata kunci            |
| `submissions`             | Menyimpan jawaban pengguna                 |
| `submission_attempts`     | Menyimpan snapshot hasil setiap percobaan  |
| `user_challenge_progress` | Menyimpan progres dan skor terbaik         |

Laravel juga menggunakan beberapa tabel pendukung untuk session, cache, queue, password reset, dan kebutuhan internal lainnya.

## Diagram Sistem

Diagram yang tersedia:

- [Use Case Diagram](database/diagram/USE%20CASE%20DIAGRAM.png)
- [Activity Diagram](database/diagram/ACTIVITY%20DIAGRAM.png)
- [Entity Relationship Diagram](database/diagram/ERD.png)
- [File ERD Draw.io](database/diagram/ERD.drawio)
- [DFD Level 0](database/diagram/DFD%20LEVEL%200.png)
- [DFD Level 1](database/diagram/DFD%20LEVEL%201.png)

## Keamanan

Beberapa mekanisme keamanan yang digunakan:

- Password disimpan menggunakan hashing Laravel.
- Form dilindungi CSRF protection.
- Route administrator dilindungi middleware `auth` dan `admin`.
- Input pengguna divalidasi pada backend.
- Panjang source code dan penjelasan dibatasi.
- Endpoint submission menggunakan rate limiting.
- Endpoint pembukaan hint menggunakan rate limiting.
- Endpoint eksekusi kode menggunakan rate limiting.
- Token eksekusi Judge0 dibatasi berdasarkan session pengguna.
- Program yang dijalankan melalui Judge0 tidak memperoleh akses jaringan.
- Solusi dan pembahasan disembunyikan sebelum tantangan selesai.
- Akses database menggunakan Eloquent dan Query Builder.
- Penghapusan tantangan menggunakan soft delete.
- File `.env` tidak disimpan di repository.

Eksekusi kode tetap memiliki risiko keamanan. Untuk production, gunakan instance Judge0 yang dikonfigurasi dengan isolasi, resource limit, dan pembatasan jaringan yang sesuai.

## Lisensi

Project ini menggunakan [MIT License](LICENSE).

## Pengembang

**Rifqi**
