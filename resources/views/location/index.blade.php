@extends('layouts.app')

@section('breadcrumbs')
    <div class="row">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="{{ url('/locations') }}" class="breadcrumb">Localizaciones</a>
                </div>
            </div>
        </nav>
    </div>
@endsection

@section('content')
    <div class="row">
        <a class="waves-effect waves-light btn modal-trigger" id="newLocation" href="#modal1">Nueva Localización</a>
        <br><br>
        <table class="responsive-table">
            <thead>
            <tr>
                <th data-field="id">Nombre</th>
                <th data-field="name">Descripción</th>
                <th data-field="price">Acciones</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($locations as $location)
                <tr>
                    <td>{{ $location->name }}</td>
                    <td>{{ $location->description }}</td>
                    <td>
                        <a class="waves-effect waves-light btn" data-edit="{{ $location->id }}" href="#modal2" data-name="{{$location->name}}" data-description="{{$location->description}}" ><i class="material-icons">mode_edit</i></a>
                        <a class="waves-effect waves-light btn" data-delete="{{ $location->id }}" href="#modal3" data-name="{{$location->name}}" ><i class="material-icons">delete</i></a>
                        <a class="waves-effect waves-light btn" href="{{ url('/plants/location/'.$location->id) }}" ><i class="material-icons left">playlist_add</i>Plantas</a>

                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" action="{{ url('/location/register') }}">
            {{ csrf_field() }}
        <div class="modal-content">
            <h4>Registrar localización</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the location's name" data-success="right">Nombre de la localización</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the location's description" data-success="right">Descripción de la localización</label>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="#" class="waves-effect waves-green btn-flat" id="save-location">Guardar</a>
        </div>
        </form>
    </div>

    <div id="modal2" class="modal">
        <form class="col s12" id="form-editar" action="{{ url('/location/editar') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Editar localización</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the location's name" data-success="right">Nombre de la locación</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the location's description" data-success="right">Descripción de la locación</label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="edit-location">Guardar</a>
            </div>
        </form>
    </div>

    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/location/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar localización</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar ésta localización? </p>
                    <p>Recuerde que si esta localizació tiene platas registradas no podrá eliminarla.</p>
                    <div class="input-field col s12">
                        <input disabled id="disabled" type="text" name="name">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-location">Eliminar</a>
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

    <script type="text/javascript" src="{{ asset('js/location/location.js') }}"></script>
@endsection
