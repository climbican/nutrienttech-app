<!DOCTYPE html>
<html class="login-content" data-ng-app="ledgedogAdmin">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nutritech Admin</title>

    <!-- Vendor CSS -->
    <link href="{{url('vendors/bower_components/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css')}}" rel="stylesheet">

    <!-- CSS -->
    <link href="{{url('css/app.min.1.css')}}" rel="stylesheet">
    <link href="{{url('css/app.min.2.css')}}" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico">
</head>

<body class="login-content" data-ng-controller="loginCtrl as lctrl">
<div class="lc-block transparent" style="visibility: visible; display:block; background:transparent; border:none; box-shadow: none; margin:5% auto 0px auto;">
    <img src="{{url('images/logo.png')}}" style="height:140px;"/>
</div>

<div class="lc-block" id="l-login" data-ng-class="{ 'toggled': lctrl.login === 1 }" data-ng-if="lctrl.login === 1" style="margin-top:40px; vertical-align:top;">
    <form role="form" method="POST" name="login_form" action="{{ url('/login') }}">
        {!! csrf_field() !!}
        <div class="input-group m-b-20">
            <span class="input-group-addon{{ $errors->has('email') ? ' has-error' : '' }}"><i class="zmdi zmdi-account"></i></span>
            <div class="fg-line">
                <input type="text" name="email" class="form-control" placeholder="email" value="{{ old('email') }}">
            </div>
            @if ($errors->has('email'))
                <span class="help-block">
                        <strong style="color:#ff0000;">{{ $errors->first('email') }}</strong>
                    </span>
            @endif
        </div>

        <div class="input-group m-b-20">
            <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
            <div class="fg-line">
                <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" placeholder="Password">
            </div>
            @if ($errors->has('password'))
                <span class="help-block">
                        <strong style="color:#ff0000;">{{ $errors->first('password') }}</strong>
                    </span>
            @endif
        </div>
        <div class="clearfix"></div>
        <a href="" class="btn btn-login btn-danger btn-float" onclick="document.forms['login_form'].submit();return false;"><i class="zmdi zmdi-arrow-forward"></i></a>
    </form>
</div>

