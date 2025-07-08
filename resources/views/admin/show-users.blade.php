@extends('layouts.master')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="container-fluid mt-4">
    <h2>Chi tiết người dùng: {{ $user->name }}</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Thông tin user -->
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Họ tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Trạng thái:</strong> 
                <span class="badge {{ $user->is_locked ? 'badge-locked' : 'badge-active' }}">
                    {{ $user->is_locked ? 'Locked' : 'Active' }}
                </span>
            </p>
            <p><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <!-- Danh sách công việc -->
    <h3>Công việc của {{ $user->name }}</h3>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Mô tả</th>
                            <th>Hạn hoàn thành</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user->tasks as $task)
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->description ?? 'Không có' }}</td>
                                <td>{{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Không có' }}</td>
                                <td>{{ $task->status }}</td>
                                <td>
                                    <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-sm btn-primary">Sửa</a>
                                    <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa công việc này?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Chưa có công việc nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection