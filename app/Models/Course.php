<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'image',
        'status',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get the lessons for the course.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the students enrolled in the course (many-to-many relationship).
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments');
    }

    /**
     * Scope to get only published courses.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get only draft courses.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to filter courses by price range.
     */
    public function scopePriceBetween($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope to search courses by name.
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Scope to filter by status.
     */
    public function scopeFilterByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Get course with lessons and enrollments count (eager loading).
     */
    public function getEnrollmentCount()
    {
        return $this->enrollments()->count();
    }

    /**
     * Get total revenue from enrollments.
     */
    public function getTotalRevenue()
    {
        return $this->price * $this->enrollments()->count();
    }
}

