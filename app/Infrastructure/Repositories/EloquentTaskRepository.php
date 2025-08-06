<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Models\Task as EloquentTask;
use Throwable;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function getAll(): array
    {
        try {
            $tasks = EloquentTask::with('keywords')->get();

            return $tasks->map(function ($t) {
                $keywords = $t->keywords->map(function ($k) {
                    return [
                        'id' => $k->id,
                        'name' => $k->name,
                        'created_at' => $k->created_at->toDateTimeString(),
                        'updated_at' => $k->updated_at->toDateTimeString(),
                    ];
                })->toArray();

                return new Task(
                    $t->id,
                    $t->title,
                    $t->is_done,
                    $keywords,
                    $t->created_at->toDateTimeString(),
                    $t->updated_at->toDateTimeString()
                );
            })->toArray();
        } catch (Throwable $e) {
            throw new \RuntimeException("Failed to retrieve tasks: ", 0, $e);
        }
    }

    public function store(Task $task): Task
    {
        try {
            $eloquentTask = new EloquentTask();
            $eloquentTask->title = $task->title;
            $eloquentTask->is_done = $task->isDone;
            $eloquentTask->save();

            if (!empty($task->keywords)) {
                $keywordIds = $task->keywords;
                $eloquentTask->keywords()->sync($keywordIds);
            }

            return new Task(
                $eloquentTask->id,
                $eloquentTask->title,
                $eloquentTask->is_done,
                $eloquentTask->keywords->map(fn($k) => [
                    'id' => $k->id,
                    'name' => $k->name,
                    'created_at' => $k->created_at->toDateTimeString(),
                    'updated_at' => $k->updated_at->toDateTimeString(),
                ])->toArray(),
                $eloquentTask->created_at->toDateTimeString(),
                $eloquentTask->updated_at->toDateTimeString()
            );
        } catch (Throwable $e) {
            throw new \RuntimeException("Failed to store task", 0, $e);
        }
    }
    public function toggleStatus(int $id): ?Task
    {
        try {
            $task = EloquentTask::find($id);

            if (!$task) {
                return null;
            }

            $task->is_done = !$task->is_done;
            $task->save();

            return new Task(
                $task->id,
                $task->title,
                $task->is_done,
                $task->keywords->map(fn($k) => [
                    'id' => $k->id,
                    'name' => $k->name,
                    'created_at' => $k->created_at->toDateTimeString(),
                    'updated_at' => $k->updated_at->toDateTimeString(),
                ])->toArray(),
                $task->created_at->toDateTimeString(),
                $task->updated_at->toDateTimeString()
            );
        } catch (Throwable $e) {
            throw new \RuntimeException('Failed to toggle task status', 0, $e);
        }
    }
}
