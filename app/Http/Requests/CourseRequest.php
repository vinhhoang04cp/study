<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:courses,slug,' . ($this->course->id ?? 'NULL'),
            'price' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên khóa học là bắt buộc',
            'price.required' => 'Giá là bắt buộc',
            'price.min' => 'Giá phải lớn hơn 0',
            'description.required' => 'Mô tả là bắt buộc',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Chỉ chấp nhận các định dạng: jpeg, png, jpg, gif',
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
        ];
    }
}

