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

    public function index()
    {
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
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function match($id): JsonResponse
    {
        $match = $this->matchService->getMatch($id);

        return response()->json($match);
    }

    /**
     * Makes a move in a match
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     *
     * @throws \App\Exceptions\Model\InvalidMatchMoveException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function move($id, Request $request): JsonResponse
    {
        $match = $this->matchService->attemptMove([
            'id' => $id,
            'position' => $request->get('position')
        ]);

        return response()->json($match);
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