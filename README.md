# BugHunt

BugHunt adalah platform latihan debugging berbasis Laravel, Inertia.js, React, TypeScript, Tailwind CSS, PostgreSQL, dan CodeMirror.

Pengunjung dapat langsung mengerjakan tantangan tanpa registrasi dan tanpa login pengguna. Saat halaman latihan pertama dibuka, aplikasi membuat identitas hunter anonim pada sesi browser untuk menyimpan progres, poin, riwayat, dan posisi leaderboard.

## Fitur

- Tantangan JavaScript, PHP, dan SQL
- Tingkat kesulitan mudah, menengah, dan sulit
- Pemilihan baris kode bermasalah
- Editor CodeMirror untuk perbaikan kode
- Penjelasan penyebab bug
- Hint berurutan dengan penalti
- Penilaian baris, kode, dan penjelasan
- Riwayat pengerjaan
- Total poin dan leaderboard
- Dashboard progres anonim
- Panel administrator terlindungi
- CRUD kategori, kesulitan, dan tantangan
- Statistik dan data submission

## Teknologi

### Backend

- PHP 8.3 atau lebih baru
- Laravel 13
- Inertia.js
- PostgreSQL

### Frontend

- React 18
- TypeScript
- Tailwind CSS 4
- CodeMirror
- Recharts
- Vite

## Persyaratan

- PHP 8.3 atau lebih baru
- Composer
- Node.js
- npm
- PostgreSQL
- Git

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

```bash
git clone https://github.com/ki1bot/debugging-pemrograman.git
cd debugging-pemrograman
composer install
npm install
```

Buat konfigurasi environment lokal, generate application key, lalu sesuaikan koneksi PostgreSQL.

```bash
php artisan key:generate
php artisan migrate --seed
npm run build
```

Untuk menjalankan Laravel dan Vite secara bersamaan:

```bash
composer run dev
```

## Autentikasi

Aplikasi tidak menyediakan registrasi atau login pengguna. Progres peserta terikat pada sesi browser dan dapat hilang ketika cookie atau data situs dihapus.

Akses administrator menggunakan konfigurasi rahasia pada environment server. URL, username, password, dan hash administrator tidak disimpan dalam repository, README, dokumentasi, atau source code.

## Keamanan

- Kode pengguna tidak dijalankan melalui `eval`, shell, compiler, atau terminal server
- Request divalidasi pada backend
- CSRF protection tetap aktif
- Submission dan pembukaan hint memiliki rate limit
- Solusi tidak dikirim sebelum tantangan diselesaikan
- Panel administrator memakai route tertutup, rate limit, session marker, dan middleware fail-closed
- Informasi internal akun anonim tidak dibagikan ke frontend

## Pengujian

```bash
php artisan test
npm run build
```

## Lisensi

MIT
