<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Transaction Management Page Of eUp Projects" name="description" />
    <meta content="Login" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('layouts.head-css')
</head>

@section('body')
    @include('layouts.body')
@show
{{--<div class="stage">--}} 
{{--    <div class="dot-pulse"></div>--}}
{{--</div>--}}
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')

        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    {{-- Preloader --}}
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    {{-- End Preloader --}}
    @include ('layouts.php-to-javascript')
    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')


    <script>
        var status ='{{session('status')}}';
        if (status){
            toastNoti(status, "top", "center", "success", "3000", "close", "", "")
        }
    </script>
    </body>
</html>
