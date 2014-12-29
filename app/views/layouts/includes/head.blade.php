<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="_token" content="{{ csrf_token() }}">

<title>@if (isset($title)) {{ $title }} @endif</title>

<link rel="shortcut icon" href="/favicon.png" type="image/png"/>

{{ HTML::style('css/bootstrap.min.css') }}
{{ HTML::style('css/font-awesome.min.css') }}
{{ HTML::style('css/animate.css') }}
{{ HTML::style('css/app.css') }}
@yield('css')