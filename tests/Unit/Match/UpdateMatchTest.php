<?php
namespace Tests\Unit\Match;

use App\Exceptions\Model\InvalidMatchMoveException;
use App\Services\MatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prettus\Validator\Exceptions\ValidatorException;
use Tests\TestCase;

class UpdateMatchTest extends TestCase
{
    use RefreshDatabase;

    protected $matchService;

    public function setUp()
    {
        parent::setUp();
        $this->matchService = app()->make(MatchService::class);
    }

    public function test_shouldUpdateOnValidMove()
    {
        $match = $this->matchService->createMatch([
            'name' => 'My Test Match',
        ]);

        $player = $match->next;

        $moveParams = [
            'id' => $match->id,
            'position' => 0,
        ];

        $this->matchService->attemptMove($moveParams);
        $match->refresh();

        $this->assertTrue (
            $match->board[0] === $player,
            sprintf('Position "0" should be as "%d" (%s).', $player, $this->getPlayerSign($player))
        );
    }

    public function test_shouldForbidAttemptOnTakenPosition()
    {
        $match = $this->matchService->createMatch([
            'name' => 'My Test Match',
        ]);

        $player = $match->next;

        $moveParams = [
            'id' => $match->id,
            'position' => 0,
        ];

        $this->matchService->attemptMove($moveParams);

        try {
            $this->matchService->attemptMove($moveParams);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidMatchMoveException::class, $e);
            return;
        }

        $this->assertTrue(false, 'should not have reached this point');
    }

    public function test_shouldNotAttemptOnFinishedMatch()
    {
        $match = $this->matchService->createMatch([
            'name' => 'My Test Match',
        ]);

        $match->winner = 1;
        $match->save();

        $moveParams = [
            'id' => $match->id,
            'position' => 0,
        ];

        try {
            // will throw exception as it breaks one of the validation rules (winner must be 0)
            $this->matchService->attemptMove($moveParams);
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidatorException::class, $e);
            return;
        }

        $this->assertTrue(false, 'should not have reached this point');
    }

    /**
     * @param int $playerNumber
     * @return string
     *
     * @throws \Exception
     */
    private function getPlayerSign(int $playerNumber): string
    {
        switch ($playerNumber) {
            case 1: return 'Cross';
            case 2: return 'Circle';
 
            default: throw new \Exception('Invalid player number.');
        }
    }
}