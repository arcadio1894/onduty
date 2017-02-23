@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="section col s12 m9 l10">
            <div id="download" class="row scrollspy">
                <h2 class="col s12 header">Hola, {{ auth()->user()->name }}</h2>
                <p class="caption col s12">
                    Bienvenido a la plataforma de Conciviles.
                </p>
                <p class="caption col s12">
                    En el lado izquierdo encontrarás un menú con las opciones que ofrece el sistema.
                </p>
            </div>
        </div>
    </div>
@endsection
