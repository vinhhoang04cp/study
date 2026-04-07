@extends('layouts.master')

@section('title', 'Tạo Bài Học Mới')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-plus-circle"></i> Tạo Bài Học Mới</h1>
    </div>

    @component('components.card')
        @slot('title')
            <i class="fas fa-form"></i> Thông Tin Bài Học - {{ $course->name }}
        @endslot

        <form method="POST" action="{{ route('courses.lessons.store', $course) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       placeholder="Nhập tiêu đề bài học" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Thứ Tự <span class="text-danger">*</span></label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                               placeholder="0" value="{{ old('order', $course->lessons()->max('order') + 1) }}" required>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">URL Video</label>
                        <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror"
                               placeholder="https://..." value="{{ old('video_url') }}">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nội Dung <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                          rows="8" placeholder="Nhập nội dung bài học" required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu Bài Học
                </button>
                <a href="{{ route('courses.lessons.index', $course) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    @endcomponent
@endsection

