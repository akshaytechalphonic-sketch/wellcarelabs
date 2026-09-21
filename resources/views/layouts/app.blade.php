<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Wellcare Labs')</title>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom css -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    {{-- 🔥 Page-specific styles pushed from views (like notifications) --}}
    @stack('styles')
</head>
<body data-menu-color="light" data-sidebar="default">

    <!-- Begin page -->
    <div id="app-layout">

        {{-- Topbar --}}
        @include('layouts.topbar')

        {{-- Toast container (global success/error notifications) --}}
        <div aria-live="polite" aria-atomic="true" class="position-fixed" style="top:1rem; right:1rem; z-index:1080;">
            <div id="globalFlashToastWrapper">
                @if(session('success'))
                    <div class="toast align-items-center text-bg-success border-0 mb-2" id="packageSuccessToast" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">{!! session('success') !!}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if(session('danger') || session('error'))
                    <div class="toast align-items-center text-bg-danger border-0 mb-2" id="packageDangerToast" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">{!! session('danger') ?? session('error') !!}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

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
                            <strong>Wellcarelabs™</strong>. All Rights Reserved.
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
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

    <!-- SweetAlert2 (global) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Delegated delete confirmation (works for AJAX-inserted forms too) -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      window.deleteDelegateInstalled = true;

      document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form.classList.contains('delete-form')) return;
        e.preventDefault();

        let message = form.getAttribute('data-confirm');
        if (!message) {
          const btn = form.querySelector('button[data-package-name], button[data-test-name], button[data-banner-name], button[data-name]');
          message = btn?.dataset.packageName || btn?.dataset.testName || btn?.dataset.bannerName || btn?.dataset.name || 'this item';
        }

        const htmlMessage = `<div style="font-size:15px;line-height:1.4">Are you sure you want to delete <strong>${message}</strong>?</div><p class="text-muted small mb-0">This action cannot be undone.</p>`;

        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Confirm Delete',
            html: htmlMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
          }).then((result) => {
            if (result.isConfirmed) {
              const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
              if (submitBtn) submitBtn.disabled = true;
              form.submit();
            }
          });
        } else {
          if (confirm(`Are you sure you want to delete ${message}?`)) {
            form.submit();
          }
        }
      }, true);
    });
    </script>

    <!-- Global Alerts JS (compiled from resources/js/admin-alerts.js) -->
    <script src="{{ asset('js/admin-alerts.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    {{-- Extra page-specific scripts --}}
    @stack('scripts')

    <script>
        if (typeof Waves !== "undefined") Waves.init();
    </script>
</body>
</html>
