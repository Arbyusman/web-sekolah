<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'activity_category_id' => 'sometimes|required|exists:activity_categories,id',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',
            'images' => 'sometimes|required|array',
            'images.*' => 'sometimes|required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
