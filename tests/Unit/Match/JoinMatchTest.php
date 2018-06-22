<?php
namespace Tests\Unit\Match;

use App\Exceptions\Model\MatchIsNotJoinableException;
use App\Services\MatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JoinMatchTest extends TestCase
{
    use RefreshDatabase;

    protected $matchService;

    public function setUp()
    {
        parent::setUp();
        $this->matchService = app()->make(MatchService::class);
    }

    public function test_shouldJoinValidMatch()
    {
        $match = $this->matchService->createMatch([
            'name' => 'My Test Match',
        ]);

        $this->assertInstanceOf(\App\Models\Match::class, $match);
    }

    public function test_shouldThrowExceptionIfNotJoinable()
    {
        $match = $this->matchService->createMatch([
            'name' => 'My Test Match',
        ]);

        $match->winner = 1;
        $match->save();

        try {
            $this->matchService->getJoinable($match->id);
        } catch (\Exception $e) {
            $this->assertInstanceOf(MatchIsNotJoinableException::class, $e);
            return;
        }

        $this->assertTrue(false, 'should not have reached this point');
    }
}