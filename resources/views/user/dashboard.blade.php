@extends('layouts.master-user')

@section('title', 'Danh sách công việc')
@section('content')
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    <h2>Xin chào {{Auth::user()->name}}</h2>
    <p>Chào mừng bạn đến với todo app</p>
    <div class="card">
    <div class="card-header">
        <h5>Thông báo</h5>
    </div>
    <ul class="list-group">
    @forelse($notifications as $noti)
        <li class="list-group-item">
            {!! $noti->data['message'] !!}
            <br>
            <small class="text-muted">{{ $noti->created_at->diffForHumans() }}</small>
        </li>
    @empty
        <li class="list-group-item">Không có thông báo nào.</li>
    @endforelse
</ul>

</div>

@endsection
