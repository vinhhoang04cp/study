@extends('layouts.master')

@section('title', 'Danh Sách Khóa Học')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-book"></i> Danh Sách Khóa Học</h1>
        <a href="{{ route('courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Khóa Học Mới
        </a>
    </div>

    @component('components.card')
        @slot('title')
            <i class="fas fa-filter"></i> Tìm Kiếm & Lọc
        @endslot

        <form method="GET" action="{{ route('courses.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" placeholder="Nhập tên khóa học..." value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tất Cả</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Bản Nháp</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Đã Xuất Bản</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Sắp xếp</label>
                <select name="sort" class="form-select">
                    <option value="created_at" {{ $sort === 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                    <option value="price" {{ $sort === 'price' ? 'selected' : '' }}>Giá</option>
                    <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Tên</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Hiển thị</label>
                <select name="show_deleted" class="form-select">
                    <option value="0" {{ !$showDeleted ? 'selected' : '' }}>Khóa học</option>
                    <option value="1" {{ $showDeleted ? 'selected' : '' }}>Đã xóa ({{ $deletedCount }})</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm Kiếm
                </button>
            </div>
        </form>
    @endcomponent

    @if($courses->count() > 0)
        <div class="row">
            @foreach($courses as $course)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-book" style="font-size: 48px;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $course->name }}</h5>
                                @if($showDeleted && $course->deleted_at)
                                    <span class="badge bg-danger"><i class="fas fa-trash"></i> Đã Xóa</span>
                                @endif
                            </div>

                            <p class="card-text text-muted small">{{ Str::limit($course->description, 80) }}</p>

                            <div class="mb-3">
                                @if(!$showDeleted)
                                    <span class="badge bg-info">
                                        <i class="fas fa-video"></i> {{ $course->lessons()->count() }} bài học
                                    </span>
                                    <span class="badge bg-success">
                                        <i class="fas fa-users"></i> {{ $course->enrollments()->count() }} học viên
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Xóa lúc: {{ $course->deleted_at->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong class="text-primary">{{ number_format($course->price, 0, ',', '.') }} đ</strong>
                                </div>
                                @if(!$showDeleted)
                                    @component('components.badge')
                                        @slot('status', $course->status)
                                    @endcomponent
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                @if(!$showDeleted)
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Xem Chi Tiết
                                    </a>
                                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Chỉnh Sửa
                                    </a>
                                    <form method="POST" action="{{ route('courses.destroy', $course) }}" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa? Dữ liệu sẽ được lưu lại.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('courses.restore', $course->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success w-100">
                                            <i class="fas fa-undo"></i> Khôi Phục
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('courses.force-delete', $course->id) }}" style="display: inline;" onsubmit="return confirm('Xóa vĩnh viễn? Không thể khôi phục!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="fas fa-times-circle"></i> Xóa Vĩnh Viễn
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $courses->appends(request()->query())->links('pagination::bootstrap-5') }}
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i>
            @if($showDeleted)
                Không có khóa học đã xóa nào
            @else
                Không tìm thấy khóa học nào
            @endif
        </div>
    @endif
@endsection

