<?php

namespace App\Application\UseCases\Keywords;

use App\Domain\Repositories\KeywordRepositoryInterface;

class GetKeywordsUseCase
{
    private KeywordRepositoryInterface $keywordRepository;

    public function __construct(KeywordRepositoryInterface $keywordRepository)
    {
        $this->keywordRepository = $keywordRepository;
    }

    public function execute(): array
    {
        return $this->keywordRepository->getAll();
    }
}
