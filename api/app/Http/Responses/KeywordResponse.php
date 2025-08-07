<?php

namespace App\Http\Responses;

/**
 * @OA\Schema(
 *     schema="KeywordResponse",
 *     type="object",
 *     title="Keyword Response",
 *     description="Represents a keyword object"
 * )
 */
class KeywordResponse implements \JsonSerializable
{
    /**
     * @OA\Property(example=1)
     */
    public int $id;

    /**
     * @OA\Property(example="Urgent")
     */
    public string $name;

    /**
     * @OA\Property(example="2025-08-06T12:00:00Z")
     */
    public string $createdAt;

    /**
     * @OA\Property(example="2025-08-06T12:00:00Z")
     */
    public string $updatedAt;

    public function __construct(int $id, string $name, string $createdAt, string $updatedAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromDomainKeyword(\App\Domain\Entities\Keyword $keyword): self
    {
        return new self(
            $keyword->id,
            $keyword->name,
            $keyword->createdAt,
            $keyword->updatedAt
        );
    }

    public static function fromArray(array $keyword): self
    {
        return new self(
            $keyword['id'],
            $keyword['name'],
            $keyword['created_at'],
            $keyword['updated_at']
        );
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
