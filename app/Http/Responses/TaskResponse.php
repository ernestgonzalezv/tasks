<?php

namespace App\Http\Responses;

/**
 * @OA\Schema(
 *     schema="TaskResponse",
 *     type="object",
 *     title="Task Response",
 *     description="Represents a task object"
 * )
 */
class TaskResponse implements \JsonSerializable
{
    /**
     * @OA\Property(example=1)
     */
    public int $id;

    /**
     * @OA\Property(example="Buy groceries")
     */
    public string $title;

    /**
     * @OA\Property(example=false)
     */
    public bool $isDone;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/KeywordResponse")
     * )
     */
    public array $keywords;

    /**
     * @OA\Property(example="2025-08-06T12:00:00Z")
     */
    public string $createdAt;

    /**
     * @OA\Property(example="2025-08-06T12:00:00Z")
     */
    public string $updatedAt;

    public function __construct(
        int $id,
        string $title,
        bool $isDone,
        array $keywords,
        string $createdAt,
        string $updatedAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->isDone = $isDone;
        $this->keywords = $keywords;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromDomainTask(\App\Domain\Entities\Task $task): self
    {
        $keywords = array_map(fn($keyword) => KeywordResponse::fromArray($keyword), $task->keywords);

        return new self(
            $task->id,
            $task->title,
            $task->isDone,
            $keywords,
            $task->createdAt,
            $task->updatedAt
        );
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isDone' => $this->isDone,
            'keywords' => $this->keywords,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
