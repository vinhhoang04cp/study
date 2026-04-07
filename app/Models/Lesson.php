<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Lesson Model - Đại diện cho một bài học trong khóa học
 *
 * Mô tả:
 * - Bài học thuộc về một khóa học cụ thể
 * - Mỗi khóa học có thể có nhiều bài học
 * - Bài học được sắp xếp theo thứ tự (order field)
 *
 * Attributes:
 * - id: ID duy nhất của bài học
 * - course_id: ID của khóa học mà bài học này thuộc về (Foreign Key)
 * - title: Tiêu đề của bài học
 * - content: Nội dung chi tiết của bài học
 * - video_url: URL của video bài học (tuỳ chọn)
 * - order: Thứ tự bài học trong khóa học (dùng để sắp xếp)
 * - created_at, updated_at: Timestamps
 */
class Lesson extends Model
{
    use HasFactory;

    /**
     * Các trường có thể được gán hàng loạt (Mass Assignment)
     */
    protected $fillable = [
        'course_id',
        'title',
        'content',
        'video_url',
        'order',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the course that owns the lesson.
     *
     * Quan hệ N-1: Một bài học thuộc về một khóa học
     * Một khóa học có nhiều bài học
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

