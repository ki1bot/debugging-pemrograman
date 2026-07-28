<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Difficulty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AdditionalProgrammingLanguageSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $admin = User::query()
                ->where('role', 'admin')
                ->orderBy('id')
                ->firstOrFail();

            $categories = collect($this->categories())
                ->mapWithKeys(function (array $category): array {
                    $model = Category::query()->updateOrCreate(
                        ['slug' => $category['slug']],
                        [
                            'name' => $category['name'],
                            'description' => $category['description'],
                            'is_active' => true,
                        ],
                    );

                    return [$category['slug'] => $model];
                });

            $difficulties = Difficulty::query()
                ->whereIn('slug', ['mudah', 'menengah', 'sulit'])
                ->get()
                ->keyBy('slug');

            foreach (['mudah', 'menengah', 'sulit'] as $difficultySlug) {
                if (! $difficulties->has($difficultySlug)) {
                    throw new RuntimeException(
                        "Tingkat kesulitan {$difficultySlug} belum tersedia.",
                    );
                }
            }

            foreach ($this->challenges() as $data) {
                $category = $categories->get($data['category']);
                $difficulty = $difficulties->get($data['difficulty']);

                $challenge = Challenge::withTrashed()->updateOrCreate(
                    ['slug' => $data['slug']],
                    [
                        'category_id' => $category->id,
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

    private function categories(): array
    {
        return [
            [
                'name' => 'C',
                'slug' => 'c',
                'description' => 'Tantangan debugging bahasa C yang mencakup array, pointer, function, alokasi memory, input, dan pengelolaan resource.',
            ],
            [
                'name' => 'C++',
                'slug' => 'cpp',
                'description' => 'Tantangan debugging C++ yang mencakup reference, object, STL, iterator, polymorphism, lambda, dan concurrency.',
            ],
            [
                'name' => 'Java',
                'slug' => 'java',
                'description' => 'Tantangan debugging Java yang mencakup String, array, collection, object, exception, concurrency, dan thread safety.',
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'description' => 'Tantangan debugging Python yang mencakup list, dictionary, function, closure, salinan data, dan asynchronous programming.',
            ],
        ];
    }

    private function challenge(
        string $category,
        string $difficulty,
        string $title,
        string $slug,
        string $description,
        string $brokenCode,
        int $buggyLine,
        string $explanation,
        string $firstHint,
        string $secondHint,
        string $primarySolutionCode,
        array $requiredKeywords,
        ?string $alternativeSolutionCode = null,
    ): array {
        $solutions = [
            [
                'solution_code' => $primarySolutionCode,
                'solution_type' => 'primary',
                'required_keywords' => $requiredKeywords,
            ],
        ];

        if ($alternativeSolutionCode !== null) {
            $solutions[] = [
                'solution_code' => $alternativeSolutionCode,
                'solution_type' => 'alternative',
                'required_keywords' => [],
            ];
        }

        return [
            'category' => $category,
            'difficulty' => $difficulty,
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'broken_code' => $brokenCode,
            'buggy_line' => $buggyLine,
            'explanation' => $explanation,
            'hints' => [
                [
                    'content' => $firstHint,
                    'point_penalty' => 10,
                ],
                [
                    'content' => $secondHint,
                    'point_penalty' => 20,
                ],
            ],
            'solutions' => $solutions,
        ];
    }

    private function challenges(): array
    {
        return [
            $this->challenge(
                'c',
                'mudah',
                'Perulangan C Melewati Batas Array',
                'c-perulangan-melewati-batas-array',
                'Program mengakses satu indeks setelah elemen terakhir array sehingga menghasilkan perilaku yang tidak terdefinisi.',
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int numbers[] = {10, 20, 30};
    int length = sizeof(numbers) / sizeof(numbers[0]);

    for (int i = 0; i <= length; i++) {
        printf("%d\n", numbers[i]);
    }

    return 0;
}
CODE,
                7,
                'Indeks array C dimulai dari nol dan indeks terakhir adalah length - 1. Kondisi i <= length membuat program mengakses numbers[length] yang berada di luar batas array. Kondisi perulangan harus menggunakan i < length.',
                'Bandingkan nilai length dengan indeks terakhir yang valid.',
                'Perulangan berjalan satu kali terlalu banyak karena operator <=.',
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int numbers[] = {10, 20, 30};
    int length = sizeof(numbers) / sizeof(numbers[0]);

    for (int i = 0; i < length; i++) {
        printf("%d\n", numbers[i]);
    }

    return 0;
}
CODE,
                [
                    'array',
                    'indeks',
                    'length',
                    'di luar batas',
                ],
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int numbers[] = {10, 20, 30};
    int length = sizeof(numbers) / sizeof(numbers[0]);

    for (int i = 0; i <= length - 1; i++) {
        printf("%d\n", numbers[i]);
    }

    return 0;
}
CODE,
            ),
            $this->challenge(
                'c',
                'mudah',
                'Assignment Digunakan pada Kondisi C',
                'c-assignment-digunakan-pada-kondisi',
                'Kondisi selalu dianggap benar karena program mengubah nilai variabel ketika seharusnya membandingkannya.',
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int age = 18;

    if (age = 20) {
        printf("Usia adalah 20 tahun\n");
    }

    return 0;
}
CODE,
                6,
                'Operator = melakukan assignment dan menghasilkan nilai 20 yang dianggap benar. Kondisi harus menggunakan operator perbandingan == agar nilai age dibandingkan dengan 20 tanpa diubah.',
                'Periksa apakah operator pada kondisi membandingkan atau mengubah nilai.',
                'Gunakan operator perbandingan, bukan operator assignment.',
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int age = 18;

    if (age == 20) {
        printf("Usia adalah 20 tahun\n");
    }

    return 0;
}
CODE,
                [
                    'assignment',
                    'perbandingan',
                    'operator',
                    'nilai',
                ],
            ),
            $this->challenge(
                'c',
                'mudah',
                'Pembagian Bilangan Bulat C',
                'c-pembagian-bilangan-bulat',
                'Hasil rata-rata kehilangan bagian desimal meskipun disimpan di dalam variabel bertipe double.',
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int total = 7;
    int count = 2;
    double average = total / count;

    printf("%.1f\n", average);

    return 0;
}
CODE,
                6,
                'total dan count bertipe int sehingga pembagian dilakukan sebagai integer division sebelum hasilnya disimpan ke double. Salah satu operand harus dikonversi menjadi double agar hasil pembagian mempertahankan bagian desimal.',
                'Tipe variabel tujuan tidak mengubah cara kedua operand dibagi.',
                'Ubah salah satu operand menjadi double sebelum pembagian.',
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int total = 7;
    int count = 2;
    double average = (double) total / count;

    printf("%.1f\n", average);

    return 0;
}
CODE,
                [
                    'integer division',
                    'double',
                    'operand',
                    'desimal',
                ],
            ),
            $this->challenge(
                'c',
                'menengah',
                'Alamat Variabel Tidak Dikirim ke scanf',
                'c-alamat-variabel-tidak-dikirim-ke-scanf',
                'Program dapat berhenti atau menulis ke alamat memory yang salah ketika membaca input angka.',
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int age;

    scanf("%d", age);
    printf("%d\n", age);

    return 0;
}
CODE,
                6,
                'scanf memerlukan alamat memory tempat hasil input akan disimpan. Variabel age harus dikirim menggunakan operator alamat &. Tanpa &, nilai age yang belum terinisialisasi dianggap sebagai alamat memory.',
                'scanf perlu mengetahui lokasi memory tujuan.',
                'Gunakan operator alamat pada variabel age.',
                <<<'CODE'
