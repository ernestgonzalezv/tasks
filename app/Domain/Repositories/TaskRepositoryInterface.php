<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Task;

interface TaskRepositoryInterface
{
    public function getAll(): array;
    public function store(Task $task): Task;
    public function toggleStatus(int $id): Task;
}
