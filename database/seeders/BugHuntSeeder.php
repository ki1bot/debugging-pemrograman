<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BugHuntSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::query()->updateOrCreate(
                ['email' => 'admin@bughunt.test'],
                [
                    'name' => 'Administrator BugHunt',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'total_points' => 0,
                    'email_verified_at' => now(),
                ],
            );

            User::query()->updateOrCreate(
                ['email' => 'user@bughunt.test'],
                [
                    'name' => 'Pengguna Demo',
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'total_points' => 0,
                    'email_verified_at' => now(),
                ],
            );

            $categories = collect([
                [
                    'name' => 'JavaScript',
                    'slug' => 'javascript',
                    'description' => 'Tantangan debugging sintaks, array, asynchronous programming, scope, dan referensi objek JavaScript.',
                ],
                [
                    'name' => 'PHP',
                    'slug' => 'php',
                    'description' => 'Tantangan debugging PHP dasar, form, session, function, database, dan keamanan input.',
                ],
                [
                    'name' => 'SQL',
                    'slug' => 'sql',
                    'description' => 'Tantangan debugging query SQL, JOIN, GROUP BY, subquery, agregasi, dan window function.',
                ],
            ])->mapWithKeys(function (array $category): array {
                $model = Category::query()->updateOrCreate(
                    ['slug' => $category['slug']],
                    [...$category, 'is_active' => true],
                );

                return [$category['slug'] => $model];
            });

            $difficulties = collect([
                [
                    'name' => 'Mudah',
                    'slug' => 'mudah',
                    'base_points' => 50,
                ],
                [
                    'name' => 'Menengah',
                    'slug' => 'menengah',
                    'base_points' => 100,
                ],
                [
                    'name' => 'Sulit',
                    'slug' => 'sulit',
                    'base_points' => 150,
                ],
            ])->mapWithKeys(function (array $difficulty): array {
                $model = Difficulty::query()->updateOrCreate(
                    ['slug' => $difficulty['slug']],
                    [...$difficulty, 'is_active' => true],
                );

                return [$difficulty['slug'] => $model];
            });

            foreach ($this->challenges() as $data) {
                $difficulty = $difficulties->get($data['difficulty']);

                $challenge = Challenge::withTrashed()->updateOrCreate(
                    ['slug' => $data['slug']],
                    [
                        'category_id' => $categories->get($data['category'])->id,
                        'difficulty_id' => $difficulty->id,
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'broken_code' => $data['broken_code'],
                        'buggy_line' => $data['buggy_line'],
                        'explanation' => $data['explanation'],
                        'base_points' => $difficulty->base_points,
                        'status' => 'published',
                        'created_by' => $admin->id,
                        'deleted_at' => null,
                    ],
                );

                $challenge->hints()->delete();
                $challenge->solutions()->delete();

                foreach ($data['hints'] as $index => $hint) {
                    $challenge->hints()->create([
                        'hint_order' => $index + 1,
                        'content' => $hint['content'],
                        'point_penalty' => $hint['point_penalty'],
                    ]);
                }

                foreach ($data['solutions'] as $solution) {
                    $challenge->solutions()->create($solution);
                }
            }
        });
    }

    private function challenges(): array
    {
        return [
            [
                'category' => 'javascript',
                'difficulty' => 'mudah',
                'title' => 'Perulangan Melewati Batas Array',
                'slug' => 'javascript-perulangan-melewati-batas-array',
                'description' => 'Perulangan menampilkan satu nilai undefined setelah seluruh elemen array selesai diproses.',
                'broken_code' => <<<'CODE'
const numbers = [1, 2, 3, 4];
for (let i = 0; i <= numbers.length; i++) {
    console.log(numbers[i]);
}
CODE,
                'buggy_line' => 2,
                'explanation' => 'Indeks terakhir array adalah numbers.length - 1. Kondisi i <= numbers.length membuat perulangan mencapai indeks yang sama dengan panjang array sehingga mengakses elemen di luar batas. Kondisi harus menggunakan i < numbers.length.',
                'hints' => [
                    [
                        'content' => 'Perhatikan hubungan antara panjang array dan indeks terakhirnya.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Bandingkan operator <= dengan < pada kondisi perulangan.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
const numbers = [1, 2, 3, 4];
for (let i = 0; i < numbers.length; i++) {
    console.log(numbers[i]);
}
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'array',
                            'indeks',
                            'length',
                            'di luar batas',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
const numbers = [1, 2, 3, 4];
for (let i = 0; i <= numbers.length - 1; i++) {
    console.log(numbers[i]);
}
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'javascript',
                'difficulty' => 'mudah',
                'title' => 'Perbandingan Menggunakan Assignment',
                'slug' => 'javascript-perbandingan-menggunakan-assignment',
                'description' => 'Blok kondisi selalu dijalankan karena variabel justru diberi nilai baru ketika diperiksa.',
                'broken_code' => <<<'CODE'
const age = 20;
if (age = 18) {
    console.log('Usia tepat 18 tahun');
}
CODE,
                'buggy_line' => 2,
                'explanation' => 'Operator = melakukan assignment, bukan perbandingan. Kondisi mengubah nilai age menjadi 18 dan menghasilkan nilai truthy. Gunakan operator perbandingan ketat === agar nilai dan tipe data diperiksa.',
                'hints' => [
                    [
                        'content' => 'Periksa apakah operator pada kondisi mengubah nilai atau membandingkan nilai.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan operator perbandingan ketat JavaScript.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
const age = 20;
if (age === 18) {
    console.log('Usia tepat 18 tahun');
}
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'assignment',
                            'perbandingan',
                            '===',
                            'nilai',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
const age = 20;
if (age == 18) {
    console.log('Usia tepat 18 tahun');
}
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'javascript',
                'difficulty' => 'mudah',
                'title' => 'Variabel Tidak Terdefinisi',
                'slug' => 'javascript-variabel-tidak-terdefinisi',
                'description' => 'Program berhenti dengan ReferenceError ketika mencoba menampilkan nama pengguna.',
                'broken_code' => <<<'CODE'
const firstName = 'Rifqi';
console.log(fullName);
CODE,
                'buggy_line' => 2,
                'explanation' => 'Variabel yang dideklarasikan bernama firstName, tetapi kode mencoba membaca fullName yang tidak pernah didefinisikan. Nama variabel harus konsisten.',
                'hints' => [
                    [
                        'content' => 'Bandingkan nama variabel saat deklarasi dan saat digunakan.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'ReferenceError muncul ketika identifier tidak pernah didefinisikan.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
const firstName = 'Rifqi';
console.log(firstName);
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'variabel',
                            'firstName',
                            'fullName',
                            'tidak didefinisikan',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'javascript',
                'difficulty' => 'menengah',
                'title' => 'Kesalahan Scope Variabel',
                'slug' => 'javascript-kesalahan-scope-variabel',
                'description' => 'Function menghasilkan ReferenceError karena variabel hanya tersedia di dalam blok if.',
                'broken_code' => <<<'CODE'
function calculateTotal() {
    if (true) {
        let total = 100;
    }
    return total;
}
CODE,
                'buggy_line' => 5,
                'explanation' => 'Variabel yang dideklarasikan dengan let memiliki block scope. total hanya tersedia di dalam blok if sehingga tidak dapat dibaca pada return di luar blok. Deklarasikan total pada scope function.',
                'hints' => [
                    [
                        'content' => 'Perhatikan batas kurung kurawal tempat let dideklarasikan.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Pindahkan deklarasi variabel ke scope function agar dapat dipakai saat return.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
function calculateTotal() {
    let total = 0;
    if (true) {
        total = 100;
    }
    return total;
}
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'let',
                            'block scope',
                            'if',
                            'function',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
function calculateTotal() {
    if (true) {
        const total = 100;
        return total;
    }
    return 0;
}
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'javascript',
                'difficulty' => 'menengah',
                'title' => 'Promise Tidak Ditunggu',
                'slug' => 'javascript-promise-tidak-ditunggu',
                'description' => 'Function mengembalikan Promise dari response.json() alih-alih data pengguna yang sudah selesai diparsing.',
                'broken_code' => <<<'CODE'
async function loadUser() {
    const response = await fetch('/api/user');
    const data = response.json();
    return data;
}
CODE,
                'buggy_line' => 3,
                'explanation' => 'response.json() bersifat asynchronous dan mengembalikan Promise. Tambahkan await agar data JSON selesai diparsing sebelum dikembalikan.',
                'hints' => [
                    [
                        'content' => 'Tidak hanya fetch yang mengembalikan Promise.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Periksa nilai kembalian dari response.json().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
async function loadUser() {
    const response = await fetch('/api/user');
    const data = await response.json();
    return data;
}
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'promise',
                            'await',
                            'response.json',
                            'asynchronous',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
async function loadUser() {
    const response = await fetch('/api/user');
    return await response.json();
}
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'javascript',
                'difficulty' => 'menengah',
                'title' => 'Method Array yang Salah',
                'slug' => 'javascript-method-array-yang-salah',
                'description' => 'Program gagal karena nama method tidak tepat dan tujuan transformasi array tidak sesuai dengan method yang digunakan.',
                'broken_code' => <<<'CODE'
const numbers = [1, 2, 3];
const doubled = numbers.foreach((number) => number * 2);
console.log(doubled);
CODE,
                'buggy_line' => 2,
                'explanation' => 'JavaScript bersifat case-sensitive sehingga method yang benar adalah forEach, bukan foreach. Namun forEach juga tidak mengembalikan array baru. Untuk menghasilkan array doubled, gunakan map().',
                'hints' => [
                    [
                        'content' => 'JavaScript membedakan huruf besar dan kecil pada nama method.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Pilih method array yang mengembalikan array hasil transformasi.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
const numbers = [1, 2, 3];
const doubled = numbers.map((number) => number * 2);
console.log(doubled);
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'map',
                            'forEach',
                            'array baru',
                            'case-sensitive',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'javascript',
                'difficulty' => 'sulit',
                'title' => 'Salinan Objek Masih Berbagi Referensi',
                'slug' => 'javascript-salinan-objek-berbagi-referensi',
                'description' => 'Mengubah tema pada objek salinan ternyata ikut mengubah objek asli.',
                'broken_code' => <<<'CODE'
const original = { settings: { theme: 'light' } };
const copy = { ...original };
copy.settings.theme = 'dark';
console.log(original.settings.theme);
CODE,
                'buggy_line' => 2,
                'explanation' => 'Spread pada level terluar hanya membuat shallow copy. Properti settings tetap menunjuk objek nested yang sama. Buat salinan baru untuk settings agar referensinya terpisah.',
                'hints' => [
                    [
                        'content' => 'Periksa apakah spread operator menyalin seluruh tingkat objek secara mendalam.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Objek settings perlu memiliki referensi baru.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
const original = { settings: { theme: 'light' } };
const copy = { ...original, settings: { ...original.settings } };
copy.settings.theme = 'dark';
console.log(original.settings.theme);
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'shallow copy',
                            'referensi',
                            'nested',
                            'spread',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
const original = { settings: { theme: 'light' } };
const copy = structuredClone(original);
copy.settings.theme = 'dark';
console.log(original.settings.theme);
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'javascript',
                'difficulty' => 'sulit',
                'title' => 'Async ForEach Tidak Ditunggu',
                'slug' => 'javascript-async-foreach-tidak-ditunggu',
                'description' => 'Function mengembalikan status selesai sebelum seluruh item benar-benar tersimpan.',
                'broken_code' => <<<'CODE'
async function saveAll(items) {
    items.forEach(async (item) => {
        await saveItem(item);
    });
    return 'selesai';
}
CODE,
                'buggy_line' => 2,
                'explanation' => 'forEach tidak menunggu Promise yang dikembalikan callback async. Gunakan for...of dengan await untuk proses berurutan atau Promise.all dengan map untuk proses paralel.',
                'hints' => [
                    [
                        'content' => 'Callback async tidak membuat forEach ikut menjadi await-aware.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan for...of atau Promise.all.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
async function saveAll(items) {
    for (const item of items) {
        await saveItem(item);
    }
    return 'selesai';
}
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'forEach',
                            'promise',
                            'await',
                            'for...of',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
async function saveAll(items) {
    await Promise.all(items.map((item) => saveItem(item)));
    return 'selesai';
}
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'php',
                'difficulty' => 'mudah',
                'title' => 'Kesalahan Operator Perbandingan',
                'slug' => 'php-kesalahan-operator-perbandingan',
                'description' => 'Kondisi selalu dianggap benar karena nilai status diubah ketika diperiksa.',
                'broken_code' => <<<'CODE'
<?php
$status = 'inactive';
if ($status = 'active') {
    echo 'Akun aktif';
}
CODE,
                'buggy_line' => 3,
                'explanation' => 'Operator = melakukan assignment ke variabel $status. Untuk membandingkan nilai dan tipe string, gunakan operator identik ===.',
                'hints' => [
                    [
                        'content' => 'Operator pada kondisi saat ini mengubah isi variabel.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan operator identik PHP.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
<?php
$status = 'inactive';
if ($status === 'active') {
    echo 'Akun aktif';
}
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'assignment',
                            'perbandingan',
                            '===',
                            '$status',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
<?php
$status = 'inactive';
if ($status == 'active') {
    echo 'Akun aktif';
}
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'php',
                'difficulty' => 'mudah',
                'title' => 'Variabel Form Tidak Ditemukan',
                'slug' => 'php-variabel-form-tidak-ditemukan',
                'description' => 'Nilai email tidak terbaca karena key input yang diakses tidak sesuai dengan nama field form.',
                'broken_code' => <<<'CODE'
<?php
$email = $_POST['username'];
echo $email;
CODE,
                'buggy_line' => 2,
                'explanation' => 'Form mengirim field email, tetapi kode membaca key username. Gunakan key email dan null coalescing agar tidak muncul peringatan undefined array key ketika field tidak tersedia.',
                'hints' => [
                    [
                        'content' => 'Periksa kesesuaian nama field HTML dengan key pada $_POST.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Tambahkan nilai default ketika key tidak tersedia.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
<?php
$email = $_POST['email'] ?? '';
echo $email;
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            '$_POST',
                            'email',
                            'key',
                            'null coalescing',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
<?php
$email = isset($_POST['email']) ? $_POST['email'] : '';
echo $email;
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'php',
                'difficulty' => 'mudah',
                'title' => 'Perulangan Array yang Salah',
                'slug' => 'php-perulangan-array-yang-salah',
                'description' => 'Perulangan mencoba membaca satu indeks setelah elemen terakhir array.',
                'broken_code' => <<<'CODE'
<?php
$items = ['A', 'B', 'C'];
for ($i = 0; $i <= count($items); $i++) {
    echo $items[$i];
}
CODE,
                'buggy_line' => 3,
                'explanation' => 'Jumlah elemen array adalah 3, tetapi indeks valid hanya 0 sampai 2. Kondisi <= membuat perulangan mencapai indeks 3. Gunakan $i < count($items).',
                'hints' => [
                    [
                        'content' => 'Bandingkan jumlah elemen dengan indeks terakhir array.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Operator kondisi perulangan harus menghentikan proses sebelum count($items).',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
<?php
$items = ['A', 'B', 'C'];
for ($i = 0; $i < count($items); $i++) {
    echo $items[$i];
}
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'array',
                            'indeks',
                            'count',
                            'di luar batas',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
<?php
$items = ['A', 'B', 'C'];
foreach ($items as $item) {
    echo $item;
}
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'php',
                'difficulty' => 'menengah',
                'title' => 'Kesalahan Penggunaan Session',
                'slug' => 'php-kesalahan-penggunaan-session',
                'description' => 'Data session digunakan sebelum session PHP diinisialisasi.',
                'broken_code' => <<<'CODE'
<?php
$_SESSION['user_id'] = 10;
echo $_SESSION['user_id'];
CODE,
                'buggy_line' => 2,
                'explanation' => 'Session harus dimulai menggunakan session_start() sebelum membaca atau menulis $_SESSION. Pemanggilan dilakukan sebelum output apa pun dikirim ke browser.',
                'hints' => [
                    [
                        'content' => 'Superglobal $_SESSION membutuhkan proses inisialisasi.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Tambahkan session_start() sebelum assignment.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
<?php
session_start();
$_SESSION['user_id'] = 10;
echo $_SESSION['user_id'];
CODE,
                        'solution_type' => 'primary',
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
                'category' => 'php',
                'difficulty' => 'menengah',
                'title' => 'Query Tidak Mengecek Hasil',
                'slug' => 'php-query-tidak-mengecek-hasil',
                'description' => 'mysqli_fetch_assoc menerima boolean false ketika query database gagal.',
                'broken_code' => <<<'CODE'
<?php
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
echo $row['name'];
CODE,
                'buggy_line' => 3,
                'explanation' => 'mysqli_query dapat mengembalikan false. Nilai tersebut tidak boleh langsung diberikan kepada mysqli_fetch_assoc. Periksa hasil query terlebih dahulu dan tangani kegagalannya.',
                'hints' => [
                    [
                        'content' => 'Periksa kemungkinan nilai kembalian mysqli_query ketika query gagal.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Tambahkan kondisi sebelum memanggil mysqli_fetch_assoc.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
<?php
$result = mysqli_query($connection, $sql);
if ($result === false) {
    throw new RuntimeException(mysqli_error($connection));
}
$row = mysqli_fetch_assoc($result);
echo $row['name'];
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'mysqli_query',
                            'false',
                            'mysqli_fetch_assoc',
                            'pengecekan',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
<?php
$result = mysqli_query($connection, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo $row['name'];
}
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'php',
                'difficulty' => 'menengah',
                'title' => 'Kesalahan Pemanggilan Function',
                'slug' => 'php-kesalahan-pemanggilan-function',
                'description' => 'Function memerlukan dua argumen tetapi hanya menerima satu argumen saat dipanggil.',
                'broken_code' => <<<'CODE'
<?php
function calculateTotal(float $price, int $quantity): float {
    return $price * $quantity;
}
echo calculateTotal(25000);
CODE,
                'buggy_line' => 5,
                'explanation' => 'calculateTotal mendefinisikan parameter $price dan $quantity. Pemanggilan hanya mengirim $price sehingga PHP melempar ArgumentCountError. Kirim kedua argumen yang dibutuhkan.',
                'hints' => [
                    [
                        'content' => 'Hitung jumlah parameter pada deklarasi function.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Tambahkan nilai quantity pada saat function dipanggil.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
<?php
function calculateTotal(float $price, int $quantity): float {
    return $price * $quantity;
}
echo calculateTotal(25000, 2);
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'parameter',
                            'argumen',
                            '$quantity',
                            'ArgumentCountError',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
<?php
function calculateTotal(float $price, int $quantity = 1): float {
    return $price * $quantity;
}
echo calculateTotal(25000);
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'php',
                'difficulty' => 'sulit',
                'title' => 'Password Disimpan Tanpa Hash',
                'slug' => 'php-password-disimpan-tanpa-hash',
                'description' => 'Password pengguna dimasukkan ke database dalam bentuk teks biasa.',
                'broken_code' => <<<'CODE'
<?php
$password = $_POST['password'];
$sql = "INSERT INTO users (password) VALUES ('$password')";
mysqli_query($connection, $sql);
CODE,
                'buggy_line' => 3,
                'explanation' => 'Password tidak boleh disimpan sebagai plaintext. Hash password menggunakan password_hash() lalu simpan hash tersebut. Query juga sebaiknya menggunakan prepared statement agar input tidak disisipkan langsung ke SQL.',
                'hints' => [
                    [
                        'content' => 'Periksa bentuk password yang masuk ke query database.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan password_hash dan prepared statement.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
<?php
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$stmt = mysqli_prepare($connection, 'INSERT INTO users (password) VALUES (?)');
mysqli_stmt_bind_param($stmt, 's', $password);
mysqli_stmt_execute($stmt);
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'password_hash',
                            'plaintext',
                            'prepared statement',
                            'PASSWORD_DEFAULT',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'php',
                'difficulty' => 'sulit',
                'title' => 'Input Rentan SQL Injection',
                'slug' => 'php-input-rentan-sql-injection',
                'description' => 'Input email pengguna digabungkan langsung ke query SQL.',
                'broken_code' => <<<'CODE'
<?php
$email = $_POST['email'];
$query = "SELECT * FROM users WHERE email = '$email'";
$result = $pdo->query($query);
CODE,
                'buggy_line' => 3,
                'explanation' => 'Menggabungkan input pengguna ke string SQL membuka celah SQL injection. Gunakan prepared statement dengan placeholder dan binding parameter.',
                'hints' => [
                    [
                        'content' => 'Jangan gabungkan input pengguna secara langsung ke query.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan prepare(), placeholder, dan execute().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
<?php
$email = $_POST['email'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$result = $stmt->fetchAll();
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'SQL injection',
                            'prepared statement',
                            'placeholder',
                            'binding',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
<?php
$email = $_POST['email'];
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$result = $stmt->fetchAll();
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'sql',
                'difficulty' => 'mudah',
                'title' => 'WHERE Diletakkan Setelah ORDER BY',
                'slug' => 'sql-where-diletakkan-setelah-order-by',
                'description' => 'Query gagal karena urutan klausa SQL tidak valid.',
                'broken_code' => <<<'CODE'
SELECT id, name
FROM users
ORDER BY name
WHERE active = TRUE;
CODE,
                'buggy_line' => 4,
                'explanation' => 'Klausa WHERE harus ditempatkan sebelum ORDER BY. WHERE menyaring baris terlebih dahulu, kemudian ORDER BY mengurutkan hasil yang tersisa.',
                'hints' => [
                    [
                        'content' => 'Periksa urutan logis klausa SELECT, FROM, WHERE, dan ORDER BY.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Pindahkan WHERE sebelum ORDER BY.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
SELECT id, name
FROM users
WHERE active = TRUE
ORDER BY name;
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'WHERE',
                            'ORDER BY',
                            'urutan klausa',
                            'menyaring',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'sql',
                'difficulty' => 'mudah',
                'title' => 'Kolom Ambigu pada JOIN',
                'slug' => 'sql-kolom-ambigu-pada-join',
                'description' => 'Database tidak dapat menentukan tabel asal kolom id dan name.',
                'broken_code' => <<<'CODE'
SELECT id, name
FROM users
JOIN orders ON users.id = orders.user_id;
CODE,
                'buggy_line' => 1,
                'explanation' => 'Saat beberapa tabel memiliki kolom dengan nama sama, kolom tanpa prefix menjadi ambigu. Gunakan nama tabel atau alias untuk menentukan sumber setiap kolom.',
                'hints' => [
                    [
                        'content' => 'Tabel users dan orders dapat memiliki kolom id yang sama.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Tambahkan prefix tabel atau alias pada kolom SELECT.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
SELECT users.id, users.name
FROM users
JOIN orders ON users.id = orders.user_id;
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'ambigu',
                            'prefix',
                            'tabel',
                            'kolom',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
SELECT u.id, u.name
FROM users AS u
JOIN orders AS o ON u.id = o.user_id;
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'sql',
                'difficulty' => 'mudah',
                'title' => 'Kesalahan GROUP BY',
                'slug' => 'sql-kesalahan-group-by',
                'description' => 'Query agregasi gagal karena sintaks pengelompokan tidak lengkap.',
                'broken_code' => <<<'CODE'
SELECT department, COUNT(*) AS total
FROM employees
GROUP department;
CODE,
                'buggy_line' => 3,
                'explanation' => 'Sintaks yang benar adalah GROUP BY diikuti kolom pengelompokan. Kata BY tidak boleh dihilangkan.',
                'hints' => [
                    [
                        'content' => 'Klausa pengelompokan terdiri dari dua kata.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Tambahkan BY setelah GROUP.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
SELECT department, COUNT(*) AS total
FROM employees
GROUP BY department;
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'GROUP BY',
                            'agregasi',
                            'department',
                            'sintaks',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'sql',
                'difficulty' => 'menengah',
                'title' => 'Menggunakan WHERE untuk Fungsi Agregat',
                'slug' => 'sql-where-untuk-fungsi-agregat',
                'description' => 'Query mencoba menyaring hasil COUNT menggunakan klausa yang salah.',
                'broken_code' => <<<'CODE'
SELECT department, COUNT(*) AS total
FROM employees
GROUP BY department
WHERE COUNT(*) > 5;
CODE,
                'buggy_line' => 4,
                'explanation' => 'WHERE menyaring baris sebelum agregasi sehingga tidak dapat menggunakan COUNT(*). Gunakan HAVING untuk menyaring kelompok setelah GROUP BY.',
                'hints' => [
                    [
                        'content' => 'WHERE bekerja sebelum proses pengelompokan dan agregasi.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan klausa yang menyaring hasil GROUP BY.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
SELECT department, COUNT(*) AS total
FROM employees
GROUP BY department
HAVING COUNT(*) > 5;
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'HAVING',
                            'WHERE',
                            'COUNT',
                            'agregasi',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'sql',
                'difficulty' => 'menengah',
                'title' => 'JOIN Menghasilkan Data Duplikat',
                'slug' => 'sql-join-menghasilkan-data-duplikat',
                'description' => 'Kondisi JOIN selalu benar dan menghasilkan kombinasi baris yang tidak semestinya.',
                'broken_code' => <<<'CODE'
SELECT users.name, orders.total
FROM users
JOIN orders ON users.id = users.id;
CODE,
                'buggy_line' => 3,
                'explanation' => 'Kondisi users.id = users.id selalu benar untuk setiap baris users. JOIN harus menghubungkan primary key users.id dengan foreign key orders.user_id.',
                'hints' => [
                    [
                        'content' => 'Kedua sisi kondisi ON saat ini berasal dari tabel yang sama.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Hubungkan id pengguna dengan user_id pada orders.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
SELECT users.name, orders.total
FROM users
JOIN orders ON users.id = orders.user_id;
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'JOIN',
                            'users.id',
                            'orders.user_id',
                            'selalu benar',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
SELECT u.name, o.total
FROM users AS u
JOIN orders AS o ON u.id = o.user_id;
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'sql',
                'difficulty' => 'menengah',
                'title' => 'Subquery Mengembalikan Banyak Baris',
                'slug' => 'sql-subquery-mengembalikan-banyak-baris',
                'description' => 'Operator perbandingan tunggal digunakan untuk subquery yang dapat menghasilkan lebih dari satu id.',
                'broken_code' => <<<'CODE'
SELECT name
FROM products
WHERE category_id = (
    SELECT id FROM categories WHERE active = TRUE
);
CODE,
                'buggy_line' => 3,
                'explanation' => 'Operator = mengharapkan satu nilai, sedangkan subquery dapat mengembalikan banyak category id. Gunakan IN untuk membandingkan category_id dengan seluruh hasil subquery.',
                'hints' => [
                    [
                        'content' => 'Perkirakan jumlah baris yang dapat dikembalikan subquery categories.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan operator untuk mencocokkan satu nilai terhadap sekumpulan nilai.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
SELECT name
FROM products
WHERE category_id IN (
    SELECT id FROM categories WHERE active = TRUE
);
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'subquery',
                            'banyak baris',
                            'IN',
                            'operator =',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'sql',
                'difficulty' => 'sulit',
                'title' => 'Alias Window Function Dipakai di WHERE',
                'slug' => 'sql-alias-window-function-di-where',
                'description' => 'Query mencoba menggunakan alias hasil window function pada WHERE dalam level SELECT yang sama.',
                'broken_code' => <<<'CODE'
SELECT name, salary,
       RANK() OVER (ORDER BY salary DESC) AS salary_rank
FROM employees
WHERE salary_rank <= 3;
CODE,
                'buggy_line' => 4,
                'explanation' => 'WHERE dievaluasi sebelum SELECT dan sebelum alias window function tersedia. Hitung ranking di CTE atau subquery, lalu saring salary_rank pada query luar.',
                'hints' => [
                    [
                        'content' => 'Perhatikan urutan evaluasi WHERE, SELECT, dan window function.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Bungkus query ranking di CTE atau subquery.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
WITH ranked_employees AS (
    SELECT name, salary,
           RANK() OVER (ORDER BY salary DESC) AS salary_rank
    FROM employees
)
SELECT name, salary, salary_rank
FROM ranked_employees
WHERE salary_rank <= 3;
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'window function',
                            'WHERE',
                            'CTE',
                            'urutan evaluasi',
                        ],
                    ],
                    [
                        'solution_code' => <<<'CODE'
SELECT name, salary, salary_rank
FROM (
    SELECT name, salary,
           RANK() OVER (ORDER BY salary DESC) AS salary_rank
    FROM employees
) AS ranked_employees
WHERE salary_rank <= 3;
CODE,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'sql',
                'difficulty' => 'sulit',
                'title' => 'Cartesian Product karena Kondisi JOIN Hilang',
                'slug' => 'sql-cartesian-product-kondisi-join-hilang',
                'description' => 'Setiap pengguna dipasangkan dengan setiap role karena relasi antartabel tidak ditentukan.',
                'broken_code' => <<<'CODE'
SELECT u.name, r.name AS role_name
FROM users AS u, roles AS r
WHERE u.active = TRUE;
CODE,
                'buggy_line' => 2,
                'explanation' => 'Daftar tabel yang dipisahkan koma tanpa kondisi relasi menghasilkan Cartesian product. Gunakan JOIN eksplisit dan hubungkan u.role_id dengan r.id.',
                'hints' => [
                    [
                        'content' => 'Tidak ada kondisi yang menghubungkan users dengan roles.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan JOIN ... ON berdasarkan role_id.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'CODE'
SELECT u.name, r.name AS role_name
FROM users AS u
JOIN roles AS r ON u.role_id = r.id
WHERE u.active = TRUE;
CODE,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'Cartesian product',
                            'JOIN',
                            'ON',
                            'role_id',
                        ],
                    ],
                ],
            ],
        ];
    }
}
