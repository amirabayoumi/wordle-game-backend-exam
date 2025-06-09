<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use App\Models\Word;
use Mockery;

class WordApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock isEnglishWord globally
        App::singleton('App\Helpers\WordHelper', function () {
            $mock = Mockery::mock('alias:App\Helpers\WordHelper');
            $mock->shouldReceive('isEnglishWord')
                ->andReturnUsing(fn($word) => in_array(strtolower($word), ['admin', 'floor', 'apple']));
            return $mock;
        });
    }

    /** @test */
    public function it_rejects_guess_that_is_not_five_characters()
    {
        $response = $this->postJson('/api/words/check', [
            'guess' => 'dog',
            'token' => 'tok1',
        ]);

        $response->assertStatus(422)
            ->assertExactJson([
                'errors' => [
                    'guess' => ['The guess field must be 5 characters.']
                ]
            ]);
    }

    /** @test */
    public function it_rejects_non_english_word()
    {
        // Create a word for today - explicitly use create() not firstOrCreate()
        Word::create([
            'word' => 'apple',
            'schedule_at' => now()->toDateString()
        ]);

        $response = $this->postJson('/api/words/check', [
            'guess' => 'xxxxx',
            'token' => 'tok2',
        ]);

        $response->assertStatus(422)
            ->assertExactJson([
                'errors' => [
                    'guess' => ['Guess is not a valid English 5-letter word.']
                ]
            ]);
    }

    /** @test */
    public function it_returns_correct_response_when_guess_is_exact_match()
    {
        // Ensure word exists with explicit create
        Word::create([
            'word' => 'admin',
            'schedule_at' => now()->toDateString()
        ]);

        $response = $this->postJson('/api/words/check', [
            'guess' => 'admin',
            'token' => 'tok3',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'result' => [
                    [
                        'letter' => 'A',
                        'color' => 'green',
                        'order' => true,
                        'exist' => true
                    ],
                    [
                        'letter' => 'D',
                        'color' => 'green',
                        'order' => true,
                        'exist' => true
                    ],
                    [
                        'letter' => 'M',
                        'color' => 'green',
                        'order' => true,
                        'exist' => true
                    ],
                    [
                        'letter' => 'I',
                        'color' => 'green',
                        'order' => true,
                        'exist' => true
                    ],
                    [
                        'letter' => 'N',
                        'color' => 'green',
                        'order' => true,
                        'exist' => true
                    ]
                ],
                'message' => 'Well done!'
            ]);
    }

    /** @test */
    public function it_returns_colored_result_for_incorrect_guess()
    {
        // Explicit create
        Word::create([
            'word' => 'apple',
            'schedule_at' => now()->toDateString()
        ]);

        $response = $this->postJson('/api/words/check', [
            'guess' => 'floor',
            'token' => 'tok4',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'tries_left'
            ])
            ->assertJsonPath('tries_left', 5);
    }

    /** @test */
    public function it_returns_game_over_after_six_tries()
    {
        // Explicit create
        Word::create([
            'word' => 'apple',
            'schedule_at' => now()->toDateString()
        ]);

        $token = 'tok5';

        // First 5 tries
        for ($i = 1; $i <= 5; $i++) {
            $this->postJson('/api/words/check', [
                'guess' => 'floor',
                'token' => $token,
            ]);
        }

        // 6th try should return Game Over message
        $response = $this->postJson('/api/words/check', [
            'guess' => 'floor',
            'token' => $token,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Game Over. You have used all 6 tries.');
    }

    /** @test */
    public function it_returns_404_if_no_word_scheduled_today()
    {
        // No word created for today

        $response = $this->postJson('/api/words/check', [
            'guess' => 'apple',
            'token' => 'tok6',
        ]);

        $response->assertStatus(404)
            ->assertExactJson([
                'error' => 'No word scheduled for today.'
            ]);
    }
}
