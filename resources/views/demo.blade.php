@extends('layouts.master-user')

@section('title', 'Quản lý người dùng')

@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
<style>
    .table-card {
        margin-bottom: 1rem;
    }
    .table-light th {
        text-transform: uppercase;
    }
    .badge-active {
        background-color: #28a745;
        color: white;
    }
    .badge-locked {
        background-color: #dc3545;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <h2>Quản lý người dùng</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Debug route -->
    <div>Debug: {{ route('admin.users.index') }}</div>

    <!-- Form tìm kiếm -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc email" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Locked</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <!-- Bảng danh sách user -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-nowrap align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->is_locked ? 'badge-locked' : 'badge-active' }}">
                                        {{ $user->is_locked ? 'Locked' : 'Active' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <ul class="list-inline hstack gap-2 mb-0">
                                        <li class="list-inline-item">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="text-info" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="ri-eye-fill fs-16"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <form action="{{ route('admin.users.toggle-lock', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-{{ $user->is_locked ? 'success' : 'warning' }} d-inline-block border-0 bg-transparent" data-bs-toggle="tooltip" title="{{ $user->is_locked ? 'Mở khóa' : 'Khóa' }}">
                                                    <i class="ri-{{ $user->is_locked ? 'lock-unlock' : 'lock' }}-fill fs-16"></i>
                                                </button>
                                            </form>
                                        </li>
                                        <li class="list-inline-item">
                                            <a class="text-danger remove-item-btn" data-bs-toggle="modal" href="#deleteUserModal" data-id="{{ $user->id }}" data-link="{{ route('admin.users.destroy', $user->id) }}" title="Xóa">
                                                <i class="ri-delete-bin-5-fill fs-16"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Chưa có người dùng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Phân trang -->
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal xóa user -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc muốn xóa người dùng này? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    // Xử lý modal xóa
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const deleteForm = document.getElementById('deleteUserForm');
            deleteForm.action = this.getAttribute('data-link');
        });
    });

    // Hiển thị SweetAlert khi xóa thành công
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