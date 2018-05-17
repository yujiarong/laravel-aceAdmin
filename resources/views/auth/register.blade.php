@extends('layouts.app')

@section('content')
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <i class="ace-icon fa fa-leaf green"></i>
                       {{--              <span class="red">Amazon</span> --}}
                                    <span class="white" id="id-text2">Laravel Ace</span>
                                </h1>
                                <h4 class="blue" id="id-company-text">&copy; ace</h4>
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">

                                <div id="signup-box" class="signup-box widget-box no-border visible">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header green lighter bigger">
                                                <i class="ace-icon fa fa-users blue"></i>
                                                New User Registration
                                            </h4>

                                            <div class="space-6"></div>
                                            <p> Enter your details to begin: </p>

                                            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                                                   {{ csrf_field() }}
 {{--                                                    @if (session()->get('errors'))
                                                    <div class="alert alert-danger">
                                                        @if(is_array(json_decode(session()->get('errors'), true)))
                                                            {!! implode('', session()->get('errors')->all(':message<br/>')) !!}
                                                        @else
                                                            {!! session()->get('errors') !!}
                                                        @endif
                                                    </div>
                                                    @endif    --}} 
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required  autofocus/>
                                                            <i class="ace-icon fa fa-envelope"></i>
                                                            @if ($errors->has('email'))
                                                                <span class="help-block">
                                                                    <strong><font  color="red">{{ $errors->first('email') }}</font></strong>
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" placeholder="Username" name="name" value="{{ old('name') }}" required autofocus />
                                                            <i class="ace-icon fa fa-user"></i>
                                                            @if ($errors->has('name'))
                                                                <span class="help-block">
                                                                    <strong><font  color="red">{{ $errors->first('name') }}</font></strong>
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" placeholder="Password" name="password" required />
                                                            <i class="ace-icon fa fa-lock"></i>
                                                            @if ($errors->has('password'))
                                                                <span class="help-block">
                                                                    <strong><font  color="red">{{ $errors->first('password') }}</font></strong>
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" placeholder="Repeat password" name="password_confirmation" required />
                                                            <i class="ace-icon fa fa-retweet"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl">
                                                            I accept the
                                                            <a href="#">User Agreement</a>
                                                        </span>
                                                    </label>

                                                    <div class="space-24"></div>

                                                    <div class="clearfix">
                                                        <button type="reset" class="width-30 pull-left btn btn-sm">
                                                            <i class="ace-icon fa fa-refresh"></i>
                                                            <span class="bigger-110">Reset</span>
                                                        </button>

                                                        <button type="submit" class="width-65 pull-right btn btn-sm btn-success">
                                                            <span class="bigger-110">Register</span>

                                                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>

                                        <div class="toolbar center">
                                            <a href="/login" data-target="#login-box" class="back-to-login-link">
                                                <i class="ace-icon fa fa-arrow-left"></i>
                                                Back to login
                                            </a>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.signup-box -->
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->
@endsection
@section('scripts')

        <script type="text/javascript">
         
        </script>
@endsection