<?php

namespace Database\Seeders\Data;

class PhpChallenges
{
    public static function all(): array
    {
        return [
            0 => [
                'category' => 'php',
                'difficulty' => 'mudah',
                'title' => 'Kesalahan Operator Perbandingan',
                'slug' => 'php-kesalahan-operator-perbandingan',
                'description' => 'Kondisi selalu dianggap benar karena nilai status diubah ketika diperiksa.',
                'broken_code' => '<?php
$status = \'inactive\';
if ($status = \'active\') {
    echo \'Akun aktif\';
}',
                'buggy_line' => 3,
                'explanation' => 'Operator = melakukan assignment ke variabel $status. Untuk membandingkan nilai dan tipe string, gunakan operator identik ===.',
                'hints' => [
                    0 => [
                        'content' => 'Operator pada kondisi saat ini mengubah isi variabel.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan operator identik PHP.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
$status = \'inactive\';
if ($status === \'active\') {
    echo \'Akun aktif\';
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'assignment',
                            1 => 'perbandingan',
                            2 => '===',
                            3 => '$status',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
$status = \'inactive\';
if ($status == \'active\') {
    echo \'Akun aktif\';
}',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            1 => [
                'category' => 'php',
                'difficulty' => 'mudah',
                'title' => 'Variabel Form Tidak Ditemukan',
                'slug' => 'php-variabel-form-tidak-ditemukan',
                'description' => 'Data form memiliki key email, tetapi program membaca key yang berbeda sehingga nilai email tidak ditemukan.',
                'broken_code' => '<?php
$form = json_decode(trim(fgets(STDIN)), true) ?? [];
$email = $form[\'username\'] ?? \'\';
echo $email;',
                'buggy_line' => 3,
                'explanation' => 'Data form menggunakan key email, tetapi kode membaca key username. Key array harus sama dengan nama field yang dikirim agar nilai email dapat ditemukan.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa key yang tersedia pada data form dan key yang dibaca program.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Ganti key username menjadi email.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
$form = json_decode(trim(fgets(STDIN)), true) ?? [];
$email = $form[\'email\'] ?? \'\';
echo $email;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'form',
                            1 => 'email',
                            2 => 'key',
                            3 => 'username',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
$form = json_decode(trim(fgets(STDIN)), true) ?? [];
$email = array_key_exists(\'email\', $form) ? $form[\'email\'] : \'\';
echo $email;',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            2 => [
                'category' => 'php',
                'difficulty' => 'mudah',
                'title' => 'Perulangan Array yang Salah',
                'slug' => 'php-perulangan-array-yang-salah',
                'description' => 'Perulangan mencoba membaca satu indeks setelah elemen terakhir array.',
                'broken_code' => '<?php
$items = [\'A\', \'B\', \'C\'];
for ($i = 0; $i <= count($items); $i++) {
    echo $items[$i];
}',
                'buggy_line' => 3,
                'explanation' => 'Jumlah elemen array adalah 3, tetapi indeks valid hanya 0 sampai 2. Kondisi <= membuat perulangan mencapai indeks 3. Gunakan $i < count($items).',
                'hints' => [
                    0 => [
                        'content' => 'Bandingkan jumlah elemen dengan indeks terakhir array.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Operator kondisi perulangan harus menghentikan proses sebelum count($items).',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
$items = [\'A\', \'B\', \'C\'];
for ($i = 0; $i < count($items); $i++) {
    echo $items[$i];
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'array',
                            1 => 'indeks',
                            2 => 'count',
                            3 => 'di luar batas',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
$items = [\'A\', \'B\', \'C\'];
foreach ($items as $item) {
    echo $item;
}',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            3 => [
                'category' => 'php',
                'difficulty' => 'menengah',
                'title' => 'Kesalahan Penggunaan Session',
                'slug' => 'php-kesalahan-penggunaan-session',
                'description' => 'Program membatalkan session ketika seharusnya memulai session sebelum data session digunakan.',
                'broken_code' => '<?php
session_abort();
$_SESSION[\'user_id\'] = 10;
if (session_status() !== PHP_SESSION_ACTIVE) {
    throw new RuntimeException(\'Session belum dimulai.\');
}
echo $_SESSION[\'user_id\'];',
                'buggy_line' => 2,
                'explanation' => 'session_abort() tidak menginisialisasi session. Gunakan session_start() sebelum membaca atau menulis $_SESSION agar status session menjadi PHP_SESSION_ACTIVE.',
                'hints' => [
                    0 => [
                        'content' => 'Baris kedua menggunakan operasi session yang tidak mengaktifkan session.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Ganti session_abort() dengan session_start().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
session_start();
$_SESSION[\'user_id\'] = 10;
if (session_status() !== PHP_SESSION_ACTIVE) {
    throw new RuntimeException(\'Session belum dimulai.\');
}
echo $_SESSION[\'user_id\'];',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'session_start',
                            1 => '$_SESSION',
                            2 => 'PHP_SESSION_ACTIVE',
                            3 => 'session_abort',
                        ],
                    ],
                ],
            ],
            4 => [
                'category' => 'php',
                'difficulty' => 'menengah',
                'title' => 'Query Tidak Mengecek Hasil',
                'slug' => 'php-query-tidak-mengecek-hasil',
                'description' => 'Hasil eksekusi query dapat bernilai false, tetapi program langsung memperlakukannya sebagai kumpulan baris.',
                'broken_code' => '<?php
function executeQuery(string $sql): array|false {
    return str_contains($sql, \'missing_table\') ? false : [[\'name\' => \'Rifqi\']];
}
function firstRow(array $result): array {
    return $result[0];
}
$sql = \'SELECT name FROM missing_table\';
$result = executeQuery($sql);
$row = firstRow($result);
echo $row[\'name\'];',
                'buggy_line' => 10,
                'explanation' => 'Fungsi executeQuery dapat mengembalikan false ketika query gagal. Nilai false tidak boleh langsung diberikan ke fungsi yang mengharapkan array. Periksa hasil query terlebih dahulu dan tangani kondisi gagal sebelum membaca baris.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa tipe nilai yang mungkin dikembalikan executeQuery.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tangani nilai false sebelum memanggil firstRow().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
function executeQuery(string $sql): array|false {
    return str_contains($sql, \'missing_table\') ? false : [[\'name\' => \'Rifqi\']];
}
function firstRow(array $result): array {
    return $result[0];
}
$sql = \'SELECT name FROM missing_table\';
$result = executeQuery($sql);
if ($result === false) {
    echo \'Query gagal\';
    exit;
}
$row = firstRow($result);
echo $row[\'name\'];',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'false',
                            1 => 'hasil query',
                            2 => 'pengecekan',
                            3 => 'array',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
function executeQuery(string $sql): array|false {
    return str_contains($sql, \'missing_table\') ? false : [[\'name\' => \'Rifqi\']];
}
function firstRow(array $result): array {
    return $result[0];
}
$sql = \'SELECT name FROM missing_table\';
$result = executeQuery($sql);
if (is_array($result) && isset($result[0])) {
    $row = firstRow($result);
    echo $row[\'name\'];
}',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            5 => [
                'category' => 'php',
                'difficulty' => 'menengah',
                'title' => 'Kesalahan Pemanggilan Function',
                'slug' => 'php-kesalahan-pemanggilan-function',
                'description' => 'Function memerlukan dua argumen tetapi hanya menerima satu argumen saat dipanggil.',
                'broken_code' => '<?php
function calculateTotal(float $price, int $quantity): float {
    return $price * $quantity;
}
echo calculateTotal(25000);',
                'buggy_line' => 5,
                'explanation' => 'calculateTotal mendefinisikan parameter $price dan $quantity. Pemanggilan hanya mengirim $price sehingga PHP melempar ArgumentCountError. Kirim kedua argumen yang dibutuhkan.',
                'hints' => [
                    0 => [
                        'content' => 'Hitung jumlah parameter pada deklarasi function.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tambahkan nilai quantity pada saat function dipanggil.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
function calculateTotal(float $price, int $quantity): float {
    return $price * $quantity;
}
echo calculateTotal(25000, 2);',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'parameter',
                            1 => 'argumen',
                            2 => '$quantity',
                            3 => 'ArgumentCountError',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
function calculateTotal(float $price, int $quantity = 1): float {
    return $price * $quantity;
}
echo calculateTotal(25000);',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            6 => [
                'category' => 'php',
                'difficulty' => 'sulit',
                'title' => 'Password Disimpan Tanpa Hash',
                'slug' => 'php-password-disimpan-tanpa-hash',
                'description' => 'Password pengguna masih disimpan dalam bentuk teks biasa sehingga password asli dapat diketahui jika data penyimpanan bocor.',
                'broken_code' => '<?php
$password = trim(fgets(STDIN));
$storedPassword = $password;
echo $storedPassword;',
                'buggy_line' => 3,
                'explanation' => 'Password tidak boleh disimpan sebagai plaintext. Password harus diubah menjadi hash satu arah menggunakan password_hash() dengan PASSWORD_DEFAULT sebelum disimpan. Ketika melakukan autentikasi, password yang diberikan pengguna dapat diperiksa terhadap hash menggunakan password_verify().',
                'hints' => [
                    0 => [
                        'content' => 'Periksa apakah nilai yang disimpan masih sama persis dengan password asli.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan password_hash() dengan PASSWORD_DEFAULT sebelum password disimpan.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
$password = trim(fgets(STDIN));
$storedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $storedPassword;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'password_hash',
                            1 => 'plaintext',
                            2 => 'PASSWORD_DEFAULT',
                            3 => 'password_verify',
                        ],
                    ],
                ],
            ],
            7 => [
                'category' => 'php',
                'difficulty' => 'sulit',
                'title' => 'Input Rentan SQL Injection',
                'slug' => 'php-input-rentan-sql-injection',
                'description' => 'Input email pengguna digabungkan langsung ke string query sehingga struktur SQL dapat dimanipulasi.',
                'broken_code' => '<?php
$email = trim(fgets(STDIN));
$query = "SELECT * FROM users WHERE email = \'$email\'";
echo $query;',
                'buggy_line' => 3,
                'explanation' => 'Menggabungkan input pengguna langsung ke string SQL membuka celah SQL injection. Gunakan prepared statement dengan placeholder dan kirim nilai input sebagai parameter terpisah saat query dieksekusi.',
                'hints' => [
                    0 => [
                        'content' => 'Jangan masukkan nilai email langsung ke dalam teks query.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan placeholder dan simpan nilai email sebagai parameter terpisah.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
$email = trim(fgets(STDIN));
$query = \'SELECT * FROM users WHERE email = ?\';
$params = [$email];
echo $query.PHP_EOL;
echo json_encode($params, JSON_UNESCAPED_SLASHES);',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'SQL injection',
                            1 => 'prepared statement',
                            2 => 'placeholder',
                            3 => 'parameter',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
$email = trim(fgets(STDIN));
$query = \'SELECT * FROM users WHERE email = :email\';
$params = [\'email\' => $email];
echo $query.PHP_EOL;
echo json_encode($params, JSON_UNESCAPED_SLASHES);',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
        ];
    }
}
