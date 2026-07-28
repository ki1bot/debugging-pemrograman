<?php

namespace Database\Seeders\Data;

class CppChallenges
{
    public static function all(): array
    {
        return [
            0 => [
                'category' => 'cpp',
                'difficulty' => 'mudah',
                'title' => 'Parameter C++ Tidak Mengubah Nilai Asli',
                'slug' => 'cpp-parameter-tidak-mengubah-nilai-asli',
                'description' => 'Function dipanggil untuk menambah skor, tetapi nilai skor pada function utama tetap tidak berubah.',
                'broken_code' => '#include <iostream>

void addPoint(int score) {
    score++;
}

int main() {
    int score = 10;
    addPoint(score);

    std::cout << score << \'\\n\';

    return 0;
}',
                'buggy_line' => 3,
                'explanation' => 'Parameter score diterima menggunakan pass by value sehingga function hanya mengubah salinan nilai. Parameter harus diterima sebagai reference menggunakan int& agar perubahan diterapkan pada variabel milik pemanggil.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa apakah function menerima nilai asli atau salinannya.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan reference pada parameter function.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <iostream>

void addPoint(int& score) {
    score++;
}

int main() {
    int score = 10;
    addPoint(score);

    std::cout << score << \'\\n\';

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'pass by value',
                            1 => 'reference',
                            2 => 'salinan',
                            3 => 'pemanggil',
                        ],
                    ],
                ],
            ],
            1 => [
                'category' => 'cpp',
                'difficulty' => 'mudah',
                'title' => 'Perulangan vector Melewati Batas',
                'slug' => 'cpp-perulangan-vector-melewati-batas',
                'description' => 'Perulangan mencoba membaca elemen pada indeks yang sama dengan ukuran vector.',
                'broken_code' => '#include <iostream>
#include <vector>

int main() {
    std::vector<int> numbers{10, 20, 30};

    for (std::size_t i = 0; i <= numbers.size(); i++) {
        std::cout << numbers.at(i) << \'\\n\';
    }

    return 0;
}',
                'buggy_line' => 7,
                'explanation' => 'Indeks terakhir vector adalah size() - 1. Kondisi <= membuat i mencapai numbers.size() sehingga at melempar std::out_of_range. Kondisi harus menggunakan i < numbers.size().',
                'hints' => [
                    0 => [
                        'content' => 'Bandingkan ukuran vector dengan indeks terakhir.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'at akan memeriksa akses di luar batas.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <iostream>
#include <vector>

int main() {
    std::vector<int> numbers{10, 20, 30};

    for (std::size_t i = 0; i < numbers.size(); i++) {
        std::cout << numbers.at(i) << \'\\n\';
    }

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'vector',
                            1 => 'indeks',
                            2 => 'size',
                            3 => 'out_of_range',
                        ],
                    ],
                ],
            ],
            2 => [
                'category' => 'cpp',
                'difficulty' => 'mudah',
                'title' => 'Assignment Digunakan pada Kondisi C++',
                'slug' => 'cpp-assignment-digunakan-pada-kondisi',
                'description' => 'Kondisi mengubah nilai variabel dan selalu masuk ke blok if.',
                'broken_code' => '#include <iostream>

int main() {
    int score = 80;

    if (score = 100) {
        std::cout << "Nilai sempurna\\n";
    }

    return 0;
}',
                'buggy_line' => 6,
                'explanation' => 'Operator = melakukan assignment, bukan perbandingan. Nilai score diubah menjadi 100 dan hasil assignment dianggap true. Gunakan == untuk membandingkan nilai.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa operator yang digunakan di dalam if.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan operator perbandingan tanpa mengubah score.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <iostream>

int main() {
    int score = 80;

    if (score == 100) {
        std::cout << "Nilai sempurna\\n";
    }

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'assignment',
                            1 => 'perbandingan',
                            2 => 'operator',
                            3 => 'true',
                        ],
                    ],
                ],
            ],
            3 => [
                'category' => 'cpp',
                'difficulty' => 'menengah',
                'title' => 'Iterator Tidak Valid Setelah erase',
                'slug' => 'cpp-iterator-tidak-valid-setelah-erase',
                'description' => 'Program melanjutkan iterasi menggunakan iterator yang sudah tidak valid setelah elemen dihapus.',
                'broken_code' => '#include <iostream>
#include <vector>

int main() {
    std::vector<int> numbers{1, 2, 3, 4, 5};

    for (auto iterator = numbers.begin(); iterator != numbers.end(); iterator++) {
        if (*iterator % 2 == 0) {
            numbers.erase(iterator);
        }
    }

    for (int number : numbers) {
        std::cout << number << \' \';
    }

    return 0;
}',
                'buggy_line' => 9,
                'explanation' => 'vector::erase membuat iterator pada posisi yang dihapus dan iterator setelahnya menjadi tidak valid. erase mengembalikan iterator berikutnya yang valid. Loop harus menggunakan nilai kembalian tersebut dan hanya melakukan increment ketika tidak menghapus elemen.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa nilai yang dikembalikan oleh vector::erase.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Jangan melakukan increment otomatis setelah erase.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <iostream>
#include <vector>

int main() {
    std::vector<int> numbers{1, 2, 3, 4, 5};

    for (auto iterator = numbers.begin(); iterator != numbers.end();) {
        if (*iterator % 2 == 0) {
            iterator = numbers.erase(iterator);
        } else {
            iterator++;
        }
    }

    for (int number : numbers) {
        std::cout << number << \' \';
    }

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'iterator',
                            1 => 'erase',
                            2 => 'tidak valid',
                            3 => 'nilai kembalian',
                        ],
                    ],
                ],
            ],
            4 => [
                'category' => 'cpp',
                'difficulty' => 'menengah',
                'title' => 'Object Slicing pada Parameter C++',
                'slug' => 'cpp-object-slicing-pada-parameter',
                'description' => 'Method turunan tidak dipanggil karena object dikirim ke function menggunakan pass by value.',
                'broken_code' => '#include <iostream>
#include <string>

class Animal {
public:
    virtual std::string sound() const {
        return "Unknown";
    }
};

class Cat : public Animal {
public:
    std::string sound() const override {
        return "Meow";
    }
};

void printSound(Animal animal) {
    std::cout << animal.sound() << \'\\n\';
}

int main() {
    Cat cat;
    printSound(cat);

    return 0;
}',
                'buggy_line' => 18,
                'explanation' => 'Mengirim Cat sebagai Animal menggunakan pass by value memotong bagian turunan dan menghasilkan object slicing. Parameter harus berupa const Animal& agar tipe dinamis tetap dipertahankan dan virtual dispatch memanggil Cat::sound.',
                'hints' => [
                    0 => [
                        'content' => 'Pass by value membuat object baru bertipe parameter.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan reference ke class dasar untuk mempertahankan polymorphism.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <iostream>
#include <string>

class Animal {
public:
    virtual std::string sound() const {
        return "Unknown";
    }

    virtual ~Animal() = default;
};

class Cat : public Animal {
public:
    std::string sound() const override {
        return "Meow";
    }
};

void printSound(const Animal& animal) {
    std::cout << animal.sound() << \'\\n\';
}

int main() {
    Cat cat;
    printSound(cat);

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'object slicing',
                            1 => 'reference',
                            2 => 'polymorphism',
                            3 => 'virtual',
                        ],
                    ],
                ],
            ],
            5 => [
                'category' => 'cpp',
                'difficulty' => 'menengah',
                'title' => 'Reference Mengarah ke Variabel Lokal',
                'slug' => 'cpp-reference-mengarah-ke-variabel-lokal',
                'description' => 'Function mengembalikan reference ke object lokal yang sudah dihancurkan setelah function selesai.',
                'broken_code' => '#include <iostream>
#include <string>

const std::string& createName() {
    std::string name = "BugHunt";
    return name;
}

int main() {
    std::cout << createName() << \'\\n\';

    return 0;
}',
                'buggy_line' => 6,
                'explanation' => 'Variabel name memiliki automatic storage duration dan dihancurkan ketika createName selesai. Reference yang dikembalikan menjadi dangling reference. Function harus mengembalikan std::string berdasarkan nilai.',
                'hints' => [
                    0 => [
                        'content' => 'Perhatikan umur variabel lokal setelah function selesai.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Jangan mengembalikan reference ke object lokal.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <iostream>
#include <string>

std::string createName() {
    std::string name = "BugHunt";
    return name;
}

int main() {
    std::cout << createName() << \'\\n\';

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'reference',
                            1 => 'variabel lokal',
                            2 => 'dangling',
                            3 => 'return by value',
                        ],
                    ],
                ],
            ],
            6 => [
                'category' => 'cpp',
                'difficulty' => 'sulit',
                'title' => 'Lambda Menangkap Reference yang Kedaluwarsa',
                'slug' => 'cpp-lambda-menangkap-reference-kedaluwarsa',
                'description' => 'Lambda menyimpan reference ke variabel lokal yang sudah tidak ada ketika lambda dijalankan.',
                'broken_code' => '#include <functional>
#include <iostream>

std::function<int()> createGetter() {
    int value = 42;
    return [&value]() {
        return value;
    };
}

int main() {
    auto getter = createGetter();
    std::cout << getter() << \'\\n\';

    return 0;
}',
                'buggy_line' => 6,
                'explanation' => 'Lambda menangkap value berdasarkan reference, tetapi value dihancurkan ketika createGetter selesai. Lambda kemudian memiliki dangling reference. Tangkap value berdasarkan nilai agar salinannya disimpan di dalam closure.',
                'hints' => [
                    0 => [
                        'content' => 'Bandingkan capture [&value] dengan [value].',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Closure digunakan setelah function pembuatnya selesai.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <functional>
#include <iostream>

std::function<int()> createGetter() {
    int value = 42;
    return [value]() {
        return value;
    };
}

int main() {
    auto getter = createGetter();
    std::cout << getter() << \'\\n\';

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'lambda',
                            1 => 'capture',
                            2 => 'reference',
                            3 => 'dangling',
                        ],
                    ],
                ],
            ],
            7 => [
                'category' => 'cpp',
                'difficulty' => 'sulit',
                'title' => 'Race Condition pada Counter C++',
                'slug' => 'cpp-race-condition-pada-counter',
                'description' => 'Dua thread mengubah counter yang sama tanpa sinkronisasi sehingga hasil akhir tidak dapat dipastikan.',
                'broken_code' => '#include <iostream>
#include <thread>

int counter = 0;

void increment() {
    for (int i = 0; i < 100000; i++) {
        counter++;
    }
}

int main() {
    std::thread first(increment);
    std::thread second(increment);

    first.join();
    second.join();

    std::cout << counter << \'\\n\';

    return 0;
}',
                'buggy_line' => 8,
                'explanation' => 'Operasi counter++ bukan operasi atomik dan terdiri dari baca, tambah, serta tulis. Dua thread dapat saling menimpa hasil sehingga terjadi data race. Gunakan std::atomic<int> atau mutex untuk menyinkronkan perubahan.',
                'hints' => [
                    0 => [
                        'content' => 'counter++ terdiri dari beberapa operasi memory.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan tipe atomik untuk data yang diubah beberapa thread.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <atomic>
#include <iostream>
#include <thread>

std::atomic<int> counter{0};

void increment() {
    for (int i = 0; i < 100000; i++) {
        counter++;
    }
}

int main() {
    std::thread first(increment);
    std::thread second(increment);

    first.join();
    second.join();

    std::cout << counter.load() << \'\\n\';

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'race condition',
                            1 => 'atomic',
                            2 => 'thread',
                            3 => 'sinkronisasi',
                        ],
                    ],
                ],
            ],
        ];
    }
}
