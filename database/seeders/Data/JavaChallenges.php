<?php

namespace Database\Seeders\Data;

class JavaChallenges
{
    public static function all(): array
    {
        return [
            0 => [
                'category' => 'java',
                'difficulty' => 'mudah',
                'title' => 'Perbandingan String Java Menggunakan Operator Identitas',
                'slug' => 'java-perbandingan-string-operator-identitas',
                'description' => 'Kondisi gagal mengenali teks dengan isi sama karena membandingkan referensi object.',
                'broken_code' => 'public class Main {
    public static void main(String[] args) {
        String role = new String("admin");

        if (role == "admin") {
            System.out.println("Akses administrator diberikan");
        }
    }
}',
                'buggy_line' => 5,
                'explanation' => 'Operator == pada String membandingkan identitas atau referensi object, bukan isi teks. Gunakan equals untuk membandingkan karakter di dalam String. Bentuk "admin".equals(role) aman ketika role mungkin null.',
                'hints' => [
                    0 => [
                        'content' => 'Operator == pada object tidak membandingkan isi.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan method String untuk membandingkan nilai teks.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'public class Main {
    public static void main(String[] args) {
        String role = new String("admin");

        if ("admin".equals(role)) {
            System.out.println("Akses administrator diberikan");
        }
    }
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'string',
                            1 => 'referensi',
                            2 => 'isi',
                            3 => 'equals',
                        ],
                    ],
                    1 => [
                        'solution_code' => 'public class Main {
    public static void main(String[] args) {
        String role = new String("admin");

        if (role.equals("admin")) {
            System.out.println("Akses administrator diberikan");
        }
    }
}',
                        'solution_type' => 'alternative',
                        'required_keywords' => [
                        ],
                    ],
                ],
            ],
            1 => [
                'category' => 'java',
                'difficulty' => 'mudah',
                'title' => 'Perulangan Java Melewati Batas Array',
                'slug' => 'java-perulangan-melewati-batas-array',
                'description' => 'Program mencoba membaca elemen pada indeks yang sama dengan panjang array.',
                'broken_code' => 'public class Main {
    public static void main(String[] args) {
        int[] numbers = {10, 20, 30};

        for (int i = 0; i <= numbers.length; i++) {
            System.out.println(numbers[i]);
        }
    }
}',
                'buggy_line' => 5,
                'explanation' => 'Indeks array Java yang valid berakhir pada length - 1. Kondisi <= membuat i mencapai numbers.length dan menyebabkan ArrayIndexOutOfBoundsException. Gunakan i < numbers.length.',
                'hints' => [
                    0 => [
                        'content' => 'Bandingkan panjang array dengan indeks terakhir.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Exception muncul ketika indeks sama dengan length.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'public class Main {
    public static void main(String[] args) {
        int[] numbers = {10, 20, 30};

        for (int i = 0; i < numbers.length; i++) {
            System.out.println(numbers[i]);
        }
    }
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'array',
                            1 => 'indeks',
                            2 => 'length',
                            3 => 'ArrayIndexOutOfBoundsException',
                        ],
                    ],
                ],
            ],
            2 => [
                'category' => 'java',
                'difficulty' => 'mudah',
                'title' => 'Pembagian Integer pada Java',
                'slug' => 'java-pembagian-integer',
                'description' => 'Nilai rata-rata kehilangan bagian desimal sebelum disimpan pada variabel double.',
                'broken_code' => 'public class Main {
    public static void main(String[] args) {
        int total = 7;
        int count = 2;
        double average = total / count;

        System.out.println(average);
    }
}',
                'buggy_line' => 5,
                'explanation' => 'Karena total dan count bertipe int, Java melakukan integer division terlebih dahulu. Salah satu operand harus dikonversi menjadi double agar hasil pembagian mempertahankan bagian desimal.',
                'hints' => [
                    0 => [
                        'content' => 'Tipe variabel average tidak mengubah tipe operasi sebelumnya.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Konversikan salah satu operand sebelum pembagian.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'public class Main {
    public static void main(String[] args) {
        int total = 7;
        int count = 2;
        double average = (double) total / count;

        System.out.println(average);
    }
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
                'category' => 'java',
                'difficulty' => 'menengah',
                'title' => 'Menghapus Elemen saat Enhanced for',
                'slug' => 'java-menghapus-elemen-saat-enhanced-for',
                'description' => 'Program mengubah ArrayList langsung ketika sedang diiterasi dan memicu ConcurrentModificationException.',
                'broken_code' => 'import java.util.ArrayList;
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
}',
                'buggy_line' => 10,
                'explanation' => 'Enhanced for menggunakan iterator internal. Mengubah struktur ArrayList secara langsung selama iterasi membuat iterator mendeteksi modifikasi dan melempar ConcurrentModificationException. Gunakan Iterator.remove atau removeIf.',
                'hints' => [
                    0 => [
                        'content' => 'Enhanced for memakai iterator di balik layar.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan operasi penghapusan yang aman selama iterasi.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'import java.util.ArrayList;
import java.util.List;

public class Main {
    public static void main(String[] args) {
        List<String> names = new ArrayList<>(List.of("Ana", "Rifqi", "Budi"));

        names.removeIf(name -> name.startsWith("R"));

        System.out.println(names);
    }
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'ArrayList',
                            1 => 'iterator',
                            2 => 'ConcurrentModificationException',
                            3 => 'removeIf',
                        ],
                    ],
                ],
            ],
            4 => [
                'category' => 'java',
                'difficulty' => 'menengah',
                'title' => 'Auto-unboxing Nilai null',
                'slug' => 'java-auto-unboxing-nilai-null',
                'description' => 'Program melempar NullPointerException ketika nilai null dari Map dikonversi otomatis menjadi int.',
                'broken_code' => 'import java.util.HashMap;
import java.util.Map;

public class Main {
    public static void main(String[] args) {
        Map<String, Integer> scores = new HashMap<>();
        scores.put("Ana", 90);

        int score = scores.get("Rifqi");

        System.out.println(score);
    }
}',
                'buggy_line' => 9,
                'explanation' => 'Map.get mengembalikan null ketika key tidak ditemukan. Penugasan ke int memicu auto-unboxing dari Integer null dan menyebabkan NullPointerException. Gunakan getOrDefault atau periksa null sebelum unboxing.',
                'hints' => [
                    0 => [
                        'content' => 'Periksa hasil Map.get untuk key yang tidak tersedia.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan nilai default sebelum dikonversi menjadi int.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'import java.util.HashMap;
import java.util.Map;

public class Main {
    public static void main(String[] args) {
        Map<String, Integer> scores = new HashMap<>();
        scores.put("Ana", 90);

        int score = scores.getOrDefault("Rifqi", 0);

        System.out.println(score);
    }
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'Map.get',
                            1 => 'null',
                            2 => 'auto-unboxing',
                            3 => 'getOrDefault',
                        ],
                    ],
                ],
            ],
            5 => [
                'category' => 'java',
                'difficulty' => 'menengah',
                'title' => 'BigDecimal Dibandingkan dengan equals',
                'slug' => 'java-bigdecimal-dibandingkan-dengan-equals',
                'description' => 'Dua nilai numerik yang sama dianggap berbeda karena memiliki skala desimal berbeda.',
                'broken_code' => 'import java.math.BigDecimal;

public class Main {
    public static void main(String[] args) {
        BigDecimal first = new BigDecimal("10.0");
        BigDecimal second = new BigDecimal("10.00");

        System.out.println(first.equals(second));
    }
}',
                'buggy_line' => 8,
                'explanation' => 'BigDecimal.equals membandingkan nilai sekaligus scale sehingga 10.0 dan 10.00 dianggap berbeda. Untuk membandingkan nilai numerik tanpa memperhatikan scale, gunakan compareTo dan periksa apakah hasilnya nol.',
                'hints' => [
                    0 => [
                        'content' => 'equals pada BigDecimal juga memperhatikan scale.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Gunakan compareTo untuk perbandingan numerik.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'import java.math.BigDecimal;

public class Main {
    public static void main(String[] args) {
        BigDecimal first = new BigDecimal("10.0");
        BigDecimal second = new BigDecimal("10.00");

        System.out.println(first.compareTo(second) == 0);
    }
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'BigDecimal',
                            1 => 'equals',
                            2 => 'scale',
                            3 => 'compareTo',
                        ],
                    ],
                ],
            ],
            6 => [
                'category' => 'java',
                'difficulty' => 'sulit',
                'title' => 'wait Dipanggil di Luar synchronized',
                'slug' => 'java-wait-di-luar-synchronized',
                'description' => 'Thread memanggil wait tanpa memiliki monitor object dan melempar IllegalMonitorStateException.',
                'broken_code' => 'public class Main {
    public static void main(String[] args) throws InterruptedException {
        Object lock = new Object();

        lock.wait();

        System.out.println("Selesai");
    }
}',
                'buggy_line' => 5,
                'explanation' => 'Object.wait hanya boleh dipanggil oleh thread yang sedang memiliki monitor object tersebut. Pemanggilan harus berada di dalam blok synchronized(lock). Jika tidak, Java melempar IllegalMonitorStateException.',
                'hints' => [
                    0 => [
                        'content' => 'wait berhubungan dengan monitor sebuah object.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Thread harus memiliki monitor sebelum memanggil wait.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'public class Main {
    public static void main(String[] args) throws InterruptedException {
        Object lock = new Object();

        synchronized (lock) {
            lock.wait(100);
        }

        System.out.println("Selesai");
    }
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'wait',
                            1 => 'synchronized',
                            2 => 'monitor',
                            3 => 'IllegalMonitorStateException',
                        ],
                    ],
                ],
            ],
            7 => [
                'category' => 'java',
                'difficulty' => 'sulit',
                'title' => 'Double-checked Locking Tanpa volatile',
                'slug' => 'java-double-checked-locking-tanpa-volatile',
                'description' => 'Singleton menggunakan double-checked locking tetapi field instance tidak menjamin visibility dan ordering antar-thread.',
                'broken_code' => 'public final class Singleton {
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
}',
                'buggy_line' => 2,
                'explanation' => 'Double-checked locking membutuhkan volatile agar penulisan reference dan konstruksi object memiliki aturan visibility serta ordering yang benar antar-thread. Tanpa volatile, thread lain dapat melihat reference sebelum konstruksi sepenuhnya terlihat.',
                'hints' => [
                    0 => [
                        'content' => 'Masalah terjadi pada visibility antar-thread.',
                        'point_penalty' => 10,
                    ],
                    1 => [
                        'content' => 'Tambahkan modifier yang mencegah reordering pada field instance.',
                        'point_penalty' => 20,
                    ],
                ],
                'solutions' => [
                    0 => [
                        'solution_code' => 'public final class Singleton {
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
}',
                        'solution_type' => 'primary',
                        'required_keywords' => [
                            0 => 'double-checked locking',
                            1 => 'volatile',
                            2 => 'visibility',
                            3 => 'reordering',
                        ],
                    ],
                ],
            ],
        ];
    }
}
