<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- Plugins -->
    <link rel="stylesheet" href="{{ asset('datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

    @yield('css')
</head>

@auth

    <body class="hold-transition layout-fixed sidebar-mini">
        <div class="wrapper">
            <!-- Navbar -->
            @include('layouts.partials.navbar')
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            @include('layouts.partials.sidebar')
            <!-- /.sidebar -->

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <div class="content p-3">
                    <div class="card">
                        <div class="card-header font-weight-bold">
                            <h4>@yield('header')</h4>
                        </div>
                        <div class="card-body">
                            @yield('content')
                        </div>
                    </div>
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
                <div class="p-3">
                    <h5>Title</h5>
                    <p>Sidebar content</p>
                </div>
            </aside>
            <!-- /.control-sidebar -->

            <!-- Main Footer -->
            {{-- @include('layouts.partials.footer') --}}
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->


        <!-- jQuery -->
        <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <!-- Plugins -->
        <script src="{{ asset('datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('datatables/dataTables.select.min.js') }}"></script>
        <script src="{{ asset('datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('datatables/buttons.print.min.js') }}"></script>
        <script src="{{ asset('datatables/jszip.min.js') }}"></script>
        <script src="{{ asset('datatables/pdfmake.min.js') }}"></script>
        <script src="{{ asset('datatables/vfs_fonts.js') }}"></script>
        <script src="{{ asset('datatables/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
        <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>

        @if (Session::has('success'))
            <script>
                Swal.fire({
                    position: 'top-end',
                    toast: true,
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    icon: 'success',
                    title: "{{ Session::get('success') }}",
                })
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    position: 'top-end',
                    toast: true,
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    icon: 'error',
                    title: "Opps terjadi kesalahan!",
                })
            </script>
        @endif

        <script>
            $(document).ready(function() {
                // Cek localStorage saat halaman pertama dibuka
                if (localStorage.getItem('theme') === 'dark') {
                    $('body').addClass('dark-mode');
                    $('#toggle-darkmode i').removeClass('fa-moon').addClass('fa-sun');
                }

                // Toggle class saat tombol diklik
                $('#toggle-darkmode').on('click', function(e) {
                    e.preventDefault();
                    $('body').toggleClass('dark-mode');

                    // Ubah ikon
                    $('#toggle-darkmode i').toggleClass('fa-moon fa-sun');

                    // Simpan preferensi
                    if ($('body').hasClass('dark-mode')) {
                        localStorage.setItem('theme', 'dark');
                    } else {
                        localStorage.setItem('theme', 'light');
                    }
                });
            });
        </script>

        @stack('script')
    </body>
@endauth

</html>
