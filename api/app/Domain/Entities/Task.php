<?php

namespace App\Domain\Entities;

class Task
{
    public ?int $id;
    public string $title;
    public bool $isDone;
    public array $keywords; // array de Keyword entities
    public string $createdAt;
    public string $updatedAt;

    public function __construct(?int $id, string $title, bool $isDone, array $keywords = [], string $createdAt = '', string $updatedAt = '')
    {
        $this->id = $id;
        $this->title = $title;
        $this->isDone = $isDone;
        $this->keywords = $keywords;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
