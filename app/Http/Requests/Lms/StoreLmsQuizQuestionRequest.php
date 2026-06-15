<?php

namespace App\Http\Requests\Lms;

use Illuminate\Foundation\Http\FormRequest;

class StoreLmsQuizQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'nullable|array',
            'correct_answer' => 'required|string',
            'score_weight' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer',
        ];
    }
}
