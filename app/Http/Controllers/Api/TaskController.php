<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends BaseController
{

     public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    /**
     * Create a new task
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskRepository->create($request->validated());
            return $this->success($task, 'Task created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create task: '.$e->getMessage(), 500);
        }
    }

    /**
     * View all tasks
     */
     public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskRepository->all($request->only('status', 'per_page'));

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully.',
            'data'    => $tasks->items(),
            'meta'    => [
                'current_page' => $tasks->currentPage(),
                'last_page'    => $tasks->lastPage(),
                'per_page'     => $tasks->perPage(),
                'total'        => $tasks->total(),
                'from'         => $tasks->firstItem(),
                'to'           => $tasks->lastItem(),
            ],
        ]);
    }

    /**
     * View single task
     */
    public function show($id): JsonResponse
    {
        try {
            $task = $this->taskRepository->find($id);
            if (!$task) {
                return $this->error('Task not found', 404);
            }
            return $this->success($task, 'Task retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to fetch task: '.$e->getMessage(), 500);
        }
    }

    /**
     * Update task
     */
    public function update(UpdateTaskRequest $request, $id): JsonResponse
    {
        try {
            $task = $this->taskRepository->update($id, $request->validated());
            if (!$task) {
                return $this->error('Task not found', 404);
            }
            return $this->success($task, 'Task updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update task: '.$e->getMessage(), 500);
        }
    }

    /**
     * Delete task (soft delete)
     */
    public function destroy($id): JsonResponse
    {
        try {
            $deleted = $this->taskRepository->delete($id);
            if (!$deleted) {
                return $this->error('Task not found', 404);
            }
            return $this->success(null, 'Task deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete task: '.$e->getMessage(), 500);
        }
    }
}