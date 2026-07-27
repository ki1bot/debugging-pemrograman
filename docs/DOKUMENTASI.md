# Dokumentasi Pendukung BugHunt

## 1. Identitas Proyek

Nama proyek:

**BugHunt: Platform Pembelajaran Debugging Pemrograman Berbasis Web**

BugHunt adalah aplikasi web interaktif yang menyediakan tantangan
berupa potongan kode yang sengaja memiliki kesalahan.

Pengguna harus:

1. Menemukan baris kode yang bermasalah.
2. Memperbaiki kode.
3. Menjelaskan penyebab kesalahan.
4. Mengirim jawaban untuk diperiksa sistem.

Sistem memberikan skor, penalti hint, riwayat pengerjaan, pembahasan,
total poin, dan leaderboard.

## 2. Latar Belakang

Kemampuan debugging tidak hanya membutuhkan kemampuan menulis kode,
tetapi juga kemampuan membaca, menganalisis, dan memahami penyebab
kesalahan pada suatu program.

Banyak pemula dapat menyalin kode yang benar tanpa memahami mengapa
kode sebelumnya mengalami error. BugHunt dibuat untuk menyediakan
latihan debugging yang terstruktur, interaktif, dan aman.

BugHunt tidak menjalankan kode pengguna secara langsung menggunakan
`eval`, compiler, shell, atau terminal server. Penilaian dilakukan
dengan membandingkan jawaban pengguna dengan solusi yang telah
disiapkan oleh administrator.

## 3. Tujuan Proyek

Tujuan BugHunt adalah:

- Membantu pengguna meningkatkan kemampuan membaca kode.
- Melatih kemampuan menemukan lokasi kesalahan.
- Melatih kemampuan memperbaiki kode.
- Membiasakan pengguna memahami penyebab kesalahan.
- Menyediakan pembelajaran debugging yang interaktif.
- Menyediakan data perkembangan belajar melalui poin dan riwayat.
- Menyediakan fitur pengelolaan tantangan untuk administrator.

## 4. Ruang Lingkup MVP

Kategori bahasa pemrograman pada versi awal:

- JavaScript.
- PHP.
- SQL.

Distribusi tantangan:

| Kategori   | Mudah | Menengah | Sulit | Total |
| ---------- | ----: | -------: | ----: | ----: |
| JavaScript |     3 |        3 |     2 |     8 |
| PHP        |     3 |        3 |     2 |     8 |
| SQL        |     3 |        3 |     2 |     8 |
| Total      |     9 |        9 |     6 |    24 |

Fitur yang tidak termasuk pada MVP:

- Eksekusi kode pengguna.
- Forum diskusi.
- Chat.
- Multiplayer.
- AI pemeriksa jawaban.
- Kompetisi langsung.
- Sertifikat otomatis.
- Docker untuk menjalankan kode pengguna.

## 5. Teknologi

### Backend

- PHP 8.3 atau lebih baru.
- Laravel 13.
- Laravel Breeze.
- Inertia.js.
- PostgreSQL.

### Frontend

- React 18.
- TypeScript.
- Tailwind CSS 4.
- CodeMirror.
- Recharts.
- Vite.

### Development Tools

- Composer.
- Node.js.
- npm.
- Git.
- Visual Studio Code.
- Browser modern.

## 6. Aktor Sistem

### 6.1 Pengunjung

Pengunjung adalah pengguna yang belum melakukan autentikasi.

Pengunjung dapat:

- Melihat landing page.
- Melihat halaman Tentang BugHunt.
- Melihat daftar tantangan.
- Mencari tantangan.
- Memfilter tantangan.
- Melihat leaderboard.
- Melakukan registrasi.
- Melakukan login.

### 6.2 User

User adalah pengguna yang telah terdaftar dan dapat mengerjakan
tantangan.

User dapat:

- Login dan logout.
- Melihat dashboard.
- Melihat daftar tantangan.
- Mencari dan memfilter tantangan.
- Melihat detail tantangan.
- Memilih baris kode yang salah.
- Memperbaiki kode melalui CodeMirror.
- Menulis penjelasan.
- Membuka hint.
- Mengirim jawaban.
- Melihat hasil penilaian.
- Melihat pembahasan setelah tantangan selesai.
- Melihat solusi utama.
- Melihat alternatif solusi.
- Melihat riwayat pengerjaan.
- Melihat total poin.
- Melihat leaderboard.
- Mengelola profil.
- Mengubah password.

