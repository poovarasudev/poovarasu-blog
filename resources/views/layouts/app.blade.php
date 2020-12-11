<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>@yield('title')</title>
    <!-- Favicon-->
{{--    <link rel="icon" href="favicon.ico" type="image/x-icon">--}}

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Custom Css -->
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/custom-style-sheet.css') }}">

    <!-- Bootstrap Core Css -->
    <link href="{{ secure_asset('asset/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="{{ secure_asset('asset/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ secure_asset('asset/plugins/node-waves/waves.css') }}" rel="stylesheet"/>

    <!-- Animation Css -->
    <link href="{{ secure_asset('asset/plugins/animate-css/animate.css') }}" rel="stylesheet"/>

    <!-- Sweet Alert Css -->
    <link href="{{ secure_asset('asset/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet"/>

    <!-- Morris Chart Css-->
    <link href="{{ secure_asset('asset/plugins/morrisjs/morris.css') }}" rel="stylesheet"/>

    <!-- Dropzone Css -->
    <link href="{{ secure_asset('asset/plugins/dropzone/dropzone.css')}}" rel="stylesheet">

    <!-- Custom Css -->
    <link href="{{ secure_asset('asset/css/style.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ secure_asset('asset/css/themes/all-themes.css') }}" rel="stylesheet"/>

    <!-- Jquery Core Js -->
    <script src="{{ secure_asset('asset/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ secure_asset('asset/plugins/bootstrap/js/bootstrap.js') }}"></script>

    <!-- Select Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/node-waves/waves.js') }}"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/jquery-countto/jquery.countTo.js') }}"></script>

    <!-- Morris Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ secure_asset('asset/plugins/morrisjs/morris.js') }}"></script>

    <!-- ChartJs -->
    <script src="{{ secure_asset('asset/plugins/chartjs/Chart.bundle.js') }}"></script>

    <!-- Jquery Validation Plugin Css -->
    <script src="{{ secure_asset('asset/plugins/jquery-validation/jquery.validate.js') }}"></script>

    <!-- JQuery Steps Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/jquery-steps/jquery.steps.js') }}"></script>

    <!-- Sweet Alert Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>

    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>

    <!-- Dropzone Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/dropzone/dropzone.js') }}"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/flot-charts/jquery.flot.js') }}"></script>
    <script src="{{ secure_asset('asset/plugins/flot-charts/jquery.flot.resize.js') }}"></script>
    <script src="{{ secure_asset('asset/plugins/flot-charts/jquery.flot.pie.js') }}"></script>
    <script src="{{ secure_asset('asset/plugins/flot-charts/jquery.flot.categories.js') }}"></script>
    <script src="{{ secure_asset('asset/plugins/flot-charts/jquery.flot.time.js') }}"></script>

    <!-- Ckeditor -->
    <script src="{{ secure_asset('asset/plugins/ckeditor/ckeditor.js') }}"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ secure_asset('asset/plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>

    <!--Datatable -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Custom Js -->
    <script src="{{ secure_asset('asset/js/admin.js') }}"></script>
    <script src="{{ secure_asset('asset/js/pages/index.js') }}"></script>
    <script src="{{ secure_asset('asset/js/pages/forms/form-wizard.js') }}"></script>
    <script src="{{ secure_asset('asset/js/pages/forms/form-validation.js') }}"></script>

{{--    <!-- Demo Js -->--}}
    <script src="{{ secure_asset('asset/js/demo.js') }}"></script>

    <link href="{{ secure_asset('asset/select2/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ secure_asset('asset/select2/select2.min.js') }}"></script>



</head>
<body class="theme-grey">

@include('layouts.header')
@auth
    @include('layouts.sidebar')
@endauth
<div id="app">
    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
<footer>
    @yield('script')
</footer>
</html>
