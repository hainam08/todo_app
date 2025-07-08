@extends('layouts.master')

@section('title', 'Sửa công việc')

@section('content')
<div class="container-fluid mt-4">
    <h2>Sửa công việc</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $task->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label">Hạn hoàn thành</label>
                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}">
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="New" {{ $task->status == 'New' ? 'selected' : '' }}>Mới tạo</option>
                        <option value="Inprogress" {{ $task->status == 'Inprogress' ? 'selected' : '' }}>Đang thực hiện</option>
                        <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                        <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Đang chờ</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection