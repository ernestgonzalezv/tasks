<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Keyword;
use App\Domain\Repositories\KeywordRepositoryInterface;
use App\Models\Keyword as EloquentKeyword;
use Throwable;

class EloquentKeywordRepository implements KeywordRepositoryInterface
{
    public function getAll(): array
    {
        try {
            $keywords = EloquentKeyword::all();

            return $keywords->map(fn($k) => new Keyword(
                $k->id,
                $k->name,
                $k->created_at->toDateTimeString(),
                $k->updated_at->toDateTimeString()
            ))->toArray();
        } catch (Throwable $e) {
            throw new \RuntimeException("Failed to retrieve keywords: ", 0, $e);
        }
    }

    public function store(Keyword $keyword): Keyword
    {
        try {
            $eloquentKeyword = new EloquentKeyword();
            $eloquentKeyword->name = $keyword->name;
            $eloquentKeyword->save();

            return new Keyword(
                $eloquentKeyword->id,
                $eloquentKeyword->name,
                $eloquentKeyword->created_at->toDateTimeString(),
                $eloquentKeyword->updated_at->toDateTimeString()
            );
        } catch (Throwable $e) {
            throw new \RuntimeException("Failed to store keyword: ", 0, $e);
        }
    }
}
