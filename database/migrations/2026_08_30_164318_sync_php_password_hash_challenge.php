<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $challenge = DB::table('challenges')
                ->where('slug', 'php-password-disimpan-tanpa-hash')
                ->first(['id']);

            if ($challenge === null) {
                return;
            }

            $now = now();

            DB::table('challenges')
                ->where('id', $challenge->id)
                ->update([
                    'description' => 'Password pengguna masih disimpan dalam bentuk teks biasa sehingga password asli dapat diketahui jika data penyimpanan bocor.',
                    'broken_code' => <<<'PHP'
<?php
$password = trim(fgets(STDIN));
$storedPassword = $password;
echo $storedPassword;
PHP,
                    'buggy_line' => 3,
                    'explanation' => 'Password tidak boleh disimpan sebagai plaintext. Password harus diubah menjadi hash satu arah menggunakan password_hash() dengan PASSWORD_DEFAULT sebelum disimpan. Ketika melakukan autentikasi, password yang diberikan pengguna dapat diperiksa terhadap hash menggunakan password_verify().',
                    'updated_at' => $now,
                ]);

            DB::table('challenge_hints')->updateOrInsert(
                [
                    'challenge_id' => $challenge->id,
                    'hint_order' => 1,
                ],
                [
                    'content' => 'Periksa apakah nilai yang disimpan masih sama persis dengan password asli.',
                    'point_penalty' => 10,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );

            DB::table('challenge_hints')->updateOrInsert(
                [
                    'challenge_id' => $challenge->id,
                    'hint_order' => 2,
                ],
                [
                    'content' => 'Gunakan password_hash() dengan PASSWORD_DEFAULT sebelum password disimpan.',
                    'point_penalty' => 20,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );

            DB::table('challenge_solutions')->updateOrInsert(
                [
                    'challenge_id' => $challenge->id,
                    'solution_type' => 'primary',
                ],
                [
                    'solution_code' => <<<'PHP'
<?php
$password = trim(fgets(STDIN));
$storedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $storedPassword;
PHP,
                    'required_keywords' => json_encode([
                        'password_hash',
                        'plaintext',
                        'PASSWORD_DEFAULT',
                        'password_verify',
                    ], JSON_THROW_ON_ERROR),
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        });
    }

    public function down(): void
    {
        DB::transaction(function (): void {
            $challenge = DB::table('challenges')
                ->where('slug', 'php-password-disimpan-tanpa-hash')
                ->first(['id']);

            if ($challenge === null) {
                return;
            }

            $now = now();

            DB::table('challenges')
                ->where('id', $challenge->id)
                ->update([
                    'description' => 'Password pengguna dimasukkan ke database dalam bentuk teks biasa.',
                    'broken_code' => <<<'PHP'
<?php
$password = $_POST['password'];
$sql = "INSERT INTO users (password) VALUES ('$password')";
mysqli_query($connection, $sql);
PHP,
                    'buggy_line' => 3,
                    'explanation' => 'Password tidak boleh disimpan sebagai plaintext. Hash password menggunakan password_hash() lalu simpan hash tersebut. Query juga sebaiknya menggunakan prepared statement agar input tidak disisipkan langsung ke SQL.',
                    'updated_at' => $now,
                ]);

            DB::table('challenge_hints')->updateOrInsert(
                [
                    'challenge_id' => $challenge->id,
                    'hint_order' => 1,
                ],
                [
                    'content' => 'Periksa bentuk password yang masuk ke query database.',
                    'point_penalty' => 10,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );

            DB::table('challenge_hints')->updateOrInsert(
                [
                    'challenge_id' => $challenge->id,
                    'hint_order' => 2,
                ],
                [
                    'content' => 'Gunakan password_hash dan prepared statement.',
                    'point_penalty' => 20,
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );

            DB::table('challenge_solutions')->updateOrInsert(
                [
                    'challenge_id' => $challenge->id,
                    'solution_type' => 'primary',
                ],
                [
                    'solution_code' => <<<'PHP'
<?php
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$stmt = mysqli_prepare($connection, 'INSERT INTO users (password) VALUES (?)');
mysqli_stmt_bind_param($stmt, 's', $password);
mysqli_stmt_execute($stmt);
PHP,
                    'required_keywords' => json_encode([
                        'password_hash',
                        'plaintext',
                        'prepared statement',
                        'PASSWORD_DEFAULT',
                    ], JSON_THROW_ON_ERROR),
                    'updated_at' => $now,
                    'created_at' => $now,
                ],
            );
        });
    }
};