<!-- Register -->
<div class="lc-block" id="l-register" data-ng-class="{ 'toggled': lctrl.register === 1 }" data-ng-if="lctrl.register === 1">
    <form role="form" method="POST" name="registration-form" action="{{ url('/register') }}">
        {{ csrf_field() }}
        <div class="input-group m-b-20">
            <span class="input-group-addon {{ $errors->has('name') ? ' has-error' : '' }}"><i class="zmdi zmdi-account"></i></span>
            <div class="fg-line">
                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name') }}">
            </div>
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong style="color:#ff0000;">{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

        <div class="input-group m-b-20">
            <span class="input-group-addon {{ $errors->has('email') ? ' has-error' : '' }}"><i class="zmdi zmdi-email"></i></span>
            <div class="fg-line">
                <input type="email" id="email" name="email" class="form-control" value="{{$errors->first('email')}}" placeholder="Email Address">
            </div>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <!-- password -->
        <div class="input-group m-b-20">
            <span class="input-group-addon {{ $errors->has('password') ? ' has-error' : '' }}"><i class="zmdi zmdi-male"></i></span>
            <div class="fg-line">
                <input type="password" class="form-control" placeholder="Password">
            </div>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <!-- Confirm Password -->
        <div class="input-group m-b-20">
            <span class="input-group-addon {{ $errors->has('password_confirmation') ? ' has-error' : '' }}"><i class="zmdi zmdi-male"></i></span>
            <div class="fg-line">
                <input  id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Password">
            </div>
            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>

        <div class="clearfix"></div>

        <div class="checkbox">
            <label>
                <input type="checkbox" value="">
                <i class="input-helper"></i>
                Accept the license agreement
            </label>
        </div>

        <a href="" class="btn btn-login btn-danger btn-float"onclick="document.forms['registration-form'].submit();return false;"><i class="zmdi zmdi-arrow-forward"></i></a>

        <ul class="login-navigation">
            <li data-block="#l-login" class="bgm-green" data-ng-click="lctrl.register = 0; lctrl.login = 1">Login</li>
            <li data-block="#l-forget-password" class="bgm-orange" data-ng-click="lctrl.register = 0; lctrl.forgot = 1">Forgot Password?</li>
        </ul>
    </form>
</div>

<!-- Forgot Password -->
<div class="lc-block" id="l-forget-password" data-ng-class="{ 'toggled': lctrl.forgot === 1 }" data-ng-if="lctrl.forgot === 1">
    <p class="text-left">Enter the email address you registered with and you will recieve an email with a link to reset your password.</p>

    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
        <div class="fg-line">
            <input type="text" class="form-control" placeholder="Email Address">
        </div>
    </div>

    <a href="" class="btn btn-login btn-danger btn-float"><i class="zmdi zmdi-arrow-forward"></i></a>

    <ul class="login-navigation">
        <li data-block="#l-login" class="bgm-green" data-ng-click="lctrl.forgot = 0; lctrl.login = 1">Login</li>
    </ul>
</div>

<!-- Older IE warning message -->
<!--[if lt IE 9]>
<div class="ie-warning">
    <h1 class="c-white">Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="{{url('')}}img/browsers/chrome.png" alt="">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="img/browsers/firefox.png" alt="">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="img/browsers/opera.png" alt="">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="img/browsers/safari.png" alt="">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="img/browsers/ie.png" alt="">
                    <div>IE (New)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->


<!-- Core -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>-->
<!-- local dev -->
<script src="{{url('js/jquery-3.1.1.min.js')}}"></script>
<script src="{{url('vendors/bower_components/chosen/chosen.jquery.min.js')}}"></script>

<!-- Angular -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular-animate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular-resource.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular-messages.js"></script>-->

<!-- Local Dev Angular Scripts -->
<script src="{{url('vendors/bower_components/angular/angular.js')}}"></script>
<script src="{{url('vendors/bower_components/angular-animate/angular-animate.min.js')}}"></script>
<script src="{{url('vendors/bower_components/angular-resource/angular-resource.js')}}"></script>
<script src="{{url('vendors/bower_components/angular-messages/angular-messages.min.js')}}"></script>
<script src="{{url('vendors/bower_components/angular-messages/angular-messages.min.js.map')}}"></script>

<!-- Angular Modules -->
<script src="{{url('vendors/bower_components/angular-ui-router/release/angular-ui-router.min.js')}}"></script>
<script src="{{url('vendors/bower_components/angular-loading-bar/src/loading-bar.js')}}"></script>
<script src="{{url('vendors/bower_components/oclazyload/dist/ocLazyLoad.min.js')}}"></script>
<script src="{{url('vendors/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js')}}"></script>

<!-- Common Vendors -->
<script src="{{url('vendors/trix-0.9.2.js')}}"></script>
<script src="{{url('vendors/angular-trix.js')}}"></script>
<script src="{{url('vendors/bower_components/Waves/dist/waves.min.js')}}"></script>
<script src="{{url('vendors/bootstrap-growl/bootstrap-growl.min.js')}}"></script>
<script src="{{url('vendors/fileinput/fileinput.min.js')}}"></script>
<script src="{{url('vendors/bower_components/angular-chosen-localytics/angular-chosen.js')}}"></script>
<script src="{{url('vendors/bower_components/autosize/dist/autosize.min.js')}}"></script>

<!-- Placeholder for IE9 -->
<!--[if IE 9 ]>
<script src="{{url('vendors/bower_components/jquery-placeholder')}}/jquery.placeholder.min.js"></script>
<![endif]-->

<!-- App level -->
<script src="{{url('js/app.js')}}"></script>
<script src="{{url('js/controllers/main.js')}}"></script>
<script src="{{url('js/services.js')}}"></script>
<script src="{{url('js/templates.js')}}"></script>
<script src="{{url('js/controllers/ui-bootstrap.js')}}"></script>
<script src="{{url('js/controllers/table.js')}}"></script>
<script src="{{url('js/ngmatch.js')}}"></script>


<!-- Template Modules -->
<script src="{{url('js/modules/template.js')}}"></script>
<script src="{{url('js/modules/ui.js')}}"></script>
<script src="{{url('js/modules/charts/flot.js')}}"></script>
<script src="{{url('js/modules/charts/other-charts.js')}}"></script>
<script src="{{url('js/modules/form.js')}}"></script>
<script src="{{url('js/modules/media.js')}}"></script>
<script src="{{url('js/modules/components.js')}}"></script>
<script src="{{url('js/modules/calendar.js')}}"></script>
<script src="{{url('js/modules/demo.js')}}"></script>
<script src="{{url('js/modules/ng-messages.js')}}"></script>
</body>
</html>
