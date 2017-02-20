@extends('layouts.app')

@section('padding-left-nav', '')

@section('content')
    <div class="row">
        <div class="col s6 offset-s3">
            <div class="card">

                <div class="card-content">
                    <span class="card-title">Inicio de sesión</span>
                    <form class="s12" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input id="email" name="email" type="email" class="validate" value="{{ old('email') }}">
                                <label for="email" data-error="Ingresa un e-mail válido" data-success="right">E-mail</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock</i>
                                <input id="password" name="password" type="password" class="validate" min="6" value="{{ old('password') }}" autocomplete="new-password">
                                <label for="password" data-error="Por favor ingresa tu contraseña" data-success="right">Contraseña</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input type="checkbox" id="test5" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                <label for="test5">Recordar sesión</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <button type="submit" class="waves-effect waves-light btn">Ingresar</button>
                                <a href="{{ route('password.request') }}" class="right">Olvidaste tu contraseña?</a>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if (count($errors) > 0)
        <script>
            @foreach ($errors->all() as $error)
                Materialize.toast('{{ $error }}', 4000); // 4000 is the duration of the toast
            @endforeach
        </script>
    @endif
@endsection
