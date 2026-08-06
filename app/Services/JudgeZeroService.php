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
                    'source_code' => $sourceCode,
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
