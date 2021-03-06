@extends('layouts.app')

@section('breadcrumbs')
    <div class="row">
        <nav class="breadcrumbs light-blue">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="{{ url('/locations') }}" class="breadcrumb">Localizaciones</a>
                    <a href="{{ url('/workFronts/location/'.$location->id) }}" class="breadcrumb">Localización: {{ $location->name }}</a>
                </div>
            </div>
        </nav>
    </div>
@endsection

@section('content')

    <div class="col s12" >
        <div class="card">
            <div class="card-content">
                @if (Auth::user()->role_id < 3)
                    <a data-delay="50"
                       data-tooltip="Nuevo frente de trabajo"
                       class="btn-floating btn-large waves-effect waves-light tooltipped teal right modal-trigger" id="newLocation" href="#modal1">
                        <i class="material-icons">add</i></a>
                @endif
                <span class="card-title">Listado de Frentes de trabajo</span>
                <p>Frentes de trabajo de la localización {{ $location->name }}</p>
                <table class="responsive-table">
                        <thead>
                        <tr>
                            <th data-field="id">Frente de Trabajo</th>
                            <th data-field="name">Descripción</th>
                            <th data-field="name">Localización</th>
                            @if (Auth::user()->role_id < 3)
                                <th data-field="">Acciones</th>
                            @endif
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($workFronts as $workfront)
                            <tr>
                                <td>{{ $workfront->name }}</td>
                                <td>{{ $workfront->description }}</td>
                                <td>{{ $workfront->location->name }}</td>
                                @if (Auth::user()->role_id < 3)
                                    <td>
                                        <a class="waves-effect waves-light btn" data-edit="{{ $workfront->id }}" data-location="{{ $location->id }}" href="#modal2" data-name="{{$workfront->name}}" data-description="{{$workfront->description}}" ><i class="material-icons">mode_edit</i></a>
                                        <a class="waves-effect waves-light btn" data-delete="{{ $workfront->id }}" href="#modal3" data-name="{{$workfront->name}}" ><i class="material-icons">delete</i></a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
            </div>
        </div>
    </div>

    {{--<div class="row">
        @if (Auth::user()->role_id < 3)
            <a class="waves-effect waves-light btn modal-trigger" id="newPlant" href="#modal1">Nuevo frente de trabajo</a>
        @endif
        <a class="waves-effect waves-light btn" href="{{ url('/locations') }}">Regresar</a>
        <br><br>
        <p>Frentes de trabajo de la localización {{ $location->name }}</p>
        <table class="responsive-table">
            <thead>
            <tr>
                <th data-field="id">Frente de Trabajo</th>
                <th data-field="name">Descripción</th>
                <th data-field="name">Localización</th>
                @if (Auth::user()->role_id < 3)
                    <th data-field="">Acciones</th>
                @endif
            </tr>
            </thead>

            <tbody>
            @foreach ($workFronts as $workfront)
                <tr>
                    <td>{{ $workfront->name }}</td>
                    <td>{{ $workfront->description }}</td>
                    <td>{{ $workfront->location->name }}</td>
                    @if (Auth::user()->role_id < 3)
                        <td>
                            <a class="waves-effect waves-light btn" data-edit="{{ $workfront->id }}" data-location="{{ $location->id }}" href="#modal2" data-name="{{$workfront->name}}" data-description="{{$workfront->description}}" ><i class="material-icons">mode_edit</i></a>
                            <a class="waves-effect waves-light btn" data-delete="{{ $workfront->id }}" href="#modal3" data-name="{{$workfront->name}}" ><i class="material-icons">delete</i></a>
                        </td>
                    @endif
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>--}}

    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" action="{{ url('/workFront/register') }}">
            {{ csrf_field() }}
            <input type="hidden" name="location" value="{{ $location->id }}">
        <div class="modal-content">
            <h4>Registrar frente de trabajo</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the work front's name" data-success="right">Nombre del frente de trabajo</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the work front's description" data-success="right">Descripción del frente de trabajo</label>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="#" class="waves-effect waves-green btn-flat" id="save-workfront">Guardar</a>
        </div>
        </form>
    </div>

    <div id="modal2" class="modal">
        <form class="col s12" id="form-editar" action="{{ url('/workFront/editar') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Editar frente de trabajo</h4>
                <input type="hidden" name="id">
                <input type="hidden" name="location">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the work front's name" data-success="right">Nombre del frente de trabajo</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the work front's description" data-success="right">Descripción del frente de trabajo</label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="edit-workfront">Guardar</a>
            </div>
        </form>
    </div>

    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/workFront/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar frente de trabajo</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar éste frente de trabajo? </p>

                    <div class="input-field col s12">
                        <input disabled id="disabled" type="text" name="workfront">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-workfront">Eliminar</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/workfront/workfront.js') }}"></script>
@endsection
