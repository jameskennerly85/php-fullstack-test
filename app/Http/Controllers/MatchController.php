<?php
namespace App\Http\Controllers;

use App\Services\MatchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MatchController extends Controller
{
    /**
     * @var MatchService
     */
    protected $matchService;

    /**
     * @param MatchService $matchService
     */
    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    public function index() {
        return view('index');
    }

    /**
     * Returns a list of joinable matches
     *
     * @return JsonResponse
     */
    public function matches(): JsonResponse
    {
        $joinableMatches = $this->matchService->allJoinableMatches();

        return response()->json($joinableMatches);
    }

    /**
     * Returns the state of a single match
     *
     * @param $id
     * @return JsonResponse
     */
    public function match($id): JsonResponse
    {
        $match = $this->matchService->getJoinable($id);

        return response()->json($match);
    }

    /**
     * Makes a move in a match
     *
     * TODO it's mocked, make this work :)
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($id) {
        $board = [
            1, 0, 2,
            0, 1, 2,
            0, 0, 0,
        ];

        $position = Input::get('position');
        $board[$position] = 2;

        return response()->json([
            'id' => $id,
            'name' => 'Match'.$id,
            'next' => 1,
            'winner' => 0,
            'board' => $board,
        ]);
    }

    /**
     * Creates a new match and returns the new list of matches
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $inputs = $request->only('name');
        $this->matchService->createMatch($inputs);

        return $this->matches();
    }

    /**
     * Deletes the match and returns the new list of matches
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $this->matchService->deleteMatch($id);

        return $this->matches();
    }
}