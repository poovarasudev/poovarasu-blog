<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>404 | Bootstrap Based Admin Template - Material Design</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ secure_asset('asset/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ secure_asset('asset/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ secure_asset('asset/plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ secure_asset('asset/css/style.css') }}" rel="stylesheet">
</head>

<body class="four-zero-four">
<div class="four-zero-four-container">
    <div class="error-code">404</div>
    <div class="error-message">This page doesn't exist</div>
    <div class="button-place">
        <a href="/" class="btn btn-default btn-lg waves-effect">GO TO HOMEPAGE</a>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="{{ secure_asset('asset/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap Core Js -->
<script src="{{ secure_asset('asset/plugins/bootstrap/js/bootstrap.js') }}"></script>

<!-- Waves Effect Plugin Js -->
<script src="{{ secure_asset('asset/plugins/node-waves/waves.js') }}"></script>
</body>

</html>
