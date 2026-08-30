<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class JudgeZeroService
{
    public function submit(
        string $language,
        string $sourceCode,
        string $stdin
    ): array {
        $languageId = config("services.judge0.languages.{$language}");

        if (! is_numeric($languageId)) {
            throw new RuntimeException(
                "Konfigurasi runtime untuk bahasa {$language} tidak tersedia.",
            );
        }

        $response = $this->client()
            ->post(
                '/submissions?base64_encoded=false&wait=false',
                [
                    'language_id' => (int) $languageId,
                    'source_code' => $this->prepareSourceCode(
                        $language,
                        $sourceCode,
                    ),
                    'stdin' => $stdin,
                    'cpu_time_limit' => 2,
                    'wall_time_limit' => 5,
                    'memory_limit' => 128000,
                    'max_file_size' => 1024,
                    'enable_network' => false,
                ],
            )
            ->throw();

        $token = $response->json('token');

        if (! is_string($token) || $token === '') {
            throw new RuntimeException(
                'Judge0 tidak mengembalikan token eksekusi.',
            );
        }

        return [
            'token' => $token,
            'status' => [
                'id' => 1,
                'description' => $this->statusDescription(1),
            ],
        ];
    }

    public function result(string $token): array
    {
        $response = $this->client()
            ->get(
                "/submissions/{$token}",
                [
                    'base64_encoded' => 'false',
                    'fields' => implode(',', [
                        'stdout',
                        'time',
                        'memory',
                        'stderr',
                        'token',
                        'compile_output',
                        'message',
                        'status',
                    ]),
                ],
            )
            ->throw();

        $statusId = (int) $response->json('status.id', 0);

        return [
            'token' => (string) $response->json('token', $token),
            'finished' => ! in_array($statusId, [1, 2], true),
            'status' => [
                'id' => $statusId,
                'description' => $this->statusDescription($statusId),
            ],
            'stdout' => $this->nullableString(
                $response->json('stdout'),
            ),
            'stderr' => $this->nullableString(
                $response->json('stderr'),
            ),
            'compile_output' => $this->nullableString(
                $response->json('compile_output'),
            ),
            'message' => $this->nullableString(
                $response->json('message'),
            ),
            'time' => $this->nullableString(
                $response->json('time'),
            ),
            'memory' => is_numeric($response->json('memory'))
                ? (int) $response->json('memory')
                : null,
        ];
    }

    private function prepareSourceCode(
        string $language,
        string $sourceCode
    ): string {
        if (strtolower($language) !== 'sql') {
            return $sourceCode;
        }

        return $this->sqlHarness()."\n\n".ltrim($sourceCode);
    }

    private function sqlHarness(): string
    {
        return <<<'SQL'
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    role_id INTEGER
);

CREATE TABLE orders (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    total REAL NOT NULL
);

CREATE TABLE employees (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    department TEXT NOT NULL,
    salary REAL NOT NULL
);

CREATE TABLE categories (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    active BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE products (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    category_id INTEGER NOT NULL
);

INSERT INTO roles (id, name) VALUES
    (1, 'Administrator'),
    (2, 'Member');

INSERT INTO users (id, name, email, active, role_id) VALUES
    (1, 'Ana', 'ana@example.com', TRUE, 1),
    (2, 'Budi', 'budi@example.com', TRUE, 2),
    (3, 'Citra', 'citra@example.com', FALSE, 2);

INSERT INTO orders (id, user_id, name, total) VALUES
    (1, 1, 'Pesanan A', 125000),
    (2, 1, 'Pesanan B', 75000),
    (3, 2, 'Pesanan C', 50000);

INSERT INTO employees (id, name, department, salary) VALUES
    (1, 'Andi', 'Engineering', 9000000),
    (2, 'Bella', 'Engineering', 8500000),
    (3, 'Candra', 'Engineering', 8000000),
    (4, 'Dina', 'Engineering', 7800000),
    (5, 'Eka', 'Engineering', 7600000),
    (6, 'Fajar', 'Engineering', 7400000),
    (7, 'Gita', 'Finance', 8200000),
    (8, 'Hadi', 'Finance', 7000000);

INSERT INTO categories (id, name, active) VALUES
    (1, 'Backend', TRUE),
    (2, 'Frontend', TRUE),
    (3, 'Legacy', FALSE);

INSERT INTO products (id, name, category_id) VALUES
    (1, 'API Course', 1),
    (2, 'React Course', 2),
    (3, 'Legacy Course', 3);
SQL;
    }

    private function client(): PendingRequest
    {
        $baseUrl = rtrim(
            (string) config(
                'services.judge0.base_url',
                'https://ce.judge0.com',
            ),
            '/',
        );

        $headers = [];
        $apiKey = (string) config('services.judge0.api_key', '');
        $rapidApiHost = (string) config(
            'services.judge0.rapidapi_host',
            '',
        );
        $authToken = (string) config(
            'services.judge0.auth_token',
            '',
        );

        if ($apiKey !== '') {
            $headers['X-RapidAPI-Key'] = $apiKey;

            if ($rapidApiHost !== '') {
                $headers['X-RapidAPI-Host'] = $rapidApiHost;
            }
        }

        if ($authToken !== '') {
            $headers['X-Auth-Token'] = $authToken;
        }

        return Http::baseUrl($baseUrl)
            ->acceptJson()
            ->asJson()
            ->withHeaders($headers)
            ->connectTimeout(5)
            ->timeout(15);
    }

    private function statusDescription(int $statusId): string
    {
        return match ($statusId) {
            1 => 'Dalam antrean',
            2 => 'Sedang diproses',
            3 => 'Berhasil',
            4 => 'Jawaban salah',
            5 => 'Batas waktu terlampaui',
            6 => 'Kesalahan kompilasi',
            7 => 'Kesalahan saat dijalankan (SIGSEGV)',
            8 => 'Kesalahan saat dijalankan (SIGXFSZ)',
            9 => 'Kesalahan saat dijalankan (SIGFPE)',
            10 => 'Kesalahan saat dijalankan (SIGABRT)',
            11 => 'Kesalahan saat dijalankan (NZEC)',
            12 => 'Kesalahan saat dijalankan lainnya',
            13 => 'Kesalahan internal',
            14 => 'Kesalahan format eksekusi',
            default => 'Status tidak diketahui',
        };
    }

    private function nullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }
}