### 6.3 Admin

Admin adalah pengguna yang memiliki hak administratif.

Admin dapat:

- Login sebagai administrator.
- Melihat dashboard administrator.
- Melihat statistik aplikasi.
- Mengelola kategori.
- Mengelola tingkat kesulitan.
- Mengelola tantangan.
- Mengelola hint.
- Mengelola solusi.
- Mengatur kata kunci penjelasan.
- Mengatur poin.
- Mengatur status publikasi.
- Melihat data pengguna.
- Mengubah role pengguna.
- Melihat seluruh submission.
- Melihat detail submission.

## 7. Alur Pengerjaan Tantangan

Alur pengerjaan tantangan adalah:

1. User melakukan login.
2. User membuka daftar tantangan.
3. User mencari atau memfilter tantangan.
4. User memilih tantangan.
5. Sistem menampilkan deskripsi dan kode bermasalah.
6. User dapat membuka hint secara berurutan.
7. Sistem menambahkan penalti ketika hint dibuka.
8. User memilih baris yang dianggap salah.
9. User memperbaiki kode melalui CodeMirror.
10. User menulis penjelasan penyebab bug.
11. User mengirim jawaban.
12. Sistem memvalidasi data.
13. Sistem membandingkan lokasi baris bug.
14. Sistem menormalisasi dan membandingkan kode.
15. Sistem memeriksa kata kunci penjelasan.
16. Sistem menghitung skor.
17. Sistem mengurangi skor berdasarkan penalti hint.
18. Sistem menyimpan submission.
19. Sistem menyimpan snapshot hasil penilaian.
20. Sistem memperbarui skor terbaik.
21. Sistem memperbarui total poin.
22. Sistem menampilkan hasil.
23. Solusi dan pembahasan dibuka setelah tantangan selesai.

## 8. Sistem Penilaian

Komponen penilaian:

| Komponen                   | Persentase |
| -------------------------- | ---------: |
| Menemukan baris yang salah |        30% |
| Memperbaiki kode           |        50% |
| Menjelaskan penyebab       |        20% |
| Total                      |       100% |

Poin maksimum berdasarkan kesulitan:

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

Total poin pengguna berasal dari skor terbaik pada setiap tantangan.

Mengerjakan tantangan yang sama berulang kali tidak menggandakan total
poin. Sistem hanya menambahkan selisih apabila skor terbaru lebih besar
daripada skor terbaik sebelumnya.

## 9. Validasi Jawaban

### 9.1 Validasi Baris Bug

Sistem membandingkan nomor baris yang dipilih pengguna dengan
`buggy_line` yang tersimpan pada tantangan.

### 9.2 Validasi Kode

Validasi kode dilakukan dengan:

- Menyamakan line ending.
- Menghapus baris kosong.
- Mengabaikan whitespace format yang tidak penting.
- Mempertahankan whitespace di dalam string.
- Membandingkan dengan solusi utama.
- Membandingkan dengan alternatif solusi.
- Membandingkan hasil normalisasi menggunakan hash.

### 9.3 Validasi Penjelasan

Sistem memeriksa kata kunci yang telah disiapkan pada solusi utama.

Penilaian penjelasan menggunakan pencocokan kata kunci sederhana.
Sistem tidak mengklaim memahami seluruh jawaban pengguna secara
semantik.

### 9.4 Status Submission

Status submission terdiri dari:

- `incorrect`
- `partially_correct`
- `completed`

## 10. Struktur Database

Tabel utama BugHunt:

1. `users`
2. `categories`
3. `difficulties`
4. `challenges`
5. `challenge_hints`
6. `challenge_solutions`
7. `submissions`
8. `submission_attempts`
9. `user_challenge_progress`

### 10.1 users

Menyimpan akun pengguna.

Atribut utama:

- `id`
- `name`
- `email`
- `password`
- `role`
- `total_points`
- `email_verified_at`
- `created_at`
- `updated_at`

### 10.2 categories

Menyimpan kategori bahasa pemrograman.

Atribut utama:

- `id`
- `name`
- `slug`
- `description`
- `is_active`
- `created_at`
- `updated_at`

### 10.3 difficulties

Menyimpan tingkat kesulitan.

Atribut utama:

- `id`
- `name`
- `slug`
- `base_points`
- `is_active`
- `created_at`
- `updated_at`

