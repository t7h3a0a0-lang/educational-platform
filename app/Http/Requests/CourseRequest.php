<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole(['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'course_code' => 'required|string|unique:courses,course_code,' . ($this->course ? $this->course->id : 'NULL') . '|max:20',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'credits' => 'required|integer|min:1|max:10',
            'department' => 'nullable|string|max:50',
            'max_students' => 'required|integer|min:5|max:200',
            'teacher_id' => 'nullable|exists:teachers,id',
        ];
    }
}
