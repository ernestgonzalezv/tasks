<?php

namespace App\Application\UseCases\Tasks;

use App\Application\UseCases\UseCaseResponse;
use App\Domain\Repositories\TaskRepositoryInterface;
use Exception;

class GetTasksUseCase
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(): UseCaseResponse
    {
        try {
            $tasks = $this->taskRepository->getAll();
            $dtos = array_map(fn($task) => \App\Http\Responses\TaskResponse::fromDomainTask($task), $tasks);
            return UseCaseResponse::success($dtos, 200);
        } catch (Exception $e) {
            return UseCaseResponse::error('Failed to retrieve tasks', 500);
        }
    }
}
