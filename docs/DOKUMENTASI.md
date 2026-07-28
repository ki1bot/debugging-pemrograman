# Dokumentasi BugHunt

## Gambaran Umum

BugHunt adalah aplikasi pembelajaran debugging yang meminta peserta menemukan lokasi bug, memperbaiki kode, dan menjelaskan penyebab kesalahan. Sistem menilai jawaban tanpa menjalankan kode yang dikirim peserta.

## Aktor Sistem

### Pengunjung

Pengunjung dapat membuka beranda, daftar tantangan, leaderboard, dan halaman informasi.

### Hunter Anonim

Hunter anonim dibuat otomatis ketika pengunjung membuka halaman yang membutuhkan progres. Identitas tersimpan pada sesi browser dan digunakan untuk:

- Mengerjakan tantangan
- Membuka hint
- Mengirim jawaban
- Menyimpan skor terbaik
- Melihat dashboard
- Melihat riwayat
- Mengikuti leaderboard

Tidak tersedia registrasi, login pengguna, reset password, perubahan profil, atau logout pengguna.

### Administrator

Administrator dapat:

- Melihat dashboard dan statistik
- Mengelola kategori
- Mengelola tingkat kesulitan
- Mengelola tantangan, hint, dan solusi
- Melihat data hunter
- Melihat submission

Akses administrator dikendalikan oleh secret environment server. Detail akses tidak ditulis dalam repository atau dokumentasi.

## Alur Pengerjaan Tantangan

1. Pengunjung membuka tantangan.
2. Sistem membuat atau menggunakan hunter anonim pada sesi browser.
3. Hunter membaca deskripsi dan kode bermasalah.
4. Hunter memilih baris yang dianggap salah.
5. Hunter memperbaiki kode melalui CodeMirror.
6. Hunter menjelaskan penyebab bug.
7. Sistem memvalidasi input.
8. Sistem membandingkan jawaban dengan solusi yang tersedia.
9. Sistem menghitung skor dan penalti hint.
10. Sistem menyimpan submission dan progres terbaik.
11. Sistem menampilkan hasil dan membuka pembahasan setelah tantangan selesai.

## Penilaian

| Komponen         | Bobot |
| ---------------- | ----: |
| Lokasi baris bug |   30% |
| Kode perbaikan   |   50% |
| Penjelasan       |   20% |

Poin maksimum mengikuti tingkat kesulitan. Hint mengurangi skor akhir sesuai penalti yang ditentukan administrator.

## Keamanan

- Tidak menggunakan `eval`
- Tidak menjalankan kode peserta
- Tidak menjalankan compiler atau shell
- Input dibatasi dan divalidasi
- CSRF protection aktif
- Submission dan hint memiliki rate limit
- Solusi disembunyikan sampai tantangan selesai
- Akses admin membutuhkan kredensial secret environment
- Route admin tertutup tidak memiliki nama route publik
- Route admin tidak didaftarkan ketika konfigurasi rahasia belum lengkap
- Middleware admin mengembalikan 404 untuk sesi yang tidak sah
- Sesi admin memerlukan marker yang hanya dibuat setelah verifikasi kredensial baru
- Akun demo lama tidak digunakan sebagai mekanisme login

## Responsivitas

Antarmuka menggunakan sistem neobrutalism bersama yang mencakup:

- Navigasi desktop dan mobile
- Target sentuh minimal 44 piksel
- Card dan tombol dengan shadow yang disesuaikan pada layar kecil
- Tabel horizontal yang dapat digulir
- Judul dengan ukuran fluid
- Code editor dan pemilihan baris yang aman pada layar sempit
- Fokus keyboard yang jelas
- Dukungan reduced motion

## Menjalankan Aplikasi

```bash
composer install
npm install
php artisan migrate --seed
composer run dev
```

## Validasi

```bash
php artisan test
npm run build
```
