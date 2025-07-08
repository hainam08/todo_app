@extends('layouts.master')

@section('title', 'Quản lý công việc')

@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
<style>
    .table-card { margin-bottom: 1rem; }
    .table-light th { text-transform: uppercase; }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <h2>Quản lý công việc</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Form tìm kiếm -->
    <form method="GET" action="{{ route('admin.tasks.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tiêu đề" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                    <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>Mới tạo</option>
                    <option value="Inprogress" {{ request('status') == 'Inprogress' ? 'selected' : '' }}>Đang thực hiện</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Đang chờ</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="due_date" class="form-control" value="{{ request('due_date') }}">
            </div>
            <div class="col-md-3">
                <select name="user_id" class="form-control">
                    <option value="">Tất cả người dùng</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </div>
    </form>

    <!-- Bảng danh sách công việc -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Mô tả</th>
                            <th>Hạn hoàn thành</th>
                            <th>Trạng thái</th>
                            <th>Người dùng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tasks as $task)
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->description ?? 'Không có' }}</td>
                                <td>{{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Không có' }}</td>
                                <td>{{ $task->status }}</td>
                                <td>{{ $task->user->name }}</td>
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
                                <td colspan="7" class="text-center">Chưa có công việc nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $tasks->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    @if (session('success'))
        Swal.fire({
            title: 'Thành công!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
    @if (session('error'))
        Swal.fire({
            title: 'Lỗi!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endsection