### 10.4 challenges

Menyimpan data tantangan.

Atribut utama:

- `id`
- `category_id`
- `difficulty_id`
- `created_by`
- `title`
- `slug`
- `description`
- `broken_code`
- `buggy_line`
- `explanation`
- `base_points`
- `status`
- `created_at`
- `updated_at`
- `deleted_at`

### 10.5 challenge_hints

Menyimpan hint setiap tantangan.

Atribut utama:

- `id`
- `challenge_id`
- `hint_order`
- `content`
- `point_penalty`
- `created_at`
- `updated_at`

### 10.6 challenge_solutions

Menyimpan solusi utama dan alternatif.

Atribut utama:

- `id`
- `challenge_id`
- `solution_code`
- `solution_type`
- `required_keywords`
- `created_at`
- `updated_at`

### 10.7 submissions

Menyimpan jawaban pengguna.

Atribut utama:

- `id`
- `user_id`
- `challenge_id`
- `selected_line`
- `submitted_code`
- `submitted_explanation`
- `line_score`
- `code_score`
- `explanation_score`
- `hint_penalty`
- `final_score`
- `status`
- `completed_at`
- `created_at`
- `updated_at`

### 10.8 submission_attempts

Menyimpan snapshot hasil penilaian submission.

Atribut utama:

- `id`
- `submission_id`
- `attempt_number`
- `line_correct`
- `code_correct`
- `matched_keywords`
- `missing_keywords`
- `score_snapshot`
- `status_snapshot`
- `created_at`
- `updated_at`

### 10.9 user_challenge_progress

Menyimpan progres pengguna pada setiap tantangan.

Atribut utama:

- `id`
- `user_id`
- `challenge_id`
- `best_submission_id`
- `best_score`
- `attempts_count`
- `hints_used`
- `hint_penalty`
- `unlocked_hint_ids`
- `is_completed`
- `completed_at`
- `created_at`
- `updated_at`

## 11. Diagram Sistem

Diagram sistem disimpan pada:

```text
database/diagram/
```

Daftar diagram:

| Diagram                     | File                                    |
| --------------------------- | --------------------------------------- |
| Use Case Diagram            | `database/diagram/USE CASE DIAGRAM.png` |
| Activity Diagram            | `database/diagram/ACTIVITY DIAGRAM.png` |
| Entity Relationship Diagram | `database/diagram/ERD.png`              |
| File ERD draw.io            | `database/diagram/ERD.drawio`           |
| DFD Level 0                 | `database/diagram/DFD LEVEL 0.png`      |
| DFD Level 1                 | `database/diagram/DFD LEVEL 1.png`      |

### 11.1 Use Case Diagram

Use Case Diagram menjelaskan interaksi Pengunjung, User, dan Admin
dengan sistem BugHunt.

### 11.2 Activity Diagram

Activity Diagram menjelaskan alur pengguna mulai dari login, memilih
tantangan, memperbaiki kode, mengirim jawaban, hingga melihat hasil.

### 11.3 Entity Relationship Diagram

ERD menjelaskan entitas, atribut, relasi, primary key, foreign key, dan
kardinalitas database BugHunt.

ERD menggunakan notasi Chen dan tersedia dalam format `.drawio` agar
dapat diedit melalui diagrams.net.

### 11.4 DFD Level 0

DFD Level 0 menggambarkan sistem BugHunt sebagai satu proses utama yang
berinteraksi dengan Pengunjung, User, dan Admin.

### 11.5 DFD Level 1

DFD Level 1 memecah proses utama menjadi:

1. Kelola autentikasi dan akun.
2. Sediakan data tantangan.
3. Kelola pengerjaan dan hint.
4. Evaluasi jawaban dan hitung skor.
5. Kelola progres, riwayat, dan leaderboard.
6. Kelola administrasi dan statistik.

## 12. Kebutuhan Fungsional

