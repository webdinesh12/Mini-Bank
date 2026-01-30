<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <title>{{ empty($title) ? '' : get_option('site_title') . ' | ' . $title }}</title>
    <link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('assets/admin/js/common.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @stack('cdn')
    @stack('css')
</head>

<body>
    @include('layouts.alerts')
    <div class="wrapper">
        @include('layouts.sidebar')
        <div class="main">
            @include('layouts.navbar')
            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>
            @include('layouts.footer')
        </div>
    </div>
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#logoutBtn').on('click', function() {
                let url = $(this).data('href');
                Swal.fire({
                    html: "Are you sure you want to logout?",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Logout",
                    cancleButtonText: "No",
                    icon: "warning",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        })
    </script>
    @stack('js')
    @stack('modal')
</body>

</html>
