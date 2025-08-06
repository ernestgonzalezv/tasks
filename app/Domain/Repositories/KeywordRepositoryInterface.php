<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Keyword;

interface KeywordRepositoryInterface
{
    public function getAll(): array;
    public function store(Keyword $keyword): Keyword;
}
