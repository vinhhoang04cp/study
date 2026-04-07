<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * Get the courses that the student is enrolled in (many-to-many relationship).
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }

    /**
     * Get the enrollments for the student.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}

