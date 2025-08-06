<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Application\UseCases\Tasks\GetTasksUseCase;
use App\Application\UseCases\Tasks\CreateTaskUseCase;
use App\Application\UseCases\Tasks\ToggleTaskStatusUseCase;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="Operations related to task management"
 * )
 */
class TaskController extends Controller
{
    public function __construct(
        private GetTasksUseCase $getTasksUseCase,
        private CreateTaskUseCase $createTaskUseCase,
        private ToggleTaskStatusUseCase $toggleTaskStatusUseCase
    ) {}

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="List all tasks",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="Tasks retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", nullable=true, example=null),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="object", example={
     *                     "id": 1,
     *                     "title": "Buy groceries",
     *                     "is_done": false
     *                 })
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve tasks",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve tasks"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $resp = $this->getTasksUseCase->execute();

            if (!$resp->success) {
                return response()->json([
                    'success' => false,
                    'message' => $resp->message ?? 'Failed to retrieve tasks',
                    'data' => null,
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => null,
                'data'    => $resp->data,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tasks',
                'data' => null,
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Buy groceries"),
     *             @OA\Property(
     *                 property="keywords",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 description="Array of keyword IDs"
     *             ),
     *             @OA\Property(
     *                 property="is_done",
     *                 type="boolean",
     *                 example=false,
     *                 description="Optional task done status, default false"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Task created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred")
     *         )
     *     )
     * )
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $data = $request->validated();

        $resp = $this->createTaskUseCase->execute(
            $data['title'],
            $data['keywords'] ?? [],
            $data['is_done'] ?? false
        );

        return response()->json([
            'success' => $resp->success,
            'message' => $resp->message,
        ], $resp->statusCode);
    }


    /**
     * @OA\Patch(
     *     path="/api/tasks/{id}/toggle",
     *     summary="Toggle task status",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task status updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", nullable=true, example=null),
     *             @OA\Property(property="data", type="object", example={
     *                 "id": 1,
     *                 "title": "Buy groceries",
     *                 "is_done": true
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Task not found"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function toggle(int $id): JsonResponse
    {
        $resp = $this->toggleTaskStatusUseCase->execute($id);

        return response()->json([
            'success' => $resp->success,
            'message' => $resp->message,
            'data'    => $resp->data,
        ], $resp->statusCode);
    }
}
