# Mermaid Diagrams - Tất cả sơ đồ chi tiết

## 1. Data Flow Diagram (Luồng dữ liệu)

```mermaid
flowchart TD
    A["User<br/>(Web Browser)"] -->|HTTP Request| B["Laravel App<br/>(Routes)"]
    B -->|Route| C["Controllers<br/>(CourseController<br/>LessonController<br/>EnrollmentController)"]
    C -->|Validate| D["Form Requests<br/>(CourseRequest<br/>LessonRequest<br/>EnrollmentRequest)"]
    D -->|Query| E["Models<br/>(Course<br/>Lesson<br/>Student<br/>Enrollment)"]
    E -->|Query| F["Database<br/>(MySQL)"]
    F -->|Return Data| E
    E -->|Data| C
    C -->|Render| G["Views<br/>(Blade Templates)"]
    G -->|HTML| A
    
    style A fill:#e1f5ff
    style B fill:#fff3e0
    style C fill:#f3e5f5
    style D fill:#e8f5e9
    style E fill:#fce4ec
    style F fill:#ede7f6
    style G fill:#e0f2f1
```

## 2. Course Management Workflow

```mermaid
flowchart TD
    A["Dashboard"] -->|View Courses| B["CourseController@index"]
    B -->|Search/Filter| C["Course Scopes<br/>published()/draft()<br/>searchByName()<br/>priceBetween()"]
    C -->|Query| D["Get Courses List"]
    
    E["Create Course"] -->|GET /courses/create| F["CourseController@create"]
    F -->|Show Form| G["Create View"]
    G -->|POST /courses| H["CourseController@store"]
    H -->|Validate| I["CourseRequest"]
    I -->|Auto-generate slug<br/>Upload image| J["Course::create"]
    J -->|Save| K["Courses Table"]
    K -->|Redirect| L["Success Message"]
    
    M["Edit Course"] -->|GET /courses/id| N["CourseController@show"]
    N -->|GET /courses/id/edit| O["CourseController@edit"]
    O -->|Show Form| P["Edit View"]
    P -->|PUT /courses/id| Q["CourseController@update"]
    Q -->|Validate| I
    I -->|Update| R["$course->update"]
    R -->|Save| K
    
    S["Delete Course"] -->|DELETE| T["CourseController@destroy"]
    T -->|Soft Delete| U["$course->delete<br/>Set deleted_at"]
    U -->|Save| K
    
    V["Recover Course"] -->|POST /restore| W["CourseController@restore"]
    W -->|onlyTrashed()| X["Find Deleted"]
    X -->|Restore| Y["$course->restore<br/>Clear deleted_at"]
    Y -->|Save| K
    
    Z["Permanent Delete"] -->|DELETE /force| AA["CourseController@forceDelete"]
    AA -->|forceDelete()| AB["Hard Delete"]
    AB -->|Remove| K
    
    style A fill:#e3f2fd
    style B fill:#f3e5f5
    style K fill:#ede7f6
    style L fill:#c8e6c9
```

## 3. Lesson Management Workflow

```mermaid
flowchart TD
    A["View Course"] -->|Lessons| B["LessonController@index"]
    B -->|with course| C["Get Lessons<br/>Ordered by 'order'"]
    C -->|Fetch| D["Lesson Table"]
    
    E["Create Lesson"] -->|GET /lessons/create| F["LessonController@create"]
    F -->|Show Form| G["Create View"]
    G -->|POST /lessons| H["LessonController@store"]
    H -->|Validate| I["LessonRequest"]
    I -->|Assign course_id| J["Lesson::create"]
    J -->|Save| D
    
    K["View Lesson"] -->|GET| L["LessonController@show"]
    L -->|Fetch| D
    
    M["Edit Lesson"] -->|GET /edit| N["LessonController@edit"]
    N -->|Show Form| O["Edit View"]
    O -->|PUT| P["LessonController@update"]
    P -->|Validate| I
    I -->|Update| Q["$lesson->update"]
    Q -->|Save| D
    
    R["Delete Lesson"] -->|DELETE| S["LessonController@destroy"]
    S -->|Hard Delete| T["$lesson->delete"]
    T -->|Remove| D
    
    style A fill:#e3f2fd
    style B fill:#f3e5f5
    style D fill:#ede7f6
    style J fill:#c8e6c9
    style T fill:#ffcccc
```

## 4. Enrollment Management Workflow

