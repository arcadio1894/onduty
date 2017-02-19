<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <style>
        .image{
            width: 144px;
            height: 144px;
        }
        @section('padding-left-nav')
            header,main,footer{padding-left:300px}
        @show
    </style>

    <!-- Compiled and minified CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">

    <link rel="stylesheet" href="{{asset('css/ghpages-materialize.css')}}">
    
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<header>
    <nav class="top-nav">
        <div class="container">
            <div class="nav-wrapper">
                <a href="#!" class="brand-logo">{{ config('app.name', 'Laravel') }}</a>
                <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Ingresar</a></li>
                        {{--<li><a href="{{ route('register') }}">Register</a></li>--}}
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-button" data-activates='dropdown1' role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul id="dropdown1" class="dropdown-content" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                                <li>
                                    <a href="#">Editar perfil</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
                <ul class="side-nav" id="mobile-demo">
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-button" data-activates='dropdown2' role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul id="dropdown2" class="dropdown-content" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>

                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container"><a href="#" data-activates="nav-mobile" class="button-collapse top-nav full hide-on-large-only"></a></div>
    @if (Auth::check())
    <ul id="nav-mobile" class="side-nav fixed" style="transform: translateX(0%);">
        <li class="logo">
            <form action="{{ url('/user/image') }}" id="avatarForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="file" style="display: none" id="avatarInput" name="photo">
            </form>
            <img src="{{ asset('images/users/'.Auth::user()->id.'.'.Auth::user()->image) }}" id="avatarImage" class="image" alt="">
        </li>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="bold"><a class="collapsible-header waves-effect waves-teal">Usuarios</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="{{ url('/#') }}">Administradores</a></li>
                            <li><a href="{{ url('/users') }}">Usuarios</a></li>
                            <li><a href="{{ url('/roles') }}">Roles de usuario</a></li>
                        </ul>
                    </div>
                </li>
                <li class="bold"><a class="collapsible-header  waves-effect waves-teal">Entidades</a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="{{ url('/locations') }}">Localizaciones</a></li>
                            <li><a href="{{ url('/areas') }}">Áreas</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            Salir
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>

        </li>
    </ul>
    @endif
</header>
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>


    <script
        src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>

    <script>
        $(".button-collapse").sideNav();
        // Initialize collapse button
        $('.dropdown-button').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrainWidth: false, // Does not change width of dropdown to that of the activator
                    hover: true, // Activate on hover
                    gutter: 0, // Spacing from edge
                    belowOrigin: false, // Displays dropdown below the button
                    alignment: 'left', // Displays dropdown with edge aligned to the left of button
                    stopPropagation: false // Stops event propagation
                }
        );
    </script>
    <script src="{{ asset('js/user/profile.js') }}"></script>

    @yield('scripts')
</body>
</html>
