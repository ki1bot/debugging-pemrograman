<?php

namespace Database\Seeders\Data;

class SqlChallenges
{
    public static function all(): array
    {
        return [
            0 => [
                'category' => 'sql',
                'difficulty' => 'mudah',
                'title' => 'WHERE Diletakkan Setelah ORDER BY',
                'slug' => 'sql-where-diletakkan-setelah-order-by',
                'description' => 'Query gagal karena urutan klausa SQL tidak valid.',
                'broken_code' => 'SELECT id, name
FROM users
ORDER BY name
WHERE active = TRUE;',
                'buggy_line' => 4,
                'explanation' => 'Klausa WHERE harus ditempatkan sebelum ORDER BY. WHERE menyaring baris terlebih dahulu, kemudian ORDER BY mengurutkan hasil yang tersisa.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa urutan logis klausa SELECT, FROM, WHERE, dan ORDER BY.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Pindahkan WHERE sebelum ORDER BY.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'SELECT id, name
FROM users
WHERE active = TRUE
ORDER BY name;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'WHERE',
                            1 => 'ORDER BY',
                            2 => 'urutan klausa',
                            3 => 'menyaring',
                        ],
                    ],
                ],
            ],
            1 => [
                'category' => 'sql',
                'difficulty' => 'mudah',
                'title' => 'Kolom Ambigu pada JOIN',
                'slug' => 'sql-kolom-ambigu-pada-join',
                'description' => 'Database tidak dapat menentukan tabel asal kolom id dan name.',
                'broken_code' => 'SELECT id, name
FROM users
JOIN orders ON users.id = orders.user_id;',
                'buggy_line' => 1,
                'explanation' => 'Saat beberapa tabel memiliki kolom dengan nama sama, kolom tanpa prefix menjadi ambigu. Gunakan nama tabel atau alias untuk menentukan sumber setiap kolom.',
                'hints' => [
                    0 => [
                        'content' => 'Tabel users dan orders dapat memiliki kolom id yang sama.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tambahkan prefix tabel atau alias pada kolom SELECT.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'SELECT users.id, users.name
FROM users
JOIN orders ON users.id = orders.user_id;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'ambigu',
                            1 => 'prefix',
                            2 => 'tabel',
                            3 => 'kolom',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'SELECT u.id, u.name
FROM users AS u
JOIN orders AS o ON u.id = o.user_id;',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            2 => [
                'category' => 'sql',
                'difficulty' => 'mudah',
                'title' => 'Kesalahan GROUP BY',
                'slug' => 'sql-kesalahan-group-by',
                'description' => 'Query agregasi gagal karena sintaks pengelompokan tidak lengkap.',
                'broken_code' => 'SELECT department, COUNT(*) AS total
FROM employees
GROUP department;',
                'buggy_line' => 3,
                'explanation' => 'Sintaks yang benar adalah GROUP BY diikuti kolom pengelompokan. Kata BY tidak boleh dihilangkan.',
                'hints' => [
                    0 => [
                        'content' => 'Klausa pengelompokan terdiri dari dua kata.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tambahkan BY setelah GROUP.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'SELECT department, COUNT(*) AS total
FROM employees
GROUP BY department;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'GROUP BY',
                            1 => 'agregasi',
                            2 => 'department',
                            3 => 'sintaks',
                        ],
                    ],
                ],
            ],
            3 => [
                'category' => 'sql',
                'difficulty' => 'menengah',
                'title' => 'Menggunakan WHERE untuk Fungsi Agregat',
                'slug' => 'sql-where-untuk-fungsi-agregat',
                'description' => 'Query mencoba menyaring hasil COUNT menggunakan klausa yang salah.',
                'broken_code' => 'SELECT department, COUNT(*) AS total
FROM employees
GROUP BY department
WHERE COUNT(*) > 5;',
                'buggy_line' => 4,
                'explanation' => 'WHERE menyaring baris sebelum agregasi sehingga tidak dapat menggunakan COUNT(*). Gunakan HAVING untuk menyaring kelompok setelah GROUP BY.',
                'hints' => [
                    0 => [
                        'content' => 'WHERE bekerja sebelum proses pengelompokan dan agregasi.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan klausa yang menyaring hasil GROUP BY.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'SELECT department, COUNT(*) AS total
FROM employees
GROUP BY department
HAVING COUNT(*) > 5;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'HAVING',
                            1 => 'WHERE',
                            2 => 'COUNT',
                            3 => 'agregasi',
                        ],
                    ],
                ],
            ],
            4 => [
                'category' => 'sql',
                'difficulty' => 'menengah',
                'title' => 'JOIN Menghasilkan Data Duplikat',
                'slug' => 'sql-join-menghasilkan-data-duplikat',
                'description' => 'Kondisi JOIN selalu benar dan menghasilkan kombinasi baris yang tidak semestinya.',
                'broken_code' => 'SELECT users.name, orders.total
FROM users
JOIN orders ON users.id = users.id;',
                'buggy_line' => 3,
                'explanation' => 'Kondisi users.id = users.id selalu benar untuk setiap baris users. JOIN harus menghubungkan primary key users.id dengan foreign key orders.user_id.',
                'hints' => [
                    0 => [
                        'content' => 'Kedua sisi kondisi ON saat ini berasal dari tabel yang sama.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Hubungkan id pengguna dengan user_id pada orders.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'SELECT users.name, orders.total
FROM users
JOIN orders ON users.id = orders.user_id;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'JOIN',
                            1 => 'users.id',
                            2 => 'orders.user_id',
                            3 => 'selalu benar',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'SELECT u.name, o.total
FROM users AS u
JOIN orders AS o ON u.id = o.user_id;',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            5 => [
                'category' => 'sql',
                'difficulty' => 'menengah',
                'title' => 'Subquery Mengembalikan Banyak Baris',
                'slug' => 'sql-subquery-mengembalikan-banyak-baris',
                'description' => 'Operator perbandingan tunggal digunakan untuk subquery yang dapat menghasilkan lebih dari satu id.',
                'broken_code' => 'SELECT name
FROM products
WHERE category_id = (
    SELECT id FROM categories WHERE active = TRUE
);',
                'buggy_line' => 3,
                'explanation' => 'Operator = mengharapkan satu nilai, sedangkan subquery dapat mengembalikan banyak category id. Gunakan IN untuk membandingkan category_id dengan seluruh hasil subquery.',
                'hints' => [
                    0 => [
                        'content' => 'Perkirakan jumlah baris yang dapat dikembalikan subquery categories.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan operator untuk mencocokkan satu nilai terhadap sekumpulan nilai.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'SELECT name
FROM products
WHERE category_id IN (
    SELECT id FROM categories WHERE active = TRUE
);',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'subquery',
                            1 => 'banyak baris',
                            2 => 'IN',
                            3 => 'operator =',
                        ],
                    ],
                ],
            ],
            6 => [
                'category' => 'sql',
                'difficulty' => 'sulit',
                'title' => 'Alias Window Function Dipakai di WHERE',
                'slug' => 'sql-alias-window-function-di-where',
                'description' => 'Query mencoba menggunakan alias hasil window function pada WHERE dalam level SELECT yang sama.',
                'broken_code' => 'SELECT name, salary,
       RANK() OVER (ORDER BY salary DESC) AS salary_rank
FROM employees
WHERE salary_rank <= 3;',
                'buggy_line' => 4,
                'explanation' => 'WHERE dievaluasi sebelum SELECT dan sebelum alias window function tersedia. Hitung ranking di CTE atau subquery, lalu saring salary_rank pada query luar.',
                'hints' => [
                    0 => [
                        'content' => 'Perhatikan urutan evaluasi WHERE, SELECT, dan window function.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Bungkus query ranking di CTE atau subquery.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'WITH ranked_employees AS (
    SELECT name, salary,
           RANK() OVER (ORDER BY salary DESC) AS salary_rank
    FROM employees
)
SELECT name, salary, salary_rank
FROM ranked_employees
WHERE salary_rank <= 3;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'window function',
                            1 => 'WHERE',
                            2 => 'CTE',
                            3 => 'urutan evaluasi',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'SELECT name, salary, salary_rank
FROM (
    SELECT name, salary,
           RANK() OVER (ORDER BY salary DESC) AS salary_rank
    FROM employees
) AS ranked_employees
WHERE salary_rank <= 3;',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            7 => [
                'category' => 'sql',
                'difficulty' => 'sulit',
                'title' => 'Cartesian Product karena Kondisi JOIN Hilang',
                'slug' => 'sql-cartesian-product-kondisi-join-hilang',
                'description' => 'Setiap pengguna dipasangkan dengan setiap role karena relasi antartabel tidak ditentukan.',
                'broken_code' => 'SELECT u.name, r.name AS role_name
FROM users AS u, roles AS r
WHERE u.active = TRUE;',
                'buggy_line' => 2,
                'explanation' => 'Daftar tabel yang dipisahkan koma tanpa kondisi relasi menghasilkan Cartesian product. Gunakan JOIN eksplisit dan hubungkan u.role_id dengan r.id.',
                'hints' => [
                    0 => [
                        'content' => 'Tidak ada kondisi yang menghubungkan users dengan roles.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan JOIN ... ON berdasarkan role_id.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'SELECT u.name, r.name AS role_name
FROM users AS u
JOIN roles AS r ON u.role_id = r.id
WHERE u.active = TRUE;',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'Cartesian product',
                            1 => 'JOIN',
                            2 => 'ON',
                            3 => 'role_id',
                        ],
                    ],
                ],
            ],
        ];
    }
}
