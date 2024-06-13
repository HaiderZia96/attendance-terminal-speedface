<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('page_title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{asset('front/coreui/css/vendors/simplebar.css')}}">
    <!-- Main styles for this application-->
    <link href="{{asset('front/coreui/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('front/coreui/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{asset('front/coreui/css/examples.css')}}" rel="stylesheet">
    @stack('head-scripts')
</head>
<body>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    @include('front.layouts.header')

        <div class="container-fluid">
            @yield('content')
        </div>

    @include('front.layouts.footer')
</div>
<!-- CoreUI and necessary plugins-->
<script src="{{asset('front/js/jquery-3.7.0.min.js')}}"></script>
<script src="{{asset('front/js/scripts.js')}}"></script>

@stack('footer-scripts')

</body>
</html>
