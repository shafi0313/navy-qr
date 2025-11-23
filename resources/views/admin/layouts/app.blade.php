<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Soft Giant BD" name="author" />
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <!-- App favicon --> --}}
    <link rel="shortcut icon" href="{{ imagePath('logo', 'navy_fav.png') }}">

    {{-- <!-- Theme Config Js --> --}}
    <script src="{{ asset('backend/js/config.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('backend/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('backend/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('common/css/custom.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('backend/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- <!-- Datatables css --> --}}
    {{-- <link href="{{ asset('backend/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" /> --}}
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet" />

    {{-- summer note --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css"
        integrity="sha512-ZbehZMIlGA8CTIOtdE+M81uj3mrcgyrh6ZFeG33A4FHECakGrOsTPlPQ8ijjLkxgImrdmSVUHn1j+ApjodYZow=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <div class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    {{-- <!-- Begin page --> --}}
    <div class="wrapper">
        {{-- <!-- ========== Topbar Start ========== --> --}}
        @include('admin.layouts.includes.header')
        {{-- <!-- ========== Topbar End ========== --> --}}

        {{-- <!-- ========== Left Sidebar Start ========== --> --}}
        @include('admin.layouts.includes.navigation')
        {{-- <!-- ========== Left Sidebar End ========== --> --}}

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            {{-- <!-- Footer Start --> --}}
            {{-- @include('admin.layouts.includes.footer') --}}
            {{-- <!-- end Footer --> --}}
        </div>
    </div>
    {{-- <!-- END wrapper --> --}}

    {{-- <!-- Theme Settings --> --}}
    @include('admin.layouts.includes.theme-settings')
    {{-- @include('admin.layouts.includes.alert') --}}

    {{-- <!-- Vendor js --> --}}
    <script src="{{ asset('backend/js/vendor.min.js') }}"></script>
    {{-- Sweet alert --}}
    <script src="{{ asset('common/plugins/sweet-alert/sweetalert-2.min.js') }}"></script>
    {{-- Cute alert --}}
    <link href="{{ asset('common/plugins/cute-alert/cute-alert.css') }}" rel="stylesheet">
    <script src="{{ asset('common/plugins/cute-alert/cute-alert.js') }}"></script>
    {{-- Select 2 --}}
    <link href="{{ asset('common/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('common/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('common/js/http.js') }}"></script>
    <script src="{{ asset('common/js/custom.js') }}"></script>

    <!-- Datatables js -->
    {{-- <script src="{{ asset('backend/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script> --}}

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    {{-- Summer Note --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"
        integrity="sha512-lVkQNgKabKsM1DA/qbhJRFQU8TuwkLF2vSN3iU/c7+iayKs08Y8GXqfFxxTZr1IcpMovXnf2N/ZZoMgmZep1YQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    {{-- <!-- App js --> --}}
    <script src="{{ asset('backend/js/app.min.js') }}"></script>
    @include('sweetalert::alert')

    @stack('scripts')
    <div id="ajax_modal_container"></div>
</body>

</html>
