<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Str;

/**
 * CourseController - Xử lý tất cả các thao tác liên quan đến khóa học
 *
 * Controller này quản lý:
 * - Hiển thị danh sách khóa học
 * - Tạo, chỉnh sửa, xóa khóa học
 * - Khôi phục khóa học bị xóa (soft delete)
 * - Xóa vĩnh viễn khóa học (hard delete)
 * - Hiển thị dashboard thống kê
 */
class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     *
     * Lấy danh sách khóa học với các tính năng:
     * - Tìm kiếm theo tên khóa học
     * - Lọc theo trạng thái (draft/published)
     * - Sắp xếp theo các trường khác nhau
     * - Hiển thị khóa học đã xóa nếu được yêu cầu
     * - Phân trang 10 khóa học mỗi trang
     */
    public function index()
    {
        // Lấy các tham số từ URL query string với giá trị mặc định
        $search = request()->get('search', '');
        $status = request()->get('status', 'all');
        $sort = request()->get('sort', 'created_at');
        $order = request()->get('order', 'desc');
        $showDeleted = request()->get('show_deleted', false);

        // Eager load quan hệ lessons và enrollments để tránh N+1 query problem
        $query = Course::with(['lessons', 'enrollments']);

        // Nếu người dùng yêu cầu xem các khóa học đã xóa, chỉ lấy các khóa học bị soft delete
        if ($showDeleted) {
            $query->onlyTrashed();
        }

        // Tìm kiếm khóa học theo tên (không phân biệt hoa/thường)
        if ($search) {
            $query->searchByName($search);
        }

        // Lọc khóa học theo trạng thái
        if ($status && $status !== 'all') {
            $query->filterByStatus($status);
        }

        // Sắp xếp kết quả theo cột và thứ tự được chỉ định
        $query->orderBy($sort, $order);

        // Phân trang 10 khóa học mỗi trang
        $courses = $query->paginate(10);

        // Đếm tổng số khóa học đã bị xóa (soft deleted)
        $deletedCount = Course::onlyTrashed()->count();

        // Truyền dữ liệu sang view
        return view('courses.index', compact('courses', 'search', 'status', 'sort', 'order', 'showDeleted', 'deletedCount'));
    }

    /**
     * Show the form for creating a new course.
     *
     * Hiển thị form tạo khóa học mới
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created course in storage.
     *
     * Lưu khóa học mới vào database:
     * - Xác thực dữ liệu đầu vào bằng CourseRequest
     * - Tự động tạo slug từ tên khóa học
     * - Xử lý upload ảnh khóa học nếu có
     * - Tạo record mới trong database
     *
     * @param CourseRequest $request Dữ liệu đã được xác thực từ form
     */
    public function store(CourseRequest $request)
    {
        // Lấy dữ liệu đã được xác thực (qua CourseRequest)
        $data = $request->validated();

        // Tự động tạo slug từ tên khóa học + timestamp để đảm bảo duy nhất
        // Ví dụ: "Khóa PHP" -> "khoa-php-1712435200"
        $data['slug'] = Str::slug($data['name']) . '-' . time();

        // Nếu có file ảnh được upload
        if ($request->hasFile('image')) {
            // Lưu file vào thư mục 'courses' trong public disk
            // Đường dẫn sẽ được lưu trong database
            $path = $request->file('image')->store('courses', 'public');
            $data['image'] = $path;
        }

        // Tạo khóa học mới trong database với dữ liệu đã xác thực
        Course::create($data);

        // Chuyển hướng về danh sách khóa học với thông báo thành công
        return redirect()->route('courses.index')
            ->with('success', 'Khóa học được tạo thành công!');
    }

    /**
     * Display the specified course.
     *
     * Hiển thị chi tiết một khóa học cụ thể:
     * - Thông tin khóa học
     * - Danh sách bài học của khóa học
     * - Danh sách học viên đã đăng ký
     *
     * @param Course $course Khóa học được chuyển qua route binding
     */
    public function show(Course $course)
    {
        // Eager load quan hệ lessons và enrollments.student để tối ưu hiệu năng
        $course->load(['lessons', 'enrollments.student']);

        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     *
     * Hiển thị form chỉnh sửa khóa học
     *
     * @param Course $course Khóa học được chuyển qua route binding
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     *
     * Cập nhật thông tin khóa học:
     * - Xác thực dữ liệu bằng CourseRequest
     * - Xử lý cập nhật ảnh nếu có
     * - Cập nhật thông tin khóa học
     *
     * @param CourseRequest $request Dữ liệu đã xác thực từ form
     * @param Course $course Khóa học được chuyển qua route binding
     */
    public function update(CourseRequest $request, Course $course)
    {
        // Lấy dữ liệu đã được xác thực
        $data = $request->validated();

        // Nếu người dùng upload ảnh mới, lưu và cập nhật đường dẫn
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $data['image'] = $path;
        }

        // Cập nhật khóa học với dữ liệu mới
        $course->update($data);

        // Chuyển hướng về danh sách khóa học với thông báo thành công
        return redirect()->route('courses.index')
            ->with('success', 'Khóa học được cập nhật thành công!');
    }

    /**
     * Remove the specified course from storage (soft delete).
     *
     * Xóa mềm khóa học (soft delete):
     * - Không xóa khóa học khỏi database vĩnh viễn
     * - Chỉ đánh dấu là đã xóa bằng cập nhật cột deleted_at
     * - Có thể khôi phục sau này
     *
     * @param Course $course Khóa học được chuyển qua route binding
     */
    public function destroy(Course $course)
    {
        // Gọi delete() sẽ thực hiện soft delete (cập nhật deleted_at)
        // Vì Course model sử dụng trait SoftDeletes
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Khóa học được xóa thành công!');
    }

    /**
     * Restore the specified course.
     *
     * Khôi phục khóa học đã bị xóa mềm:
     * - Tìm khóa học trong trash (chỉ những bị xóa)
     * - Khôi phục bằng cách xóa giá trị deleted_at
     *
     * @param int $id ID của khóa học cần khôi phục
     */
    public function restore($id)
    {
        // Tìm khóa học trong trash (đã bị soft delete)
        $course = Course::onlyTrashed()->findOrFail($id);

        // Khôi phục khóa học bằng cách xóa giá trị deleted_at
        $course->restore();

        return redirect()->route('courses.index')
            ->with('success', 'Khóa học "' . $course->name . '" được khôi phục thành công!');
    }

    /**
     * Permanently delete the specified course.
     *
     * Xóa vĩnh viễn khóa học:
     * - Tìm khóa học trong trash
     * - Xóa hoàn toàn khỏi database (hard delete)
     * - Không thể khôi phục sau đó
     *
     * @param int $id ID của khóa học cần xóa vĩnh viễn
     */
    public function forceDelete($id)
    {
        // Tìm khóa học trong trash
        $course = Course::onlyTrashed()->findOrFail($id);

        // Lưu lại tên khóa học để hiển thị trong thông báo
        $courseName = $course->name;

        // Xóa vĩnh viễn khóa học khỏi database
        $course->forceDelete();

        return redirect()->route('courses.index', ['show_deleted' => true])
            ->with('success', 'Khóa học "' . $courseName . '" được xóa vĩnh viễn!');
    }

    /**
     * Display dashboard with statistics.
     *
     * Hiển thị dashboard với các thống kê:
     * - Tổng số khóa học
     * - Tổng số học viên
     * - Tổng doanh thu từ tất cả khóa học
     * - Khóa học có nhiều học viên đăng ký nhất
     * - 5 khóa học mới nhất
     */
    public function dashboard()
    {
        // Đếm tổng số khóa học (không bao gồm soft deleted)
        $totalCourses = Course::count();

        // Đếm tổng số học viên trong hệ thống
        $totalStudents = \App\Models\Student::count();

        // Tính tổng doanh thu = giá của mỗi khóa học × số học viên đăng ký
        $totalRevenue = Course::with('enrollments')->get()->sum(function ($course) {
            return $course->getTotalRevenue();
        });

        // Tìm khóa học có nhiều học viên đăng ký nhất (top course)
        $topCourse = Course::with('enrollments')
            ->get()
            ->sortByDesc(function ($course) {
                return $course->enrollments()->count();
            })
            ->first();

        // Lấy 5 khóa học mới nhất (sắp xếp theo created_at giảm dần)
        $recentCourses = Course::with(['lessons', 'enrollments'])
            ->latest()
            ->limit(5)
            ->get();

        // Truyền dữ liệu sang dashboard view
        return view('dashboard', compact('totalCourses', 'totalStudents', 'totalRevenue', 'topCourse', 'recentCourses'));
    }
}

