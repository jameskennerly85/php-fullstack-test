<?php
namespace Tests\Unit\Match;

use App\Services\MatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateMatchTest extends TestCase
{
    use RefreshDatabase;

    protected $matchService;

    public function setUp()
    {
        parent::setUp();
        $this->matchService = app()->make(MatchService::class);
    }

    public function test_shouldCreateModel()
    {
        $attributes = [
            'name' => 'My Test Match',
        ];

        $this->matchService->createMatch($attributes);
        $this->assertDatabaseHas('matches', $attributes);
    }
}