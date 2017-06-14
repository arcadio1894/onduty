@extends('layouts.app')

@section('content')

    @if (Auth::user()->role_id < 3)
        <div class="fixed-action-btn">
            <a data-delay="50" data-tooltip="Nuevo usuario" data-position="top"
               class="btn-floating btn-large waves-effect waves-light tooltipped teal right modal-trigger" id="newLocation" href="#modal1">
                <i class="material-icons">add</i></a>
        </div>
    @endif

    <div class="row">
        <h2 class="header teal-text">Listado de usuarios</h2>

        @foreach ($users as $user)
        <div class="col s12 l6">
            <div class="card-panel grey lighten-5 z-depth-1">
                <div class="row valign-wrapper">
                    <div class="col s2">
                        @if ($user->image)
                            <img src=" {{ asset('images/users/'.$user->id.'.'.$user->image) }}" class="circle responsive-img" alt="Avatar del usuario">
                        @else
                            <img src=" {{ asset('images/users/default.jpg') }}" class="circle responsive-img" alt="Avatar por defecto">
                        @endif
                    </div>
                    <div class="col s10 black-text">
                        <p>
                            <strong>Nombre:</strong>
                            {{ $user->name }}
                            <br>
                            <strong>Email:</strong>
                            {{ $user->email }}
                            <br>
                            <strong>Localización:</strong>
                            {{ $user->location ? $user->location->name : 'Sin asignar' }}
                            <br>
                            <strong>Estado:</strong>
                            {{ $user->confirmed == 1 ? 'Confirmado' : 'Pendiente' }}
                            <br>
                            <strong>Rol:</strong>
                            {{ $user->role->name }}
                            <br>
                            <strong>Cargo:</strong>
                            {{ $user->position ? $user->position->name : 'Cargo sin asignar' }}
                        </p>
                        @if (Auth::user()->role_id < 3)
                            <span class="right">
                                @if(!$user->trashed())
                                    <a class="waves-effect waves-light btn-floating" data-edit="{{ $user->id }}"
                                       href="#modal2" data-roleid="{{ $user->role->id }}"
                                       @if ($user->position)
                                       data-positionid="{{ $user->position_id }}"
                                       data-departmentid="{{ $user->position->department_id }}"
                                       @endif
                                       data-locationid="{{ $user->location_id }}"
                                       data-role="{{ $user->role->name }}" data-name="{{ $user->name }}"
                                       data-password="{{ $user->password }}">
                                        <i class="material-icons">mode_edit</i>
                                    </a>
                                @endif
                                <a class="waves-effect waves-light btn-floating" data-delete="{{ $user->id }}"
                                   href="#modal3" data-name="{{$user->name}}">
                                    @if ($user->trashed())
                                        <i class="material-icons">restore</i>
                                    @else
                                        <i class="material-icons">delete</i>
                                    @endif
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal: New user -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" method="POST" action="{{ url('/user/register') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Registrar usuario</h4>

                <div class="row">
                    <div class="input-field col s6">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the user's name" data-success="right">Nombre del usuario</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="email" name="email" type="email" class="validate">
                        <label for="email" data-error="Please write the user's email" data-success="right">Email del usuario</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="password" name="password" type="password" autocomplete="new-password">
                        <label for="password" data-error="Please write the user's password" data-success="right">Contraseña del usuario</label>
                    </div>
                    <div class="input-field col s6">
                        <select id="role" name="role">
                            <option value="" disabled selected>Escoja un rol</option>
                            @foreach( $roles as $role )
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <label for="role">Roles de usuario</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6" id="departments">
                        <select id="department" name="department">
                            <option value="" disabled selected>Escoja un departamento</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <label for="department">Departamento </label>
                    </div>
                    <div class="input-field col s6" id="positions">
                        <select id="position" name="position">
                            <option value="" disabled selected>Escoja un cargo</option>
                        </select>
                        <label for="position">Cargos </label>
                    </div>

                </div>

                <div class="row">
                    <div class="input-field col s6" id="">
                        <select id="location-id" name="location-id">
                            <option value="" disabled selected>Escoja una localización</option>
                            @foreach( $locations as $location )
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        <label for="location-id">Localizaciones </label>
                    </div>
                    <div class="input-field col s6">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Imagen</span>
                                <input type="file" name="image" accept="image/*" id="avatarInput">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" >
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <button type="submit" href="#" class="waves-effect waves-green btn-flat" >Guardar</button>
            </div>
        </form>
    </div>

    <!-- Modal: Edit user -->
    <div id="modal2" class="modal">
        <form class="col s12" id="form-editar" method="POST" action="{{ url('/user/editar') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Editar usuario</h4>
                <input type="hidden" name="id">

                <div class="row">
                    <div class="input-field col s6">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the user's name" data-success="right">Nombre del usuario</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="password" name="password" type="password" autocomplete="new-password">
                        <label for="password" data-error="Please write the user's password" data-success="right">Contraseña del usuario</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <select id="role_select" name="role">

                        </select>
                        <label for="role_select">Roles de usuario</label>
                    </div>
                    <div class="input-field col s6">
                        <select id="location_select" name="location_select">

                        </select>
                        <label for="location_select">Localizaciones</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6" id="departmentDropdown">
                        <select id="department_select" name="department_select">

                        </select>
                        <label for="department_select">Departamento del usuario</label>
                    </div>
                    <div class="input-field col s6" id="positionDropdown">
                        <select id="position_select" name="position_select">

                        </select>
                        <label for="position_select">Cargo del usuario</label>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <button type="submit" href="#" class="waves-effect waves-green btn-flat" >Guardar</button>
            </div>
        </form>
    </div>

    <!-- Modal: Delete user -->
    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/user/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Desactivar/activar usuario</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de activar o desactivar este usuario? </p>
                    <div class="input-field col s12">
                        <input disabled id="disabled" type="text" name="name">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-user">Cambiar estado</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('.modal').modal();

            $('select').material_select();
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/user/user.js') }}"></script>
@endsection
