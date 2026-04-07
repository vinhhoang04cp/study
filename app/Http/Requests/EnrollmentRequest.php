<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * EnrollmentRequest - Xác thực dữ liệu từ form đăng ký khóa học
 *
 * Class này xác thực:
 * - Khóa học (bắt buộc, phải tồn tại trong bảng courses)
 * - Tên học viên (bắt buộc, text, tối đa 255 ký tự)
 * - Email (bắt buộc, email hợp lệ, duy nhất trong bảng students)
 *
 * Ghi chú:
 * - Nếu email đã tồn tại, dựa vào logic trong EnrollmentController,
 *   nó sẽ lấy học viên hiện có thay vì tạo mới
 * - Điều này có nghĩa 1 học viên có thể đăng ký nhiều khóa học
 */
class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Quyết định xem người dùng có được phép tạo đăng ký không
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
     * - course_id: Bắt buộc, phải tồn tại trong bảng courses (kiểm tra referential integrity)
     * - name: Bắt buộc, string, tối đa 255 ký tự
     * - email: Bắt buộc, email hợp lệ, duy nhất trong bảng students
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Khóa học: bắt buộc chọn, phải tồn tại trong bảng courses
            // exists:courses,id kiểm tra xem ID có tồn tại trong bảng courses không
            'course_id' => 'required|exists:courses,id',

            // Tên học viên: bắt buộc nhập, phải là text, tối đa 255 ký tự
            'name' => 'required|string|max:255',

            // Email: bắt buộc nhập, phải là email hợp lệ, duy nhất trong bảng students
            // unique:students,email kiểm tra xem email đã tồn tại chưa
            'email' => 'required|email|unique:students,email',
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
            // Thông báo cho trường 'course_id'
            'course_id.required' => 'Khóa học là bắt buộc',
            'course_id.exists' => 'Khóa học không tồn tại',

            // Thông báo cho trường 'name'
            'name.required' => 'Tên học viên là bắt buộc',

            // Thông báo cho trường 'email'
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được đăng ký',
        ];
    }
}

