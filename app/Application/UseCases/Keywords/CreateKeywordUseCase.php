<?php

namespace App\Application\UseCases\Keywords;

use App\Domain\Repositories\KeywordRepositoryInterface;
use App\Domain\Entities\Keyword;

class CreateKeywordUseCase
{
    private KeywordRepositoryInterface $keywordRepository;

    public function __construct(KeywordRepositoryInterface $keywordRepository)
    {
        $this->keywordRepository = $keywordRepository;
    }

    public function execute(string $name): Keyword
    {
        $keyword = new Keyword(null, $name);
        return $this->keywordRepository->store($keyword);
    }
}