| ID    | Kebutuhan                                     |
| ----- | --------------------------------------------- |
| KF-01 | Sistem menyediakan registrasi pengguna        |
| KF-02 | Sistem menyediakan login                      |
| KF-03 | Sistem menyediakan logout                     |
| KF-04 | Sistem membedakan role user dan admin         |
| KF-05 | Pengunjung dapat melihat landing page         |
| KF-06 | Pengunjung dapat melihat Tentang BugHunt      |
| KF-07 | Pengunjung dapat melihat daftar tantangan     |
| KF-08 | Pengunjung dapat melihat leaderboard          |
| KF-09 | User dapat melihat dashboard                  |
| KF-10 | User dapat mencari tantangan                  |
| KF-11 | User dapat memfilter berdasarkan kategori     |
| KF-12 | User dapat memfilter berdasarkan kesulitan    |
| KF-13 | User dapat melihat detail tantangan           |
| KF-14 | User dapat memilih baris bug                  |
| KF-15 | User dapat memperbaiki kode                   |
| KF-16 | User dapat menulis penjelasan                 |
| KF-17 | User dapat membuka hint                       |
| KF-18 | Sistem menerapkan penalti hint                |
| KF-19 | User dapat mengirim jawaban                   |
| KF-20 | Sistem memvalidasi baris bug                  |
| KF-21 | Sistem memvalidasi kode                       |
| KF-22 | Sistem memeriksa kata kunci penjelasan        |
| KF-23 | Sistem menghitung skor                        |
| KF-24 | Sistem menyimpan submission                   |
| KF-25 | Sistem menyimpan submission attempt           |
| KF-26 | Sistem menyimpan skor terbaik                 |
| KF-27 | Sistem memperbarui total poin                 |
| KF-28 | Sistem mencegah penggandaan poin              |
| KF-29 | User dapat melihat hasil penilaian            |
| KF-30 | User dapat melihat pembahasan setelah selesai |
| KF-31 | User dapat melihat solusi utama               |
| KF-32 | User dapat melihat alternatif solusi          |
| KF-33 | User dapat melihat riwayat                    |
| KF-34 | User dapat mengubah profil                    |
| KF-35 | User dapat mengubah password                  |
| KF-36 | Admin dapat melihat dashboard administrator   |
| KF-37 | Admin dapat melihat statistik                 |
| KF-38 | Admin dapat mengelola kategori                |
| KF-39 | Admin dapat mengelola tingkat kesulitan       |
| KF-40 | Admin dapat menambah tantangan                |
| KF-41 | Admin dapat mengubah tantangan                |
| KF-42 | Admin dapat menonaktifkan tantangan           |
| KF-43 | Admin dapat mengatur lokasi baris bug         |
| KF-44 | Admin dapat mengatur hint                     |
| KF-45 | Admin dapat mengatur penalti hint             |
| KF-46 | Admin dapat mengatur solusi utama             |
| KF-47 | Admin dapat mengatur solusi alternatif        |
| KF-48 | Admin dapat mengatur kata kunci               |
| KF-49 | Admin dapat mengatur poin                     |
| KF-50 | Admin dapat mengatur publikasi                |
| KF-51 | Admin dapat melihat pengguna                  |
| KF-52 | Admin dapat mengubah role                     |
| KF-53 | Admin dapat melihat seluruh submission        |
| KF-54 | Admin dapat melihat detail submission         |

## 13. Kebutuhan Nonfungsional

| ID     | Kategori        | Kebutuhan                                 |
| ------ | --------------- | ----------------------------------------- |
| KNF-01 | Keamanan        | Password disimpan menggunakan hashing     |
| KNF-02 | Keamanan        | Sistem tidak menggunakan `eval()`         |
| KNF-03 | Keamanan        | Sistem tidak menjalankan kode pengguna    |
| KNF-04 | Keamanan        | Halaman admin dilindungi middleware       |
| KNF-05 | Keamanan        | Request divalidasi di backend             |
| KNF-06 | Keamanan        | Sistem menggunakan CSRF protection        |
| KNF-07 | Keamanan        | Panjang kode dibatasi                     |
| KNF-08 | Keamanan        | Panjang penjelasan dibatasi               |
| KNF-09 | Keamanan        | Kode ditampilkan sebagai teks             |
| KNF-10 | Keamanan        | Submission memiliki rate limit            |
| KNF-11 | Keamanan        | Pembukaan hint memiliki rate limit        |
| KNF-12 | Kerahasiaan     | Solusi disembunyikan sebelum selesai      |
| KNF-13 | Integritas      | Penyimpanan jawaban menggunakan transaksi |
| KNF-14 | Integritas      | Total poin berasal dari skor terbaik      |
| KNF-15 | Integritas      | Progress user dan challenge harus unik    |
| KNF-16 | Reliabilitas    | Data submission tersimpan dengan benar    |
| KNF-17 | Kinerja         | Waktu pemuatan tetap wajar                |
| KNF-18 | Usability       | Antarmuka responsif                       |
| KNF-19 | Usability       | Editor diprioritaskan untuk desktop       |
| KNF-20 | Kompatibilitas  | Mendukung browser modern                  |
| KNF-21 | Maintainability | Backend dan frontend dipisahkan           |
| KNF-22 | Testability     | Fitur inti memiliki automated test        |
| KNF-23 | Database        | Database utama menggunakan PostgreSQL     |
| KNF-24 | Aksesibilitas   | Input memiliki label yang jelas           |
| KNF-25 | Feedback        | Pesan validasi ditampilkan kepada user    |

