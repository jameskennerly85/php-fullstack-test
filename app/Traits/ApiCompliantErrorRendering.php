<?php
namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use ReflectionClass;

trait ApiCompliantErrorRendering
{
    /**
     * @var array
     */
    protected $apiCompliantRender = [
        \App\Exceptions\Model\InvalidMatchMoveException::class,
        \App\Exceptions\Model\MatchIsNotJoinableException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Prettus\Validator\Exceptions\ValidatorException::class,
    ];

    /**
     * @param Exception $exception
     * @return bool
     */
    protected function checkForApiRender(Exception $exception): bool
    {
        return in_array(get_class($exception), $this->apiCompliantRender);
    }

    /**
     * @param Exception $exception
     * @return JsonResponse
     */
    protected function getApiCompliantErrorOutput(Exception $exception): JsonResponse
    {
        $reflectedClass = new ReflectionClass($exception);
        $classShortName = $reflectedClass->getShortName();
        // default errors object
        $errors = [
            [
                'status' => '400',
                'title'  => $classShortName,
                'detail' => $exception->getMessage()
            ]
        ];

        if ($exception->getMessageBag() !== null) {
            $errors = [];

            foreach ($exception->getMessageBag()->all() as $errorMsg) {
                $errors[] = [
                    'status' => '400',
                    'title'  => $classShortName,
                    'detail' => $errorMsg
                ];
            }
        }

        return response()->json(['errors' => $errors], 400);
    }
}
