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
                'description' => 'Nilai email tidak terbaca karena key input yang diakses tidak sesuai dengan nama field form.',
                'broken_code' => '<?php
$email = $_POST[\'username\'];
echo $email;',
                'buggy_line' => 2,
                'explanation' => 'Form mengirim field email, tetapi kode membaca key username. Gunakan key email dan null coalescing agar tidak muncul peringatan undefined array key ketika field tidak tersedia.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa kesesuaian nama field HTML dengan key pada $_POST.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tambahkan nilai default ketika key tidak tersedia.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
$email = $_POST[\'email\'] ?? \'\';
echo $email;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => '$_POST',
                            1 => 'email',
                            2 => 'key',
                            3 => 'null coalescing',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
$email = isset($_POST[\'email\']) ? $_POST[\'email\'] : \'\';
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
                'description' => 'Data session digunakan sebelum session PHP diinisialisasi.',
                'broken_code' => '<?php
$_SESSION[\'user_id\'] = 10;
echo $_SESSION[\'user_id\'];',
                'buggy_line' => 2,
                'explanation' => 'Session harus dimulai menggunakan session_start() sebelum membaca atau menulis $_SESSION. Pemanggilan dilakukan sebelum output apa pun dikirim ke browser.',
                'hints' => [
                    0 => [
                        'content' => 'Superglobal $_SESSION membutuhkan proses inisialisasi.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tambahkan session_start() sebelum assignment.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
session_start();
$_SESSION[\'user_id\'] = 10;
echo $_SESSION[\'user_id\'];',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'session_start',
                            1 => '$_SESSION',
                            2 => 'inisialisasi',
                            3 => 'sebelum output',
                        ],
                    ],
                ],
            ],
            4 => [
                'category' => 'php',
                'difficulty' => 'menengah',
                'title' => 'Query Tidak Mengecek Hasil',
                'slug' => 'php-query-tidak-mengecek-hasil',
                'description' => 'mysqli_fetch_assoc menerima boolean false ketika query database gagal.',
                'broken_code' => '<?php
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
echo $row[\'name\'];',
                'buggy_line' => 3,
                'explanation' => 'mysqli_query dapat mengembalikan false. Nilai tersebut tidak boleh langsung diberikan kepada mysqli_fetch_assoc. Periksa hasil query terlebih dahulu dan tangani kegagalannya.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa kemungkinan nilai kembalian mysqli_query ketika query gagal.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tambahkan kondisi sebelum memanggil mysqli_fetch_assoc.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
$result = mysqli_query($connection, $sql);
if ($result === false) {
    throw new RuntimeException(mysqli_error($connection));
}
$row = mysqli_fetch_assoc($result);
echo $row[\'name\'];',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'mysqli_query',
                            1 => 'false',
                            2 => 'mysqli_fetch_assoc',
                            3 => 'pengecekan',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
$result = mysqli_query($connection, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
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
                'description' => 'Input email pengguna digabungkan langsung ke query SQL.',
                'broken_code' => '<?php
$email = $_POST[\'email\'];
$query = "SELECT * FROM users WHERE email = \'$email\'";
$result = $pdo->query($query);',
                'buggy_line' => 3,
                'explanation' => 'Menggabungkan input pengguna ke string SQL membuka celah SQL injection. Gunakan prepared statement dengan placeholder dan binding parameter.',
                'hints' => [
                    0 => [
                        'content' => 'Jangan gabungkan input pengguna secara langsung ke query.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan prepare(), placeholder, dan execute().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '<?php
$email = $_POST[\'email\'];
$stmt = $pdo->prepare(\'SELECT * FROM users WHERE email = :email\');
$stmt->execute([\'email\' => $email]);
$result = $stmt->fetchAll();',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'SQL injection',
                            1 => 'prepared statement',
                            2 => 'placeholder',
                            3 => 'binding',
                        ],
                    ],
                    1 => [
                        'solution_code' => '<?php
$email = $_POST[\'email\'];
$stmt = $pdo->prepare(\'SELECT * FROM users WHERE email = ?\');
$stmt->execute([$email]);
$result = $stmt->fetchAll();',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
        ];
    }
}
