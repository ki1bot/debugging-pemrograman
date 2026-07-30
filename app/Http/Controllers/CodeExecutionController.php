<?php

namespace App\Http\Controllers;

use App\Services\JudgeZeroService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;

class CodeExecutionController extends Controller
{
    public function __construct(
        private readonly JudgeZeroService $judgeZeroService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'language' => [
                'required',
                'string',
                'in:c,cpp,go,java,javascript,php,python,sql',
            ],
            'source_code' => [
                'required',
                'string',
                'max:20000',
            ],
            'stdin' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            $submission = $this->judgeZeroService->submit(
                $validated['language'],
                $validated['source_code'],
                $validated['stdin'] ?? '',
            );

            $request->session()->put(
                "code_execution_tokens.{$submission['token']}",
                now()->addMinutes(5)->getTimestamp(),
            );

            return response()->json($submission, 201);
        } catch (ConnectionException|RequestException $exception) {
            report($exception);

            return response()->json([
                'message' => 'Layanan eksekusi kode sedang tidak dapat dihubungi.',
            ], 502);
        } catch (RuntimeException $exception) {
            report($exception);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, string $token): JsonResponse
    {
        if (! Str::isUuid($token)) {
            return response()->json([
                'message' => 'Token eksekusi tidak valid.',
            ], 422);
        }

        $sessionKey = "code_execution_tokens.{$token}";
        $expiresAt = (int) $request->session()->get($sessionKey, 0);

        if ($expiresAt < now()->getTimestamp()) {
            $request->session()->forget($sessionKey);

            return response()->json([
                'message' => 'Token eksekusi sudah kedaluwarsa atau tidak dikenal.',
            ], 404);
        }

        try {
            return response()->json(
                $this->judgeZeroService->result($token),
            );
        } catch (ConnectionException|RequestException $exception) {
            report($exception);

            return response()->json([
                'message' => 'Hasil eksekusi belum dapat diambil.',
            ], 502);
        } catch (RuntimeException $exception) {
            report($exception);

            return response()->json([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
