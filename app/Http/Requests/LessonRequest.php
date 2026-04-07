<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'order' => 'required|integer|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề bài học là bắt buộc',
            'content.required' => 'Nội dung bài học là bắt buộc',
            'video_url.url' => 'URL video không hợp lệ',
            'order.required' => 'Thứ tự là bắt buộc',
            'order.integer' => 'Thứ tự phải là số nguyên',
        ];
    }
}

