# Hướng Dẫn Đọc Code - Course Management System

## 📖 Cách đọc code một cách hiệu quả

### 1. Bắt đầu từ Models (Dữ liệu)

**Tại sao Models trước?** Models đại diện cho cấu trúc dữ liệu, nếu hiểu models thì dễ hiểu business logic hơn.

#### Thứ tự đọc:
1. **Course.php** - Đây là "trái tim" của ứng dụng
   - Có 4 relationships (lessons, enrollments, students)
   - Có 5 scopes để tìm kiếm/lọc
   - Có 2 methods tính toán (getEnrollmentCount, getTotalRevenue)

2. **Lesson.php** - Bài học (con của Course)
   - Chỉ 1 relationship đơn giản

3. **Student.php** & **Enrollment.php** - Đăng ký (liên kết Course & Student)
   - Enrollment là "cầu nối" giữa Course và Student

### 2. Đọc Controllers (Logic xử lý)

**Thứ tự:**
1. **CourseController** - Quản lý khóa học chính
2. **LessonController** - Quản lý bài học (lồng nhau dưới Course)
3. **EnrollmentController** - Quản lý đăng ký

**Mẫu CRUD:**
```
index()   → danh sách
create()  → form tạo mới
store()   → lưu dữ liệu
show()    → chi tiết
edit()    → form chỉnh sửa
update()  → lưu cập nhật
destroy() → xóa
```

### 3. Đọc Requests (Xác thực dữ liệu)

**Thứ tự:**
1. **CourseRequest** - Xác thực form tạo/sửa khóa học
2. **LessonRequest** - Xác thực form tạo/sửa bài học
3. **EnrollmentRequest** - Xác thực form đăng ký

---

## 🔗 Relationships - Mối quan hệ dữ liệu

### 1. One-to-Many (1-N) - Một-Nhiều

**Course → Lesson**
```php
// Course.php
public function lessons() {
    return $this->hasMany(Lesson::class);
}

// Lesson.php
public function course() {
    return $this->belongsTo(Course::class);
}
```

Ý nghĩa: 1 khóa học có nhiều bài học, 1 bài học chỉ thuộc 1 khóa học

Cách dùng:
```php
$course = Course::find(1);
$lessons = $course->lessons; // Lấy tất cả bài học

$lesson = Lesson::find(1);
$course = $lesson->course; // Lấy khóa học chứa bài học này
```

### 2. Many-to-Many (N-N) - Nhiều-Nhiều

**Course ↔ Student (qua Enrollment)**
```php
// Course.php
public function students() {
    return $this->belongsToMany(Student::class, 'enrollments');
}

// Student.php
public function courses() {
    return $this->belongsToMany(Course::class, 'enrollments');
}

// Enrollment.php (Pivot table)
public function course() {
    return $this->belongsTo(Course::class);
}

public function student() {
    return $this->belongsTo(Student::class);
}
```

Ý nghĩa: 1 khóa học có nhiều học viên, 1 học viên có thể đăng ký nhiều khóa học

Cách dùng:
```php
// Lấy tất cả học viên của khóa học
$course = Course::find(1);
$students = $course->students;

// Lấy tất cả khóa học của học viên
$student = Student::find(1);
$courses = $student->courses;

// Lấy bản ghi đăng ký (với thông tin thêm)
$enrollments = Enrollment::with(['course', 'student'])->get();
```

---

## 🔍 Scopes - Tìm kiếm & Lọc

Scopes là các method tiền định để tìm kiếm/lọc dữ liệu

### Course Scopes:

```php
// 1. Lấy chỉ khóa học published
Course::published()->get();
// Tương đương: Course::where('status', 'published')->get();

// 2. Lấy chỉ khóa học draft
Course::draft()->get();

// 3. Lọc theo giá (từ 100 đến 500)
Course::priceBetween(100, 500)->get();

// 4. Tìm kiếm theo tên
Course::searchByName('PHP')->get();
// Tìm tất cả khóa học có tên chứa từ "PHP"

// 5. Lọc theo trạng thái
Course::filterByStatus('published')->get();
Course::filterByStatus('all')->get(); // Tất cả

// 6. Kết hợp nhiều scopes (method chaining)
Course::published()
    ->searchByName('PHP')
    ->priceBetween(100, 500)
    ->orderBy('created_at', 'desc')
    ->paginate(10);
```

---

## 💾 Soft Deletes - Xóa mềm

**Khác biệt:**
- **Soft Delete**: Đánh dấu là đã xóa, không xóa khỏi database
- **Hard Delete**: Xóa hoàn toàn khỏi database

### Course hỗ trợ Soft Delete:

```php
// Xóa mềm
$course->delete(); // Cập nhật deleted_at = now()

// Khôi phục
$course->restore(); // Cập nhật deleted_at = NULL

// Xóa vĩnh viễn
$course->forceDelete(); // Xóa khỏi database

// Lấy chỉ khóa học đã xóa
Course::onlyTrashed()->get();

// Lấy tất cả (kể cả đã xóa)
Course::withTrashed()->get();
```

---

## 🔐 Validation - Xác thực dữ liệu

### Ví dụ: CourseRequest

```php
// Rules
'name' => 'required|string|max:255'
// Ý nghĩa: Bắt buộc nhập, phải là text, tối đa 255 ký tự

'price' => 'required|numeric|min:0.01'
// Ý nghĩa: Bắt buộc nhập, phải là số, tối thiểu 0.01

'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
// Ý nghĩa: Tuỳ chọn, phải là file hình ảnh, định dạng jpeg/png/jpg/gif, tối đa 2MB

'status' => 'required|in:draft,published'
// Ý nghĩa: Bắt buộc nhập, chỉ chấp nhận 'draft' hoặc 'published'

'email' => 'required|email|unique:students,email'
// Ý nghĩa: Bắt buộc, email hợp lệ, duy nhất trong bảng students
```

