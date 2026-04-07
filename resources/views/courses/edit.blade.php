@extends('layouts.master')

@section('title', 'Chỉnh Sửa Khóa Học')

@section('content')
    <div class="topbar">
        <h1 class="page-title"><i class="fas fa-edit"></i> Chỉnh Sửa Khóa Học</h1>
    </div>

    @component('components.card')
        @slot('title')
            <i class="fas fa-form"></i> Thông Tin Khóa Học
        @endslot

        <form method="POST" action="{{ route('courses.update', $course) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tên Khóa Học <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="Nhập tên khóa học" value="{{ old('name', $course->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Giá <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                   placeholder="0" step="0.01" value="{{ old('price', $course->price) }}" required>
                            <span class="input-group-text">đ</span>
                        </div>
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô Tả <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="5" placeholder="Nhập mô tả khóa học" required>{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ảnh Khóa Học</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Các định dạng: JPEG, PNG, JPG, GIF. Tối đa 2MB</small>
                        @if($course->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->name }}" style="max-width: 200px; border-radius: 5px;">
                                <p class="small text-muted mt-2">Ảnh hiện tại</p>
                            </div>
                        @endif
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Chọn Trạng Thái --</option>
                            <option value="draft" {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>Bản Nháp</option>
                            <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>Đã Xuất Bản</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Cập Nhật Khóa Học
                </button>
                <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    @endcomponent
@endsection

