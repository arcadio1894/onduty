@extends('layouts.app')

@section('content')

    <div class="row">
        <br>
        <a class="waves-effect waves-light btn modal-trigger" id="newLocation" href="#modal1">Nuevo Rol</a>
        <br><br>
        <table class="responsive-table">
            <thead>
            <tr>
                <th data-field="id">Rol</th>
                <th data-field="name">Descripción</th>
                @if (Auth::user()->role_id <3)
                    <th data-field="">Acciones</th>
                @endif
            </tr>
            </thead>

            <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->description }}</td>
                    @if (Auth::user()->role_id < 3)
                        <td>
                            <a class="waves-effect waves-light btn" data-edit="{{ $role->id }}" href="#modal2" data-name="{{$role->name}}" data-description="{{$role->description}}" ><i class="material-icons">mode_edit</i></a>
                            <a class="waves-effect waves-light btn" data-delete="{{ $role->id }}" href="#modal3" data-name="{{$role->name}}" ><i class="material-icons">delete</i></a>
                        </td>
                    @endif
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" action="{{ url('/role/register') }}">
            {{ csrf_field() }}
        <div class="modal-content">
            <h4>Registrar Rol</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the rol's name" data-success="right">Nombre del rol</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the role's description" data-success="right">Descripción del rol</label>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="#" class="waves-effect waves-green btn-flat" id="save-rol">Guardar</a>
        </div>
        </form>
    </div>

    <div id="modal2" class="modal">
        <form class="col s12" id="form-editar" action="{{ url('/role/editar') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Editar Rol</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the role's name" data-success="right">Nombre del área</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="description" name="description" type="text" class="">
                        <label for="description" data-error="Please write the role's description" data-success="right">Descripción del área</label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="edit-role">Guardar</a>
            </div>
        </form>
    </div>

    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/role/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar Rol</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar ésta rol? </p>
                    <p>Recuerde que si este rol pertenece a un usuario, no podrá eliminar el rol </p>
                    <div class="input-field col s12">
                        <input disabled id="disabled" type="text" name="name">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-role">Eliminar</a>
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
    <script type="text/javascript" src="{{ asset('js/role/role.js') }}"></script>
@endsection
