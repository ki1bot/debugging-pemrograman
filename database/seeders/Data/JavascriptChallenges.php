<?php

namespace Database\Seeders\Data;

class JavascriptChallenges
{
    public static function all(): array
    {
        return [
            0 => [
                'category' => 'javascript',
                'difficulty' => 'mudah',
                'title' => 'Perulangan Melewati Batas Array',
                'slug' => 'javascript-perulangan-melewati-batas-array',
                'description' => 'Perulangan menampilkan satu nilai undefined setelah seluruh elemen array selesai diproses.',
                'broken_code' => 'const numbers = [1, 2, 3, 4];
for (let i = 0; i <= numbers.length; i++) {
    console.log(numbers[i]);
}',
                'buggy_line' => 2,
                'explanation' => 'Indeks terakhir array adalah numbers.length - 1. Kondisi i <= numbers.length membuat perulangan mencapai indeks yang sama dengan panjang array sehingga mengakses elemen di luar batas. Kondisi harus menggunakan i < numbers.length.',
                'hints' => [
                    0 => [
                        'content' => 'Perhatikan hubungan antara panjang array dan indeks terakhirnya.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Bandingkan operator <= dengan < pada kondisi perulangan.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'const numbers = [1, 2, 3, 4];
for (let i = 0; i < numbers.length; i++) {
    console.log(numbers[i]);
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'array',
                            1 => 'indeks',
                            2 => 'length',
                            3 => 'di luar batas',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'const numbers = [1, 2, 3, 4];
for (let i = 0; i <= numbers.length - 1; i++) {
    console.log(numbers[i]);
}',
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            1 => [
                'category' => 'javascript',
                'difficulty' => 'mudah',
                'title' => 'Perbandingan Menggunakan Assignment',
                'slug' => 'javascript-perbandingan-menggunakan-assignment',
                'description' => 'Blok kondisi selalu dijalankan karena variabel justru diberi nilai baru ketika diperiksa.',
                'broken_code' => 'let age = 20;
if (age = 18) {
    console.log(\'Usia tepat 18 tahun\');
}',
                'buggy_line' => 2,
                'explanation' => 'Operator = melakukan assignment, bukan perbandingan. Kondisi mengubah nilai age menjadi 18 dan menghasilkan nilai truthy. Gunakan operator perbandingan ketat === agar nilai dan tipe data diperiksa.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa apakah operator pada kondisi mengubah nilai atau membandingkan nilai.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan operator perbandingan ketat JavaScript.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'let age = 20;
if (age === 18) {
    console.log(\'Usia tepat 18 tahun\');
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'assignment',
                            1 => 'perbandingan',
                            2 => '===',
                            3 => 'nilai',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'let age = 20;
if (age == 18) {
    console.log(\'Usia tepat 18 tahun\');
}',
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            2 => [
                'category' => 'javascript',
                'difficulty' => 'mudah',
                'title' => 'Variabel Tidak Terdefinisi',
                'slug' => 'javascript-variabel-tidak-terdefinisi',
                'description' => 'Program berhenti dengan ReferenceError ketika mencoba menampilkan nama pengguna.',
                'broken_code' => 'const firstName = \'Rifqi\';
console.log(fullName);',
                'buggy_line' => 2,
                'explanation' => 'Variabel yang dideklarasikan bernama firstName, tetapi kode mencoba membaca fullName yang tidak pernah didefinisikan. Nama variabel harus konsisten.',
                'hints' => [
                    0 => [
                        'content' => 'Bandingkan nama variabel saat deklarasi dan saat digunakan.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'ReferenceError muncul ketika identifier tidak pernah didefinisikan.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'const firstName = \'Rifqi\';
console.log(firstName);',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'variabel',
                            1 => 'firstName',
                            2 => 'fullName',
                            3 => 'tidak didefinisikan',
                        ],
                    ],
                ],
            ],
            3 => [
                'category' => 'javascript',
                'difficulty' => 'menengah',
                'title' => 'Kesalahan Scope Variabel',
                'slug' => 'javascript-kesalahan-scope-variabel',
                'description' => 'Function menghasilkan ReferenceError karena variabel hanya tersedia di dalam blok if.',
                'broken_code' => 'function calculateTotal() {
    if (true) {
        let total = 100;
    }
    return total;
}

console.log(calculateTotal());',
                'buggy_line' => 5,
                'explanation' => 'Variabel yang dideklarasikan dengan let memiliki block scope. total hanya tersedia di dalam blok if sehingga tidak dapat dibaca pada return di luar blok. Deklarasikan total pada scope function.',
                'hints' => [
                    0 => [
                        'content' => 'Perhatikan batas kurung kurawal tempat let dideklarasikan.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Pindahkan deklarasi variabel ke scope function agar dapat dipakai saat return.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'function calculateTotal() {
    let total = 0;
    if (true) {
        total = 100;
    }
    return total;
}

console.log(calculateTotal());',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'let',
                            1 => 'block scope',
                            2 => 'if',
                            3 => 'function',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'function calculateTotal() {
    if (true) {
        const total = 100;
        return total;
    }
    return 0;
}

console.log(calculateTotal());',
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            4 => [
                'category' => 'javascript',
                'difficulty' => 'menengah',
                'title' => 'Promise Tidak Ditunggu',
                'slug' => 'javascript-promise-tidak-ditunggu',
                'description' => 'Program mencoba membaca properti data ketika hasil response.json() masih berupa Promise.',
                'broken_code' => 'async function fakeFetch() {
    return {
        json: async () => ({ name: \'Rifqi\' }),
    };
}

async function loadUser() {
    const response = await fakeFetch();
    const data = response.json();
    console.log(data.name);
}

loadUser();',
                'buggy_line' => 9,
                'explanation' => 'response.json() bersifat asynchronous dan mengembalikan Promise. Tanpa await, variabel data masih berupa Promise sehingga properti name belum tersedia. Tambahkan await agar hasil parsing JSON selesai sebelum digunakan.',
                'hints' => [
                    0 => [
                        'content' => 'Tidak hanya proses mengambil response yang dapat bersifat asynchronous.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Periksa nilai yang dikembalikan oleh response.json().',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'async function fakeFetch() {
    return {
        json: async () => ({ name: \'Rifqi\' }),
    };
}

async function loadUser() {
    const response = await fakeFetch();
    const data = await response.json();
    console.log(data.name);
}

loadUser();',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'promise',
                            1 => 'await',
                            2 => 'response.json',
                            3 => 'asynchronous',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'async function fakeFetch() {
    return {
        json: async () => ({ name: \'Rifqi\' }),
    };
}

async function loadUser() {
    const response = await fakeFetch();
    response.json().then((data) => console.log(data.name));
}

loadUser();',
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            5 => [
                'category' => 'javascript',
                'difficulty' => 'menengah',
                'title' => 'Method Array yang Salah',
                'slug' => 'javascript-method-array-yang-salah',
                'description' => 'Program gagal karena nama method tidak tepat dan tujuan transformasi array tidak sesuai dengan method yang digunakan.',
                'broken_code' => 'const numbers = [1, 2, 3];
const doubled = numbers.foreach((number) => number * 2);
console.log(doubled);',
                'buggy_line' => 2,
                'explanation' => 'JavaScript bersifat case-sensitive sehingga method yang benar adalah forEach, bukan foreach. Namun forEach juga tidak mengembalikan array baru. Untuk menghasilkan array doubled, gunakan map().',
                'hints' => [
                    0 => [
                        'content' => 'JavaScript membedakan huruf besar dan kecil pada nama method.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Pilih method array yang mengembalikan array hasil transformasi.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'const numbers = [1, 2, 3];
const doubled = numbers.map((number) => number * 2);
console.log(doubled);',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'map',
                            1 => 'forEach',
                            2 => 'array baru',
                            3 => 'case-sensitive',
                        ],
                    ],
                ],
            ],
            6 => [
                'category' => 'javascript',
                'difficulty' => 'sulit',
                'title' => 'Salinan Objek Masih Berbagi Referensi',
                'slug' => 'javascript-salinan-objek-berbagi-referensi',
                'description' => 'Mengubah tema pada objek salinan ternyata ikut mengubah objek asli.',
                'broken_code' => 'const original = { settings: { theme: \'light\' } };
const copy = { ...original };
copy.settings.theme = \'dark\';
console.log(original.settings.theme);',
                'buggy_line' => 2,
                'explanation' => 'Spread pada level terluar hanya membuat shallow copy. Properti settings tetap menunjuk objek nested yang sama. Buat salinan baru untuk settings agar referensinya terpisah.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa apakah spread operator menyalin seluruh tingkat objek secara mendalam.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Objek settings perlu memiliki referensi baru.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'const original = { settings: { theme: \'light\' } };
const copy = { ...original, settings: { ...original.settings } };
copy.settings.theme = \'dark\';
console.log(original.settings.theme);',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'shallow copy',
                            1 => 'referensi',
                            2 => 'nested',
                            3 => 'spread',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'const original = { settings: { theme: \'light\' } };
const copy = structuredClone(original);
copy.settings.theme = \'dark\';
console.log(original.settings.theme);',
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            7 => [
                'category' => 'javascript',
                'difficulty' => 'sulit',
                'title' => 'Async ForEach Tidak Ditunggu',
                'slug' => 'javascript-async-foreach-tidak-ditunggu',
                'description' => 'Function mengembalikan status selesai sebelum seluruh operasi asynchronous pada setiap item benar-benar selesai.',
                'broken_code' => 'async function saveItem(item) {
    await new Promise((resolve) => setTimeout(resolve, 20));
    console.log(`tersimpan: ${item}`);
}

async function saveAll(items) {
    items.forEach(async (item) => {
        await saveItem(item);
    });
    return \'selesai\';
}

saveAll([\'A\', \'B\', \'C\']).then((status) => console.log(status));',
                'buggy_line' => 7,
                'explanation' => 'forEach tidak menunggu Promise yang dikembalikan callback async. Akibatnya saveAll dapat mengembalikan status selesai sebelum seluruh saveItem selesai. Gunakan for...of dengan await untuk proses berurutan atau Promise.all dengan map untuk proses paralel.',
                'hints' => [
                    0 => [
                        'content' => 'Callback async tidak membuat forEach ikut menunggu Promise.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan for...of atau Promise.all agar seluruh operasi selesai sebelum return.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'async function saveItem(item) {
    await new Promise((resolve) => setTimeout(resolve, 20));
    console.log(`tersimpan: ${item}`);
}

async function saveAll(items) {
    for (const item of items) {
        await saveItem(item);
    }
    return \'selesai\';
}

saveAll([\'A\', \'B\', \'C\']).then((status) => console.log(status));',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'forEach',
                            1 => 'promise',
                            2 => 'await',
                            3 => 'for...of',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'async function saveItem(item) {
    await new Promise((resolve) => setTimeout(resolve, 20));
    console.log(`tersimpan: ${item}`);
}

async function saveAll(items) {
    await Promise.all(items.map((item) => saveItem(item)));
    return \'selesai\';
}

saveAll([\'A\', \'B\', \'C\']).then((status) => console.log(status));',
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
        ];
    }
}