```mermaid
flowchart TD
    A["View Enrollments"] -->|GET /enrollments| B["EnrollmentController@index"]
    B -->|with course,student| C["Get All Enrollments"]
    C -->|Fetch| D["Enrollment Table"]
    
    E["Register Course"] -->|GET /create| F["EnrollmentController@create"]
    F -->|published courses| G["Course::published()"]
    G -->|Show Form| H["Create View"]
    H -->|POST /enrollments| I["EnrollmentController@store"]
    I -->|Validate| J["EnrollmentRequest"]
    J -->|Check Email| K["Student::firstOrCreate"]
    K -->|Email exists?| L{Found?}
    L -->|Yes| M["Get Existing Student"]
    L -->|No| N["Create New Student"]
    M -->|Create Enrollment| O["Enrollment::create"]
    N -->|Create Enrollment| O
    O -->|Save| D
    O -->|Save| P["Student Table"]
    
    Q["View Course Enrollments"] -->|GET /by-course| R["EnrollmentController@byCourse"]
    R -->|with student| S["Get Course Enrollments"]
    S -->|Fetch| D
    S -->|Count| T["totalStudents"]
    
    U["Unenroll"] -->|DELETE| V["EnrollmentController@destroy"]
    V -->|Delete Record| W["$enrollment->delete"]
    W -->|Remove| D
    
    style A fill:#e3f2fd
    style B fill:#f3e5f5
    style D fill:#ede7f6
    style O fill:#c8e6c9
    style W fill:#ffcccc
```

## 5. Model Relationships

```mermaid
graph LR
    Course["Course<br/>---<br/>id<br/>name<br/>slug<br/>price<br/>description<br/>image<br/>status"]
    
    Lesson["Lesson<br/>---<br/>id<br/>course_id FK<br/>title<br/>content<br/>video_url<br/>order"]
    
    Student["Student<br/>---<br/>id<br/>name<br/>email"]
    
    Enrollment["Enrollment<br/>---<br/>id<br/>course_id FK<br/>student_id FK"]
    
    Course -->|"1:N hasMany"| Lesson
    Lesson -->|"N:1 belongsTo"| Course
    
    Course -->|"1:N hasMany"| Enrollment
    Enrollment -->|"N:1 belongsTo"| Course
    
    Student -->|"1:N hasMany"| Enrollment
    Enrollment -->|"N:1 belongsTo"| Student
    
    Course -->|"N:N belongsToMany<br/>through Enrollment"| Student
    Student -->|"N:N belongsToMany<br/>through Enrollment"| Course
    
    style Course fill:#ffe0b2
    style Lesson fill:#e1bee7
    style Student fill:#b2dfdb
    style Enrollment fill:#fff9c4
```

## 6. Query Scopes Usage

```mermaid
flowchart TD
    A["Course Model"]
    
    A --> B["published()"]
    B -->|where status='published'| C["Get published courses"]
    
    A --> D["draft()"]
    D -->|where status='draft'| E["Get draft courses"]
    
    A --> F["priceBetween min,max"]
    F -->|whereBetween price| G["Get courses in price range"]
    
    A --> H["searchByName name"]
    H -->|where name like %name%| I["Search by name"]
    
    A --> J["filterByStatus status"]
    J -->|if status != all| K["Filter by status"]
    
    L["Method Chaining Example"]
    L -->|Course::published<br/>.searchByName<br/>.priceBetween<br/>.orderBy<br/>.paginate| M["Fluent Query Building"]
    
    style A fill:#fff9c4
    style B fill:#e1bee7
    style D fill:#e1bee7
    style F fill:#e1bee7
    style H fill:#e1bee7
    style J fill:#e1bee7
    style M fill:#c8e6c9
```

## 7. Soft Delete Lifecycle

```mermaid
flowchart TD
    A["Course Exists<br/>deleted_at = NULL"]
    
    B["User Delete"]
    B -->|$course->delete| C["Soft Delete<br/>deleted_at = NOW"]
    
    C -->|Invisible in normal query| D["Course::all<br/>Won't include deleted"]
    D -->|E.g., 5 courses total<br/>4 showing in list|E["User sees 4"]
    
    F["View Deleted"]
    F -->|$course->onlyTrashed| G["Get only deleted<br/>WHERE deleted_at IS NOT NULL"]
    
    H["Restore"]
    H -->|$course->restore| I["Recovery<br/>deleted_at = NULL"]
    I -->|Back to visible| J["Course visible again"]
    
    K["Force Delete"]
    K -->|$course->forceDelete| L["Permanent Delete<br/>Remove from database"]
    L -->|No way to recover| M["Completely gone"]
    
    style A fill:#c8e6c9
    style C fill:#fff9c4
    style E fill:#ffe0b2
    style I fill:#c8e6c9
    style L fill:#ffcccc
    style M fill:#ffcccc
```

