# 🚀 Quick Reference Guide - Hướng dẫn nhanh

## 🎯 5 phút để hiểu codebase

### Bước 1: Hiểu Models (1 phút)

```
Course ←→ Student  (through Enrollment)
   ↓
Lesson

- 1 Course có nhiều Lessons
- 1 Course có nhiều Students (qua Enrollment)
- 1 Student có nhiều Courses (qua Enrollment)
- 1 Lesson thuộc 1 Course
```

### Bước 2: Hiểu Controllers (2 phút)

```
CourseController
├── index()     → Danh sách khóa học (search, filter, sort, paginate)
├── create()    → Form tạo
├── store()     → Lưu (validate, auto-slug, upload image)
├── show()      → Chi tiết
├── edit()      → Form sửa
├── update()    → Cập nhật
├── destroy()   → Soft delete (xóa mềm)
├── restore()   → Khôi phục
├── forceDelete() → Xóa vĩnh viễn
└── dashboard() → Thống kê

LessonController
├── index()    → Danh sách bài học (nested under course)
├── create()   → Form tạo
├── store()    → Lưu
├── show()     → Chi tiết
├── edit()     → Form sửa
├── update()   → Cập nhật
└── destroy()  → Xóa

EnrollmentController
├── index()    → Danh sách đăng ký
├── create()   → Form đăng ký
├── store()    → Lưu (firstOrCreate student)
├── byCourse() → Đăng ký theo khóa học
└── destroy()  → Hủy đăng ký
```

### Bước 3: Hiểu Validation (1 phút)

```
CourseRequest  → name, slug, price, description, image, status
LessonRequest  → title, content, video_url, order
EnrollmentRequest → course_id, name, email
```

### Bước 4: Hiểu Scopes (30 giây)

```
Course::published()           → status = 'published'
Course::draft()               → status = 'draft'
Course::priceBetween(100,500) → price between 100 and 500
Course::searchByName('PHP')   → name like '%PHP%'
Course::filterByStatus('all') → all or specific status

Method chaining:
Course::published()->searchByName('PHP')->priceBetween(100,500)->get()
```

### Bước 5: Hiểu Relationships (30 giây)

```
HasMany (1-N):
$course->lessons
$lesson->course

BelongsToMany (N-N):
$course->students (qua enrollment)
$student->courses (qua enrollment)
```

---

## 🔥 Các câu lệnh thường dùng

### Lấy dữ liệu

```php
// Lấy tất cả khóa học
$courses = Course::all();
$courses = Course::get();

// Lấy khóa học đã publish
$courses = Course::published()->get();

// Tìm khóa học
$course = Course::find(1);
$course = Course::where('slug', 'php-basics')->first();

// Lấy bài học của khóa học
$lessons = $course->lessons;  // Lazy
$lessons = $course->lessons()->get();
$lessons = $course->lessons()->orderBy('order')->get();

// Lấy học viên đã đăng ký
$students = $course->students;  // Many-to-many
$enrollments = $course->enrollments;  // Với chi tiết

// Lấy khóa học của học viên
$courses = $student->courses;
```

### Tạo dữ liệu

```php
// Tạo khóa học
$course = Course::create([
    'name' => 'PHP Basics',
    'slug' => 'php-basics',
    'price' => 99.99,
    'description' => '...',
    'status' => 'published'
]);

// Tạo bài học
$lesson = $course->lessons()->create([
    'title' => 'Lesson 1',
    'content' => '...',
    'order' => 1
]);

// Tạo đăng ký (auto create student)
$student = Student::firstOrCreate(
    ['email' => 'user@example.com'],
    ['name' => 'User Name']
);
$enrollment = Enrollment::create([
    'course_id' => $course->id,
    'student_id' => $student->id
]);
```

### Cập nhật dữ liệu

```php
// Cập nhật khóa học
$course->update([
    'name' => 'New Name',
    'price' => 149.99
]);

// Hoặc
$course->name = 'New Name';
$course->save();
```

### Xóa dữ liệu

```php
// Soft delete (xóa mềm)
$course->delete();

// Khôi phục
$course->restore();

// Xóa vĩnh viễn
$course->forceDelete();

// Chỉ lấy đã xóa
$deleted = Course::onlyTrashed()->get();

// Lấy tất cả (kể cả xóa)
$all = Course::withTrashed()->get();
```

### Phân trang

```php
// Phân trang 10 bản ghi
$courses = Course::paginate(10);

// Phân trang 15 bản ghi
$enrollments = Enrollment::paginate(15);

// Lấy trang 2
$page2 = Course::paginate(10)->appends(request()->query());
```

---

## 🎨 Naming Conventions (Quy ước đặt tên)

```php
// Models
Course       // Singular, PascalCase
Student      // Singular, PascalCase
Enrollment   // Singular, PascalCase

// Database tables
courses      // Plural, snake_case
students     // Plural, snake_case
enrollments  // Plural, snake_case

// Foreign keys
course_id    // snake_case
student_id   // snake_case

// Controllers
CourseController      // PascalCase + Controller
StudentController

// Methods
index()               // lowercase
create(), store()     // lowercase
show(), edit()        // lowercase
destroy()             // lowercase
getEnrollmentCount()  // camelCase

// Scopes
published()           // camelCase
searchByName()
priceBetween()

// Variables
$course               // camelCase
$courses              // camelCase
$student
$enrollments
```

---

## 📋 HTTP Methods → Controller Actions

```
GET    /courses              → CourseController@index
GET    /courses/create       → CourseController@create
POST   /courses              → CourseController@store
GET    /courses/{id}         → CourseController@show
GET    /courses/{id}/edit    → CourseController@edit
PUT    /courses/{id}         → CourseController@update
DELETE /courses/{id}         → CourseController@destroy
POST   /courses/{id}/restore → CourseController@restore
DELETE /courses/{id}/force   → CourseController@forceDelete
```

