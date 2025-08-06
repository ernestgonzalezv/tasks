<?php

namespace App\Domain\Entities;

class Keyword
{
    public ?int $id;
    public string $name;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(?int $id, string $name, string $createdAt = '', string $updatedAt = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
