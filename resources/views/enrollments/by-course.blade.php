@extends('layouts.master')

@section('title', 'Học Viên Khóa Học')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-users"></i> Học Viên - {{ $course->name }}</h1>
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Học Viên
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
                <p><strong>Tổng học viên:</strong> <span class="badge bg-success">{{ $totalStudents }}</span></p>
                <p><strong>Doanh thu:</strong> <span class="text-success">{{ number_format($course->getTotalRevenue(), 0, ',', '.') }} đ</span></p>
            </div>
        </div>
    @endcomponent

    @if($enrollments->count() > 0)
        <div class="table-responsive">
            @component('components.card')
                @slot('title')
                    <i class="fas fa-list"></i> Danh Sách Học Viên ({{ $totalStudents }})
                @endslot

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th>Tên Học Viên</th>
                            <th>Email</th>
                            <th>Ngày Đăng Ký</th>
                            <th style="width: 100px;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $key => $enrollment)
                            <tr>
                                <td>{{ ($enrollments->currentPage() - 1) * $enrollments->perPage() + $key + 1 }}</td>
                                <td>{{ $enrollment->student->name }}</td>
                                <td>{{ $enrollment->student->email }}</td>
                                <td>{{ $enrollment->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('enrollments.destroy', $enrollment) }}" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn hủy đăng ký?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hủy
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endcomponent
        </div>

        {{ $enrollments->links('pagination::bootstrap-5') }}
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Chưa có học viên nào đăng ký khóa học này
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
        </a>
    </div>
@endsection

