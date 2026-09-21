<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Wellcare Labs') }}</title>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom css -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
</head>
<body data-menu-color="light" data-sidebar="default">

    <!-- Begin page -->
    <div id="app-layout">

        {{-- Topbar --}}
        @include('layouts.topbar')

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
        <footer class="footer bg-light py-2">
    <div class="container-fluid">
        <div class="row">
            <div class="col fs-13 text-muted text-center">
                &copy; <script>document.write(new Date().getFullYear())</script> 
                <strong>Wellcare™</strong>. All Rights Reserved.
            </div>
        </div>
    </div>
</footer>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Vendor JS -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

    <!-- App js (must load last so dependencies are ready) -->
    <script src="{{ asset('assets/js/app.js') }}"></script>


         <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

        <!-- for basic area chart -->
        <script src="../../../apexcharts.com/samples/assets/stock-prices.js"></script>

        <!-- Widgets Init Js -->
        <script src="assets/js/pages/crm-dashboard.init.js"></script>

        <!-- App js-->
        <script src="assets/js/app.js"></script>

    {{-- Extra page-specific scripts --}}
    @stack('scripts')

    <script>
        // Initialize Waves after it's loaded
        if (typeof Waves !== "undefined") {
            Waves.init();
        }
    </script>
</body>
</html>
