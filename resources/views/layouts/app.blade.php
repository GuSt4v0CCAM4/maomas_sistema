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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body style="background-color: #ffffff;">
<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="custom-menu">
            <button type="button" id="sidebarCollapse" class="btn btn-outline" style="background: #dd0f29;
         border-color: #dd0f29;">
                <i class="bi bi-list" style="color: white;"></i>
                <span class="visually-hidden">Toggle Menu</span>
            </button>
        </div>
        <div class="p-4 pt-5">
            <h1><a class="navbar-brand" href="{{ url('/home') }}">
                    Maoma's
                </a></h1>
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#homeSubmenu" aria-expanded="false">
                        Caja
                    </a>
                    <div class="collapse" id="homeSubmenu">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/cashregister')}}"><i class="bi bi-caret-right-fill"></i> Registrar Caja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/cashconsult')}}"><i class="bi bi-caret-right-fill"></i> Consultar Caja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/sellerreport')}}"><i class="bi bi-caret-right-fill"></i> Ranking de Trabajadores</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link" href="{{url('/cashreport')}}">Informe</a>
                </li>
                <li class="nav-item mb-lg-5">
                    <a class="nav-link" href="#">Calendario</a>
                </li>
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <small>Cerrar Sesión </small><i class="bi bi-power"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                @endguest
            </ul>

            <!--
                        <div class="mb-5">
                            <h3 class="h6">Subscribe for newsletter</h3>
                            <form action="#" class="colorlib-subscribe-form">
                                <div class="form-group d-flex">
                                    <div class="icon"><span class="icon-paper-plane"></span></div>
                                    <input type="text" class="form-control" placeholder="Enter Email Address">
                                </div>
                            </form>
            </div>-->

            <div class="footer">
                <p>
                    Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Gustavo Ccama</a>
                    </p>
            </div>

        </div>
    </nav>
    <main id="content" class="p-4 p-md-5 pt-5">
        @yield('content')
    </main>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var fullHeight = function() {
            $('.js-fullheight').css('height', $(window).height());
            $(window).resize(function(){
                $('.js-fullheight').css('height', $(window).height());
            });
        };

        fullHeight();

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>

</body>
</html>
