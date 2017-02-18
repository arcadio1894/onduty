@extends('layouts.app')

@section('content')

    <div class="row">
        <br>
        <a class="waves-effect waves-light btn modal-trigger" id="newLocation" href="#modal1">Nueva planta</a>
        <a class="waves-effect waves-light btn red" href="{{ url('/locations') }}">Regresar</a>
        <br><br>
        <p>Plantas de la localización {{ $location->name }}</p>
        <table class="responsive-table">
            <thead>
            <tr>
                <th data-field="id">Planta</th>
                <th data-field="name">Descripción</th>
                <th data-field="name">Localización</th>
                <th data-field="price">Acciones</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($plants as $plant)
                <tr>
                    <td>{{ $plant->name }}</td>
                    <td>{{ $plant->description }}</td>
                    <td>{{ $plant->location->name }}</td>
                    <td>
                        <a class="waves-effect waves-light btn" data-edit="{{ $plant->id }}" href="#modal2" data-location="{{ $location->id }}" href="#modal2" data-name="{{$plant->name}}" data-description="{{$plant->description}}" ><i class="material-icons">mode_edit</i></a>
                        <a class="waves-effect waves-light btn" data-delete="{{ $plant->id }}" href="#modal3" data-name="{{$plant->name}}" ><i class="material-icons">delete</i></a>
                        <a class="waves-effect waves-light btn" href="{{ url('/workFronts/plant/'.$plant->id) }}" ><i class="material-icons left">playlist_add</i>Frentes de trabajo</a>

                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" action="{{ url('/plant/register') }}">
            {{ csrf_field() }}
            <input type="hidden" name="location" value="{{ $location->id }}">
        <div class="modal-content">
            <h4>Registrar planta</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the plany's name" data-success="right">Nombre de la planta</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the planta's description" data-success="right">Descripción de la planta</label>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="#" class="waves-effect waves-green btn-flat" id="save-plant">Guardar</a>
        </div>
        </form>
    </div>

    <div id="modal2" class="modal">
        <form class="col s12" id="form-editar" action="{{ url('/plant/editar') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Editar Planta</h4>
                <input type="hidden" name="id">
                <input type="hidden" name="location">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the plant's name" data-success="right">Nombre de la planta</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the plant's description" data-success="right">Descripción de la planta</label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="edit-plant">Guardar</a>
            </div>
        </form>
    </div>

    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/plant/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar planta</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar ésta planta? </p>
                    <p>Recuerde que si esta planta tiene frentes de trabajo registrados no podrá eliminarla.</p>
                    <div class="input-field col s12">
                        <input disabled id="disabled" type="text" name="plant">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-plant">Eliminar</a>
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
    <script type="text/javascript" src="{{ asset('js/plant/plant.js') }}"></script>
@endsection
