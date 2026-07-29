<?php

namespace Database\Seeders\Data;

class GoChallenges
{
    public static function all(): array
    {
        return [
            [
                'category' => 'go',
                'difficulty' => 'mudah',
                'title' => 'Perulangan Go Melewati Batas Slice',
                'slug' => 'go-perulangan-melewati-batas-slice',
                'description' => 'Perulangan mencoba membaca indeks yang sama dengan panjang slice dan menyebabkan panic.',
                'broken_code' => <<<'GO'
package main

import "fmt"

func main() {
    numbers := []int{10, 20, 30}
    for i := 0; i <= len(numbers); i++ {
        fmt.Println(numbers[i])
    }
}
GO,
                'buggy_line' => 7,
                'explanation' => 'Indeks slice Go yang valid dimulai dari 0 sampai len(numbers) - 1. Kondisi i <= len(numbers) membuat i mencapai nilai yang sama dengan panjang slice sehingga akses numbers[i] memicu panic index out of range. Gunakan i < len(numbers).',
                'hints' => [
                    [
                        'content' => 'Indeks terakhir slice selalu satu lebih kecil dari panjangnya.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Periksa operator perbandingan pada kondisi perulangan.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    numbers := []int{10, 20, 30}
    for i := 0; i < len(numbers); i++ {
        fmt.Println(numbers[i])
    }
}
GO,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'slice',
                            'indeks',
                            'len',
                            'index out of range',
                        ],
                    ],
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    numbers := []int{10, 20, 30}
    for _, number := range numbers {
        fmt.Println(number)
    }
}
GO,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'go',
                'difficulty' => 'mudah',
                'title' => 'Pembagian Integer Sebelum Konversi float64',
                'slug' => 'go-pembagian-integer-sebelum-konversi-float64',
                'description' => 'Hasil rata-rata kehilangan bagian desimal karena pembagian integer dilakukan sebelum konversi.',
                'broken_code' => <<<'GO'
package main

import "fmt"

func main() {
    total := 7
    count := 2
    average := float64(total / count)

    fmt.Println(average)
}
GO,
                'buggy_line' => 8,
                'explanation' => 'total dan count bertipe int sehingga operasi total / count diselesaikan sebagai pembagian integer dan menghasilkan 3. Konversi ke float64 baru dilakukan setelah bagian desimal hilang. Konversikan salah satu operand sebelum pembagian.',
                'hints' => [
                    [
                        'content' => 'Tipe variabel hasil tidak mengubah tipe operasi yang sudah dilakukan.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Lakukan konversi sebelum operator pembagian dijalankan.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    total := 7
    count := 2
    average := float64(total) / float64(count)

    fmt.Println(average)
}
GO,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'integer division',
                            'float64',
                            'operand',
                            'desimal',
                        ],
                    ],
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    total := 7.0
    count := 2.0
    average := total / count

    fmt.Println(average)
}
GO,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'go',
                'difficulty' => 'mudah',
                'title' => 'Menulis Data ke nil Map',
                'slug' => 'go-menulis-data-ke-nil-map',
                'description' => 'Program mencoba menambahkan pasangan key-value ke map yang belum diinisialisasi.',
                'broken_code' => <<<'GO'
package main

import "fmt"

func main() {
    scores := map[string]int(nil)
    scores["Rifqi"] = 100

    fmt.Println(scores)
}
GO,
                'buggy_line' => 6,
                'explanation' => 'Nil map dapat dibaca, tetapi tidak dapat menerima penulisan. Penugasan scores["Rifqi"] = 100 pada nil map memicu panic assignment to entry in nil map. Inisialisasikan map menggunakan make atau literal map kosong.',
                'hints' => [
                    [
                        'content' => 'Nil map berbeda dengan map kosong yang sudah dialokasikan.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan make atau literal map kosong sebelum menambahkan data.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    scores := make(map[string]int)
    scores["Rifqi"] = 100

    fmt.Println(scores)
}
GO,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'nil map',
                            'panic',
                            'make',
                            'inisialisasi',
                        ],
                    ],
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    scores := map[string]int{}
    scores["Rifqi"] = 100

    fmt.Println(scores)
}
GO,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'go',
                'difficulty' => 'menengah',
                'title' => 'Hasil append Tidak Disimpan',
                'slug' => 'go-hasil-append-tidak-disimpan',
                'description' => 'Program mengabaikan slice baru yang dikembalikan oleh append.',
                'broken_code' => <<<'GO'