#include <stdio.h>

int main(void) {
    int age;

    scanf("%d", &age);
    printf("%d\n", age);

    return 0;
}
CODE,
                [
                    'scanf',
                    'alamat',
                    'memory',
                    '&age',
                ],
            ),
            $this->challenge(
                'c',
                'menengah',
                'sizeof Digunakan pada Pointer',
                'c-sizeof-digunakan-pada-pointer',
                'Function menghitung panjang array secara salah karena parameter array telah berubah menjadi pointer.',
                <<<'CODE'
#include <stdio.h>

void printNumbers(int numbers[]) {
    int length = sizeof(numbers) / sizeof(numbers[0]);

    for (int i = 0; i < length; i++) {
        printf("%d\n", numbers[i]);
    }
}

int main(void) {
    int numbers[] = {10, 20, 30, 40};
    printNumbers(numbers);

    return 0;
}
CODE,
                4,
                'Parameter array pada function C mengalami array-to-pointer decay sehingga sizeof(numbers) menghasilkan ukuran pointer, bukan ukuran seluruh array. Panjang array harus dihitung di pemanggil lalu dikirim sebagai parameter terpisah.',
                'Parameter array pada function tidak lagi membawa informasi ukurannya.',
                'Kirim panjang array sebagai parameter function.',
                <<<'CODE'
#include <stdio.h>

void printNumbers(int numbers[], int length) {
    for (int i = 0; i < length; i++) {
        printf("%d\n", numbers[i]);
    }
}

int main(void) {
    int numbers[] = {10, 20, 30, 40};
    int length = sizeof(numbers) / sizeof(numbers[0]);
    printNumbers(numbers, length);

    return 0;
}
CODE,
                [
                    'sizeof',
                    'pointer',
                    'array',
                    'panjang',
                ],
            ),
            $this->challenge(
                'c',
                'menengah',
                'Alokasi Memory C Terlalu Kecil',
                'c-alokasi-memory-terlalu-kecil',
                'Program hanya mengalokasikan beberapa byte meskipun membutuhkan ruang untuk beberapa nilai int.',
                <<<'CODE'
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int length = 5;
    int *numbers = malloc(length);

    if (numbers == NULL) {
        return 1;
    }

    for (int i = 0; i < length; i++) {
        numbers[i] = i * 10;
    }

    free(numbers);
    return 0;
}
CODE,
                6,
                'malloc menerima jumlah byte, sedangkan length hanya menyatakan jumlah elemen. Alokasi harus mengalikan jumlah elemen dengan sizeof(int) atau sizeof(*numbers) agar tersedia ruang yang cukup.',
                'malloc bekerja menggunakan satuan byte.',
                'Kalikan jumlah elemen dengan ukuran setiap elemen.',
                <<<'CODE'
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int length = 5;
    int *numbers = malloc(length * sizeof(*numbers));

    if (numbers == NULL) {
        return 1;
    }

    for (int i = 0; i < length; i++) {
        numbers[i] = i * 10;
    }

    free(numbers);
    return 0;
}
CODE,
                [
                    'malloc',
                    'byte',
                    'sizeof',
                    'elemen',
                ],
            ),
            $this->challenge(
                'c',
                'sulit',
                'Pointer Digunakan Setelah free',
                'c-pointer-digunakan-setelah-free',
                'Program membaca memory yang sudah dibebaskan sehingga menghasilkan undefined behavior.',
                <<<'CODE'
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int *value = malloc(sizeof(*value));
    *value = 42;
    free(value);

    printf("%d\n", *value);

    return 0;
}
CODE,
                9,
                'Setelah free dipanggil, memory yang ditunjuk value tidak lagi boleh diakses. Membaca *value setelah free adalah use-after-free. Nilai harus digunakan sebelum memory dibebaskan dan pointer sebaiknya diatur menjadi NULL.',
                'Perhatikan urutan penggunaan pointer dan pelepasan memory.',
                'Memory tidak boleh dibaca setelah diberikan kepada free.',
                <<<'CODE'
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int *value = malloc(sizeof(*value));

    if (value == NULL) {
        return 1;
    }

    *value = 42;
    printf("%d\n", *value);

    free(value);
    value = NULL;

    return 0;
}
CODE,
                [
                    'use-after-free',
                    'free',
                    'pointer',
                    'undefined behavior',
                ],
            ),
            $this->challenge(
                'c',
                'sulit',
                'Hasil realloc Menimpa Pointer Asli',
                'c-hasil-realloc-menimpa-pointer-asli',
                'Kegagalan realloc dapat membuat alamat alokasi lama hilang dan menyebabkan memory leak.',
                <<<'CODE'
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int *numbers = malloc(2 * sizeof(*numbers));

    numbers = realloc(numbers, 4 * sizeof(*numbers));

    if (numbers == NULL) {
        return 1;
    }

    free(numbers);
    return 0;
}
CODE,
                7,
                'Jika realloc gagal, function mengembalikan NULL sementara alokasi lama tetap aktif. Menyimpan hasil langsung ke numbers menghilangkan alamat alokasi lama. Hasil realloc harus disimpan pada pointer sementara lalu diperiksa sebelum mengganti pointer asli.',
                'realloc dapat mengembalikan NULL tanpa membebaskan blok lama.',
                'Simpan hasil realloc pada pointer sementara.',
                <<<'CODE'
