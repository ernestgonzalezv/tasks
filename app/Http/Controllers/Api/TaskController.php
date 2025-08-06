<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Application\UseCases\Tasks\GetTasksUseCase;
use App\Application\UseCases\Tasks\CreateTaskUseCase;
use App\Application\UseCases\Tasks\ToggleTaskStatusUseCase;
use App\Http\Requests\TaskRequest;
use App\Http\Responses\TaskResponse;

/**
 * @OA\PathItem(path="/api/tasks")
 */
class TaskController extends Controller
{
    private GetTasksUseCase $getTasksUseCase;
    private CreateTaskUseCase $createTaskUseCase;
    private ToggleTaskStatusUseCase $toggleTaskStatusUseCase;

    public function __construct(
        GetTasksUseCase $getTasksUseCase,
        CreateTaskUseCase $createTaskUseCase,
        ToggleTaskStatusUseCase $toggleTaskStatusUseCase
    ) {
        $this->getTasksUseCase = $getTasksUseCase;
        $this->createTaskUseCase = $createTaskUseCase;
        $this->toggleTaskStatusUseCase = $toggleTaskStatusUseCase;
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get all tasks",
     *     description="Retrieve all tasks with their associated keywords",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks retrieved successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TaskResponse"))
     *     )
     * )
     */
    public function index()
    {
        $tasks = $this->getTasksUseCase->execute();

        $response = array_map(fn($task) => TaskResponse::fromDomainTask($task), $tasks);

        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     description="Creates a new task and optionally assigns keywords to it",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Buy groceries"),
     *             @OA\Property(
     *                 property="keywords",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResponse")
     *     )
     * )
     */
    public function store(TaskRequest $request)
    {
        $task = $this->createTaskUseCase->execute(
            $request->title,
            $request->input('keywords', [])
        );

        $response = TaskResponse::fromDomainTask($task);

        return response($response, 201);
    }

    /**
     * @OA\Patch(
     *     path="/api/tasks/{id}/toggle",
     *     summary="Toggle task status",
     *     description="Switch the status of a task between completed and pending",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task status updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResponse")
     *     ),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function toggle($id)
    {
        $task = $this->toggleTaskStatusUseCase->execute($id);

        $response = TaskResponse::fromDomainTask($task);

        return $response;
    }
}
