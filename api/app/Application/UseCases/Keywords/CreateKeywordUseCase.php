<?php

namespace App\Application\UseCases\Keywords;

use App\Application\UseCases\UseCaseResponse;
use App\Domain\Repositories\KeywordRepositoryInterface;
use Exception;

class CreateKeywordUseCase
{
    private KeywordRepositoryInterface $keywordRepository;

    public function __construct(KeywordRepositoryInterface $keywordRepository)
    {
        $this->keywordRepository = $keywordRepository;
    }

    public function execute(string $name): UseCaseResponse
    {
        try {
            $keyword = new \App\Domain\Entities\Keyword(null, $name);
            $stored = $this->keywordRepository->store($keyword);
            return UseCaseResponse::success(null, 'Keyword created successfully',  201);
        } catch (Exception $e) {
            return UseCaseResponse::error("Failed to create keyword", 500);
        }
    }

}