---

## 🔐 Validation Rules Cheat Sheet

```
required              → Bắt buộc nhập
nullable              → Tuỳ chọn
string                → Phải là text
integer, numeric      → Phải là số
email                 → Phải là email hợp lệ
url                   → Phải là URL hợp lệ
image                 → Phải là file hình ảnh
mimes:jpeg,png,jpg    → Chỉ chấp nhận các định dạng này
max:255               → Tối đa 255 ký tự
min:0.01              → Tối thiểu 0.01
between:10,100        → Từ 10 đến 100
in:draft,published    → Chỉ chấp nhận những giá trị này
unique:table,column   → Duy nhất trong bảng
exists:table,column   → Phải tồn tại trong bảng
confirmed             → Phải match với *_confirmation field
```

---

## 💾 Soft Deletes vs Hard Deletes

```
SOFT DELETE (Mềm)
$course->delete()
→ Cập nhật deleted_at = NOW
→ Row vẫn ở database
→ Normal queries không lấy
→ Có thể khôi phục

HARD DELETE (Cứng)
$course->forceDelete()
→ Xóa hoàn toàn khỏi database
→ Không thể khôi phục
→ Sử dụng khi chắc chắn

Queries:
Course::all()              → Không lấy soft deleted
Course::withTrashed()      → Lấy cả soft deleted
Course::onlyTrashed()      → Chỉ lấy soft deleted
```

---

## 🔄 Many-to-Many Relationship (N-N)

```
Course ↔ Student (through Enrollment)

Database:
courses table
├── id
├── name
└── ...

students table
├── id
├── name
├── email
└── ...

enrollments table (Pivot)
├── id
├── course_id (FK → courses.id)
├── student_id (FK → students.id)
└── timestamps

Relationships:
// Course.php
public function students() {
    return $this->belongsToMany(Student::class, 'enrollments');
}

// Student.php
public function courses() {
    return $this->belongsToMany(Course::class, 'enrollments');
}

// Enrollment.php
public function course() { return $this->belongsTo(Course::class); }
public function student() { return $this->belongsTo(Student::class); }

Usage:
$course = Course::find(1);
$course->students;  // Tất cả học viên của khóa học

$student = Student::find(1);
$student->courses;  // Tất cả khóa học của học viên

$enrollment = Enrollment::find(1);
$enrollment->course;  // Khóa học
$enrollment->student; // Học viên
```

---

## 🎯 Common Tasks

### Tạo khóa học mới

```php
$course = Course::create([
    'name' => 'PHP Basics',
    'slug' => 'php-basics-' . time(),
    'price' => 99.99,
    'description' => 'Learn PHP',
    'status' => 'published'
]);
```

### Thêm bài học vào khóa học

```php
$course = Course::find(1);
$lesson = $course->lessons()->create([
    'title' => 'Lesson 1',
    'content' => '...',
    'order' => 1
]);
```

### Học viên đăng ký khóa học

```php
$student = Student::firstOrCreate(
    ['email' => 'user@example.com'],
    ['name' => 'User Name']
);

Enrollment::create([
    'course_id' => $course->id,
    'student_id' => $student->id
]);
```

### Lấy thống kê dashboard

```php
$totalCourses = Course::count();
$totalStudents = Student::count();
$totalRevenue = Course::with('enrollments')->get()->sum(fn($c) => $c->getTotalRevenue());
$topCourse = Course::with('enrollments')->get()->sortByDesc(fn($c) => $c->enrollments->count())->first();
```

### Tìm kiếm khóa học

```php
$courses = Course::published()
    ->searchByName('PHP')
    ->priceBetween(50, 200)
    ->orderBy('created_at', 'desc')
    ->paginate(10);
```

---

## 🐛 Debug Tips

```php
// Xem SQL query
DB::listen(function ($query) {
    echo $query->sql; // Print query
    echo $query->bindings; // Print bindings
});

// Hoặc dùng Laravel Debugbar
// Hoặc dùng Ray
ray($variable)->dump();

// Check relationship loading
if ($course->relationLoaded('lessons')) {
    // Already loaded
}

// Count queries
DB::enableQueryLog();
// ... code ...
dd(DB::getQueryLog());
```

---

## 📚 Tài liệu tham khảo

- **Eloquent Relationships**: `HOW_TO_READ_CODE.md` - Phần Relationships
- **Scopes**: `HOW_TO_READ_CODE.md` - Phần Query Scopes
- **Validation**: File Request classes
- **Architecture**: `MERMAID_DIAGRAMS.md`

---

## ⚡ Performance Tips

```php
// ✅ GOOD - Eager loading
$courses = Course::with(['lessons', 'enrollments'])->get();

// ❌ BAD - N+1 queries
$courses = Course::all();
foreach ($courses as $course) {
    $lessons = $course->lessons; // Extra query per course!
}

// ✅ GOOD - Pagination
$courses = Course::paginate(10);

// ❌ BAD - Load all
$courses = Course::all();

// ✅ GOOD - Use scopes
$courses = Course::published()->searchByName('PHP')->get();

// ❌ BAD - Manual where
$courses = Course::where('status', 'published')
    ->where('name', 'like', '%PHP%')
    ->get();
```

---

## 🎓 Tiếp theo là gì?

1. **Đọc code** - Bắt đầu với Models, sau đó Controllers
2. **Chạy project** - `php artisan serve` + `npm run dev`
3. **Test features** - Tạo, sửa, xóa khóa học, bài học, đăng ký
4. **Thêm features** - Theo quy ước hiện tại
5. **Viết tests** - Thêm unit tests cho logic mới

---

**Chúc bạn học tập vui vẻ!** 🚀

