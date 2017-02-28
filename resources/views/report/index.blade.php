@extends('layouts.app')

@section('content')

    <div class="row">
        <br>
        <div class="col s12">
            <div class="row card padding-1">
                <div class="col s5">
                    <span class="flow-text ng-binding">Informe - {{ $informe->id }}</span>
                </div>
                <div class="col s7">
                    <a data-todate="{{ $informe->to_date }}" data-fromdate="{{ $informe->from_date }}" data-user="{{ $informe->user_id }}" data-location="{{ $informe->location_id }}" data-informe="{{ $informe->id }}" id="edit-informe" data-position="bottom" data-delay="50" data-tooltip="Editar informe" class="waves-effect waves-light btn tooltipped right teal margin-1 ng-hide" data-tooltip-id="82dd755b-da34-def9-871a-21cf68abe1de"><i class="material-icons">mode_edit</i></a>
                </div>
            </div>
            <div class="row card padding-1">
                <div class="col s12 m2 l2">
                    <label>Localización</label>
                    <p class="margin-0 ng-binding">{{ $informe->location->name }}</p>
                </div>
                <div class="col s12 m3 l3">
                    <label>Onduty</label>
                    <p class="margin-0 ng-binding">{{ $informe->user->name }}</p>
                </div>
                <div class="col s12 m2 l2">
                    <label>Fecha de registro</label>
                    <p class="margin-0 ng-binding">{{ $informe->updated_at }}</p>
                </div>
                <div class="col s12 m2 l2">
                    <label>Fecha de visita de</label>
                    <p class="margin-0 ng-binding">{{ $informe->from_date }}</p>
                </div>
                <div class="col s12 m2 l2">
                    <label>Fecha de visita hasta</label>
                    <p class="margin-0 ng-binding">{{ $informe->to_date }}</p>
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="row card padding-1">
                <div class="col s5">
                    <span class="flow-text">Reportes</span>
                </div>
                <div class="col s7">
                    <div class="right">
                        @if (Auth::user()->role_id < 3)
                            <a data-position="" data-delay="50" data-tooltip="Registrar nuevo reporte" class="waves-effect waves-light btn tooltipped left teal margin-1 ng-hide" data-tooltip-id="82dd755b-da34-def9-871a-21cf68abe1de">Nuevo reporte</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 table-responsing">

        </div>
        {{--<table class="responsive-table">
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
            @foreach ($areas as $area)
                <tr>
                    <td>{{ $area->name }}</td>
                    <td>{{ $area->description }}</td>
                    @if (Auth::user()->role_id < 3)
                        <td>
                            <a class="waves-effect waves-light btn" data-edit="{{ $area->id }}" href="#modal2" data-name="{{$area->name}}" data-description="{{$area->description}}" ><i class="material-icons">mode_edit</i></a>
                            <a class="waves-effect waves-light btn" data-delete="{{ $area->id }}" href="#modal3" data-name="{{$area->name}}" ><i class="material-icons">delete</i></a>
                        </td>
                    @endif
                </tr>
            @endforeach

            </tbody>
        </table>--}}
    </div>

    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-edit" action="{{ url('/informe/edit') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id">
            <div class="modal-content">
                <h4>Editar informe</h4>

                <div class="row">
                    <div class="input-field col s6">
                        <select id="location-select" name="location-select">
                            <option value="" disabled selected>Escoja una localización</option>

                        </select>
                        <label for="location-select">Localizaciones </label>
                    </div>
                    <div class="input-field col s6">
                        <select id="user-select" name="user-select">
                            <option value="" disabled selected>Escoja un onduty</option>

                        </select>
                        <label for="user-select">Onduty </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input type="date" class="datepicker" id="fromdate" name="fromdate" required>
                        <label for="fromdate" data-error="Please choose a date " data-success="right">Fecha de incio de visita</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="date" class="datepicker" id="todate" name="todate" required>
                        <label for="todate" data-error="Please choose a date" data-success="right">Fecha de fin de visita</label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="save-informe">Guardar</a>
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
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15, // Creates a dropdown of 15 years to control year
            format: 'yyyy-mm-dd'
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/report/report.js') }}"></script>
@endsection
