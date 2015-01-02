<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html lang="en">
<head>
    @include('layouts.includes.head')
</head>
<body>
    <div id="wrapper" class="container-fluid">
        <div id="body" class="main">
            <div class="row">
                @yield('main')
            </div>
        </div>
        @include('layouts.includes.footer')
    </div>
</body>
<div id="back-to-top" class="back-to-top hide">
    <div class="inner">
        <a href="#" title="Back to Top">
            <i class="fa fa-arrow-up fa-3x"></i>
        </a>
    </div>
</div>
@include('layouts.includes.script')
</html>