<?php

namespace Tests\Unit;

use App\Models\Challenge;
use App\Models\ChallengeSolution;
use App\Services\ChallengeEvaluationService;
use PHPUnit\Framework\TestCase;

class ChallengeEvaluationServiceTest extends TestCase
{
    public function test_correct_answer_receives_maximum_score(): void
    {
        $result = $this->service()->evaluate(
            $this->challenge(),
            2,
            $this->correctCode(),
            'Array memiliki indeks terakhir length dikurangi satu sehingga akses di luar batas harus dicegah.',
            0,
        );

        $this->assertSame(
            30,
            $result['line_score'],
        );

        $this->assertSame(
            50,
            $result['code_score'],
        );

        $this->assertSame(
            20,
            $result['explanation_score'],
        );

        $this->assertSame(
            100,
            $result['final_score'],
        );

        $this->assertSame(
            'completed',
            $result['status'],
        );
    }

    public function test_unimportant_formatting_does_not_make_code_incorrect(): void
    {
        $submittedCode = <<<'CODE'
const   numbers=[1,2,3,4];

for(let i=0;i<numbers.length;i++)
{
        console.log( numbers[i] );
}
CODE;

        $result = $this->service()->evaluate(
            $this->challenge(),
            2,
            $submittedCode,
            'Array menggunakan indeks dan length sehingga tidak boleh mengakses data di luar batas.',
            0,
        );

        $this->assertSame(
            50,
            $result['code_score'],
        );
    }

    public function test_space_inside_string_is_not_removed(): void
    {
        $challenge = new Challenge([
            'base_points' => 100,
            'buggy_line' => 1,
        ]);

        $challenge->setRelation(
            'solutions',
            collect([
                new ChallengeSolution([
                    'solution_code' =>
                        "const message = 'Hello World';",
                    'solution_type' =>
                        'primary',
                    'required_keywords' =>
                        ['string'],
                ]),
            ]),
        );

        $result = $this->service()->evaluate(
            $challenge,
            1,
            "const message = 'HelloWorld';",
            'Kesalahan terdapat pada string.',
            0,
        );

        $this->assertSame(
            0,
            $result['code_score'],
        );
    }

    public function test_identifier_separator_is_not_ignored(): void
    {
        $challenge = new Challenge([
            'base_points' => 100,
            'buggy_line' => 1,
        ]);

        $challenge->setRelation(
            'solutions',
            collect([
                new ChallengeSolution([
                    'solution_code' =>
                        'const message = 10;',
                    'solution_type' =>
                        'primary',
                    'required_keywords' =>
                        ['variabel'],
                ]),
            ]),
        );

        $result = $this->service()->evaluate(
            $challenge,
            1,
            'constmessage = 10;',
            'Kesalahan nama variabel.',
            0,
        );

        $this->assertSame(
            0,
            $result['code_score'],
        );
    }

    public function test_hint_penalty_reduces_final_score(): void
    {
        $result = $this->service()->evaluate(
            $this->challenge(),
            2,
            $this->correctCode(),
            'Array memiliki indeks terakhir berdasarkan length sehingga akses di luar batas harus dicegah.',
            10,
        );

        $this->assertSame(
            90,
            $result['final_score'],
        );

        $this->assertSame(
            10,
            $result['hint_penalty'],
        );
    }

    private function service(): ChallengeEvaluationService
    {
        return new ChallengeEvaluationService();
    }

    private function challenge(): Challenge
    {
        $challenge = new Challenge([
            'base_points' => 100,
            'buggy_line' => 2,
        ]);

        $challenge->setRelation(
            'solutions',
            collect([
                new ChallengeSolution([
                    'solution_code' =>
                        $this->correctCode(),
                    'solution_type' =>
                        'primary',
                    'required_keywords' => [
                        'array',
                        'indeks',
                        'length',
                        'di luar batas',
                    ],
                ]),
            ]),
        );

        return $challenge;
    }

    private function correctCode(): string
    {
        return <<<'CODE'
const numbers = [1, 2, 3, 4];
for (let i = 0; i < numbers.length; i++) {
    console.log(numbers[i]);
}
CODE;
    }
}
