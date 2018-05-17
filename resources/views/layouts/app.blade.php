<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
        

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="font-awesome/4.5.0/css/font-awesome.min.css" />
    <!-- text fonts -->
    <link rel="stylesheet" href="css/fonts.googleapis.com.css" />
    <!-- ace styles -->
    <link rel="stylesheet" href="css/ace.min.css" />
    <!--[if lte IE 9]>
        <link rel="stylesheet" href="css/ace-part2.min.css" />
    <![endif]-->
    <link rel="stylesheet" href="css/ace-rtl.min.css" />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="login-layout">
    <div id="app">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="js/jquery-2.1.4.min.js"></script>
    @yield('scripts')
</body>
</html>
