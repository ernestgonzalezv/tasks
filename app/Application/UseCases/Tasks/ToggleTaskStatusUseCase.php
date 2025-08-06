<?php

namespace App\Application\UseCases\Tasks;

use App\Application\UseCases\UseCaseResponse;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Http\Responses\TaskResponse;
use Exception;

class ToggleTaskStatusUseCase
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute(int $id): UseCaseResponse
    {
        try {
            $updated = $this->taskRepository->toggleStatus($id);

            if (!$updated) {
                return UseCaseResponse::error('Task not found', 404);
            }

            $dto = TaskResponse::fromDomainTask($updated);
            return UseCaseResponse::success($dto);
        } catch (Exception $e) {
            return UseCaseResponse::error('An unexpected error occurred', 500);
        }
    }
}
