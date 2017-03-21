@extends('layouts.app')

@section('content')

    <div class="col s12" >
        <div class="card">
            <div class="card-content">
                @if (Auth::user()->role_id < 3)
                    <a data-delay="50"
                       data-tooltip="Nuevo departamento"
                       class="btn-floating btn-large waves-effect waves-light tooltipped teal right modal-trigger" id="newLocation" href="#modal1">
                        <i class="material-icons">add</i></a>
                @endif
                <span class="card-title">Listado de Departamentos</span>
                <p>Se han registrado {{ $departments->count() }} departamentos.</p>
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
                        @foreach ($departments as $department)
                            <tr>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->description }}</td>
                                @if (Auth::user()->role_id < 3)
                                    <td>
                                        <a class="waves-effect waves-light btn" data-edit="{{ $department->id }}" href="#modal2" data-name="{{$department->name}}" data-description="{{$department->description}}" ><i class="material-icons">mode_edit</i></a>
                                        <a class="waves-effect waves-light btn" data-delete="{{ $department->id }}" href="#modal3" data-name="{{$department->name}}" ><i class="material-icons">delete</i></a>
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
            <a class="waves-effect waves-light btn modal-trigger" id="newLocation" href="#modal1">Nueva Área</a>
        @endif
        <br><br>

        <br>

    </div>--}}

    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" action="{{ url('/department/register') }}">
            {{ csrf_field() }}
        <div class="modal-content">
            <h4>Registrar departamento</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the department's name" data-success="right">Nombre del departamento</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the area's description" data-success="right">Descripción del departamento</label>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="#" class="waves-effect waves-green btn-flat" id="save-department">Guardar</a>
        </div>
        </form>
    </div>

    <div id="modal2" class="modal">
        <form class="col s12" id="form-editar" action="{{ url('/department/editar') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Editar departamento</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the departament's name" data-success="right">Nombre del departamento</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the area's description" data-success="right">Descripción del departamento</label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="edit-department">Guardar</a>
            </div>
        </form>
    </div>

    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/department/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar Departamento</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar éste departamento? </p>

                    <div class="input-field col s12">
                        <input disabled id="disabled" type="text" name="name">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-department">Eliminar</a>
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
    <script type="text/javascript" src="{{ asset('js/department/department.js') }}"></script>
@endsection