### Cách dùng trong Controller:

```php
public function store(CourseRequest $request) {
    // Nếu validation thất bại, Laravel tự động chuyển hướng với lỗi
    // Nếu thành công, lấy dữ liệu đã xác thực
    $data = $request->validated();
    
    // Tạo khóa học mới
    Course::create($data);
}
```

---

## 🎯 Flow - Luồng xử lý

### Tạo Khóa Học (Course):

```
1. User truy cập GET /courses/create
   → CourseController@create()
   → Trả về view form

2. User submit form POST /courses
   → CourseController@store(CourseRequest $request)
   → CourseRequest xác thực dữ liệu
   → Auto-generate slug từ tên + timestamp
   → Upload ảnh (nếu có)
   → Course::create($data)
   → Redirect /courses với thông báo "Thành công!"

3. Validation thất bại
   → Redirect lại form với lỗi
   → Người dùng sửa và gửi lại
```

### Tạo Bài Học (Lesson):

```
1. User truy cập GET /courses/{course}/lessons/create
   → LessonController@create($course)
   → Trả về view form (đã biết khóa học nào)

2. User submit form POST /courses/{course}/lessons
   → LessonController@store(LessonRequest $request, Course $course)
   → LessonRequest xác thực dữ liệu
   → Gán course_id = $course->id
   → Lesson::create($data)
   → Redirect /courses/{course}/lessons
```

### Đăng Ký Khóa Học (Enrollment):

```
1. User truy cập GET /enrollments/create
   → EnrollmentController@create()
   → Lấy danh sách khóa học published
   → Trả về view form

2. User submit form POST /enrollments
   → EnrollmentController@store(EnrollmentRequest $request)
   → EnrollmentRequest xác thực dữ liệu
   → Kiểm tra email trong bảng students
      - Nếu tồn tại → lấy student đó
      - Nếu chưa → tạo student mới
   → Enrollment::create([course_id, student_id])
   → Redirect /enrollments
```

---

## 📊 Dashboard - Thống kê:

```php
public function dashboard() {
    $totalCourses = Course::count();
    // Tổng số khóa học

    $totalStudents = Student::count();
    // Tổng số học viên

    $totalRevenue = Course::with('enrollments')->get()->sum(function ($course) {
        return $course->getTotalRevenue();
    });
    // Tổng doanh thu từ tất cả khóa học
    // VD: Khóa PHP (500k) × 10 học viên = 5M
    //     Khóa JS (300k) × 15 học viên = 4.5M
    //     Tổng = 9.5M

    $topCourse = Course::with('enrollments')
        ->get()
        ->sortByDesc(function ($course) {
            return $course->enrollments()->count();
        })
        ->first();
    // Khóa học có nhiều học viên đăng ký nhất

    $recentCourses = Course::latest()->limit(5)->get();
    // 5 khóa học mới nhất
}
```

---

## 🎓 Ví dụ Thực Tế

### Ví dụ 1: Lấy tất cả bài học của khóa học "PHP Cơ bản"

```php
$course = Course::searchByName('PHP Cơ bản')->first();
$lessons = $course->lessons()->orderBy('order')->get();
// foreach ($lessons as $lesson) { echo $lesson->title; }
```

### Ví dụ 2: Lấy tất cả khóa học mà học viên "Nguyễn Văn A" đã đăng ký

```php
$student = Student::where('email', 'a@example.com')->first();
$courses = $student->courses; // Lazy loading
// hoặc eager loading:
$student = Student::with('courses')->where('email', 'a@example.com')->first();
```

### Ví dụ 3: Xóa học viên khỏi khóa học (hủy đăng ký)

```php
$enrollment = Enrollment::find(1);
$enrollment->delete(); // Xóa bản ghi đăng ký

// Hoặc
$student = Student::find(1);
$course = Course::find(1);
// Không trực tiếp xóa, mà xóa bản ghi enrollment
Enrollment::where('student_id', $student->id)
    ->where('course_id', $course->id)
    ->delete();
```

---

## 🏆 Best Practices (Thực hành tốt)

1. **Eager Loading** - Dùng `.with()` để tránh N+1 queries
   ```php
   Course::with(['lessons', 'enrollments'])->get();
   // Tốt - 2 queries
   
   Course::get(); // Mỗi course có lessons sẽ là 1 query thêm
   // Xấu - N+1 queries
   ```

2. **Query Scopes** - Dùng scopes thay vì lặp lại query
   ```php
   Course::published()->searchByName('PHP')->get();
   // Tốt - dễ đọc, tái sử dụng
   ```

3. **Form Requests** - Tập trung validation logic
   ```php
   public function store(CourseRequest $request) {
       $data = $request->validated();
       // Dữ liệu đã an toàn
   }
   ```

4. **Comments** - Giải thích tại sao, không phải là gì
   ```php
   // Tốt
   // Kiểm tra nếu email đã tồn tại để học viên có thể đăng ký nhiều khóa học
   $student = Student::firstOrCreate(...)
   
   // Xấu
   // Tạo hoặc lấy student
   $student = Student::firstOrCreate(...)
   ```

---

## 🔗 Liên hệ với Database

**Migration files** (cấu trúc bảng):
- `2024_04_07_000001_create_courses_table.php`
- `2024_04_07_000002_create_lessons_table.php`
- `2024_04_07_000003_create_students_table.php`
- `2024_04_07_000004_create_enrollments_table.php`

Xem migration để hiểu cấu trúc bảng, từ đó hiểu models tốt hơn.

---

**Happy Coding!** 🚀

