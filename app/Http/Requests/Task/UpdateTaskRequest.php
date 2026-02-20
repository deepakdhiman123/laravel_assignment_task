<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BaseRequest;

class UpdateTaskRequest extends BaseRequest
{
    public function authorize(): bool
    {
        // Allow all authenticated users
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
            'status'      => 'sometimes|nullable|in:pending,in-progress,completed',
            'due_date'    => 'sometimes|nullable|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required',
            'title.string'   => 'Title must be a string',
            'status.in'      => 'Status must be one of pending, in-progress, or completed',
            'due_date.date'  => 'Due date must be a valid date',
            'due_date.after_or_equal' => 'Due date cannot be in the past',
        ];
    }
}