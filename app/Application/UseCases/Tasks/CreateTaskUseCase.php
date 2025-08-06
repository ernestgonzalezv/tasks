<?php

namespace App\Application\UseCases\Tasks;

use App\Application\UseCases\UseCaseResponse;
use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;
use Exception;

class CreateTaskUseCase
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(string $title, array $keywords, bool $isDone = false): UseCaseResponse
    {
        try {
            $task = new Task(null, $title, $isDone, $keywords);
            $stored = $this->taskRepository->store($task);

            return UseCaseResponse::success(null, 'Task created successfully', 201);
        } catch (Exception $e) {
            return UseCaseResponse::error('Failed to create task', 500);
        }
    }
}
