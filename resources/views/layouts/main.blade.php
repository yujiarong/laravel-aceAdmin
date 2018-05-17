<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>推广系统</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/font-awesome/4.5.0/css/font-awesome.min.css" />

        <!-- page specific plugin styles -->
        <link rel="stylesheet" href="/css/jquery-ui.custom.min.css" />
        <link rel="stylesheet" href="/css/chosen.min.css" />
        <link rel="stylesheet" href="/css/bootstrap-datepicker3.min.css" />
        <link rel="stylesheet" href="/css/bootstrap-timepicker.min.css" />
        <link rel="stylesheet" href="/css/daterangepicker.min.css" />
        <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css" />
        <link rel="stylesheet" href="/css/bootstrap-colorpicker.min.css" />
        <link rel="stylesheet" href="/css/jquery.gritter.min.css" />
        <link rel="stylesheet" href="{{ asset('/css/sweetalert.css') }}">   
        <!-- text fonts -->
        <link rel="stylesheet" href="/css/fonts.googleapis.com.css" />

        <!-- ace styles -->
        <link rel="stylesheet" href="/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

        <!--[if lte IE 9]>
            <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
        <![endif]-->
        <link rel="stylesheet" href="/css/ace-skins.min.css" />
        <link rel="stylesheet" href="/css/ace-rtl.min.css" />

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->
        <script src="/js/ace-extra.min.js"></script>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="no-skin">
    <div class="wrapper">

            @include('layouts/header')
            @include('layouts/sidebar')     
            <!-- Main content -->
            <div class="main-content">
            @include('messages')
            @yield('content')
            </div>
            @include('layouts/footer')
            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->
    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->
       <script src="/js/jquery-2.1.4.min.js"></script>

    <!-- jQuery 2.1.3 -->
        <script src="/js/bootstrap.min.js"></script>

        <!-- page specific plugin scripts -->

        <!--[if lte IE 8]>
          <script src="/js/excanvas.min.js"></script>
        <![endif]-->
        <script src="/js/jquery-ui.custom.min.js"></script>
        <script src="/js/jquery.ui.touch-punch.min.js"></script>
        <script src="/js/chosen.jquery.min.js"></script>
        <script src="/js/spinbox.min.js"></script>
        <script src="/js/bootstrap-datepicker.min.js"></script>
        <script src="/js/bootstrap-timepicker.min.js"></script>
        <script src="/js/moment.min.js"></script>
        <script src="/js/daterangepicker.min.js"></script>
        <script src="/js/bootstrap-datetimepicker.min.js"></script>
        <script src="/js/bootstrap-colorpicker.min.js"></script>
        <script src="/js/jquery.knob.min.js"></script>
        <script src="/js/autosize.min.js"></script>
        <script src="/js/jquery.inputlimiter.min.js"></script>
        <script src="/js/jquery.maskedinput.min.js"></script>
        <script src="/js/bootstrap-tag.min.js"></script>
        <script src="/js/jquery.easypiechart.min.js"></script>
        <script src="/js/jquery.sparkline.index.min.js"></script>
        <script src="/js/jquery.flot.min.js"></script>
        <script src="/js/jquery.flot.pie.min.js"></script>
        <script src="/js/jquery.flot.resize.min.js"></script>
        <script src="/js/jquery.dataTables.min.js"></script>
        <script src="/js/jquery.dataTables.bootstrap.min.js"></script>
        <script src="/js/dataTables.buttons.min.js"></script>
        <script src="/js/buttons.flash.min.js"></script>
        <script src="/js/buttons.html5.min.js"></script>
        <script src="/js/buttons.print.min.js"></script>
        <script src="/js/buttons.colVis.min.js"></script>
        <script src="/js/dataTables.select.min.js"></script>
        <script src="/js/clipboard.min.js"></script>
        <script src="{{ asset ('/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('/js/sweetalert.min.js') }}"></script>
        <!-- ace scripts -->
        <script src="/js/ace-elements.min.js"></script>
        <script src="/js/ace.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/3.8.0/echarts.common.min.js"></script>
    <script type="text/javascript">
         $('div.alert').not('.alert-important').delay(2000).slideUp(300);

    </script>
    @yield('scripts')

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
          Both of these plugins are recommended to enhance the
          user experience -->
    </body>
</html>