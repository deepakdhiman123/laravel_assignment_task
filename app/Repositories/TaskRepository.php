<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    
    /**
     * Get tasks with pagination and status filter
     */
    public function all(array $filters = [])
    {
        return Task::where('user_id', auth()->guard('api')->id())
            ->when(!empty($filters['status']), fn($q) => $q->status($filters['status']))
            ->orderBy('created_at', 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Find a single task by ID
     */
    public function find(int $id): ?Task
    {
        return Task::with('user')
            ->where('id', $id)
            ->where('user_id', auth()->guard("api")->id())
            ->first();
    }

    /**
     * Create a new task
     */
    public function create(array $data): Task
    {
        return Task::create([
            'user_id'     => auth()->guard("api")->id(),
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? 'pending',
            'due_date'    => $data['due_date'] ?? null,
        ]);
    }

    /**
     * Update existing task
     */
    public function update(int $id, array $data): ?Task
    {
        $task = Task::where('id', $id)
            ->where('user_id', auth()->guard("api")->id())
            ->first();

        if (!$task) {
            return null;
        }

        $task->update([
            'title'       => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'status'      => $data['status'] ?? $task->status,
            'due_date'    => $data['due_date'] ?? $task->due_date,
        ]);

        return $task;
    }

    /**
     * Soft delete a task
     */
    public function delete(int $id): bool
    {
        $task = Task::where('id', $id)
            ->where('user_id', auth()->guard("api")->id())
            ->first();

        if (!$task) {
            return false;
        }

        return (bool) $task->delete();
    }

    /**
     * Get paginated tasks with filters
     */
    public function paginate(array $filters = [])
    {
        $query = Task::with('user')
            ->where('user_id', auth()->guard("api")->id());

        // 🔍 Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by due date
        if (!empty($filters['from_date'])) {
            $query->whereDate('due_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('due_date', '<=', $filters['to_date']);
        }

        $perPage = $filters['per_page'] ?? 10;

        return $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
