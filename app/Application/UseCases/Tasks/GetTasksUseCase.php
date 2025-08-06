<?php

namespace App\Application\UseCases\Tasks;

use App\Domain\Repositories\TaskRepositoryInterface;

class GetTasksUseCase
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(): array
    {
        return $this->taskRepository->getAll();
    }
}
