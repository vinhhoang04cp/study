<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Http\Requests\LessonRequest;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons for a course.
     */
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('order')->get();
        return view('lessons.index', compact('course', 'lessons'));
    }

    /**
     * Show the form for creating a new lesson.
     */
    public function create(Course $course)
    {
        return view('lessons.create', compact('course'));
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function store(LessonRequest $request, Course $course)
    {
        $data = $request->validated();
        $data['course_id'] = $course->id;

        Lesson::create($data);

        return redirect()->route('courses.lessons.index', $course)
            ->with('success', 'Bài học được tạo thành công!');
    }

    /**
     * Display the specified lesson.
     */
    public function show(Course $course, Lesson $lesson)
    {
        return view('lessons.show', compact('course', 'lesson'));
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        return view('lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified lesson in storage.
     */
    public function update(LessonRequest $request, Course $course, Lesson $lesson)
    {
        $data = $request->validated();
        $lesson->update($data);

        return redirect()->route('courses.lessons.index', $course)
            ->with('success', 'Bài học được cập nhật thành công!');
    }

    /**
     * Remove the specified lesson from storage.
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('courses.lessons.index', $course)
            ->with('success', 'Bài học được xóa thành công!');
    }
}

