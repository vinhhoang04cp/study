<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * LessonRequest - Xác thực dữ liệu từ form tạo/chỉnh sửa bài học
 *
 * Class này xác thực:
 * - Tiêu đề bài học (bắt buộc, text, tối đa 255 ký tự)
 * - Nội dung (bắt buộc, text dài)
 * - URL video (tuỳ chọn, phải là URL hợp lệ)
 * - Thứ tự (bắt buộc, số nguyên, tối thiểu 0)
 *
 * Ghi chú:
 * - course_id được gán trong controller, không cần validate ở đây
 */
class LessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Quyết định xem người dùng có được phép tạo/cập nhật bài học không
     * Hiện tại cho phép tất cả người dùng (return true)
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Định nghĩa các quy tắc xác thực cho các trường dữ liệu
     *
     * Quy tắc:
     * - title: Bắt buộc, string, tối đa 255 ký tự
     * - content: Bắt buộc, string dài
     * - video_url: Tuỳ chọn, phải là URL hợp lệ
     * - order: Bắt buộc, số nguyên, tối thiểu 0
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Tiêu đề: bắt buộc nhập, phải là text, tối đa 255 ký tự
            'title' => 'required|string|max:255',

            // Nội dung: bắt buộc nhập, phải là text dài
            'content' => 'required|string',

            // URL video: tuỳ chọn, nếu có thì phải là URL hợp lệ
            'video_url' => 'nullable|url',

            // Thứ tự: bắt buộc, phải là số nguyên, tối thiểu 0
            'order' => 'required|integer|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * Tùy chỉnh thông báo lỗi hiển thị cho người dùng (Tiếng Việt)
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // Thông báo cho trường 'title'
            'title.required' => 'Tiêu đề bài học là bắt buộc',

            // Thông báo cho trường 'content'
            'content.required' => 'Nội dung bài học là bắt buộc',

            // Thông báo cho trường 'video_url'
            'video_url.url' => 'URL video không hợp lệ',

            // Thông báo cho trường 'order'
            'order.required' => 'Thứ tự là bắt buộc',
            'order.integer' => 'Thứ tự phải là số nguyên',
        ];
    }
}

