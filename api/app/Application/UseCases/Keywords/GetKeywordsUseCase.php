<?php

namespace App\Application\UseCases\Keywords;

use App\Application\UseCases\UseCaseResponse;
use App\Domain\Repositories\KeywordRepositoryInterface;
use Exception;

class GetKeywordsUseCase
{
    private KeywordRepositoryInterface $keywordRepository;

    public function __construct(KeywordRepositoryInterface $keywordRepository)
    {
        $this->keywordRepository = $keywordRepository;
    }

    public function execute(): UseCaseResponse
    {
        try {
            $keywords = $this->keywordRepository->getAll();

            return UseCaseResponse::success($keywords, 200);
        } catch (Exception $e) {
            return UseCaseResponse::error('Failed to retrieve keywords', 500);
        }
    }
}