#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int *numbers = malloc(2 * sizeof(*numbers));

    if (numbers == NULL) {
        return 1;
    }

    int *resized = realloc(numbers, 4 * sizeof(*numbers));

    if (resized == NULL) {
        free(numbers);
        return 1;
    }

    numbers = resized;

    free(numbers);
    return 0;
}
CODE,
                [
                    'realloc',
                    'pointer sementara',
                    'NULL',
                    'memory leak',
                ],
            ),
            $this->challenge(
                'cpp',
                'mudah',
                'Parameter C++ Tidak Mengubah Nilai Asli',
                'cpp-parameter-tidak-mengubah-nilai-asli',
                'Function dipanggil untuk menambah skor, tetapi nilai skor pada function utama tetap tidak berubah.',
                <<<'CODE'
#include <iostream>

void addPoint(int score) {
    score++;
}

int main() {
    int score = 10;
    addPoint(score);

    std::cout << score << '\n';

    return 0;
}
CODE,
                3,
                'Parameter score diterima menggunakan pass by value sehingga function hanya mengubah salinan nilai. Parameter harus diterima sebagai reference menggunakan int& agar perubahan diterapkan pada variabel milik pemanggil.',
                'Periksa apakah function menerima nilai asli atau salinannya.',
                'Gunakan reference pada parameter function.',
                <<<'CODE'
#include <iostream>

void addPoint(int& score) {
    score++;
}

int main() {
    int score = 10;
    addPoint(score);

    std::cout << score << '\n';

    return 0;
}
CODE,
                [
                    'pass by value',
                    'reference',
                    'salinan',
                    'pemanggil',
                ],
            ),
            $this->challenge(
                'cpp',
                'mudah',
                'Perulangan vector Melewati Batas',
                'cpp-perulangan-vector-melewati-batas',
                'Perulangan mencoba membaca elemen pada indeks yang sama dengan ukuran vector.',
                <<<'CODE'
#include <iostream>
#include <vector>

int main() {
    std::vector<int> numbers{10, 20, 30};

    for (std::size_t i = 0; i <= numbers.size(); i++) {
        std::cout << numbers.at(i) << '\n';
    }

    return 0;
}
CODE,
                7,
                'Indeks terakhir vector adalah size() - 1. Kondisi <= membuat i mencapai numbers.size() sehingga at melempar std::out_of_range. Kondisi harus menggunakan i < numbers.size().',
                'Bandingkan ukuran vector dengan indeks terakhir.',
                'at akan memeriksa akses di luar batas.',
                <<<'CODE'
#include <iostream>
#include <vector>

int main() {
    std::vector<int> numbers{10, 20, 30};

    for (std::size_t i = 0; i < numbers.size(); i++) {
        std::cout << numbers.at(i) << '\n';
    }

    return 0;
}
CODE,
                [
                    'vector',
                    'indeks',
                    'size',
                    'out_of_range',
                ],
            ),
            $this->challenge(
                'cpp',
                'mudah',
                'Assignment Digunakan pada Kondisi C++',
                'cpp-assignment-digunakan-pada-kondisi',
                'Kondisi mengubah nilai variabel dan selalu masuk ke blok if.',
                <<<'CODE'
#include <iostream>

int main() {
    int score = 80;

    if (score = 100) {
        std::cout << "Nilai sempurna\n";
    }

    return 0;
}
CODE,
                6,
                'Operator = melakukan assignment, bukan perbandingan. Nilai score diubah menjadi 100 dan hasil assignment dianggap true. Gunakan == untuk membandingkan nilai.',
                'Periksa operator yang digunakan di dalam if.',
                'Gunakan operator perbandingan tanpa mengubah score.',
                <<<'CODE'
#include <iostream>

int main() {
    int score = 80;

    if (score == 100) {
        std::cout << "Nilai sempurna\n";
    }

    return 0;
}
CODE,
                [
                    'assignment',
                    'perbandingan',
                    'operator',
                    'true',
                ],
            ),
            $this->challenge(
                'cpp',
                'menengah',
                'Iterator Tidak Valid Setelah erase',
                'cpp-iterator-tidak-valid-setelah-erase',
                'Program melanjutkan iterasi menggunakan iterator yang sudah tidak valid setelah elemen dihapus.',
                <<<'CODE'
#include <iostream>
#include <vector>

int main() {
    std::vector<int> numbers{1, 2, 3, 4, 5};

    for (auto iterator = numbers.begin(); iterator != numbers.end(); iterator++) {
        if (*iterator % 2 == 0) {
            numbers.erase(iterator);
        }
    }

    for (int number : numbers) {
        std::cout << number << ' ';
    }

    return 0;
}
CODE,
                9,
                'vector::erase membuat iterator pada posisi yang dihapus dan iterator setelahnya menjadi tidak valid. erase mengembalikan iterator berikutnya yang valid. Loop harus menggunakan nilai kembalian tersebut dan hanya melakukan increment ketika tidak menghapus elemen.',
                'Periksa nilai yang dikembalikan oleh vector::erase.',
                'Jangan melakukan increment otomatis setelah erase.',
                <<<'CODE'
#include <iostream>
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
        std::cout << number << ' ';
    }

    return 0;
}
CODE,
                [
                    'iterator',
                    'erase',
                    'tidak valid',
                    'nilai kembalian',
                ],
            ),
            $this->challenge(
                'cpp',
                'menengah',
                'Object Slicing pada Parameter C++',
                'cpp-object-slicing-pada-parameter',
                'Method turunan tidak dipanggil karena object dikirim ke function menggunakan pass by value.',
                <<<'CODE'
#include <iostream>
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
    std::cout << animal.sound() << '\n';
}

