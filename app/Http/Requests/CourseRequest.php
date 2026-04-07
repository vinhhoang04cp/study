<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CourseRequest - Xác thực dữ liệu từ form tạo/chỉnh sửa khóa học
 *
 * Class này xác thực:
 * - Tên khóa học (bắt buộc, text, tối đa 255 ký tự)
 * - Slug (tuỳ chọn, duy nhất, tối đa 255 ký tự)
 * - Giá (bắt buộc, số, tối thiểu 0.01)
 * - Mô tả (bắt buộc, text dài)
 * - Ảnh (tuỳ chọn, file hình ảnh, tối đa 2MB)
 * - Trạng thái (bắt buộc, phải là 'draft' hoặc 'published')
 *
 * Ghi chú:
 * - Slug tự động được tạo trong controller, nhưng có validation nếu được cung cấp
 * - Image là tuỳ chọn, nếu không cung cấp thì chỉ cập nhật các trường khác
 */
class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Quyết định xem người dùng có được phép tạo/cập nhật khóa học không
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
     * - name: Bắt buộc, string, tối đa 255 ký tự
     * - slug: Tuỳ chọn, string, duy nhất trong bảng courses (trừ bản ghi hiện tại)
     * - price: Bắt buộc, số, giá trị tối thiểu 0.01
     * - description: Bắt buộc, string dài
     * - image: Tuỳ chọn, phải là file hình ảnh, định dạng jpeg/png/jpg/gif, tối đa 2MB
     * - status: Bắt buộc, phải là 'draft' hoặc 'published'
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Tên khóa học: bắt buộc nhập, phải là text, tối đa 255 ký tự
            'name' => 'required|string|max:255',

            // Slug: tuỳ chọn, phải là text, duy nhất (loại trừ bản ghi hiện tại trong update)
            // $this->course->id sẽ được truyền trong route update
            'slug' => 'nullable|string|max:255|unique:courses,slug,' . ($this->course->id ?? 'NULL'),

            // Giá: bắt buộc, phải là số (integer hoặc decimal), tối thiểu 0.01
            'price' => 'required|numeric|min:0.01',

            // Mô tả: bắt buộc nhập, phải là text dài
            'description' => 'required|string',

            // Ảnh: tuỳ chọn, phải là file hình ảnh, chỉ chấp nhận jpeg/png/jpg/gif, tối đa 2MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Trạng thái: bắt buộc, chỉ chấp nhận giá trị 'draft' hoặc 'published'
            'status' => 'required|in:draft,published',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * Tùy chỉnh thông báo lỗi hiển thị cho người dùng (Tiếng Việt)
     * Nếu không định nghĩa ở đây, Laravel sẽ sử dụng thông báo mặc định (Tiếng Anh)
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // Thông báo cho trường 'name'
            'name.required' => 'Tên khóa học là bắt buộc',

            // Thông báo cho trường 'price'
            'price.required' => 'Giá là bắt buộc',
            'price.min' => 'Giá phải lớn hơn 0',

            // Thông báo cho trường 'description'
            'description.required' => 'Mô tả là bắt buộc',

            // Thông báo cho trường 'image'
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Chỉ chấp nhận các định dạng: jpeg, png, jpg, gif',

            // Thông báo cho trường 'status'
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
        ];
    }
}

