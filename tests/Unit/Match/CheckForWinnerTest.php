<?php
namespace Tests\Unit\Match;

use App\Services\MatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckForWinnerTest extends TestCase
{
    use RefreshDatabase;

    protected $matchService;
    protected $ticTacToes;

    public function setUp()
    {
        parent::setUp();
        $this->matchService = app()->make(MatchService::class);
    }

    public function test_shouldDetectWinner()
    {
        $this->loadTicTacToes();

        foreach ($this->ticTacToes as $key => $gameSetting) {
            list($line, $position) = explode('_', $key);
            $match = $this->simulateWinningMove($gameSetting, intval($position));

            $this->assertEquals(2, $match->winner);
        }
    }

    private function loadTicTacToes()
    {
        $this->ticTacToes = include sprintf('%s/tests/data/ticTacToes.php', base_path());
    }

    private function simulateWinningMove($boardSettings, $position)
    {
        $match = $this->matchService->createMatch([
            'name' => 'My Test Match',
        ]);

        $match->board = $boardSettings;
        $match->save();

        return $this->matchService->attemptMove([
            'id' => $match->id,
            'position' => $position,
        ]);
    }
}