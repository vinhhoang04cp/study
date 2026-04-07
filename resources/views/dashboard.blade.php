@extends('layouts.master')

@section('title', 'Dashboard - Course Management System')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-chart-line"></i> Dashboard</h1>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-book" style="font-size: 32px; color: #3498db;"></i>
                <div class="stat-value">{{ $totalCourses }}</div>
                <div class="stat-label">Tổng Khóa Học</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-users" style="font-size: 32px; color: #2ecc71;"></i>
                <div class="stat-value">{{ $totalStudents }}</div>
                <div class="stat-label">Tổng Học Viên</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-dollar-sign" style="font-size: 32px; color: #f39c12;"></i>
                <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }} đ</div>
                <div class="stat-label">Tổng Doanh Thu</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-star" style="font-size: 32px; color: #e74c3c;"></i>
                <div class="stat-value">
                    @if($topCourse)
                        {{ $topCourse->enrollments()->count() }}
                    @else
                        0
                    @endif
                </div>
                <div class="stat-label">Học Viên Nhiều Nhất</div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Top Course -->
        <div class="col-md-6">
            @component('components.card')
                @slot('title')
                    <i class="fas fa-star"></i> Khóa Học Có Học Viên Nhiều Nhất
                @endslot
                @if($topCourse)
                    <div class="mb-3">
                        <h5>{{ $topCourse->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($topCourse->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><strong>{{ $topCourse->enrollments()->count() }}</strong> học viên</span>
                            <a href="{{ route('courses.show', $topCourse) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-muted">Chưa có khóa học nào</p>
                @endif
            @endcomponent
        </div>

        <!-- Quick Actions -->
        <div class="col-md-6">
            @component('components.card')
                @slot('title')
                    <i class="fas fa-plus-circle"></i> Hành Động Nhanh
                @endslot
                <div class="d-grid gap-2">
                    <a href="{{ route('courses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo Khóa Học Mới
                    </a>
                    <a href="{{ route('enrollments.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Đăng Ký Học Viên
                    </a>
                    <a href="{{ route('courses.index') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> Danh Sách Khóa Học
                    </a>
                </div>
            @endcomponent
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="row mt-4">
        <div class="col-12">
            @component('components.card')
                @slot('title')
                    <i class="fas fa-clock"></i> 5 Khóa Học Mới Nhất
                @endslot
                @if($recentCourses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tên Khóa Học</th>
                                    <th>Giá</th>
                                    <th>Trạng Thái</th>
                                    <th>Bài Học</th>
                                    <th>Học Viên</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCourses as $course)
                                    <tr>
                                        <td>
                                            <strong>{{ $course->name }}</strong>
                                        </td>
                                        <td>{{ number_format($course->price, 0, ',', '.') }} đ</td>
                                        <td>
                                            @component('components.badge')
                                                @slot('status', $course->status)
                                            @endcomponent
                                        </td>
                                        <td>{{ $course->lessons()->count() }}</td>
                                        <td>{{ $course->enrollments()->count() }}</td>
                                        <td>
                                            <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Chưa có khóa học nào</p>
                @endif
            @endcomponent
        </div>
    </div>
@endsection

