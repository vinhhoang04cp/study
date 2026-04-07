<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Course Model - Đại diện cho một khóa học
 *
 * Mô tả:
 * - Khóa học có thể chứa nhiều bài học (Lesson)
 * - Khóa học có thể có nhiều đăng ký (Enrollment)
 * - Học viên đăng ký khóa học thông qua Enrollment (Many-to-Many)
 * - Sử dụng SoftDeletes để có thể khôi phục khóa học bị xóa
 *
 * Attributes:
 * - id: ID duy nhất của khóa học
 * - name: Tên khóa học
 * - slug: URL-friendly version của tên (dùng trong URL)
 * - price: Giá khóa học (decimal)
 * - description: Mô tả chi tiết về khóa học
 * - image: Đường dẫn ảnh khóa học
 * - status: Trạng thái ('draft' hoặc 'published')
 * - created_at, updated_at: Timestamps
 * - deleted_at: Timestamp của soft delete
 */
class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Các trường có thể được gán hàng loạt (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'image',
        'status',
    ];

    /**
     * Các trường được coi là datetime (để Eloquent tự động chuyển đổi)
     */
    protected $dates = ['deleted_at'];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the lessons for the course.
     *
     * Quan hệ 1-N: Một khóa học có nhiều bài học
     * Một bài học chỉ thuộc về một khóa học
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the enrollments for the course.
     *
     * Quan hệ 1-N: Một khóa học có nhiều đăng ký
     * Một đăng ký chỉ liên quan đến một khóa học
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the students enrolled in the course (many-to-many relationship).
     *
     * Quan hệ N-N: Một khóa học có nhiều học viên, một học viên có thể đăng ký nhiều khóa học
     * Thông qua bảng trung gian 'enrollments'
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments');
    }

    // ==================== SCOPES (Query Builders) ====================

    /**
     * Scope to get only published courses.
     *
     * Lọc chỉ lấy các khóa học đã publish (status = 'published')
     *
     * Cách dùng: Course::published()->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get only draft courses.
     *
     * Lọc chỉ lấy các khóa học là draft (status = 'draft')
     *
     * Cách dùng: Course::draft()->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to filter courses by price range.
     *
     * Lọc khóa học theo giá trong một khoảng cụ thể
     *
     * Cách dùng: Course::priceBetween(100, 500)->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $minPrice Giá tối thiểu
     * @param float $maxPrice Giá tối đa
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriceBetween($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope to search courses by name.
     *
     * Tìm kiếm khóa học theo tên (không phân biệt hoa/thường)
     * Sử dụng LIKE để tìm các khóa học có tên chứa chuỗi tìm kiếm
     *
     * Cách dùng: Course::searchByName('PHP')->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name Chuỗi tìm kiếm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Scope to filter by status.
     *
     * Lọc khóa học theo trạng thái
     * Nếu status là 'all', sẽ trả về tất cả khóa học
     *
     * Cách dùng: Course::filterByStatus('published')->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status Trạng thái ('draft', 'published', hoặc 'all')
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    // ==================== CUSTOM METHODS ====================

    /**
     * Get course with lessons and enrollments count (eager loading).
     *
     * Lấy số lượng học viên đã đăng ký khóa học này
     *
     * @return int Số lượng đăng ký
     */
    public function getEnrollmentCount()
    {
        return $this->enrollments()->count();
    }

    /**
     * Get total revenue from enrollments.
     *
     * Tính tổng doanh thu từ khóa học này
     * Công thức: Giá khóa học × Số lượng đăng ký
     *
     * Ví dụ: Khóa học giá 500k, có 10 học viên → doanh thu = 5,000,000
     *
     * @return float Tổng doanh thu
     */
    public function getTotalRevenue()
    {
        return $this->price * $this->enrollments()->count();
    }
}

