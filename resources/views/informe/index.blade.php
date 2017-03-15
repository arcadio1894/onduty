@extends('layouts.app')

@section('breadcrumbs')
    <div class="row">
        <nav class="light-blue">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="{{ url('/informes') }}" class="breadcrumb">Informes</a>
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
                       data-tooltip="Nuevo informe"
                       class="btn-floating btn-large waves-effect waves-light teal right modal-trigger tooltip" id="newInforme" href="#modal1">
                        <i class="material-icons">add</i></a>
                @endif
                <span class="card-title">Listado de Informes</span>
                <table class="responsive-table">
                        <thead>
                        <tr>
                            <th data-field="id">Localización</th>
                            <th data-field="name">Onduty</th>
                            <th data-field="id">Fecha desde</th>
                            <th data-field="name">Fecha hasta</th>
                            @if (Auth::user()->role_id <3)
                                <th data-field="">Acciones</th>
                            @endif
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($informes as $informe)
                            <tr>
                                <td>{{ $informe->location->name }}</td>
                                <td>{{ $informe->user->name }}</td>
                                <td>{{ $informe->from_date }}</td>
                                <td>{{ $informe->to_date }}</td>
                                <td>
                                    <a class="waves-effect waves-light btn tooltip" data-tooltip="Reportes" href="{{ url('reports/informe/'. $informe->id) }}">
                                        <i class="material-icons">list</i>
                                    </a>
                                    <a class="waves-effect waves-light btn tooltip" data-tooltip="Observaciones" href="{{ url('observations/informe/'. $informe->id) }}">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    @if (Auth::user()->role_id < 3)
                                        <a class="waves-effect waves-light btn tooltip" data-tooltip="Eliminar" data-delete="{{ $informe->id }}" href="#modal3">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-register" action="{{ url('/informe/register') }}">
            {{ csrf_field() }}
        <div class="modal-content">
            <h4>Registrar informe</h4>
                <input type="hidden" name="user" value="{{ auth()->user()->id }}">
                <div class="row">
                    <div class="input-field col s12">
                        <select id="location" name="location">
                            <option value="" disabled selected>Escoja una localización</option>
                            @foreach( $locations as $location )
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        <label for="location">Localizaciones</label>
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
            <button type="submit" class="waves-effect waves-green btn-flat" id="save-informe">Guardar</button>
        </div>
        </form>
    </div>

    <div id="modal3" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/informe/delete') }}">
            {{ csrf_field() }}
            <div class="modal-content">
                <h4>Eliminar Informe</h4>
                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar éste informe? </p>
                    <p>Si este informe contiene reportes no podrá eliminarlo </p>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-informe">Eliminar</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('.tooltip').tooltip({delay: 50});

            $('.modal').modal({
                startingTop: '5%', // Starting top style attribute
                endingTop: '5%' // Ending top style attribute
            });

            $('select').material_select();

            $('.datepicker').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year
                format: 'yyyy-mm-dd'
            });
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/informe/informe.js') }}"></script>
@endsection
