<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $search = request()->get('search', '');
        $status = request()->get('status', 'all');
        $sort = request()->get('sort', 'created_at');
        $order = request()->get('order', 'desc');
        $showDeleted = request()->get('show_deleted', false);

        $query = Course::with(['lessons', 'enrollments']);

        // Show deleted courses if requested
        if ($showDeleted) {
            $query->onlyTrashed();
        }

        // Search by name
        if ($search) {
            $query->searchByName($search);
        }

        // Filter by status
        if ($status && $status !== 'all') {
            $query->filterByStatus($status);
        }

        // Sort
        $query->orderBy($sort, $order);

        $courses = $query->paginate(10);
        $deletedCount = Course::onlyTrashed()->count();

        return view('courses.index', compact('courses', 'search', 'status', 'sort', 'order', 'showDeleted', 'deletedCount'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(CourseRequest $request)
    {
        $data = $request->validated();

        // Auto-generate slug
        $data['slug'] = Str::slug($data['name']) . '-' . time();

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $data['image'] = $path;
        }

        Course::create($data);

        return redirect()->route('courses.index')
            ->with('success', 'Khóa học được tạo thành công!');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['lessons', 'enrollments.student']);
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $data['image'] = $path;
        }

        $course->update($data);

        return redirect()->route('courses.index')
            ->with('success', 'Khóa học được cập nhật thành công!');
    }

    /**
     * Remove the specified course from storage (soft delete).
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Khóa học được xóa thành công!');
    }

    /**
     * Restore the specified course.
     */
    public function restore($id)
    {
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->restore();

        return redirect()->route('courses.index')
            ->with('success', 'Khóa học "' . $course->name . '" được khôi phục thành công!');
    }

    /**
     * Permanently delete the specified course.
     */
    public function forceDelete($id)
    {
        $course = Course::onlyTrashed()->findOrFail($id);
        $courseName = $course->name;
        $course->forceDelete();

        return redirect()->route('courses.index', ['show_deleted' => true])
            ->with('success', 'Khóa học "' . $courseName . '" được xóa vĩnh viễn!');
    }

    /**
     * Display dashboard with statistics.
     */
    public function dashboard()
    {
        $totalCourses = Course::count();
        $totalStudents = \App\Models\Student::count();
        $totalRevenue = Course::with('enrollments')->get()->sum(function ($course) {
            return $course->getTotalRevenue();
        });

        $topCourse = Course::with('enrollments')
            ->get()
            ->sortByDesc(function ($course) {
                return $course->enrollments()->count();
            })
            ->first();

        $recentCourses = Course::with(['lessons', 'enrollments'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('totalCourses', 'totalStudents', 'totalRevenue', 'topCourse', 'recentCourses'));
    }
}

