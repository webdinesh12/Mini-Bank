@php
    $msg = '';
    $type = '';
    if (session()->has('success')) {
        $msg = session('success');
        $type = 'success';
        session()->forget('success');
    } elseif (session()->has('error')) {
        $msg = session('error');
        $type = 'error';
        session()->forget('error');
    } elseif (session()->has('info')) {
        $msg = session('info');
        $type = 'info';
        session()->forget('info');
    } elseif (session()->has('warning')) {
        $msg = session('warning');
        $type = 'warning';
        session()->forget('warning');
    }
@endphp
@if (!empty($msg))
    <script>
        Swal.fire({
            title: '',
            html: "{{ $msg }}",
            icon: "{{ $type }}"
        });
    </script>
@endif
