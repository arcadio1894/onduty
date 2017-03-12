@extends('layouts.app')

@section('content')

    <div class="col s12" >
        <div class="card">
            <div class="card-content">
                @if (Auth::user()->role_id < 3)
                <a data-delay="50"
                   data-tooltip="Nuevo cargo"
                   class="btn-floating btn-large waves-effect waves-light tooltipped teal right modal-trigger" id="newLocation" href="#modal1">
                    <i class="material-icons">add</i></a>
                @endif
                <span class="card-title">Listado de Cargos</span>
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th data-field="id">Nombre</th>
                            <th data-field="name">Descripción</th>
                            @if (Auth::user()->role_id <3)
                                <th data-field="">Acciones</th>
                            @endif
                        </tr>
                        </thead>

                    <tbody>
                    @foreach ($positions as $position)
                        <tr>
                            <td>{{ $position->name }}</td>
                            <td>{{ $position->description }}</td>
                            @if (Auth::user()->role_id < 3)
                                <td>
                                    <a class="waves-effect waves-light btn" data-edit="{{ $position->id }}" href="#modal2" data-name="{{$position->name}}" data-description="{{$position->description}}" ><i class="material-icons">mode_edit</i></a>
                                    <a class="waves-effect waves-light btn" data-delete="{{ $position->id }}" href="#modal3" data-name="{{$position->name}}" ><i class="material-icons">delete</i></a>
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
        <br>
        @if (Auth::user()->role_id < 3)
            <a class="waves-effect waves-light btn modal-trigger" id="newLocation" href="#modal1">Nuevo Cargo</a>
        @endif
        <br><br>
        <table class="responsive-table">
            <thead>
            <tr>
                <th data-field="id">Nombre</th>
                <th data-field="name">Descripción</th>
                @if (Auth::user()->role_id <3)
                    <th data-field="">Acciones</th>
                @endif
            </tr>
            </thead>

            <tbody>
            @foreach ($positions as $position)
                <tr>
                    <td>{{ $position->name }}</td>
                    <td>{{ $position->description }}</td>
                    @if (Auth::user()->role_id < 3)
                        <td>
                            <a class="waves-effect waves-light btn" data-edit="{{ $position->id }}" href="#modal2" data-name="{{$position->name}}" data-description="{{$position->description}}" ><i class="material-icons">mode_edit</i></a>
                            <a class="waves-effect waves-light btn" data-delete="{{ $position->id }}" href="#modal3" data-name="{{$position->name}}" ><i class="material-icons">delete</i></a>
                        </td>
                    @endif
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>--}}

    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" action="{{ url('/position/register') }}">
            {{ csrf_field() }}
        <div class="modal-content">
            <h4>Registrar cargo</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the position's name" data-success="right">Nombre del cargo</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the position's description" data-success="right">Descripción del cargo</label>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="#" class="waves-effect waves-green btn-flat" id="save-area">Guardar</a>
        </div>
        </form>
    </div>

    <div id="modal2" class="modal">
        <form class="col s12" id="form-editar" action="{{ url('/position/editar') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Editar cargo</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the position's name" data-success="right">Nombre del cargo</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the position's description" data-success="right">Descripción del cargo</label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="edit-area">Guardar</a>
            </div>
        </form>
    </div>

    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/position/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar cargo</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar ésta cargo? </p>
                    <p>Si existen usuarios con éste cargo no podrá eliminarlo </p>
                    <div class="input-field col s12">
                        <input disabled id="disabled" type="text" name="name">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-area">Eliminar</a>
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
    <script type="text/javascript" src="{{ asset('js/position/position.js') }}"></script>
@endsection