int main() {
    Cat cat;
    printSound(cat);

    return 0;
}
CODE,
                18,
                'Mengirim Cat sebagai Animal menggunakan pass by value memotong bagian turunan dan menghasilkan object slicing. Parameter harus berupa const Animal& agar tipe dinamis tetap dipertahankan dan virtual dispatch memanggil Cat::sound.',
                'Pass by value membuat object baru bertipe parameter.',
                'Gunakan reference ke class dasar untuk mempertahankan polymorphism.',
                <<<'CODE'
#include <iostream>
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
    std::cout << animal.sound() << '\n';
}

int main() {
    Cat cat;
    printSound(cat);

    return 0;
}
CODE,
                [
                    'object slicing',
                    'reference',
                    'polymorphism',
                    'virtual',
                ],
            ),
            $this->challenge(
                'cpp',
                'menengah',
                'Reference Mengarah ke Variabel Lokal',
                'cpp-reference-mengarah-ke-variabel-lokal',
                'Function mengembalikan reference ke object lokal yang sudah dihancurkan setelah function selesai.',
                <<<'CODE'
#include <iostream>
#include <string>

const std::string& createName() {
    std::string name = "BugHunt";
    return name;
}

int main() {
    std::cout << createName() << '\n';

    return 0;
}
CODE,
                6,
                'Variabel name memiliki automatic storage duration dan dihancurkan ketika createName selesai. Reference yang dikembalikan menjadi dangling reference. Function harus mengembalikan std::string berdasarkan nilai.',
                'Perhatikan umur variabel lokal setelah function selesai.',
                'Jangan mengembalikan reference ke object lokal.',
                <<<'CODE'
#include <iostream>
#include <string>

std::string createName() {
    std::string name = "BugHunt";
    return name;
}

int main() {
    std::cout << createName() << '\n';

    return 0;
}
CODE,
                [
                    'reference',
                    'variabel lokal',
                    'dangling',
                    'return by value',
                ],
            ),
            $this->challenge(
                'cpp',
                'sulit',
                'Lambda Menangkap Reference yang Kedaluwarsa',
                'cpp-lambda-menangkap-reference-kedaluwarsa',
                'Lambda menyimpan reference ke variabel lokal yang sudah tidak ada ketika lambda dijalankan.',
                <<<'CODE'
#include <functional>
#include <iostream>

std::function<int()> createGetter() {
    int value = 42;
    return [&value]() {
        return value;
    };
}

int main() {
    auto getter = createGetter();
    std::cout << getter() << '\n';

    return 0;
}
CODE,
                6,
                'Lambda menangkap value berdasarkan reference, tetapi value dihancurkan ketika createGetter selesai. Lambda kemudian memiliki dangling reference. Tangkap value berdasarkan nilai agar salinannya disimpan di dalam closure.',
                'Bandingkan capture [&value] dengan [value].',
                'Closure digunakan setelah function pembuatnya selesai.',
                <<<'CODE'
#include <functional>
#include <iostream>

std::function<int()> createGetter() {
    int value = 42;
    return [value]() {
        return value;
    };
}

int main() {
    auto getter = createGetter();
    std::cout << getter() << '\n';

    return 0;
}
CODE,
                [
                    'lambda',
                    'capture',
                    'reference',
                    'dangling',
                ],
            ),
            $this->challenge(
                'cpp',
                'sulit',
                'Race Condition pada Counter C++',
                'cpp-race-condition-pada-counter',
                'Dua thread mengubah counter yang sama tanpa sinkronisasi sehingga hasil akhir tidak dapat dipastikan.',
                <<<'CODE'
#include <iostream>
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

    std::cout << counter << '\n';

    return 0;
}
CODE,
                8,
                'Operasi counter++ bukan operasi atomik dan terdiri dari baca, tambah, serta tulis. Dua thread dapat saling menimpa hasil sehingga terjadi data race. Gunakan std::atomic<int> atau mutex untuk menyinkronkan perubahan.',
                'counter++ terdiri dari beberapa operasi memory.',
                'Gunakan tipe atomik untuk data yang diubah beberapa thread.',
                <<<'CODE'
