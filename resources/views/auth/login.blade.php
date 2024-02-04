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
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div id="screen">

    <div id="card">
        <div id="image" style="background-image: url('{{asset('/resources/images/image.png') }}');">
        </div>
        <div id="text_welcome">
            Bienvenido a Maoma's Store
        </div>
    </div>
    <div id="card_r">
        <div id="sleep" class="mb-5">
            Maoma's
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
        <div id="login" class="mb-4">
            Iniciar Sesión
        </div>
        <label for="input_email" class="label_login mb-2">
            Ingrese su correo:
        </label>
            <br>
            <input id="input_email" type="email" class=" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus >
            @error('email')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
            <br class="mb-4">
        <label for="input_password" class="label_login mb-2">
            Ingrese su contraseña:
        </label>
            <br>
            <input id="input_password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
            <br class="mb-4">
            <div class="mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
            </div>
            <button type="submit" id="button_login" class="mb-2"> Iniciar Sesion</button>
            <br>
            @if (Route::has('password.request'))
                <a id="forgot_password " href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </form>
    </div>
</div>
</body>
