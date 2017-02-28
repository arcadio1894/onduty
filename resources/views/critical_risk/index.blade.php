@extends('layouts.app')

@section('content')

    <div class="row">
        <br>
        @if (Auth::user()->role_id < 3)
            <a class="waves-effect waves-light btn modal-trigger" id="newRisk" href="#modal1">Nueva Riesgo Crítico</a>
        @endif
        <br><br>
        <table class="responsive-table">
            <thead>
            <tr>
                <th data-field="id">Nombre</th>
                @if (Auth::user()->role_id <3)
                    <th data-field="">Acciones</th>
                @endif
            </tr>
            </thead>

            <tbody>
            @foreach ($risks as $risk)
                <tr>
                    <td>{{ $risk->name }}</td>
                    @if (Auth::user()->role_id < 3)
                        <td>
                            <a class="waves-effect waves-light btn" data-edit="{{ $risk->id }}" href="#modal2" data-name="{{$risk->name}}"  ><i class="material-icons">mode_edit</i></a>
                            <a class="waves-effect waves-light btn" data-delete="{{ $risk->id }}" href="#modal3" data-name="{{$risk->name}}" ><i class="material-icons">delete</i></a>
                        </td>
                    @endif
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" action="{{ url('/critical_risk/register') }}">
            {{ csrf_field() }}
        <div class="modal-content">
            <h4>Registrar riesgo crítico</h4>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the area's name" data-success="right">Nombre del riesgo</label>
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
        <form class="col s12" id="form-editar" action="{{ url('/critical_risk/editar') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Editar riesgo crítico</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name" data-error="Please write the risk's name" data-success="right">Nombre del riesgo crítico</label>
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
        <form class="col s12" id="form-delete" action="{{ url('/critical_risk/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar riesgo crítico</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar éste riesgo crítico? </p>

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
    <script type="text/javascript" src="{{ asset('js/critical_risk/risk.js') }}"></script>
@endsection
