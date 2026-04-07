@extends('layouts.master')

@section('title', 'Danh Sách Đăng Ký Học')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-users"></i> Danh Sách Đăng Ký Học</h1>
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Đăng Ký
        </a>
    </div>

    @component('components.card')
        @slot('title')
            <i class="fas fa-list"></i> Tất Cả Đơn Đăng Ký
        @endslot

        @if($enrollments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Khóa Học</th>
                            <th>Tên Học Viên</th>
                            <th>Email</th>
                            <th>Giá</th>
                            <th>Ngày Đăng Ký</th>
                            <th style="width: 100px;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                            <tr>
                                <td>
                                    @if($enrollment->course)
                                        <a href="{{ route('courses.show', $enrollment->course->id) }}">
                                            {{ $enrollment->course->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">(Đã xóa)</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->student->name ?? 'N/A' }}</td>
                                <td>{{ $enrollment->student->email ?? 'N/A' }}</td>
                                <td>
                                    @if($enrollment->course)
                                        {{ number_format($enrollment->course->price, 0, ',', '.') }} đ
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
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
            </div>

            {{ $enrollments->links('pagination::bootstrap-5') }}
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> Chưa có đơn đăng ký nào. <a href="{{ route('enrollments.create') }}">Tạo đơn đăng ký mới</a>
            </div>
        @endif
    @endcomponent
@endsection