## 8. Form Validation Flow

```mermaid
flowchart TD
    A["User Submit Form<br/>POST /courses"]
    A -->|Request Data| B["CourseController@store"]
    B -->|Dependency Injection| C["CourseRequest"]
    
    C -->|Check Rules| D["name: required|string|max:255"]
    C -->|Check Rules| E["price: required|numeric|min:0.01"]
    C -->|Check Rules| F["description: required|string"]
    C -->|Check Rules| G["image: nullable|image|mimes:jpeg,png,jpg,gif|max:2048"]
    C -->|Check Rules| H["status: required|in:draft,published"]
    
    I{All Valid?}
    D --> I
    E --> I
    F --> I
    G --> I
    H --> I
    
    I -->|Yes| J["$request->validated()"]
    J -->|Return validated data| K["$data = Array"]
    K -->|Auto slug + image| L["Course::create data"]
    L -->|Success| M["Redirect with success"]
    
    I -->|No| N["Validation Failed"]
    N -->|Show Errors| O["Redirect to form<br/>with error messages"]
    O -->|Vietnamese messages| P["User sees<br/>Tiếng Việt errors"]
    
    style A fill:#e3f2fd
    style C fill:#e8f5e9
    style I fill:#fff9c4
    style M fill:#c8e6c9
    style P fill:#ffcccc
```

## 9. Dashboard Statistics

```mermaid
flowchart TD
    A["GET /dashboard"]
    A -->|CourseController@dashboard| B["Collect Statistics"]
    
    B -->|Course::count| C["Total Courses"]
    C -->|E.g. 15| D["15 courses"]
    
    B -->|Student::count| E["Total Students"]
    E -->|E.g. 120| F["120 students"]
    
    B -->|Calculate Revenue| G["Total Revenue"]
    G -->|For each course| H["price × enrollmentCount"]
    H -->|Sum all| I["10,500,000 VND<br/>e.g."]
    
    B -->|Find top course| J["Top Course"]
    J -->|Sort by enrollment count| K["Most popular"]
    K -->|E.g. PHP Basics<br/>45 students| L["Top Course"]
    
    B -->|Recent courses| M["Latest 5 Courses"]
    M -->|Order by created_at desc| N["5 newest"]
    
    O["Dashboard View"]
    D --> O
    F --> O
    I --> O
    L --> O
    N --> O
    
    style A fill:#e3f2fd
    style B fill:#f3e5f5
    style O fill:#c8e6c9
    style I fill:#ffe0b2
    style L fill:#ffe0b2
```

## 10. Complete Request Lifecycle

```mermaid
sequenceDiagram
    participant User as User<br/>Browser
    participant Web as Web<br/>Server
    participant Router as Router
    participant Controller as Controller
    participant Request as Request<br/>Validator
    participant Model as Model
    participant DB as Database
    participant View as View
    
    User->>Web: 1. Send HTTP Request<br/>POST /courses
    Web->>Router: 2. Match Route
    Router->>Controller: 3. Dispatch to<br/>CourseController@store
    Controller->>Request: 4. Dependency Injection<br/>CourseRequest
    Request->>Request: 5. Validate Rules
    
    alt Validation Failed
        Request-->>Controller: Return Errors
        Controller-->>Web: Redirect to Form
        Web-->>User: Show Form + Errors
    else Validation Passed
        Request->>Controller: Return validated data
        Controller->>Controller: Process Data<br/>Auto-generate slug<br/>Upload image
        Controller->>Model: 6. Call Course::create
        Model->>DB: 7. INSERT Query
        DB->>DB: 8. Save to Database
        DB-->>Model: Return New Course
        Model-->>Controller: Return Course Object
        Controller->>View: 9. Render View<br/>or Redirect
        View-->>Web: Return HTML/Response
        Web-->>User: 10. Display Success
    end
```

---

**Sử dụng các diagram này để:**
1. Hiểu luồng dữ liệu từ đầu đến cuối
2. Trực quan hóa quan hệ giữa các thành phần
3. Tra cứu khi có câu hỏi về cách hoạt động
4. Giải thích cho team members


