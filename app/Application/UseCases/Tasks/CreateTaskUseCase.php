<?php

namespace App\Application\UseCases\Tasks;

use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;

class CreateTaskUseCase
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(string $title, array $keywords): Task
    {
        $task = new Task(null, $title, false, $keywords);
        return $this->taskRepository->store($task);
    }
}