## 14. Wireframe

Wireframe adalah rancangan awal tata letak halaman sebelum menjadi
tampilan final.

Wireframe berbeda dari:

- Use Case Diagram.
- Activity Diagram.
- ERD.
- DFD.

Wireframe tidak menggambarkan aliran data atau relasi database.
Wireframe hanya menggambarkan posisi komponen antarmuka seperti navbar,
sidebar, tombol, form, card, tabel, dan code editor.

Wireframe BugHunt harus dibuat sebagai gambar, bukan diagram ASCII.

Simpan wireframe pada:

```text
docs/wireframes/
```

Daftar wireframe yang harus tersedia:

| No. | Halaman              | Nama file                     |
| --: | -------------------- | ----------------------------- |
|   1 | Landing Page         | `01-landing-page.png`         |
|   2 | Dashboard User       | `02-dashboard-user.png`       |
|   3 | Daftar Tantangan     | `03-daftar-tantangan.png`     |
|   4 | Pengerjaan Tantangan | `04-pengerjaan-tantangan.png` |
|   5 | Hasil Pengerjaan     | `05-hasil-pengerjaan.png`     |
|   6 | Leaderboard          | `06-leaderboard.png`          |
|   7 | Dashboard Admin      | `07-dashboard-admin.png`      |
|   8 | Form Tantangan       | `08-form-tantangan.png`       |

### 14.1 Wireframe Landing Page

Komponen:

- Navbar.
- Logo BugHunt.
- Menu Beranda.
- Menu Tantangan.
- Menu Leaderboard.
- Menu Tentang.
- Tombol login atau register.
- Hero section.
- Call-to-action.
- Statistik aplikasi.
- Penjelasan cara kerja.
- Tantangan terbaru.
- Footer.

### 14.2 Wireframe Dashboard User

Komponen:

- Navbar.
- Total poin.
- Jumlah tantangan selesai.
- Jumlah percobaan.
- Progres pengguna.
- Aktivitas terakhir.
- Rekomendasi tantangan.

### 14.3 Wireframe Daftar Tantangan

Komponen:

- Judul halaman.
- Input pencarian.
- Filter kategori.
- Filter kesulitan.
- Card tantangan.
- Status progres.
- Pagination.

### 14.4 Wireframe Pengerjaan Tantangan

Komponen:

- Judul tantangan.
- Kategori.
- Kesulitan.
- Poin maksimum.
- Deskripsi masalah.
- Daftar hint.
- Pemilihan baris bug.
- CodeMirror.
- Input penjelasan.
- Tombol kirim jawaban.

### 14.5 Wireframe Hasil Pengerjaan

Komponen:

- Status submission.
- Skor akhir.
- Skor baris.
- Skor kode.
- Skor penjelasan.
- Penalti hint.
- Kode yang dikirim.
- Solusi utama atau tampilan terkunci.
- Penjelasan pengguna.
- Pembahasan atau tampilan terkunci.
- Alternatif solusi.
- Tombol kerjakan lagi.
- Tombol riwayat.
- Tombol tantangan lain.

### 14.6 Wireframe Leaderboard

Komponen:

- Judul leaderboard.
- Tiga peringkat teratas.
- Tabel peringkat.
- Nama pengguna.
- Total poin.
- Jumlah tantangan selesai.
- Tanggal bergabung.

### 14.7 Wireframe Dashboard Admin

Komponen:

- Sidebar admin.
- Ringkasan jumlah user.
- Ringkasan jumlah tantangan.
- Ringkasan jumlah submission.
- Grafik status submission.
- Grafik kategori.
- Daftar submission terbaru.

