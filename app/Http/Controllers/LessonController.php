<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Http\Requests\LessonRequest;

/**
 * LessonController - Xử lý tất cả các thao tác liên quan đến bài học
 *
 * Controller này quản lý bài học lồng nhau (nested) dưới từng khóa học:
 * - Hiển thị danh sách bài học của một khóa học
 * - Tạo, chỉnh sửa, xóa bài học
 *
 * Lưu ý: Tất cả các route đều là nested dưới course
 * VD: /courses/{course}/lessons
 */
class LessonController extends Controller
{
    /**
     * Display a listing of lessons for a course.
     *
     * Hiển thị danh sách tất cả bài học của một khóa học
     * Sắp xếp theo thứ tự (order field)
     *
     * @param Course $course Khóa học được chuyển qua route binding
     */
    public function index(Course $course)
    {
        // Lấy tất cả bài học của khóa học, sắp xếp theo order tăng dần
        $lessons = $course->lessons()->orderBy('order')->get();

        return view('lessons.index', compact('course', 'lessons'));
    }

    /**
     * Show the form for creating a new lesson.
     *
     * Hiển thị form tạo bài học mới cho khóa học cụ thể
     *
     * @param Course $course Khóa học được chuyển qua route binding
     */
    public function create(Course $course)
    {
        return view('lessons.create', compact('course'));
    }

    /**
     * Store a newly created lesson in storage.
     *
     * Lưu bài học mới vào database:
     * - Xác thực dữ liệu bằng LessonRequest
     * - Gán course_id cho bài học
     * - Tạo record mới
     *
     * @param LessonRequest $request Dữ liệu đã xác thực từ form
     * @param Course $course Khóa học được chuyển qua route binding
     */
    public function store(LessonRequest $request, Course $course)
    {
        // Lấy dữ liệu đã xác thực
        $data = $request->validated();

        // Gán khóa học hiện tại cho bài học
        $data['course_id'] = $course->id;

        // Tạo bài học mới trong database
        Lesson::create($data);

        // Chuyển hướng về danh sách bài học của khóa học này
        return redirect()->route('courses.lessons.index', $course)
            ->with('success', 'Bài học được tạo thành công!');
    }

    /**
     * Display the specified lesson.
     *
     * Hiển thị chi tiết một bài học cụ thể
     *
     * @param Course $course Khóa học được chuyển qua route binding
     * @param Lesson $lesson Bài học được chuyển qua route binding
     */
    public function show(Course $course, Lesson $lesson)
    {
        return view('lessons.show', compact('course', 'lesson'));
    }

    /**
     * Show the form for editing the specified lesson.
     *
     * Hiển thị form chỉnh sửa bài học
     *
     * @param Course $course Khóa học được chuyển qua route binding
     * @param Lesson $lesson Bài học được chuyển qua route binding
     */
    public function edit(Course $course, Lesson $lesson)
    {
        return view('lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified lesson in storage.
     *
     * Cập nhật thông tin bài học:
     * - Xác thực dữ liệu bằng LessonRequest
     * - Cập nhật record trong database
     *
     * @param LessonRequest $request Dữ liệu đã xác thực từ form
     * @param Course $course Khóa học được chuyển qua route binding
     * @param Lesson $lesson Bài học được chuyển qua route binding
     */
    public function update(LessonRequest $request, Course $course, Lesson $lesson)
    {
        // Lấy dữ liệu đã xác thực
        $data = $request->validated();

        // Cập nhật bài học với dữ liệu mới
        $lesson->update($data);

        // Chuyển hướng về danh sách bài học với thông báo thành công
        return redirect()->route('courses.lessons.index', $course)
            ->with('success', 'Bài học được cập nhật thành công!');
    }

    /**
     * Remove the specified lesson from storage.
     *
     * Xóa bài học khỏi database
     *
     * @param Course $course Khóa học được chuyển qua route binding
     * @param Lesson $lesson Bài học được chuyển qua route binding
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        // Xóa bài học từ database
        $lesson->delete();

        // Chuyển hướng về danh sách bài học với thông báo thành công
        return redirect()->route('courses.lessons.index', $course)
            ->with('success', 'Bài học được xóa thành công!');
    }
}

