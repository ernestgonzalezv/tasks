<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Models\Task as EloquentTask;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function getAll(): array
    {
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
    }

    public function store(Task $task): Task
    {
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
    }

    public function toggleStatus(int $id): Task
    {
        $task = EloquentTask::findOrFail($id);
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
    }
}
