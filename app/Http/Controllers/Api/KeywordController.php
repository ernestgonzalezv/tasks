<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Application\UseCases\Keywords\GetKeywordsUseCase;
use App\Application\UseCases\Keywords\CreateKeywordUseCase;
use App\Http\Requests\KeywordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * @OA\Tag(
 *     name="Keywords",
 *     description="Operations related to keyword management"
 * )
 */
class KeywordController extends Controller
{
    public function __construct(
        private GetKeywordsUseCase $getKeywordsUseCase,
        private CreateKeywordUseCase $createKeywordUseCase
    ) {}

    /**
     * @OA\Get(
     *     path="/api/keywords",
     *     summary="List all keywords",
     *     tags={"Keywords"},
     *     @OA\Response(
     *         response=200,
     *         description="Keywords retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", nullable=true, example=null),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="object", example={
     *                     "id": 1,
     *                     "name": "Urgent",
     *                     "created_at": "2025-08-06 12:00:00",
     *                     "updated_at": "2025-08-06 12:00:00"
     *                 })
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve keywords",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve keywords"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $resp = $this->getKeywordsUseCase->execute();

            if (!$resp->success) {
                return response()->json([
                    'success' => false,
                    'message' => $resp->message ?? 'Failed to retrieve keywords',
                    'data'    => null,
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => null,
                'data'    => $resp->data,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve keywords',
                'data'    => null,
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/keywords",
     *     summary="Create a new keyword",
     *     tags={"Keywords"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Urgent")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Keyword created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Keyword created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Urgent"),
     *                 @OA\Property(property="created_at", type="string", example="2025-08-06 14:23:45"),
     *                 @OA\Property(property="updated_at", type="string", example="2025-08-06 14:23:45")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred")
     *         )
     *     )
     * )
     */
    public function store(KeywordRequest $request): JsonResponse
    {
        $result = $this->createKeywordUseCase->execute($request->name);

        return response()->json([
            'success' => $result->success,
            'message' => $result->message ?? ($result->success ? 'Keyword created successfully' : 'Failed to create keyword'),
        ], $result->statusCode);
    }
}
