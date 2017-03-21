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
        .image {
            width: 144px;
            margin: 1em;
        }
        ul.side-nav.fixed li.logo a:hover, ul.side-nav.fixed li.logo a.active {
            background-color: #ffffff;
        }
        @section('padding-left-nav')
            header,main,footer{padding-left:300px;}
        @show
    </style>
    @yield('styles')

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
        @yield('breadcrumbs')

        @if (Auth::check())

            <ul id="nav-mobile" class="side-nav fixed" style="transform: translateX(0%);">
                <li class="logo">
                    <a href="{{ url('/') }}">
                        <img class="image" src="{{ asset('images/logo/logo.png') }}" alt="Logo Conciviles">
                    </a>
                </li>
                <li class="no-padding center-align">
                    <form action="{{ url('/user/image') }}" id="avatarForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="file" style="display: none" id="avatarInput" name="photo">
                    </form>

                    @if (auth()->user()->image)
                        <img src=" {{ asset('images/users/'.Auth::user()->id.'.'.Auth::user()->image) }}" id="avatarImage" class="image" alt="Avatar del usuario">
                    @else
                        <img src=" {{ asset('images/users/default.jpg') }}" id="avatarImage" class="image" alt="Avatar por defecto">
                    @endif
                </li>
                <li class="no-padding">
                    <ul class="collapsible collapsible-accordion">
                        <li class="bold"><a class="collapsible-header waves-effect waves-teal">Usuarios</a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a href="{{ url('/users') }}">Usuarios</a></li>
                                    <li><a href="{{ url('/roles') }}">Roles de usuario</a></li>
                                    <li><a href="{{ url('/positions') }}">Cargos</a></li>
                                    <li><a href="{{ url('/departments') }}">Departamentos</a></li>
                                </ul>
                            </div>
                        </li>
                        @if (Auth::user()->role_id < 3)
                            <li class="bold"><a class="collapsible-header  waves-effect waves-teal">Entidades</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="{{ url('/locations') }}">Localizaciones</a></li>
                                        <li><a href="{{ url('/areas') }}">Áreas</a></li>
                                        <li><a href="{{ url('/critical-risks') }}">Riesgos críticos</a></li>

                                    </ul>
                                </div>
                            </li>
                        @endif
                        <li class="bold"><a class="collapsible-header waves-effect waves-teal" href="{{ url('/informes') }}">Informes</a></li>
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

            <a href="#" data-activates="nav-mobile" class="button-collapse hide-on-large-only"><i class="material-icons">menu</i></a>
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
    </script>
    <script src="{{ asset('js/user/profile.js') }}"></script>

    @yield('scripts')
</body>
</html>
