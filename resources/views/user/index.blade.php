@extends('layouts.master-user')

@section('title', 'Danh sách công việc')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .status-checkbox {
        transform: scale(1.5);
        margin-right: 10px;
    }
    .status-form {
        display: inline-flex;
        align-items: center;
    }
    .status-label {
        cursor: pointer;
    }
    .auto-dismiss {
    animation: fadeOut 0.5s ease-in-out 3s forwards;
    }

        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
                height: 0;
                padding: 0;
                margin: 0;
            }
        }
</style>

<div class="container mt-5">
    <h2>Danh sách công việc</h2>

    @if (session('success'))
        <div class="alert alert-success auto-dismiss ">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger auto-dismiss">{{ session('error') }}</div>
    @endif

    <!-- Form tìm kiếm và lọc -->
    <form method="GET" action="{{ route('user.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
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
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
        </div>
    </form>

    <!-- Nút mở modal thêm công việc -->
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createTaskModal">
        Thêm công việc mới
    </button>

    <!-- Form hoàn thành hàng loạt -->
    <form action="{{ route('tasks.bulk-complete') }}" method="POST" id="bulk-form">
        @csrf
        <button class="btn btn-success mb-2" type="submit">Hoàn thành</button>
    </form>

    <!-- Bảng danh sách công việc -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>Tiêu đề</th>
                    <th>Mô tả</th>
                    <th>Hạn hoàn thành</th>
                    <th>Thời gian nhắc </th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>
                            <input type="checkbox" name="task_ids[]" value="{{ $task->id }}" class="task-checkbox" form="bulk-form"  {{ $task->status === 'Completed' ? 'checked' : '' }}>
                        </td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description ?? 'Không có' }}</td>
                        <td>{{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Không có' }}</td>
                       <td>
                            {{ $task->remind_at ? \Carbon\Carbon::parse($task->remind_at)->format('d/m/Y H:i') : 'Không có' }}

                            <form action="{{ route('tasks.toggleReminder', $task->id) }}" method="POST" style="display:inline-block; margin-left:10px;">
                                @csrf
                               
                                  <button type="submit" class="btn btn-sm border-0 bg-transparent">
                                    @if ($task->is_reminder_enabled)
                                        <i class="fas fa-bell text-success" title="Nhắc nhở đang bật"></i>
                                    @else
                                        <i class="fas fa-bell-slash text-muted" title="Nhắc nhở đang tắt"></i>
                                    @endif
                                </button>
                            </form>
                        </td>

                        <td>
                            <!-- Form cập nhật trạng thái riêng -->
                            <form action="{{ route('tasks.update', $task) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm
                                    {{ $task->status == 'New' ? 'bg-info text-white' : '' }}
                                    {{ $task->status == 'Inprogress' ? 'bg-warning text-dark' : '' }}
                                    {{ $task->status == 'Pending' ? 'bg-secondary text-white' : '' }}
                                    {{ $task->status == 'Completed' ? 'bg-success text-white' : '' }}"
                                                        
                                
                                >
                                    <option value="New" {{ $task->status == 'New' ? 'selected' : '' }}>Mới tạo</option>
                                    <option value="Inprogress" {{ $task->status == 'Inprogress' ? 'selected' : '' }}>Đang thực hiện</option>
                                    <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                    <option  value="Pending"  {{ $task->status == 'Pending' ? 'selected' : '' }}>Đang chờ</option>
                                </select>
                                <input type="hidden" name="title" value="{{ $task->title }}">
                                <input type="hidden" name="description" value="{{ $task->description }}">
                                <input type="hidden" name="due_date" value="{{ $task->due_date }}">
                            </form>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                                Sửa
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal({{ $task->id }})">
                                Xóa
                            </button>
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

    <!-- Form delete ẩn -->
    @foreach ($tasks as $task)
        <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <!-- Phân trang -->
    {{ $tasks->links() }}

    <!-- Modal thêm công việc -->
    @include('user.modals.create-modal')

    <!-- Modal sửa -->
    @foreach ($tasks as $task)
        @include('user.modals.edit-modal', ['task' => $task])
    @endforeach
    @include('user.modals.delete-modal')
</div>
@endsection

@section('scripts')
<script>
    function showDeleteModal(taskId) {
        const form = document.getElementById('deleteTaskForm');
        form.action = `/tasks/${taskId}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }
</script>
@endsection
