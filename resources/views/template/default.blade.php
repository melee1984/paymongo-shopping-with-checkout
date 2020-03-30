<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <script>
      window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token()
      ]); ?>
    </script>

    <title>{{ __('Test Demo - Checkout') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

  <body>
    <div id="app">
      @yield('content')
    </div>
  </body>

  <script async src="{{mix('js/app.js')}}"></script>

</html>
