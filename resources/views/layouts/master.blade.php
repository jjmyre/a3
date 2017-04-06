<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="{{ asset('favicon.ico') }}" rel="shortcut icon" />
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" />
	<link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet" />
	<link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel='stylesheet')" />
    <link href="{{ asset('css/style.css') }}" type='text/css' rel='stylesheet' />
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.js' type='text/javascript' ></script>
    <script src="{{ asset('js/javascript.js') }}" type='text/javascript'></script>
	<title>
        @yield('title')
    </title>
</head>

<body>
	@yield('content')
</body>

</html>