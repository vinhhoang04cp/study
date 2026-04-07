<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Student Model - Đại diện cho một học viên
 *
 * Mô tả:
 * - Học viên có thể đăng ký nhiều khóa học
 * - Khóa học có thể có nhiều học viên
 * - Quan hệ N-N thông qua bảng trung gian 'enrollments'
 *
 * Attributes:
 * - id: ID duy nhất của học viên
 * - name: Tên học viên
 * - email: Email học viên (dùng để xác định học viên duy nhất)
 * - created_at, updated_at: Timestamps
 */
class Student extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán hàng loạt (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'email',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the courses that the student is enrolled in (many-to-many relationship).
     *
     * Quan hệ N-N: Một học viên có thể đăng ký nhiều khóa học
     * Một khóa học có thể có nhiều học viên
     * Thông qua bảng trung gian 'enrollments'
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }

    /**
     * Get the enrollments for the student.
     *
     * Quan hệ 1-N: Một học viên có nhiều bản ghi đăng ký
     * Mỗi bản ghi đăng ký đại diện cho việc đăng ký một khóa học
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}

