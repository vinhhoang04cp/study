@extends('layouts.master')

@section('title', 'Chi Tiết Khóa Học')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-book"></i> {{ $course->name }}</h1>
        <div>
            <a href="{{ route('courses.lessons.index', $course) }}" class="btn btn-info">
                <i class="fas fa-list"></i> Bài Học
            </a>
            <a href="{{ route('enrollments.by-course', $course) }}" class="btn btn-info">
                <i class="fas fa-users"></i> Học Viên
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            @component('components.card')
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}" class="img-fluid rounded mb-3">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center rounded mb-3" style="height: 300px; color: white;">
                        <i class="fas fa-book" style="font-size: 80px;"></i>
                    </div>
                @endif

                <h5 class="mb-3">{{ $course->name }}</h5>

                <div class="mb-3">
                    <label class="text-muted small">Trạng Thái</label>
                    <br>
                    @component('components.badge')
                        @slot('status', $course->status)
                    @endcomponent
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Giá</label>
                    <p class="fs-5 text-success">
                        <strong>{{ number_format($course->price, 0, ',', '.') }} đ</strong>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Slug</label>
                    <p class="small">{{ $course->slug }}</p>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh Sửa
                    </a>
                    <form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </form>
                </div>
            @endcomponent
        </div>

        <div class="col-md-8">
            @component('components.card')
                @slot('title')
                    <i class="fas fa-file-alt"></i> Mô Tả Khóa Học
                @endslot
                <p>{{ $course->description }}</p>
            @endcomponent

            <div class="row">
                <div class="col-md-6">
                    @component('components.card')
                        @slot('title')
                            <i class="fas fa-video"></i> Bài Học
                        @endslot
                        <div class="text-center">
                            <p class="display-6 text-primary">{{ $course->lessons()->count() }}</p>
                            <p class="text-muted">Bài học trong khóa học</p>
                            <a href="{{ route('courses.lessons.create', $course) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Thêm Bài Học
                            </a>
                        </div>
                    @endcomponent
                </div>

                <div class="col-md-6">
                    @component('components.card')
                        @slot('title')
                            <i class="fas fa-users"></i> Học Viên Đăng Ký
                        @endslot
                        <div class="text-center">
                            <p class="display-6 text-success">{{ $course->enrollments()->count() }}</p>
                            <p class="text-muted">Học viên đã đăng ký</p>
                            <a href="{{ route('enrollments.by-course', $course) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-list"></i> Xem Danh Sách
                            </a>
                        </div>
                    @endcomponent
                </div>
            </div>

            @component('components.card')
                @slot('title')
                    <i class="fas fa-list"></i> Danh Sách Bài Học
                @endslot

                @if($course->lessons()->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">STT</th>
                                    <th>Tiêu Đề</th>
                                    <th style="width: 200px;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course->lessons()->orderBy('order')->get() as $lesson)
                                    <tr>
                                        <td>{{ $lesson->order }}</td>
                                        <td>
                                            <strong>{{ $lesson->title }}</strong>
                                            @if($lesson->video_url)
                                                <span class="badge bg-info"><i class="fas fa-video"></i> Video</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('courses.lessons.destroy', [$course, $lesson]) }}" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Chưa có bài học nào. <a href="{{ route('courses.lessons.create', $course) }}">Thêm bài học</a></p>
                @endif
            @endcomponent
        </div>
    </div>
@endsection

