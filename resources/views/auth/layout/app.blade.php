<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <title>{{ $title ?? 'Sign in' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('assets/admin/js/common.js') }}"></script>
    @stack('cdn')
    @stack('css')
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    @stack('js')

    @if (session()->has('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    html: "{{ session('success') }}",
                    icon: 'success'
                });
            });
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    html: "{{ session('error') }}",
                    icon: 'error'
                });
            });
        </script>
    @endif
</body>

</html>
