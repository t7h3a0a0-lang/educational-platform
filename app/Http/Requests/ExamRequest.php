<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole(['admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'exam_date' => 'required|date|after_or_equal:today',
            'max_points' => 'required|numeric|min:1|max:1000',
            'type' => 'required|string|in:quiz,midterm,final,assignment',
        ];
    }
}
