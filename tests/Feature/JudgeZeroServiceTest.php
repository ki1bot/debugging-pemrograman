<?php

namespace Tests\Feature;

use App\Services\JudgeZeroService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JudgeZeroServiceTest extends TestCase
{
    public function test_sql_source_receives_execution_schema(): void
    {
        config([
            'services.judge0.base_url' => 'https://judge0.test',
            'services.judge0.languages.sql' => 82,
        ]);

        Http::fake([
            'https://judge0.test/*' => Http::response([
                'token' => '11111111-1111-4111-8111-111111111111',
            ], 201),
        ]);

        $sourceCode = <<<'SQL'
SELECT users.id, users.name
FROM users
JOIN orders ON users.id = orders.user_id;
SQL;

        (new JudgeZeroService)->submit('sql', $sourceCode, '');

        Http::assertSent(function (Request $request) use ($sourceCode): bool {
            $preparedSource = (string) ($request->data()['source_code'] ?? '');

            return str_contains($preparedSource, 'CREATE TABLE users')
                && str_contains($preparedSource, 'CREATE TABLE orders')
                && str_contains($preparedSource, 'CREATE TABLE employees')
                && str_contains($preparedSource, 'CREATE TABLE categories')
                && str_contains($preparedSource, 'CREATE TABLE products')
                && str_contains($preparedSource, 'CREATE TABLE roles')
                && str_ends_with($preparedSource, $sourceCode);
        });
    }

    public function test_non_sql_source_is_not_modified(): void
    {
        config([
            'services.judge0.base_url' => 'https://judge0.test',
            'services.judge0.languages.php' => 98,
        ]);

        Http::fake([
            'https://judge0.test/*' => Http::response([
                'token' => '22222222-2222-4222-8222-222222222222',
            ], 201),
        ]);

        $sourceCode = <<<'PHP'
<?php
echo 'BugHunt';
PHP;

        (new JudgeZeroService)->submit('php', $sourceCode, '');

        Http::assertSent(
            fn (Request $request): bool => ($request->data()['source_code'] ?? null) === $sourceCode,
        );
    }
}
