<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Enrollment Model - Đại diện cho một bản ghi đăng ký khóa học
 *
 * Mô tả:
 * - Bảng trung gian (pivot table) liên kết Course và Student
 * - Đại diện cho việc một học viên đăng ký một khóa học
 * - Mỗi đăng ký tương ứng với một cặp (course_id, student_id)
 *
 * Attributes:
 * - id: ID duy nhất của bản ghi đăng ký
 * - course_id: ID của khóa học (Foreign Key)
 * - student_id: ID của học viên (Foreign Key)
 * - created_at, updated_at: Timestamps
 */
class Enrollment extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán hàng loạt (Mass Assignment)
     */
    protected $fillable = [
        'course_id',
        'student_id',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the course associated with the enrollment.
     *
     * Quan hệ N-1: Một đăng ký liên quan đến một khóa học
     * Một khóa học có thể có nhiều đăng ký
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the student associated with the enrollment.
     *
     * Quan hệ N-1: Một đăng ký liên quan đến một học viên
     * Một học viên có thể có nhiều đăng ký
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

