@extends('layouts.master')

@section('title', 'Danh Sách Bài Học')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-list"></i> Bài Học - {{ $course->name }}</h1>
        <a href="{{ route('courses.lessons.create', $course) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Bài Học
        </a>
    </div>

    @component('components.card')
        @slot('title')
            <i class="fas fa-info-circle"></i> Thông Tin Khóa Học
        @endslot
        <div class="row">
            <div class="col-md-6">
                <p><strong>Tên khóa:</strong> {{ $course->name }}</p>
                <p><strong>Giá:</strong> {{ number_format($course->price, 0, ',', '.') }} đ</p>
            </div>
            <div class="col-md-6">
                <p><strong>Trạng thái:</strong>
                    @component('components.badge')
                        @slot('status', $course->status)
                    @endcomponent
                </p>
                <p><strong>Số bài học:</strong> {{ $lessons->count() }}</p>
            </div>
        </div>
    @endcomponent

    @if($lessons->count() > 0)
        <div class="table-responsive">
            @component('components.card')
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Thứ Tự</th>
                            <th>Tiêu Đề</th>
                            <th>Video</th>
                            <th>Ngày Tạo</th>
                            <th style="width: 150px;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lessons as $lesson)
                            <tr>
                                <td><strong>{{ $lesson->order }}</strong></td>
                                <td>{{ $lesson->title }}</td>
                                <td>
                                    @if($lesson->video_url)
                                        <a href="{{ $lesson->video_url }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-video"></i> Xem
                                        </a>
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                                <td>{{ $lesson->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form method="POST" action="{{ route('courses.lessons.destroy', [$course, $lesson]) }}" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endcomponent
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Chưa có bài học nào. <a href="{{ route('lessons.create', $course) }}">Thêm bài học đầu tiên</a>
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
        </a>
    </div>
@endsection

