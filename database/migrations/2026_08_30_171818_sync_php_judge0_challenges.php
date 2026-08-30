<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            foreach ($this->newDefinitions() as $definition) {
                $this->syncChallenge($definition);
            }
        });
    }

    public function down(): void
    {
        DB::transaction(function (): void {
            foreach ($this->oldDefinitions() as $definition) {
                $this->syncChallenge($definition);
            }
        });
    }

    private function syncChallenge(array $definition): void
    {
        $challenge = DB::table('challenges')
            ->where('slug', $definition['slug'])
            ->first(['id']);

        if ($challenge === null) {
            return;
        }

        $now = now();

        DB::table('challenges')
            ->where('id', $challenge->id)
            ->update([
                'description' => $definition['description'],
                'broken_code' => $definition['broken_code'],
                'buggy_line' => $definition['buggy_line'],
                'explanation' => $definition['explanation'],
                'updated_at' => $now,
            ]);

        foreach ($definition['hints'] as $hintOrder => $hint) {
            $existingHint = DB::table('challenge_hints')
                ->where('challenge_id', $challenge->id)
                ->where('hint_order', $hintOrder)
                ->first(['id']);

            $values = [
                'content' => $hint['content'],
                'point_penalty' => $hint['point_penalty'],
                'updated_at' => $now,
            ];

            if ($existingHint === null) {
                DB::table('challenge_hints')->insert([
                    'challenge_id' => $challenge->id,
                    'hint_order' => $hintOrder,
                    ...$values,
                    'created_at' => $now,
                ]);
            } else {
                DB::table('challenge_hints')
                    ->where('id', $existingHint->id)
                    ->update($values);
            }
        }

        foreach ($definition['solutions'] as $solutionType => $solution) {
            $existingSolution = DB::table('challenge_solutions')
                ->where('challenge_id', $challenge->id)
                ->where('solution_type', $solutionType)
                ->first(['id']);

            $values = [
                'solution_code' => $solution['solution_code'],
                'required_keywords' => json_encode(
                    $solution['required_keywords'],
                    JSON_THROW_ON_ERROR,
                ),
                'updated_at' => $now,
            ];

            if ($existingSolution === null) {
                DB::table('challenge_solutions')->insert([
                    'challenge_id' => $challenge->id,
                    'solution_type' => $solutionType,
                    ...$values,
                    'created_at' => $now,
                ]);
            } else {
                DB::table('challenge_solutions')
                    ->where('id', $existingSolution->id)
                    ->update($values);
            }
        }
    }

    private function newDefinitions(): array
    {
        return [
            [
                'slug' => 'php-variabel-form-tidak-ditemukan',
                'description' => 'Data form memiliki key email, tetapi program membaca key yang berbeda sehingga nilai email tidak ditemukan.',
                'broken_code' => <<<'PHP'
<?php
$form = json_decode(trim(fgets(STDIN)), true) ?? [];
$email = $form['username'] ?? '';
echo $email;
PHP,
                'buggy_line' => 3,
                'explanation' => 'Data form menggunakan key email, tetapi kode membaca key username. Key array harus sama dengan nama field yang dikirim agar nilai email dapat ditemukan.',
                'hints' => [
                    1 => [
                        'content' => 'Periksa key yang tersedia pada data form dan key yang dibaca program.',
                        'point_penalty' => 10,
                    ],
                    2 => [
                        'content' => 'Ganti key username menjadi email.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    'primary' => [
                        'solution_code' => <<<'PHP'
<?php
$form = json_decode(trim(fgets(STDIN)), true) ?? [];
$email = $form['email'] ?? '';
echo $email;
PHP,
                        'required_keywords' => [
                            'form',
                            'email',
                            'key',
                            'username',
                        ],
                    ],
                    'alternative' => [
                        'solution_code' => <<<'PHP'
<?php
$form = json_decode(trim(fgets(STDIN)), true) ?? [];
$email = array_key_exists('email', $form) ? $form['email'] : '';
echo $email;
PHP,
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'slug' => 'php-kesalahan-penggunaan-session',
                'description' => 'Program membatalkan session ketika seharusnya memulai session sebelum data session digunakan.',
                'broken_code' => <<<'PHP'
<?php
session_abort();
$_SESSION['user_id'] = 10;
if (session_status() !== PHP_SESSION_ACTIVE) {
    throw new RuntimeException('Session belum dimulai.');
}
echo $_SESSION['user_id'];
PHP,
                'buggy_line' => 2,
                'explanation' => 'session_abort() tidak menginisialisasi session. Gunakan session_start() sebelum membaca atau menulis $_SESSION agar status session menjadi PHP_SESSION_ACTIVE.',
                'hints' => [
                    1 => [
                        'content' => 'Baris kedua menggunakan operasi session yang tidak mengaktifkan session.',
                        'point_penalty' => 10,
                    ],
                    2 => [
                        'content' => 'Ganti session_abort() dengan session_start().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    'primary' => [
                        'solution_code' => <<<'PHP'
<?php
session_start();
$_SESSION['user_id'] = 10;
if (session_status() !== PHP_SESSION_ACTIVE) {
    throw new RuntimeException('Session belum dimulai.');
}
echo $_SESSION['user_id'];
PHP,
                        'required_keywords' => [
                            'session_start',
                            '$_SESSION',
                            'PHP_SESSION_ACTIVE',
                            'session_abort',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'php-query-tidak-mengecek-hasil',
                'description' => 'Hasil eksekusi query dapat bernilai false, tetapi program langsung memperlakukannya sebagai kumpulan baris.',
                'broken_code' => <<<'PHP'
<?php
function executeQuery(string $sql): array|false {
    return str_contains($sql, 'missing_table') ? false : [['name' => 'Rifqi']];
}
function firstRow(array $result): array {
    return $result[0];
}
$sql = 'SELECT name FROM missing_table';
$result = executeQuery($sql);
$row = firstRow($result);
echo $row['name'];
PHP,
                'buggy_line' => 10,
                'explanation' => 'Fungsi executeQuery dapat mengembalikan false ketika query gagal. Nilai false tidak boleh langsung diberikan ke fungsi yang mengharapkan array. Periksa hasil query terlebih dahulu dan tangani kondisi gagal sebelum membaca baris.',
                'hints' => [
                    1 => [
                        'content' => 'Periksa tipe nilai yang mungkin dikembalikan executeQuery.',
                        'point_penalty' => 10,
                    ],
                    2 => [
                        'content' => 'Tangani nilai false sebelum memanggil firstRow().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    'primary' => [
                        'solution_code' => <<<'PHP'
<?php
function executeQuery(string $sql): array|false {
    return str_contains($sql, 'missing_table') ? false : [['name' => 'Rifqi']];
}
function firstRow(array $result): array {
    return $result[0];
}
$sql = 'SELECT name FROM missing_table';
$result = executeQuery($sql);
if ($result === false) {
    echo 'Query gagal';
    exit;
}
$row = firstRow($result);
echo $row['name'];
PHP,
                        'required_keywords' => [
                            'false',
                            'hasil query',
                            'pengecekan',
                            'array',
                        ],
                    ],
                    'alternative' => [
                        'solution_code' => <<<'PHP'
<?php
function executeQuery(string $sql): array|false {
    return str_contains($sql, 'missing_table') ? false : [['name' => 'Rifqi']];
}
function firstRow(array $result): array {
    return $result[0];
}
$sql = 'SELECT name FROM missing_table';
$result = executeQuery($sql);
if (is_array($result) && isset($result[0])) {
    $row = firstRow($result);
    echo $row['name'];
}
PHP,
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'slug' => 'php-input-rentan-sql-injection',
                'description' => 'Input email pengguna digabungkan langsung ke string query sehingga struktur SQL dapat dimanipulasi.',
                'broken_code' => <<<'PHP'
<?php
$email = trim(fgets(STDIN));
$query = "SELECT * FROM users WHERE email = '$email'";
echo $query;
PHP,
                'buggy_line' => 3,
                'explanation' => 'Menggabungkan input pengguna langsung ke string SQL membuka celah SQL injection. Gunakan prepared statement dengan placeholder dan kirim nilai input sebagai parameter terpisah saat query dieksekusi.',
                'hints' => [
                    1 => [
                        'content' => 'Jangan masukkan nilai email langsung ke dalam teks query.',
                        'point_penalty' => 10,
                    ],
                    2 => [
                        'content' => 'Gunakan placeholder dan simpan nilai email sebagai parameter terpisah.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    'primary' => [
                        'solution_code' => <<<'PHP'
<?php
$email = trim(fgets(STDIN));
$query = 'SELECT * FROM users WHERE email = ?';
$params = [$email];
echo $query.PHP_EOL;
echo json_encode($params, JSON_UNESCAPED_SLASHES);
PHP,
                        'required_keywords' => [
                            'SQL injection',
                            'prepared statement',
                            'placeholder',
                            'parameter',
                        ],
                    ],
                    'alternative' => [
                        'solution_code' => <<<'PHP'
<?php
$email = trim(fgets(STDIN));
$query = 'SELECT * FROM users WHERE email = :email';
$params = ['email' => $email];
echo $query.PHP_EOL;
echo json_encode($params, JSON_UNESCAPED_SLASHES);
PHP,
                        'required_keywords' => [],
                    ],
                ],
            ],
        ];
    }

    private function oldDefinitions(): array
    {
        return [
            [
                'slug' => 'php-variabel-form-tidak-ditemukan',
                'description' => 'Nilai email tidak terbaca karena key input yang diakses tidak sesuai dengan nama field form.',
                'broken_code' => <<<'PHP'
<?php
$email = $_POST['username'];
echo $email;
PHP,
                'buggy_line' => 2,
                'explanation' => 'Form mengirim field email, tetapi kode membaca key username. Gunakan key email dan null coalescing agar tidak muncul peringatan undefined array key ketika field tidak tersedia.',
                'hints' => [
                    1 => [
                        'content' => 'Periksa kesesuaian nama field HTML dengan key pada $_POST.',
                        'point_penalty' => 10,
                    ],
                    2 => [
                        'content' => 'Tambahkan nilai default ketika key tidak tersedia.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    'primary' => [
                        'solution_code' => <<<'PHP'
<?php
$email = $_POST['email'] ?? '';
echo $email;
PHP,
                        'required_keywords' => [
                            '$_POST',
                            'email',
                            'key',
                            'null coalescing',
                        ],
                    ],
                    'alternative' => [
                        'solution_code' => <<<'PHP'
<?php
$email = isset($_POST['email']) ? $_POST['email'] : '';
echo $email;
PHP,
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'slug' => 'php-kesalahan-penggunaan-session',
                'description' => 'Data session digunakan sebelum session PHP diinisialisasi.',
                'broken_code' => <<<'PHP'
<?php
$_SESSION['user_id'] = 10;
echo $_SESSION['user_id'];
PHP,
                'buggy_line' => 2,
                'explanation' => 'Session harus dimulai menggunakan session_start() sebelum membaca atau menulis $_SESSION. Pemanggilan dilakukan sebelum output apa pun dikirim ke browser.',
                'hints' => [
                    1 => [
                        'content' => 'Superglobal $_SESSION membutuhkan proses inisialisasi.',
                        'point_penalty' => 10,
                    ],
                    2 => [
                        'content' => 'Tambahkan session_start() sebelum assignment.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    'primary' => [
                        'solution_code' => <<<'PHP'
<?php
session_start();
$_SESSION['user_id'] = 10;
echo $_SESSION['user_id'];
PHP,
                        'required_keywords' => [
                            'session_start',
                            '$_SESSION',
                            'inisialisasi',
                            'sebelum output',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'php-query-tidak-mengecek-hasil',
                'description' => 'mysqli_fetch_assoc menerima boolean false ketika query database gagal.',
                'broken_code' => <<<'PHP'
<?php
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
echo $row['name'];
PHP,
                'buggy_line' => 3,
                'explanation' => 'mysqli_query dapat mengembalikan false. Nilai tersebut tidak boleh langsung diberikan kepada mysqli_fetch_assoc. Periksa hasil query terlebih dahulu dan tangani kegagalannya.',
                'hints' => [
                    1 => [
                        'content' => 'Periksa kemungkinan nilai kembalian mysqli_query ketika query gagal.',
                        'point_penalty' => 10,
                    ],
                    2 => [
                        'content' => 'Tambahkan kondisi sebelum memanggil mysqli_fetch_assoc.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    'primary' => [
                        'solution_code' => <<<'PHP'
<?php
$result = mysqli_query($connection, $sql);
if ($result === false) {
    throw new RuntimeException(mysqli_error($connection));
}
$row = mysqli_fetch_assoc($result);
echo $row['name'];
PHP,
                        'required_keywords' => [
                            'mysqli_query',
                            'false',
                            'mysqli_fetch_assoc',
                            'pengecekan',
                        ],
                    ],
                    'alternative' => [
                        'solution_code' => <<<'PHP'
<?php
$result = mysqli_query($connection, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo $row['name'];
}
PHP,
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'slug' => 'php-input-rentan-sql-injection',
                'description' => 'Input email pengguna digabungkan langsung ke query SQL.',
                'broken_code' => <<<'PHP'
<?php
$email = $_POST['email'];
$query = "SELECT * FROM users WHERE email = '$email'";
$result = $pdo->query($query);
PHP,
                'buggy_line' => 3,
                'explanation' => 'Menggabungkan input pengguna ke string SQL membuka celah SQL injection. Gunakan prepared statement dengan placeholder dan binding parameter.',
                'hints' => [
                    1 => [
                        'content' => 'Jangan gabungkan input pengguna secara langsung ke query.',
                        'point_penalty' => 10,
                    ],
                    2 => [
                        'content' => 'Gunakan prepare(), placeholder, dan execute().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    'primary' => [
                        'solution_code' => <<<'PHP'
<?php
$email = $_POST['email'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$result = $stmt->fetchAll();
PHP,
                        'required_keywords' => [
                            'SQL injection',
                            'prepared statement',
                            'placeholder',
                            'binding',
                        ],
                    ],
                    'alternative' => [
                        'solution_code' => <<<'PHP'
<?php
$email = $_POST['email'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$result = $stmt->fetchAll();
PHP,
                        'required_keywords' => [],
                    ],
                ],
            ],
        ];
    }
};