### 14.8 Wireframe Form Tantangan

Komponen:

- Input judul.
- Input slug.
- Pilihan kategori.
- Pilihan kesulitan.
- Input poin.
- Pilihan status.
- Input deskripsi.
- Code editor kode bermasalah.
- Input nomor baris bug.
- Input pembahasan.
- Form hint.
- Form penalti hint.
- Form solusi utama.
- Form alternatif solusi.
- Form kata kunci.
- Tombol simpan.

## 15. Screenshot Aplikasi

Screenshot harus diambil dari aplikasi yang benar-benar berjalan.

Simpan screenshot pada:

```text
docs/screenshots/
```

Ukuran browser yang disarankan:

```text
1440 × 900 piksel
```

Daftar screenshot:

| No. | Halaman              | Nama file                     |
| --: | -------------------- | ----------------------------- |
|   1 | Landing Page         | `01-landing-page.png`         |
|   2 | Login                | `02-login.png`                |
|   3 | Register             | `03-register.png`             |
|   4 | Dashboard User       | `04-dashboard-user.png`       |
|   5 | Daftar Tantangan     | `05-daftar-tantangan.png`     |
|   6 | Detail Tantangan     | `06-detail-tantangan.png`     |
|   7 | Pengerjaan Tantangan | `07-pengerjaan-tantangan.png` |
|   8 | Hasil Belum Selesai  | `08-hasil-belum-selesai.png`  |
|   9 | Hasil Selesai        | `09-hasil-selesai.png`        |
|  10 | Riwayat              | `10-riwayat.png`              |
|  11 | Leaderboard          | `11-leaderboard.png`          |
|  12 | Dashboard Admin      | `12-dashboard-admin.png`      |
|  13 | Statistik Admin      | `13-statistik-admin.png`      |
|  14 | Kelola Kategori      | `14-kelola-kategori.png`      |
|  15 | Kelola Kesulitan     | `15-kelola-kesulitan.png`     |
|  16 | Kelola Tantangan     | `16-kelola-tantangan.png`     |
|  17 | Form Tantangan       | `17-form-tantangan.png`       |
|  18 | Kelola Pengguna      | `18-kelola-pengguna.png`      |
|  19 | Data Submission      | `19-data-submission.png`      |
|  20 | Tampilan Mobile      | `20-responsive-mobile.png`    |

## 16. Skenario Video Demonstrasi

Durasi video yang disarankan adalah 5 sampai 7 menit.

| Durasi      | Demonstrasi                         |
| ----------- | ----------------------------------- |
| 00:00–00:30 | Perkenalan BugHunt                  |
| 00:30–01:00 | Landing page dan daftar tantangan   |
| 01:00–01:30 | Login sebagai user                  |
| 01:30–02:30 | Memilih dan mengerjakan tantangan   |
| 02:30–03:00 | Membuka hint dan melihat penalti    |
| 03:00–03:30 | Mengirim jawaban yang belum selesai |
| 03:30–04:00 | Menunjukkan solusi masih terkunci   |
| 04:00–04:30 | Mengirim jawaban yang benar         |
| 04:30–05:00 | Melihat skor dan pembahasan         |
| 05:00–05:30 | Melihat riwayat dan leaderboard     |
| 05:30–06:00 | Login sebagai administrator         |
| 06:00–06:30 | Mengelola kategori dan tantangan    |
| 06:30–07:00 | Melihat statistik dan penutup       |

Contoh pembukaan video:

> BugHunt merupakan platform pembelajaran debugging pemrograman
> berbasis web. Pengguna harus menemukan lokasi bug, memperbaiki kode,
> dan menjelaskan penyebab kesalahan. Sistem kemudian memberikan skor,
> pembahasan, riwayat, dan leaderboard.

## 17. Keamanan

Aturan keamanan BugHunt:

- Tidak menggunakan `eval`.
- Tidak menjalankan kode pengguna di terminal.
- Tidak menjalankan compiler dari jawaban pengguna.
- Password disimpan menggunakan hashing Laravel.
- Request divalidasi pada backend.
- Halaman admin dilindungi middleware role.
- Menggunakan CSRF protection.
- Membatasi panjang kode.
- Membatasi panjang penjelasan.
- Menampilkan kode sebagai teks.
- Tidak merender kode pengguna sebagai HTML.
- Submission memiliki rate limit.
- Pembukaan hint memiliki rate limit.
- Solusi tidak dikirim sebelum challenge selesai.
- Query database menggunakan Eloquent dan Query Builder.

