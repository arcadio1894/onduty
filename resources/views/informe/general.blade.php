@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('/css/informe/index.css') }}">
@endsection

@section('breadcrumbs')
    <div class="row">
        <nav class="light-blue">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="{{ url('/informes') }}" class="breadcrumb">Informe general</a>
                </div>
            </div>
        </nav>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-content">
            <span class="card-title">Listado de Informes</span>
            <p><small>Mostrando informes desde el más reciente al más antiguo.</small></p>
            <div class="table-responsive-vertical">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Localización</th>
                        <th>Onduty</th>
                        <th>Fecha desde</th>
                        <th>Fecha hasta</th>
                        @if (Auth::user()->role_id <3)
                            <th>Acciones</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($informes as $informe)
                        <tr>
                            <td data-title="Localización">{{ $informe->location->name }}</td>
                            <td data-title="Onduty">{{ $informe->user->name }}</td>
                            <td data-title="Desde">{{ $informe->from_date->format('d/m/Y') }}</td>
                            <td data-title="Hasta">{{ $informe->to_date->format('d/m/Y') }}</td>
                            <td>
                                <a class="waves-effect waves-light btn tooltip" data-tooltip="Reportes" href="{{ url('reports/informe/'. $informe->id) }}">
                                    <i class="material-icons">list</i>
                                </a>
                                <a class="waves-effect waves-light btn tooltip" data-tooltip="Observaciones" href="{{ url('observations/informe/'. $informe->id) }}">
                                    <i class="material-icons">visibility</i>
                                </a>
                                <a class="waves-effect waves-light btn tooltip" data-tooltip="Gráficas" href="{{ url('graphics/informe/'. $informe->id) }}">
                                    <i class="material-icons">equalizer</i>
                                </a>
                                <a class="waves-effect waves-light btn tooltip" data-tooltip="Exportar a Excel" href="{{ url('excel/informe/'. $informe->id) }}">
                                    <i class="material-icons">file_download</i>
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

            <div class="fixed-action-btn horizontal">
                <a class="btn-floating btn-large light-blue">
                    <i class="large material-icons">menu</i>
                </a>
                <ul>
                    <li>
                        <a class="btn-floating waves-effect waves-light green tooltip" href="{{ url('/excel/informes/general') }}"
                           data-tooltip="Informe general" data-position="top">
                            <i class="material-icons">file_download</i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('.tooltip').tooltip({delay: 50});

            $('.datepicker').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
