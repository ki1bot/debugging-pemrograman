<?php

namespace Database\Seeders\Data;

class PythonChallenges
{
    public static function all(): array
    {
        return [
            0 => [
                'category' => 'python',
                'difficulty' => 'mudah',
                'title' => 'Hasil append Python Disimpan Kembali',
                'slug' => 'python-hasil-append-disimpan-kembali',
                'description' => 'Variabel list berubah menjadi None setelah program menambahkan elemen.',
                'broken_code' => 'numbers = [1, 2, 3]
numbers = numbers.append(4)

print(numbers)',
                'buggy_line' => 2,
                'explanation' => 'Method append mengubah list secara langsung dan mengembalikan None. Menyimpan hasil append ke numbers mengganti reference list dengan None. Panggil numbers.append(4) tanpa assignment.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa nilai kembalian method append.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'append mengubah list yang sudah ada.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'numbers = [1, 2, 3]
numbers.append(4)

print(numbers)',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'append',
                            1 => 'list',
                            2 => 'None',
                            3 => 'assignment',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'numbers = [1, 2, 3]
numbers = numbers + [4]

print(numbers)',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            1 => [
                'category' => 'python',
                'difficulty' => 'mudah',
                'title' => 'Perulangan Python Melewati Batas List',
                'slug' => 'python-perulangan-melewati-batas-list',
                'description' => 'Program mencoba membaca indeks yang sama dengan panjang list dan memicu IndexError.',
                'broken_code' => 'numbers = [10, 20, 30]

for index in range(len(numbers) + 1):
    print(numbers[index])',
                'buggy_line' => 3,
                'explanation' => 'range sudah berhenti sebelum batas akhirnya. Menambahkan 1 membuat index mencapai len(numbers), sedangkan indeks terakhir adalah len(numbers) - 1. Gunakan range(len(numbers)).',
                'hints' => [
                    0 => [
                        'content' => 'Indeks terakhir selalu satu lebih kecil dari panjang list.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'range tidak perlu ditambah satu.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'numbers = [10, 20, 30]

for index in range(len(numbers)):
    print(numbers[index])',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'list',
                            1 => 'indeks',
                            2 => 'range',
                            3 => 'IndexError',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'numbers = [10, 20, 30]

for number in numbers:
    print(number)',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            2 => [
                'category' => 'python',
                'difficulty' => 'mudah',
                'title' => 'String Python Dibandingkan dengan is',
                'slug' => 'python-string-dibandingkan-dengan-is',
                'description' => 'Kondisi memakai pemeriksaan identitas object untuk membandingkan isi string.',
                'broken_code' => 'role = "".join(["ad", "min"])

if role is "admin":
    print("Akses administrator diberikan")',
                'buggy_line' => 3,
                'explanation' => 'Operator is memeriksa apakah dua reference menunjuk object yang sama, bukan apakah nilainya sama. Untuk membandingkan isi string gunakan operator ==.',
                'hints' => [
                    0 => [
                        'content' => 'is digunakan untuk identitas object.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan operator kesamaan nilai untuk string.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'role = "".join(["ad", "min"])

if role == "admin":
    print("Akses administrator diberikan")',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'string',
                            1 => 'identitas',
                            2 => 'is',
                            3 => '==',
                        ],
                    ],
                ],
            ],
            3 => [
                'category' => 'python',
                'difficulty' => 'menengah',
                'title' => 'Default Argument Mutable Python',
                'slug' => 'python-default-argument-mutable',
                'description' => 'Pemanggilan function yang berbeda berbagi list default yang sama sehingga data lama ikut terbawa.',
                'broken_code' => 'def add_item(item, items=[]):
    items.append(item)
    return items

print(add_item("A"))
print(add_item("B"))',
                'buggy_line' => 1,
                'explanation' => 'Default argument dievaluasi sekali ketika function didefinisikan. List yang sama dipakai pada setiap pemanggilan sehingga state tersimpan. Gunakan None sebagai default lalu buat list baru di dalam function.',
                'hints' => [
                    0 => [
                        'content' => 'Default argument tidak dibuat ulang setiap function dipanggil.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan None lalu inisialisasi list di dalam function.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'def add_item(item, items=None):
    if items is None:
        items = []

    items.append(item)
    return items

print(add_item("A"))
print(add_item("B"))',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'default argument',
                            1 => 'mutable',
                            2 => 'list',
                            3 => 'None',
                        ],
                    ],
                ],
            ],
            4 => [
                'category' => 'python',
                'difficulty' => 'menengah',
                'title' => 'Late Binding pada Lambda Python',
                'slug' => 'python-late-binding-pada-lambda',
                'description' => 'Semua lambda menggunakan nilai loop terakhir karena variabel dicari ketika lambda dipanggil.',
                'broken_code' => 'functions = []

for number in range(3):
    functions.append(lambda: number)

print([function() for function in functions])',
                'buggy_line' => 4,
                'explanation' => 'Closure Python menggunakan late binding sehingga number dibaca saat lambda dijalankan, setelah loop selesai. Tangkap nilai setiap iterasi menggunakan default argument lambda number=number.',
                'hints' => [
                    0 => [
                        'content' => 'Lambda tidak langsung menyimpan nilai variabel loop.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tangkap nilai loop melalui default argument.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'functions = []

for number in range(3):
    functions.append(lambda number=number: number)

print([function() for function in functions])',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'lambda',
                            1 => 'closure',
                            2 => 'late binding',
                            3 => 'default argument',
                        ],
                    ],
                ],
            ],
            5 => [
                'category' => 'python',
                'difficulty' => 'menengah',
                'title' => 'Dictionary Diubah saat Iterasi',
                'slug' => 'python-dictionary-diubah-saat-iterasi',
                'description' => 'Program menghapus key dari dictionary yang sedang diiterasi dan memicu RuntimeError.',
                'broken_code' => 'scores = {
    "Ana": 90,
    "Rifqi": 40,
    "Budi": 75,
}

for name, score in scores.items():
    if score < 60:
        del scores[name]

print(scores)',
                'buggy_line' => 9,
                'explanation' => 'Mengubah ukuran dictionary selama iterasi atas scores.items menyebabkan RuntimeError. Iterasikan salinan item, buat dictionary baru, atau kumpulkan key yang akan dihapus terlebih dahulu.',
                'hints' => [
                    0 => [
                        'content' => 'Loop sedang memakai view langsung dari dictionary.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Iterasikan salinan sebelum menghapus key.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'scores = {
    "Ana": 90,
    "Rifqi": 40,
    "Budi": 75,
}

for name, score in list(scores.items()):
    if score < 60:
        del scores[name]

print(scores)',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'dictionary',
                            1 => 'iterasi',
                            2 => 'RuntimeError',
                            3 => 'salinan',
                        ],
                    ],
                ],
            ],
            6 => [
                'category' => 'python',
                'difficulty' => 'sulit',
                'title' => 'Salinan Dangkal pada List Bersarang',
                'slug' => 'python-salinan-dangkal-list-bersarang',
                'description' => 'Mengubah list hasil salinan juga mengubah list asli karena elemen bersarang masih menggunakan object yang sama.',
                'broken_code' => 'original = [[1, 2], [3, 4]]
copied = original.copy()

copied[0].append(99)

print(original)',
                'buggy_line' => 2,
                'explanation' => 'list.copy hanya membuat shallow copy. List luar berbeda, tetapi list di dalamnya masih direferensikan bersama. Gunakan copy.deepcopy ketika seluruh struktur bersarang harus disalin secara independen.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa object pada tingkat list bagian dalam.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan deep copy untuk struktur bersarang.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'import copy

original = [[1, 2], [3, 4]]
copied = copy.deepcopy(original)

copied[0].append(99)

print(original)',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'shallow copy',
                            1 => 'deepcopy',
                            2 => 'list bersarang',
                            3 => 'reference',
                        ],
                    ],
                ],
            ],
            7 => [
                'category' => 'python',
                'difficulty' => 'sulit',
                'title' => 'Coroutine Python Tidak Ditunggu',
                'slug' => 'python-coroutine-tidak-ditunggu',
                'description' => 'Program mencetak object coroutine dan function asynchronous tidak pernah benar-benar dijalankan.',
                'broken_code' => 'import asyncio

async def load_data():
    await asyncio.sleep(0.1)
    return {"status": "ok"}

async def main():
    result = load_data()
    print(result)

asyncio.run(main())',
                'buggy_line' => 8,
                'explanation' => 'Memanggil async function menghasilkan object coroutine. Coroutine harus ditunggu menggunakan await agar dijalankan dan menghasilkan nilai kembalian. Gunakan result = await load_data().',
                'hints' => [
                    0 => [
                        'content' => 'async function tidak langsung mengembalikan data akhirnya.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan await di dalam function asynchronous.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'import asyncio

async def load_data():
    await asyncio.sleep(0.1)
    return {"status": "ok"}

async def main():
    result = await load_data()
    print(result)

asyncio.run(main())',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'coroutine',
                            1 => 'async',
                            2 => 'await',
                            3 => 'nilai kembalian',
                        ],
                    ],
                ],
            ],
        ];
    }
}
