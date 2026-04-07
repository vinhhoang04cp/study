<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Http\Requests\EnrollmentRequest;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['course', 'student'])->paginate(15);
        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create()
    {
        $courses = Course::published()->get();
        return view('enrollments.create', compact('courses'));
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(EnrollmentRequest $request)
    {
        // Create or get student
        $student = Student::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name]
        );

        // Create enrollment
        $enrollment = Enrollment::create([
            'course_id' => $request->course_id,
            'student_id' => $student->id,
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Đăng ký khóa học thành công!');
    }

    /**
     * Display enrollments for a specific course.
     */
    public function byCourse(Course $course)
    {
        $enrollments = $course->enrollments()->with('student')->paginate(15);
        $totalStudents = $course->enrollments()->count();
        return view('enrollments.by-course', compact('course', 'enrollments', 'totalStudents'));
    }

    /**
     * Remove the specified enrollment from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $course = $enrollment->course;
        $enrollment->delete();

        return redirect()->back()
            ->with('success', 'Hủy đăng ký thành công!');
    }
}

