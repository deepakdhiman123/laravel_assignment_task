<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BaseRequest;

class TaskFilterRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'   => 'nullable|in:pending,in-progress,completed',
            'due_date' => 'nullable|date_format:Y-m-d',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in'          => 'Status must be one of: pending, in-progress, completed.',
            'due_date.date_format'=> 'Due date must be in format YYYY-MM-DD.',
            'per_page.integer'   => 'Per page must be a number.',
            'per_page.min'       => 'Per page must be at least 1.',
            'per_page.max'       => 'Per page cannot exceed 100.',
        ];
    }
}
