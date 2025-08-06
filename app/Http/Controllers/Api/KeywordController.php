<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Application\UseCases\Keywords\GetKeywordsUseCase;
use App\Application\UseCases\Keywords\CreateKeywordUseCase;
use App\Http\Requests\KeywordRequest;
use App\Http\Responses\KeywordResponse;

/**
 * @OA\PathItem(path="/api/keywords")
 */
class KeywordController extends Controller
{
    private GetKeywordsUseCase $getKeywordsUseCase;
    private CreateKeywordUseCase $createKeywordUseCase;

    public function __construct(
        GetKeywordsUseCase $getKeywordsUseCase,
        CreateKeywordUseCase $createKeywordUseCase
    ) {
        $this->getKeywordsUseCase = $getKeywordsUseCase;
        $this->createKeywordUseCase = $createKeywordUseCase;
    }

    /**
     * @OA\Get(
     *     path="/api/keywords",
     *     summary="Get all keywords",
     *     description="Retrieve a list of all reusable keywords",
     *     tags={"Keywords"},
     *     @OA\Response(
     *         response=200,
     *         description="List of keywords retrieved successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/KeywordResponse"))
     *     )
     * )
     */
    public function index()
    {
        $keywords = $this->getKeywordsUseCase->execute();

        $response = array_map(fn($keyword) => KeywordResponse::fromDomainKeyword($keyword), $keywords);

        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/keywords",
     *     summary="Create a new keyword",
     *     description="Creates a new reusable keyword",
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
     *         @OA\JsonContent(ref="#/components/schemas/KeywordResponse")
     *     )
     * )
     */
    public function store(KeywordRequest $request)
    {
        $keyword = $this->createKeywordUseCase->execute($request->name);

        $response = KeywordResponse::fromDomainKeyword($keyword);

        return response($response, 201);
    }
}