## 18. Automated Testing

Test yang tersedia:

- Test autentikasi.
- Test authorization.
- Test role admin.
- Test seeder.
- Test jumlah kategori.
- Test jumlah tingkat kesulitan.
- Test jumlah tantangan.
- Test distribusi tantangan.
- Test solusi utama.
- Test hint.
- Test evaluasi kode.
- Test submission.
- Test penalti hint.
- Test skor terbaik.
- Test pencegahan penggandaan poin.
- Test solusi terkunci.
- Test solusi terbuka setelah challenge selesai.

Menjalankan seluruh test:

```bash
php artisan test
```

Menjalankan test submission:

```bash
php artisan test --filter=ChallengeSubmissionTest
```

Menjalankan test seeder:

```bash
php artisan test --filter=BugHuntSeederTest
```

Menjalankan build frontend:

```bash
npm run build
```

## 19. Akun Demo

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

Akun tersebut hanya digunakan untuk development dan demonstrasi.

## 20. Repository

Repository GitHub:

```text
https://github.com/ki1bot/debugging-pemrograman
```

## 21. Deployment

Status deployment saat ini:

```text
Belum tersedia
```

Setelah aplikasi berhasil di-hosting, ubah menjadi:

```text
URL aplikasi: https://alamat-aplikasi
```

## 22. Lisensi

BugHunt menggunakan MIT License.

File lisensi:

```text
LICENSE
```

## 23. Struktur Dokumentasi

Struktur dokumentasi yang digunakan:

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
├── wireframes/
│   ├── 01-landing-page.png
│   ├── 02-dashboard-user.png
│   ├── 03-daftar-tantangan.png
│   ├── 04-pengerjaan-tantangan.png
│   ├── 05-hasil-pengerjaan.png
│   ├── 06-leaderboard.png
│   ├── 07-dashboard-admin.png
│   └── 08-form-tantangan.png
└── screenshots/
    ├── 01-landing-page.png
    ├── 02-login.png
    └── file screenshot lainnya
```

## 24. Checklist Penyelesaian

### Fitur Aplikasi

- [x] Registrasi.
- [x] Login.
- [x] Logout.
- [x] Role user dan admin.
- [x] Daftar tantangan.
- [x] Filter tantangan.
- [x] Detail tantangan.
- [x] Pemilihan baris bug.
- [x] CodeMirror.
- [x] Penjelasan pengguna.
- [x] Hint.
- [x] Penalti hint.
- [x] Penilaian.
- [x] Submission.
- [x] Riwayat.
- [x] Total poin.
- [x] Leaderboard.
- [x] Dashboard user.
- [x] Dashboard admin.
- [x] Statistik admin.
- [x] CRUD kategori.
- [x] CRUD tingkat kesulitan.
- [x] CRUD tantangan.
- [x] Pengelolaan pengguna.
- [x] Data submission.
- [x] Solusi terkunci sebelum challenge selesai.

### Data dan Testing

- [x] Tiga kategori.
- [x] Tiga tingkat kesulitan.
- [x] Dua puluh empat tantangan.
- [x] Akun demo admin.
- [x] Akun demo user.
- [x] Test seeder.
- [x] Test authorization.
- [x] Test evaluation.
- [x] Test submission.

### Dokumentasi

- [x] README.
- [x] Use Case Diagram.
- [x] Activity Diagram.
- [x] ERD.
- [x] DFD Level 0.
- [x] DFD Level 1.
- [x] Kebutuhan fungsional.
- [x] Kebutuhan nonfungsional.
- [ ] Wireframe dalam bentuk gambar.
- [ ] Screenshot aplikasi.
- [ ] Video demonstrasi.
- [ ] Link deployment aktif.

## 25. Validasi Akhir

Sebelum presentasi atau pengumpulan:

1. Jalankan seluruh migration.
2. Jalankan seeder.
3. Jalankan seluruh automated test.
4. Jalankan production build.
5. Periksa 24 tantangan.
6. Periksa akun demo.
7. Periksa setiap diagram.
8. Tambahkan wireframe berbentuk gambar.
9. Ambil screenshot aplikasi.
10. Rekam video demonstrasi.
11. Lakukan deployment.
12. Perbarui URL deployment pada README dan dokumentasi.