package main

import "fmt"

func main() {
    numbers := []int{1, 2, 3}
    append(numbers, 4)

    fmt.Println(numbers)
}
GO,
                'buggy_line' => 7,
                'explanation' => 'Fungsi bawaan append mengembalikan slice hasil penambahan dan dapat menggunakan backing array baru. Nilai hasilnya wajib disimpan. Mengabaikan nilai tersebut menyebabkan compile error karena hasil append tidak digunakan dan numbers tetap tidak diperbarui.',
                'hints' => [
                    [
                        'content' => 'append tidak mengubah panjang variabel slice secara otomatis tanpa penugasan.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Simpan nilai yang dikembalikan append ke variabel slice.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    numbers := []int{1, 2, 3}
    numbers = append(numbers, 4)

    fmt.Println(numbers)
}
GO,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'append',
                            'return value',
                            'slice',
                            'penugasan',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'go',
                'difficulty' => 'menengah',
                'title' => 'Field Struct Tidak Terekspor ke JSON',
                'slug' => 'go-field-struct-tidak-terekspor-ke-json',
                'description' => 'json.Marshal menghasilkan object kosong karena field struct tidak diekspor.',
                'broken_code' => <<<'GO'
package main

import (
    "encoding/json"
    "fmt"
)

type User struct {
    name string `json:"name"`
}

func main() {
    user := User{name: "Rifqi"}
    data, err := json.Marshal(user)
    if err != nil {
        panic(err)
    }

    fmt.Println(string(data))
}
GO,
                'buggy_line' => 9,
                'explanation' => 'Paket encoding/json hanya memproses field struct yang diekspor. Di Go, nama field harus diawali huruf kapital agar diekspor. Field name tidak terlihat oleh json.Marshal sehingga hasilnya menjadi object kosong.',
                'hints' => [
                    [
                        'content' => 'Visibilitas identifier Go ditentukan oleh huruf pertama namanya.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Field yang dibaca encoding/json harus diekspor.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'GO'
package main

import (
    "encoding/json"
    "fmt"
)

type User struct {
    Name string `json:"name"`
}

func main() {
    user := User{Name: "Rifqi"}
    data, err := json.Marshal(user)
    if err != nil {
        panic(err)
    }

    fmt.Println(string(data))
}
GO,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'encoding/json',
                            'field',
                            'exported',
                            'huruf kapital',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'go',
                'difficulty' => 'menengah',
                'title' => 'Error Tertutupi oleh Short Variable Declaration',
                'slug' => 'go-error-tertutupi-short-variable-declaration',
                'description' => 'Variabel err di dalam blok menutupi variabel err di scope luar sehingga status error hilang setelah blok selesai.',
                'broken_code' => <<<'GO'
package main

import (
    "fmt"
    "os"
)

func main() {
    var err error

    if _, err := os.Open("config.json"); err != nil {
        fmt.Println("Gagal membuka file:", err)
    }

    if err == nil {
        fmt.Println("Program menganggap tidak ada error")
    }
}
GO,
                'buggy_line' => 11,
                'explanation' => 'Operator := pada initializer if membuat variabel err baru di scope blok. Variabel tersebut menutupi err di scope fungsi. Setelah blok selesai, err luar tetap nil. Gunakan assignment = agar hasil error disimpan pada variabel yang sudah dideklarasikan.',
                'hints' => [
                    [
                        'content' => 'Perhatikan perbedaan scope antara err di luar dan di dalam blok if.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Gunakan assignment biasa untuk memperbarui variabel yang sudah ada.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'GO'
package main

import (
    "fmt"
    "os"
)

func main() {
    var err error

    if _, err = os.Open("config.json"); err != nil {
        fmt.Println("Gagal membuka file:", err)
    }

    if err == nil {
        fmt.Println("Program menganggap tidak ada error")
    }
}
GO,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'shadowing',
                            'scope',
                            'short variable declaration',
                            'assignment',
                        ],
                    ],
                ],
            ],
            [
                'category' => 'go',
                'difficulty' => 'sulit',
                'title' => 'Slice Turunan Mengubah Backing Array Asli',
                'slug' => 'go-slice-turunan-mengubah-backing-array-asli',
                'description' => 'append pada subslice menimpa elemen slice asli karena keduanya masih berbagi backing array.',
                'broken_code' => <<<'GO'
package main

import "fmt"

func main() {
    original := make([]string, 3, 4)
    copy(original, []string{"Ana", "Budi", "Citra"})

    selected := original[:2]
    selected = append(selected, "Dina")

    fmt.Println("original:", original)
    fmt.Println("selected:", selected)
}
GO,
                'buggy_line' => 9,
                'explanation' => 'Subslice selected masih menggunakan backing array yang sama dengan original dan memiliki capacity yang cukup untuk append. Penambahan elemen pada selected menulis ke indeks berikutnya pada backing array sehingga original[2] berubah dari Citra menjadi Dina. Buat salinan terpisah sebelum append.',
                'hints' => [
                    [
                        'content' => 'Slice menyimpan referensi ke backing array, panjang, dan kapasitas.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Salin elemen subslice ke slice baru sebelum menambahkan data.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    original := make([]string, 3, 4)
    copy(original, []string{"Ana", "Budi", "Citra"})

    selected := append([]string(nil), original[:2]...)
    selected = append(selected, "Dina")

    fmt.Println("original:", original)
    fmt.Println("selected:", selected)
}
GO,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'slice',
                            'backing array',
                            'capacity',
                            'copy',
                        ],
                    ],
                    [
                        'solution_code' => <<<'GO'
package main

import "fmt"

func main() {
    original := make([]string, 3, 4)
    copy(original, []string{"Ana", "Budi", "Citra"})

    selected := make([]string, 2)
    copy(selected, original[:2])
    selected = append(selected, "Dina")

    fmt.Println("original:", original)
    fmt.Println("selected:", selected)
}
GO,
                        'solution_type' => 'alternative',
                        'required_keywords' => [],
                    ],
                ],
            ],
            [
                'category' => 'go',
                'difficulty' => 'sulit',
                'title' => 'WaitGroup Add Dipanggil di Dalam Goroutine',
                'slug' => 'go-waitgroup-add-di-dalam-goroutine',
                'description' => 'Wait dapat berjalan ketika counter masih nol karena Add baru dipanggil setelah goroutine dijadwalkan.',
                'broken_code' => <<<'GO'
package main

import (
    "fmt"
    "sync"
)

func main() {
    var wg sync.WaitGroup

    for i := 1; i <= 3; i++ {
        go func(value int) {
            wg.Add(1)
            defer wg.Done()
            fmt.Println(value)
        }(i)
    }

    wg.Wait()
}
GO,
                'buggy_line' => 13,
                'explanation' => 'Pemanggilan Add dengan nilai positif harus dilakukan sebelum goroutine dimulai ketika counter masih nol. Jika Add berada di dalam goroutine, fungsi utama dapat mencapai Wait saat counter masih nol lalu selesai terlalu cepat. Pindahkan wg.Add(1) sebelum perintah go.',
                'hints' => [
                    [
                        'content' => 'Wait hanya menunggu ketika counter sudah lebih besar dari nol.',
                        'point_penalty' => 10,
                    ],
                    [
                        'content' => 'Tambahkan counter sebelum goroutine dijadwalkan.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    [
                        'solution_code' => <<<'GO'
package main

import (
    "fmt"
    "sync"
)

func main() {
    var wg sync.WaitGroup

    for i := 1; i <= 3; i++ {
        wg.Add(1)
        go func(value int) {
            defer wg.Done()
            fmt.Println(value)
        }(i)
    }

    wg.Wait()
}
GO,
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            'WaitGroup',
                            'Add',
                            'goroutine',
                            'Wait',
                        ],
                    ],
                ],
            ],
        ];
    }
}
