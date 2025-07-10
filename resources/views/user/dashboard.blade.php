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
@endsection
