<?php

namespace App\Application\UseCases\Tasks;

use App\Domain\Repositories\TaskRepositoryInterface;
use App\Domain\Entities\Task;

class ToggleTaskStatusUseCase
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(int $id): Task
    {
        return $this->taskRepository->toggleStatus($id);
    }
}
