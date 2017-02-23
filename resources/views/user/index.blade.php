@extends('layouts.app')

@section('content')

    <div class="row">
        <br>
        <a class="waves-effect waves-light btn modal-trigger" id="newLocation" href="#modal1">Nuevo usuario</a>
        <br><br>
        <table class="responsive-table">
            <thead>
            <tr>
                <th data-field="id">Nombre</th>
                <th data-field="name">Email</th>
                <th data-field="name">Rol</th>
                @if (Auth::user()->role_id <3)
                    <th data-field="">Acciones</th>
                @endif
            </tr>
            </thead>

            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name }}</td>
                    @if (Auth::user()->role_id < 3)
                        <td>
                            <a class="waves-effect waves-light btn" data-edit="{{ $user->id }}" href="#modal2" data-roleid="{{ $user->role->id }}" data-role="{{ $user->role->name }}" data-name="{{$user->name}}" data-password="{{$user->password}}" ><i class="material-icons">mode_edit</i></a>
                            <a class="waves-effect waves-light btn" data-delete="{{ $user->id }}" href="#modal3" data-name="{{$user->name}}" ><i class="material-icons">delete</i></a>
                        </td>
                    @endif
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <!-- Modal Structure -->
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
                <div class="input-field col s12">
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
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <button type="submit" href="#" class="waves-effect waves-green btn-flat" >Guardar</button>
            </div>
        </form>
    </div>

    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/user/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar usuario</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar éste usuario? </p>
                    <p>Recuerde que si este usuario tiene (acciones) registradas no podrá eliminarlo.</p>
                    <div class="input-field col s12">
                        <input disabled id="disabled" type="text" name="name">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-user">Eliminar</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();

            $('select').material_select();
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/user/user.js') }}"></script>
@endsection
