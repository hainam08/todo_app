<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="{{ URL::asset('build/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/css/app.min.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include('layouts.sidebar-user')
            <div class="col-md-9">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @include('user.modals.logout-modal')

    @yield('scripts')
</body>
</html>