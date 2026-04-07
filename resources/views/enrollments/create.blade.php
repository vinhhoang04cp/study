@extends('layouts.master')

@section('title', 'Đăng Ký Khóa Học')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-user-plus"></i> Đăng Ký Khóa Học</h1>
    </div>

    @component('components.card')
        @slot('title')
            <i class="fas fa-form"></i> Thông Tin Đăng Ký
        @endslot

        <form method="POST" action="{{ route('enrollments.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Chọn Khóa Học <span class="text-danger">*</span></label>
                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                    <option value="">-- Chọn Khóa Học --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} - {{ number_format($course->price, 0, ',', '.') }} đ
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tên Học Viên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       placeholder="Nhập tên học viên" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       placeholder="Nhập email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Đăng Ký
                </button>
                <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    @endcomponent
@endsection

