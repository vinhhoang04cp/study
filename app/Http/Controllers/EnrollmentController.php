<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Http\Requests\EnrollmentRequest;

/**
 * EnrollmentController - Xử lý tất cả các thao tác liên quan đến đăng ký khóa học
 *
 * Controller này quản lý:
 * - Danh sách tất cả đăng ký (enrollments)
 * - Tạo đăng ký mới (học viên đăng ký khóa học)
 * - Xóa đăng ký (hủy đăng ký khóa học)
 * - Xem đăng ký theo khóa học cụ thể
 */
class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments.
     *
     * Hiển thị danh sách tất cả đăng ký khóa học:
     * - Eager load thông tin course và student
     * - Phân trang 15 bản ghi mỗi trang
     */
    public function index()
    {
        // Lấy danh sách đăng ký, eager load quan hệ course và student
        // Để tránh N+1 query problem
        $enrollments = Enrollment::with(['course', 'student'])->paginate(15);

        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     *
     * Hiển thị form đăng ký khóa học:
     * - Lấy danh sách các khóa học đã publish
     * - Truyền vào form để người dùng chọn
     */
    public function create()
    {
        // Lấy tất cả khóa học có status = 'published'
        $courses = Course::published()->get();

        return view('enrollments.create', compact('courses'));
    }

    /**
     * Store a newly created enrollment in storage.
     *
     * Lưu đăng ký khóa học mới:
     * - Xác thực dữ liệu bằng EnrollmentRequest
     * - Tạo hoặc lấy học viên hiện có dựa trên email
     * - Tạo bản ghi đăng ký liên kết course và student
     *
     * @param EnrollmentRequest $request Dữ liệu đã xác thực từ form
     */
    public function store(EnrollmentRequest $request)
    {
        // Tạo học viên mới HOẶC lấy học viên hiện có dựa trên email
        // Nếu email đã tồn tại, sẽ lấy record đó
        // Nếu email chưa tồn tại, sẽ tạo record mới với name
        $student = Student::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name]
        );

        // Tạo bản ghi đăng ký, liên kết course và student
        $enrollment = Enrollment::create([
            'course_id' => $request->course_id,
            'student_id' => $student->id,
        ]);

        // Chuyển hướng về danh sách đăng ký với thông báo thành công
        return redirect()->route('enrollments.index')
            ->with('success', 'Đăng ký khóa học thành công!');
    }

    /**
     * Display enrollments for a specific course.
     *
     * Hiển thị danh sách học viên đã đăng ký cho một khóa học cụ thể:
     * - Lấy tất cả đăng ký của khóa học
     * - Eager load thông tin student
     * - Đếm tổng số học viên
     * - Phân trang 15 bản ghi mỗi trang
     *
     * @param Course $course Khóa học được chuyển qua route binding
     */
    public function byCourse(Course $course)
    {
        // Lấy tất cả đăng ký của khóa học, eager load student
        $enrollments = $course->enrollments()->with('student')->paginate(15);

        // Đếm tổng số học viên đã đăng ký khóa học này
        $totalStudents = $course->enrollments()->count();

        return view('enrollments.by-course', compact('course', 'enrollments', 'totalStudents'));
    }

    /**
     * Remove the specified enrollment from storage.
     *
     * Xóa đăng ký khóa học (hủy đăng ký):
     * - Xóa bản ghi đăng ký khỏi database
     *
     * @param Enrollment $enrollment Đăng ký được chuyển qua route binding
     */
    public function destroy(Enrollment $enrollment)
    {
        // Lấy thông tin course trước khi xóa để chuyển hướng
        $course = $enrollment->course;

        // Xóa bản ghi đăng ký
        $enrollment->delete();

        // Chuyển hướng quay lại trang trước đó
        return redirect()->back()
            ->with('success', 'Hủy đăng ký thành công!');
    }
}

