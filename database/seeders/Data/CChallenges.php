<?php

namespace Database\Seeders\Data;

class CChallenges
{
    public static function all(): array
    {
        return [
            0 => [
                'category' => 'c',
                'difficulty' => 'mudah',
                'title' => 'Perulangan C Melewati Batas Array',
                'slug' => 'c-perulangan-melewati-batas-array',
                'description' => 'Program mengakses satu indeks setelah elemen terakhir array sehingga menghasilkan perilaku yang tidak terdefinisi.',
                'broken_code' => '#include <stdio.h>

int main(void) {
    int numbers[] = {10, 20, 30};
    int length = sizeof(numbers) / sizeof(numbers[0]);

    for (int i = 0; i <= length; i++) {
        printf("%d\\n", numbers[i]);
    }

    return 0;
}',
                'buggy_line' => 7,
                'explanation' => 'Indeks array C dimulai dari nol dan indeks terakhir adalah length - 1. Kondisi i <= length membuat program mengakses numbers[length] yang berada di luar batas array. Kondisi perulangan harus menggunakan i < length.',
                'hints' => [
                    0 => [
                        'content' => 'Bandingkan nilai length dengan indeks terakhir yang valid.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Perulangan berjalan satu kali terlalu banyak karena operator <=.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <stdio.h>

int main(void) {
    int numbers[] = {10, 20, 30};
    int length = sizeof(numbers) / sizeof(numbers[0]);

    for (int i = 0; i < length; i++) {
        printf("%d\\n", numbers[i]);
    }

    return 0;
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
                        'solution_code' => '#include <stdio.h>

int main(void) {
    int numbers[] = {10, 20, 30};
    int length = sizeof(numbers) / sizeof(numbers[0]);

    for (int i = 0; i <= length - 1; i++) {
        printf("%d\\n", numbers[i]);
    }

    return 0;
}',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            1 => [
                'category' => 'c',
                'difficulty' => 'mudah',
                'title' => 'Assignment Digunakan pada Kondisi C',
                'slug' => 'c-assignment-digunakan-pada-kondisi',
                'description' => 'Kondisi selalu dianggap benar karena program mengubah nilai variabel ketika seharusnya membandingkannya.',
                'broken_code' => '#include <stdio.h>

int main(void) {
    int age = 18;

    if (age = 20) {
        printf("Usia adalah 20 tahun\\n");
    }

    return 0;
}',
                'buggy_line' => 6,
                'explanation' => 'Operator = melakukan assignment dan menghasilkan nilai 20 yang dianggap benar. Kondisi harus menggunakan operator perbandingan == agar nilai age dibandingkan dengan 20 tanpa diubah.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa apakah operator pada kondisi membandingkan atau mengubah nilai.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan operator perbandingan, bukan operator assignment.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <stdio.h>

int main(void) {
    int age = 18;

    if (age == 20) {
        printf("Usia adalah 20 tahun\\n");
    }

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'assignment',
                            1 => 'perbandingan',
                            2 => 'operator',
                            3 => 'nilai',
                        ],
                    ],
                ],
            ],
            2 => [
                'category' => 'c',
                'difficulty' => 'mudah',
                'title' => 'Pembagian Bilangan Bulat C',
                'slug' => 'c-pembagian-bilangan-bulat',
                'description' => 'Hasil rata-rata kehilangan bagian desimal meskipun disimpan di dalam variabel bertipe double.',
                'broken_code' => '#include <stdio.h>

int main(void) {
    int total = 7;
    int count = 2;
    double average = total / count;

    printf("%.1f\\n", average);

    return 0;
}',
                'buggy_line' => 6,
                'explanation' => 'total dan count bertipe int sehingga pembagian dilakukan sebagai integer division sebelum hasilnya disimpan ke double. Salah satu operand harus dikonversi menjadi double agar hasil pembagian mempertahankan bagian desimal.',
                'hints' => [
                    0 => [
                        'content' => 'Tipe variabel tujuan tidak mengubah cara kedua operand dibagi.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Ubah salah satu operand menjadi double sebelum pembagian.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <stdio.h>

int main(void) {
    int total = 7;
    int count = 2;
    double average = (double) total / count;

    printf("%.1f\\n", average);

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'integer division',
                            1 => 'double',
                            2 => 'operand',
                            3 => 'desimal',
                        ],
                    ],
                ],
            ],
            3 => [
                'category' => 'c',
                'difficulty' => 'menengah',
                'title' => 'Alamat Variabel Tidak Dikirim ke scanf',
                'slug' => 'c-alamat-variabel-tidak-dikirim-ke-scanf',
                'description' => 'Program dapat berhenti atau menulis ke alamat memory yang salah ketika membaca input angka.',
                'broken_code' => '#include <stdio.h>

int main(void) {
    int age;

    scanf("%d", age);
    printf("%d\\n", age);

    return 0;
}',
                'buggy_line' => 6,
                'explanation' => 'scanf memerlukan alamat memory tempat hasil input akan disimpan. Variabel age harus dikirim menggunakan operator alamat &. Tanpa &, nilai age yang belum terinisialisasi dianggap sebagai alamat memory.',
                'hints' => [
                    0 => [
                        'content' => 'scanf perlu mengetahui lokasi memory tujuan.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan operator alamat pada variabel age.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <stdio.h>

int main(void) {
    int age;

    scanf("%d", &age);
    printf("%d\\n", age);

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'scanf',
                            1 => 'alamat',
                            2 => 'memory',
                            3 => '&age',
                        ],
                    ],
                ],
            ],
            4 => [
                'category' => 'c',
                'difficulty' => 'menengah',
                'title' => 'sizeof Digunakan pada Pointer',
                'slug' => 'c-sizeof-digunakan-pada-pointer',
                'description' => 'Function menghitung panjang array secara salah karena parameter array telah berubah menjadi pointer.',
                'broken_code' => '#include <stdio.h>

void printNumbers(int numbers[]) {
    int length = sizeof(numbers) / sizeof(numbers[0]);

    for (int i = 0; i < length; i++) {
        printf("%d\\n", numbers[i]);
    }
}

int main(void) {
    int numbers[] = {10, 20, 30, 40};
    printNumbers(numbers);

    return 0;
}',
                'buggy_line' => 4,
                'explanation' => 'Parameter array pada function C mengalami array-to-pointer decay sehingga sizeof(numbers) menghasilkan ukuran pointer, bukan ukuran seluruh array. Panjang array harus dihitung di pemanggil lalu dikirim sebagai parameter terpisah.',
                'hints' => [
                    0 => [
                        'content' => 'Parameter array pada function tidak lagi membawa informasi ukurannya.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Kirim panjang array sebagai parameter function.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <stdio.h>

void printNumbers(int numbers[], int length) {
    for (int i = 0; i < length; i++) {
        printf("%d\\n", numbers[i]);
    }
}

int main(void) {
    int numbers[] = {10, 20, 30, 40};
    int length = sizeof(numbers) / sizeof(numbers[0]);
    printNumbers(numbers, length);

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'sizeof',
                            1 => 'pointer',
                            2 => 'array',
                            3 => 'panjang',
                        ],
                    ],
                ],
            ],
            5 => [
                'category' => 'c',
                'difficulty' => 'menengah',
                'title' => 'Alokasi Memory C Terlalu Kecil',
                'slug' => 'c-alokasi-memory-terlalu-kecil',
                'description' => 'Program hanya mengalokasikan beberapa byte meskipun membutuhkan ruang untuk beberapa nilai int.',
                'broken_code' => '#include <stdio.h>
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
}',
                'buggy_line' => 6,
                'explanation' => 'malloc menerima jumlah byte, sedangkan length hanya menyatakan jumlah elemen. Alokasi harus mengalikan jumlah elemen dengan sizeof(int) atau sizeof(*numbers) agar tersedia ruang yang cukup.',
                'hints' => [
                    0 => [
                        'content' => 'malloc bekerja menggunakan satuan byte.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Kalikan jumlah elemen dengan ukuran setiap elemen.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <stdio.h>
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
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'malloc',
                            1 => 'byte',
                            2 => 'sizeof',
                            3 => 'elemen',
                        ],
                    ],
                ],
            ],
            6 => [
                'category' => 'c',
                'difficulty' => 'sulit',
                'title' => 'Pointer Digunakan Setelah free',
                'slug' => 'c-pointer-digunakan-setelah-free',
                'description' => 'Program membaca memory yang sudah dibebaskan sehingga menghasilkan undefined behavior.',
                'broken_code' => '#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int *value = malloc(sizeof(*value));
    *value = 42;
    free(value);

    printf("%d\\n", *value);

    return 0;
}',
                'buggy_line' => 9,
                'explanation' => 'Setelah free dipanggil, memory yang ditunjuk value tidak lagi boleh diakses. Membaca *value setelah free adalah use-after-free. Nilai harus digunakan sebelum memory dibebaskan dan pointer sebaiknya diatur menjadi NULL.',
                'hints' => [
                    0 => [
                        'content' => 'Perhatikan urutan penggunaan pointer dan pelepasan memory.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Memory tidak boleh dibaca setelah diberikan kepada free.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int *value = malloc(sizeof(*value));

    if (value == NULL) {
        return 1;
    }

    *value = 42;
    printf("%d\\n", *value);

    free(value);
    value = NULL;

    return 0;
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'use-after-free',
                            1 => 'free',
                            2 => 'pointer',
                            3 => 'undefined behavior',
                        ],
                    ],
                ],
            ],
            7 => [
                'category' => 'c',
                'difficulty' => 'sulit',
                'title' => 'Hasil realloc Menimpa Pointer Asli',
                'slug' => 'c-hasil-realloc-menimpa-pointer-asli',
                'description' => 'Kegagalan realloc dapat membuat alamat alokasi lama hilang dan menyebabkan memory leak.',
                'broken_code' => '#include <stdio.h>
#include <stdlib.h>

int main(void) {
    int *numbers = malloc(2 * sizeof(*numbers));

    numbers = realloc(numbers, 4 * sizeof(*numbers));

    if (numbers == NULL) {
        return 1;
    }

    free(numbers);
    return 0;
}',
                'buggy_line' => 7,
                'explanation' => 'Jika realloc gagal, function mengembalikan NULL sementara alokasi lama tetap aktif. Menyimpan hasil langsung ke numbers menghilangkan alamat alokasi lama. Hasil realloc harus disimpan pada pointer sementara lalu diperiksa sebelum mengganti pointer asli.',
                'hints' => [
                    0 => [
                        'content' => 'realloc dapat mengembalikan NULL tanpa membebaskan blok lama.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Simpan hasil realloc pada pointer sementara.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => '#include <stdio.h>
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
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'realloc',
                            1 => 'pointer sementara',
                            2 => 'NULL',
                            3 => 'memory leak',
                        ],
                    ],
                ],
            ],
        ];
    }
}
