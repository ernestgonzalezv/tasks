<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Keyword;
use App\Domain\Repositories\KeywordRepositoryInterface;
use App\Models\Keyword as EloquentKeyword;

class EloquentKeywordRepository implements KeywordRepositoryInterface
{
    public function getAll(): array
    {
        $keywords = EloquentKeyword::all();

        return $keywords->map(fn($k) => new Keyword(
            $k->id,
            $k->name,
            $k->created_at->toDateTimeString(),
            $k->updated_at->toDateTimeString()
        ))->toArray();
    }

    public function store(Keyword $keyword): Keyword
    {
        $eloquentKeyword = new EloquentKeyword();
        $eloquentKeyword->name = $keyword->name;
        $eloquentKeyword->save();

        return new Keyword(
            $eloquentKeyword->id,
            $eloquentKeyword->name,
            $eloquentKeyword->created_at->toDateTimeString(),
            $eloquentKeyword->updated_at->toDateTimeString()
        );
    }
}

