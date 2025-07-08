@extends('layouts.master')

@section('title', 'Thống kê')

@section('content')
<div class="container-fluid mt-4">
    <h2>Thống kê công việc</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Người dùng</th>
                            <th>Tổng công việc</th>
                            <th>Đã hoàn thành</th>
                            <th>Chưa hoàn thành</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stats as $user)
                            <tr>
                                <td>{{ $user->name }} ({{ $user->email }})</td>
                                <td>{{ $user->tasks_count }}</td>
                                <td>{{ $user->completed_tasks_count }}</td>
                                <td>{{ $user->pending_tasks_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Chưa có dữ liệu thống kê.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection