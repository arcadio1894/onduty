@extends('layouts.app')

@section('padding-left-nav', '')

@section('content')
    <div class="row">
        <div class="col s6 offset-s3">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Resetear contraseña</span>

                    <form class="s12" role="form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <p>Te enviaremos un enlace a tu correo para cambiar tu contraseña.</p>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input id="email" name="email" type="email" class="validate" value="{{ old('email') }}">
                                <label for="email" data-error="Ingresa un e-mail válido" data-success="right">E-mail</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <button type="submit" class="waves-effect waves-light btn">Enviar link por correo</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                Materialize.toast('{{ $error }}', 4000); // 4000 is the duration of the toast
            @endforeach
        @endif

        @if (session('status'))
            Materialize.toast('{{ session('status') }}', 4000);
        @endif
    </script>
@endsection