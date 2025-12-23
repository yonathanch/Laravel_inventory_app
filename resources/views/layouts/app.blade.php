<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .bg-purple-light {
            background: linear-gradient(135deg,
                    #bd7ce8 0%,
                    #8348c1 40%,
                    #b538db 100%);
        }

        .navbar-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>

<body class="bg-purple-light">
    <div id="app" class="vh-100 d-flex flex-column">
        <nav class="navbar navbar-expand-md navbar-light  shadow-sm navbar-glass">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/login') }}">
                    {{ env('APP_NAME') }}
                </a>
            </div>
        </nav>

        <main class="flex-fill d-flex justify-content-center align-items-center">
            <div class="col-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