#include <atomic>
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

    std::cout << counter.load() << '\n';

    return 0;
}
CODE,
                [
                    'race condition',
                    'atomic',
                    'thread',
                    'sinkronisasi',
                ],
            ),
            $this->challenge(
                'java',
                'mudah',
                'Perbandingan String Java Menggunakan Operator Identitas',
                'java-perbandingan-string-operator-identitas',
                'Kondisi gagal mengenali teks dengan isi sama karena membandingkan referensi object.',
                <<<'CODE'
public class Main {
    public static void main(String[] args) {
        String role = new String("admin");

        if (role == "admin") {
            System.out.println("Akses administrator diberikan");
        }
    }
}
CODE,
                5,
                'Operator == pada String membandingkan identitas atau referensi object, bukan isi teks. Gunakan equals untuk membandingkan karakter di dalam String. Bentuk "admin".equals(role) aman ketika role mungkin null.',
                'Operator == pada object tidak membandingkan isi.',
                'Gunakan method String untuk membandingkan nilai teks.',
                <<<'CODE'
public class Main {
    public static void main(String[] args) {
        String role = new String("admin");

        if ("admin".equals(role)) {
            System.out.println("Akses administrator diberikan");
        }
    }
}
CODE,
                [
                    'string',
                    'referensi',
                    'isi',
                    'equals',
                ],
                <<<'CODE'
public class Main {
    public static void main(String[] args) {
        String role = new String("admin");

        if (role.equals("admin")) {
            System.out.println("Akses administrator diberikan");
        }
    }
}
CODE,
            ),
            $this->challenge(
                'java',
                'mudah',
                'Perulangan Java Melewati Batas Array',
                'java-perulangan-melewati-batas-array',
                'Program mencoba membaca elemen pada indeks yang sama dengan panjang array.',
                <<<'CODE'
public class Main {
    public static void main(String[] args) {
        int[] numbers = {10, 20, 30};

        for (int i = 0; i <= numbers.length; i++) {
            System.out.println(numbers[i]);
        }
    }
}
CODE,
                5,
                'Indeks array Java yang valid berakhir pada length - 1. Kondisi <= membuat i mencapai numbers.length dan menyebabkan ArrayIndexOutOfBoundsException. Gunakan i < numbers.length.',
                'Bandingkan panjang array dengan indeks terakhir.',
                'Exception muncul ketika indeks sama dengan length.',
                <<<'CODE'
public class Main {
    public static void main(String[] args) {
        int[] numbers = {10, 20, 30};

        for (int i = 0; i < numbers.length; i++) {
            System.out.println(numbers[i]);
        }
    }
}
CODE,
                [
                    'array',
                    'indeks',
                    'length',
                    'ArrayIndexOutOfBoundsException',
                ],
            ),
            $this->challenge(
                'java',
                'mudah',
                'Pembagian Integer pada Java',
                'java-pembagian-integer',
                'Nilai rata-rata kehilangan bagian desimal sebelum disimpan pada variabel double.',
                <<<'CODE'
public class Main {
    public static void main(String[] args) {
        int total = 7;
        int count = 2;
        double average = total / count;

        System.out.println(average);
    }
}
CODE,
                5,
                'Karena total dan count bertipe int, Java melakukan integer division terlebih dahulu. Salah satu operand harus dikonversi menjadi double agar hasil pembagian mempertahankan bagian desimal.',
                'Tipe variabel average tidak mengubah tipe operasi sebelumnya.',
                'Konversikan salah satu operand sebelum pembagian.',
                <<<'CODE'
public class Main {
    public static void main(String[] args) {
        int total = 7;
        int count = 2;
        double average = (double) total / count;

        System.out.println(average);
    }
}
CODE,
                [
                    'integer division',
                    'double',
                    'operand',
                    'desimal',
                ],
            ),
            $this->challenge(
                'java',
                'menengah',
                'Menghapus Elemen saat Enhanced for',
                'java-menghapus-elemen-saat-enhanced-for',
                'Program mengubah ArrayList langsung ketika sedang diiterasi dan memicu ConcurrentModificationException.',
                <<<'CODE'
import java.util.ArrayList;
import java.util.List;

public class Main {
    public static void main(String[] args) {
        List<String> names = new ArrayList<>(List.of("Ana", "Rifqi", "Budi"));

        for (String name : names) {
            if (name.startsWith("R")) {
                names.remove(name);
            }
        }

        System.out.println(names);
    }
}
CODE,
                10,
                'Enhanced for menggunakan iterator internal. Mengubah struktur ArrayList secara langsung selama iterasi membuat iterator mendeteksi modifikasi dan melempar ConcurrentModificationException. Gunakan Iterator.remove atau removeIf.',
                'Enhanced for memakai iterator di balik layar.',
                'Gunakan operasi penghapusan yang aman selama iterasi.',
                <<<'CODE'
import java.util.ArrayList;
import java.util.List;

public class Main {
    public static void main(String[] args) {
        List<String> names = new ArrayList<>(List.of("Ana", "Rifqi", "Budi"));

        names.removeIf(name -> name.startsWith("R"));

        System.out.println(names);
    }
}
CODE,
                [
                    'ArrayList',
                    'iterator',
                    'ConcurrentModificationException',
                    'removeIf',
                ],
            ),
            $this->challenge(
                'java',
                'menengah',
                'Auto-unboxing Nilai null',
                'java-auto-unboxing-nilai-null',
                'Program melempar NullPointerException ketika nilai null dari Map dikonversi otomatis menjadi int.',
                <<<'CODE'
import java.util.HashMap;
import java.util.Map;

public class Main {
    public static void main(String[] args) {
        Map<String, Integer> scores = new HashMap<>();
        scores.put("Ana", 90);

        int score = scores.get("Rifqi");

        System.out.println(score);
    }
}
CODE,
                9,
                'Map.get mengembalikan null ketika key tidak ditemukan. Penugasan ke int memicu auto-unboxing dari Integer null dan menyebabkan NullPointerException. Gunakan getOrDefault atau periksa null sebelum unboxing.',
                'Periksa hasil Map.get untuk key yang tidak tersedia.',
                'Gunakan nilai default sebelum dikonversi menjadi int.',
                <<<'CODE'
import java.util.HashMap;
import java.util.Map;

public class Main {
    public static void main(String[] args) {
        Map<String, Integer> scores = new HashMap<>();
        scores.put("Ana", 90);

        int score = scores.getOrDefault("Rifqi", 0);

        System.out.println(score);
    }
}
CODE,
                [
                    'Map.get',
                    'null',
                    'auto-unboxing',
                    'getOrDefault',
                ],
            ),
            $this->challenge(
                'java',
                'menengah',
                'BigDecimal Dibandingkan dengan equals',
                'java-bigdecimal-dibandingkan-dengan-equals',
                'Dua nilai numerik yang sama dianggap berbeda karena memiliki skala desimal berbeda.',
                <<<'CODE'
import java.math.BigDecimal;

public class Main {
    public static void main(String[] args) {
        BigDecimal first = new BigDecimal("10.0");
        BigDecimal second = new BigDecimal("10.00");

        System.out.println(first.equals(second));
    }
}
CODE,
                8,
                'BigDecimal.equals membandingkan nilai sekaligus scale sehingga 10.0 dan 10.00 dianggap berbeda. Untuk membandingkan nilai numerik tanpa memperhatikan scale, gunakan compareTo dan periksa apakah hasilnya nol.',
                'equals pada BigDecimal juga memperhatikan scale.',
                'Gunakan compareTo untuk perbandingan numerik.',
                <<<'CODE'
import java.math.BigDecimal;

public class Main {
    public static void main(String[] args) {
        BigDecimal first = new BigDecimal("10.0");
        BigDecimal second = new BigDecimal("10.00");

        System.out.println(first.compareTo(second) == 0);
    }
}
CODE,
                [
                    'BigDecimal',
                    'equals',
                    'scale',
                    'compareTo',
                ],
            ),
            $this->challenge(
                'java',
                'sulit',
                'wait Dipanggil di Luar synchronized',
                'java-wait-di-luar-synchronized',
                'Thread memanggil wait tanpa memiliki monitor object dan melempar IllegalMonitorStateException.',
                <<<'CODE'
public class Main {
    public static void main(String[] args) throws InterruptedException {
        Object lock = new Object();

        lock.wait();

        System.out.println("Selesai");
    }
}
CODE,
                5,
                'Object.wait hanya boleh dipanggil oleh thread yang sedang memiliki monitor object tersebut. Pemanggilan harus berada di dalam blok synchronized(lock). Jika tidak, Java melempar IllegalMonitorStateException.',
                'wait berhubungan dengan monitor sebuah object.',
                'Thread harus memiliki monitor sebelum memanggil wait.',
                <<<'CODE'
public class Main {
    public static void main(String[] args) throws InterruptedException {
        Object lock = new Object();

        synchronized (lock) {
            lock.wait(100);
        }

        System.out.println("Selesai");
    }
}
CODE,
                [
                    'wait',
                    'synchronized',
                    'monitor',
                    'IllegalMonitorStateException',
                ],
            ),
            $this->challenge(
                'java',
                'sulit',
                'Double-checked Locking Tanpa volatile',
                'java-double-checked-locking-tanpa-volatile',
                'Singleton menggunakan double-checked locking tetapi field instance tidak menjamin visibility dan ordering antar-thread.',
                <<<'CODE'
public final class Singleton {
    private static Singleton instance;

    private Singleton() {
    }

    public static Singleton getInstance() {
        if (instance == null) {
            synchronized (Singleton.class) {
                if (instance == null) {
                    instance = new Singleton();
                }
            }
        }

        return instance;
    }
}
CODE,
                2,
                'Double-checked locking membutuhkan volatile agar penulisan reference dan konstruksi object memiliki aturan visibility serta ordering yang benar antar-thread. Tanpa volatile, thread lain dapat melihat reference sebelum konstruksi sepenuhnya terlihat.',
                'Masalah terjadi pada visibility antar-thread.',
                'Tambahkan modifier yang mencegah reordering pada field instance.',
                <<<'CODE'
public final class Singleton {
    private static volatile Singleton instance;

    private Singleton() {
    }

    public static Singleton getInstance() {
        if (instance == null) {
            synchronized (Singleton.class) {
                if (instance == null) {
                    instance = new Singleton();
                }
            }
        }

        return instance;
    }
}
CODE,
                [
                    'double-checked locking',
                    'volatile',
                    'visibility',
                    'reordering',
                ],
            ),
            $this->challenge(
                'python',
                'mudah',
                'Hasil append Python Disimpan Kembali',
                'python-hasil-append-disimpan-kembali',
                'Variabel list berubah menjadi None setelah program menambahkan elemen.',
                <<<'CODE'
numbers = [1, 2, 3]
numbers = numbers.append(4)

print(numbers)
CODE,
                2,
                'Method append mengubah list secara langsung dan mengembalikan None. Menyimpan hasil append ke numbers mengganti reference list dengan None. Panggil numbers.append(4) tanpa assignment.',
                'Periksa nilai kembalian method append.',
                'append mengubah list yang sudah ada.',
                <<<'CODE'
numbers = [1, 2, 3]
numbers.append(4)

print(numbers)
CODE,
                [
                    'append',
                    'list',
                    'None',
                    'assignment',
                ],
                <<<'CODE'
numbers = [1, 2, 3]
numbers = numbers + [4]

print(numbers)
CODE,
            ),
            $this->challenge(
                'python',
                'mudah',
                'Perulangan Python Melewati Batas List',
                'python-perulangan-melewati-batas-list',
                'Program mencoba membaca indeks yang sama dengan panjang list dan memicu IndexError.',
                <<<'CODE'
numbers = [10, 20, 30]

for index in range(len(numbers) + 1):
    print(numbers[index])
CODE,
                3,
                'range sudah berhenti sebelum batas akhirnya. Menambahkan 1 membuat index mencapai len(numbers), sedangkan indeks terakhir adalah len(numbers) - 1. Gunakan range(len(numbers)).',
                'Indeks terakhir selalu satu lebih kecil dari panjang list.',
                'range tidak perlu ditambah satu.',
                <<<'CODE'
numbers = [10, 20, 30]

for index in range(len(numbers)):
    print(numbers[index])
CODE,
                [
                    'list',
                    'indeks',
                    'range',
                    'IndexError',
                ],
                <<<'CODE'
numbers = [10, 20, 30]

for number in numbers:
    print(number)
CODE,
            ),
            $this->challenge(
                'python',
                'mudah',
                'String Python Dibandingkan dengan is',
                'python-string-dibandingkan-dengan-is',
                'Kondisi memakai pemeriksaan identitas object untuk membandingkan isi string.',
                <<<'CODE'
role = "".join(["ad", "min"])

if role is "admin":
    print("Akses administrator diberikan")
CODE,
                3,
                'Operator is memeriksa apakah dua reference menunjuk object yang sama, bukan apakah nilainya sama. Untuk membandingkan isi string gunakan operator ==.',
                'is digunakan untuk identitas object.',
                'Gunakan operator kesamaan nilai untuk string.',
                <<<'CODE'
role = "".join(["ad", "min"])

if role == "admin":
    print("Akses administrator diberikan")
CODE,
                [
                    'string',
                    'identitas',
                    'is',
                    '==',
                ],
            ),
            $this->challenge(
                'python',
                'menengah',
                'Default Argument Mutable Python',
                'python-default-argument-mutable',
                'Pemanggilan function yang berbeda berbagi list default yang sama sehingga data lama ikut terbawa.',
                <<<'CODE'
def add_item(item, items=[]):
    items.append(item)
    return items

print(add_item("A"))
print(add_item("B"))
CODE,
                1,
                'Default argument dievaluasi sekali ketika function didefinisikan. List yang sama dipakai pada setiap pemanggilan sehingga state tersimpan. Gunakan None sebagai default lalu buat list baru di dalam function.',
                'Default argument tidak dibuat ulang setiap function dipanggil.',
                'Gunakan None lalu inisialisasi list di dalam function.',
                <<<'CODE'
def add_item(item, items=None):
    if items is None:
        items = []

    items.append(item)
    return items

print(add_item("A"))
print(add_item("B"))
CODE,
                [
                    'default argument',
                    'mutable',
                    'list',
                    'None',
                ],
            ),
            $this->challenge(
                'python',
                'menengah',
                'Late Binding pada Lambda Python',
                'python-late-binding-pada-lambda',
                'Semua lambda menggunakan nilai loop terakhir karena variabel dicari ketika lambda dipanggil.',
                <<<'CODE'
functions = []

for number in range(3):
    functions.append(lambda: number)

print([function() for function in functions])
CODE,
                4,
                'Closure Python menggunakan late binding sehingga number dibaca saat lambda dijalankan, setelah loop selesai. Tangkap nilai setiap iterasi menggunakan default argument lambda number=number.',
                'Lambda tidak langsung menyimpan nilai variabel loop.',
                'Tangkap nilai loop melalui default argument.',
                <<<'CODE'
functions = []

for number in range(3):
    functions.append(lambda number=number: number)

print([function() for function in functions])
CODE,
                [
                    'lambda',
                    'closure',
                    'late binding',
                    'default argument',
                ],
            ),
            $this->challenge(
                'python',
                'menengah',
                'Dictionary Diubah saat Iterasi',
                'python-dictionary-diubah-saat-iterasi',
                'Program menghapus key dari dictionary yang sedang diiterasi dan memicu RuntimeError.',
                <<<'CODE'
scores = {
    "Ana": 90,
    "Rifqi": 40,
    "Budi": 75,
}

for name, score in scores.items():
    if score < 60:
        del scores[name]

print(scores)
CODE,
                9,
                'Mengubah ukuran dictionary selama iterasi atas scores.items menyebabkan RuntimeError. Iterasikan salinan item, buat dictionary baru, atau kumpulkan key yang akan dihapus terlebih dahulu.',
                'Loop sedang memakai view langsung dari dictionary.',
                'Iterasikan salinan sebelum menghapus key.',
                <<<'CODE'
scores = {
    "Ana": 90,
    "Rifqi": 40,
    "Budi": 75,
}

for name, score in list(scores.items()):
    if score < 60:
        del scores[name]

print(scores)
CODE,
                [
                    'dictionary',
                    'iterasi',
                    'RuntimeError',
                    'salinan',
                ],
            ),
            $this->challenge(
                'python',
                'sulit',
                'Salinan Dangkal pada List Bersarang',
                'python-salinan-dangkal-list-bersarang',
                'Mengubah list hasil salinan juga mengubah list asli karena elemen bersarang masih menggunakan object yang sama.',
                <<<'CODE'
original = [[1, 2], [3, 4]]
copied = original.copy()

copied[0].append(99)

print(original)
CODE,
                2,
                'list.copy hanya membuat shallow copy. List luar berbeda, tetapi list di dalamnya masih direferensikan bersama. Gunakan copy.deepcopy ketika seluruh struktur bersarang harus disalin secara independen.',
                'Periksa object pada tingkat list bagian dalam.',
                'Gunakan deep copy untuk struktur bersarang.',
                <<<'CODE'
import copy

original = [[1, 2], [3, 4]]
copied = copy.deepcopy(original)

copied[0].append(99)

print(original)
CODE,
                [
                    'shallow copy',
                    'deepcopy',
                    'list bersarang',
                    'reference',
                ],
            ),
            $this->challenge(
                'python',
                'sulit',
                'Coroutine Python Tidak Ditunggu',
                'python-coroutine-tidak-ditunggu',
                'Program mencetak object coroutine dan function asynchronous tidak pernah benar-benar dijalankan.',
                <<<'CODE'
import asyncio

async def load_data():
    await asyncio.sleep(0.1)
    return {"status": "ok"}

async def main():
    result = load_data()
    print(result)

asyncio.run(main())
CODE,
                8,
                'Memanggil async function menghasilkan object coroutine. Coroutine harus ditunggu menggunakan await agar dijalankan dan menghasilkan nilai kembalian. Gunakan result = await load_data().',
                'async function tidak langsung mengembalikan data akhirnya.',
                'Gunakan await di dalam function asynchronous.',
                <<<'CODE'
import asyncio

async def load_data():
    await asyncio.sleep(0.1)
    return {"status": "ok"}

async def main():
    result = await load_data()
    print(result)

asyncio.run(main())
CODE,
                [
                    'coroutine',
                    'async',
                    'await',
                    'nilai kembalian',
                ],
            ),
        ];
    }
}
