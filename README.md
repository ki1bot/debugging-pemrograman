# BugHunt

BugHunt adalah platform pembelajaran debugging pemrograman berbasis web. Pengguna mengerjakan tantangan berupa kode yang sengaja memiliki kesalahan, memilih lokasi bug, menulis kode perbaikan, dan menjelaskan penyebab kesalahan tersebut.

## Latar Belakang

Kemampuan debugging tidak hanya membutuhkan kemampuan menulis kode, tetapi juga kemampuan membaca, menganalisis, dan memahami penyebab sebuah program gagal berjalan.

Banyak pemula dapat menyalin kode yang benar tanpa memahami kesalahan pada kode sebelumnya. BugHunt dibuat untuk menyediakan proses latihan debugging yang terstruktur dan interaktif.

## Tujuan

- Membantu pengguna meningkatkan kemampuan membaca kode.
- Melatih kemampuan menemukan lokasi kesalahan.
- Melatih kemampuan memperbaiki kode.
- Membiasakan pengguna menjelaskan penyebab teknis suatu error.
- Menyediakan pembelajaran debugging yang interaktif.
- Menyediakan data perkembangan belajar melalui poin dan riwayat.

## Kategori MVP

BugHunt versi awal mendukung:

- JavaScript
- PHP
- SQL

Setiap kategori memiliki:

| Kesulitan          | Jumlah |
| ------------------ | -----: |
| Mudah              |      3 |
| Menengah           |      3 |
| Sulit              |      2 |
| Total per kategori |      8 |

Total tantangan awal adalah 24 tantangan.

## Tahapan Tantangan

Setiap tantangan terdiri dari tiga tahapan:

1. Memilih baris yang dianggap mengandung bug.
2. Menulis kode perbaikan melalui CodeMirror.
3. Menjelaskan penyebab kesalahan secara teknis.

## Fitur Pengguna

- Registrasi.
- Login dan logout.
- Dashboard pengguna.
- Melihat daftar tantangan.
- Pencarian tantangan.
- Filter berdasarkan kategori.
- Filter berdasarkan tingkat kesulitan.
- Melihat detail tantangan.
- Memilih baris yang salah.
- Mengedit kode melalui CodeMirror.
- Mengirim penjelasan.
- Membuka hint secara berurutan.
- Melihat hasil penilaian.
- Melihat pembahasan.
- Melihat solusi utama dan alternatif.
- Melihat riwayat pengerjaan.
- Melihat total poin.
- Melihat leaderboard.
- Mengubah profil dan password.

## Fitur Administrator

- Login sebagai administrator.
- Dashboard administrator.
- Statistik aplikasi.
- Mengelola kategori.
- Mengelola tingkat kesulitan.
- Menambahkan tantangan.
- Mengubah tantangan.
- Menonaktifkan atau mengarsipkan tantangan.
- Mengatur kode yang bermasalah.
- Mengatur lokasi baris bug.
- Mengatur solusi utama.
- Mengatur solusi alternatif.
- Mengatur kata kunci penjelasan.
- Mengatur hint.
- Mengatur penalti hint.
- Mengatur poin.
- Mengatur status publikasi.
- Melihat pengguna.
- Mengubah role pengguna.
- Melihat seluruh submission.
- Melihat detail hasil penilaian.

## Role

### User

User dapat mengerjakan tantangan, menggunakan hint, mendapatkan poin, melihat riwayat, dan melihat leaderboard.

### Admin

Admin dapat mengelola kategori, tingkat kesulitan, tantangan, hint, solusi, pengguna, submission, publikasi, dan statistik.

## Sistem Penilaian

| Bagian                     | Persentase |
| -------------------------- | ---------: |
| Menemukan baris yang salah |        30% |
| Memperbaiki kode           |        50% |
| Menjelaskan penyebab       |        20% |

Poin maksimum berdasarkan tingkat kesulitan:

| Kesulitan | Poin maksimum |
| --------- | ------------: |
| Mudah     |            50 |
| Menengah  |           100 |
| Sulit     |           150 |

Penalti hint:

| Hint         | Penalti |
| ------------ | ------: |
| Hint pertama |     10% |
| Hint kedua   |     20% |

Total poin pengguna berasal dari skor terbaik yang diperoleh pada setiap tantangan. Mengerjakan tantangan yang sama berulang kali tidak menggandakan poin.

## Validasi Jawaban

BugHunt tidak menjalankan kode pengguna menggunakan `eval`, perintah shell, compiler, atau terminal server.

Validasi dilakukan dengan:

- Membandingkan baris yang dipilih dengan lokasi bug.
- Menormalisasi line ending.
- Mengabaikan spasi format yang tidak penting.
- Mengabaikan baris kosong.
- Membandingkan jawaban dengan solusi utama.
- Membandingkan jawaban dengan alternatif solusi.
- Memeriksa kata kunci penjelasan.
- Menghitung penalti berdasarkan hint yang dibuka.

Penilaian penjelasan menggunakan pencocokan kata kunci sederhana. Sistem tidak mengklaim memahami penjelasan pengguna secara semantik.

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

### Peralatan

- Composer
- Node.js
- npm
- Git
- Browser modern

## Struktur Database

Tabel utama:

- `users`
- `categories`
- `difficulties`
- `challenges`
- `challenge_hints`
- `challenge_solutions`
- `submissions`
- `submission_attempts`
- `user_challenge_progress`

### users

Menyimpan akun, role, dan total poin pengguna.

### categories

Menyimpan kategori bahasa pemrograman.

### difficulties

Menyimpan tingkat kesulitan dan poin dasar.

### challenges

Menyimpan deskripsi, kode rusak, lokasi bug, pembahasan, poin, serta status publikasi.

### challenge_hints

Menyimpan hint dan penalti poin.

### challenge_solutions

Menyimpan solusi utama, alternatif solusi, dan kata kunci penjelasan.

### submissions

Menyimpan setiap jawaban yang dikirim pengguna.

### submission_attempts

Menyimpan snapshot hasil penilaian setiap submission.

### user_challenge_progress

Menyimpan skor terbaik, jumlah percobaan, hint yang dibuka, dan status penyelesaian.

## Keamanan

BugHunt menerapkan aturan berikut:

- Tidak menggunakan `eval`.
- Tidak menjalankan kode pengguna pada terminal server.
- Password disimpan menggunakan hashing Laravel.
- Request divalidasi pada backend.
- Halaman admin dilindungi middleware role.
- CSRF protection menggunakan Laravel.
- Panjang kode dan penjelasan dibatasi.
- Kode ditampilkan sebagai teks.
- Submission dan pembukaan hint memiliki rate limit.
- Solusi tidak dikirim sebelum pengguna mengirim jawaban.
- Query database menggunakan Eloquent dan Query Builder.

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

```text
PHP 8.3+
Composer
Node.js
npm
PostgreSQL
Git
```

## Credits & Contact

**Rifqi**

GitHub: [ki1bot](https://github.com/ki1bot)

⭐ Jika project ini membantu atau menarik, jangan lupa beri star di GitHub!
