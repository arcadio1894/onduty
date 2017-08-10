@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('/css/informe/index.css') }}">
@endsection

@section('breadcrumbs')
    <div class="row">
        <nav class="light-blue">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="{{ url('/informes') }}" class="breadcrumb">Informes</a>
                    <a href="#" class="breadcrumb">Informe general</a>
                </div>
            </div>
        </nav>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-content">
            <span class="card-title">Consolidado de reportes</span>
            <p>Seleccione un intervalo de fechas para generar el consolidado de reportes.</p>
            <div class="row">
                <form action="">
                    <div class="input-field col s4">
                        <input type="date" class="datepicker" id="start_date" name="start_date" required>
                        <label for="start_date" data-error="Escoge una fecha" data-success="Bien">Fecha de incio</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="date" class="datepicker" id="end_date" name="end_date" required>
                        <label for="end_date" data-error="Escoge una fecha" data-success="Bien">Fecha de fin</label>
                    </div>
                    <div class="input-field col s4">
                        <button type="submit" class="waves-effect waves-light btn tooltip" data-tooltip="Consultar reportes">
                            <i class="material-icons">visibility</i>
                        </button>
                        <a class="waves-effect waves-light btn tooltip" data-tooltip="Exportar excel">
                            <i class="material-icons">file_download</i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($reports)
    <div class="col s12">
        <div class="row">
            <div class="cards">
                @foreach ($reports as $report)
                    <div class="col s12 m6 l4 xl3">
                        <div class="card">
                            <div class="card-image waves-effect waves-block waves-light">
                                @if(!$report->image)
                                    <img class="activator"  src="{{ asset('images/report/default.png') }}" alt="">
                                @else
                                    <img class="activator"  src="{{ asset('images/report/' . $report->id . '.' . $report->image) }}" alt="">
                                @endif
                            </div>
                            <div class="card-content">
                                <span class="card-title activator grey-text text-darken-4">{{ $report->description }}<i class="material-icons right">more_vert</i></span>
                                <p><strong>Fecha de registro:</strong> {{ $report->created_at }}</p>
                                <p><strong>Frente:</strong> {{ $report->work_front->name }}</p>
                                <p><strong>Área:</strong> {{ $report->area->name }}</p>
                                <p><strong>Responsable:</strong> {{ $report->responsible->name }}</p>
                                <p><strong>Fecha planificada:</strong> {{ $report->planned_date ?: 'No indicado' }}</p>
                                <p><strong>Fecha de cierre:</strong> {{ $report->deadline ?: 'No indicado' }}</p>
                                <p class="{{ $report->state=='Abierto' ? 'red' : 'green' }}-text">
                                    <strong>Estado:</strong> {{ $report->state }}
                                </p>
                            </div>

                            @if ($informe->active && auth()->user()->role_id < 4) {{-- Not available for visitors --}}
                            @if ( auth()->user()->role_id < 3 ||
                                    $informe->user_id == auth()->user()->id ||
                                    $report->user_id == auth()->user()->id ||
                                    $report->responsible_id == auth()->user()->id )
                                <div class="card-action">
                                    <a href="{{ url('edit/informe/report/'. $informe->id.'/'.$report->id) }}">Editar</a>
                                    <a data-delete="{{ $report->id }}" href="#modal1">Eliminar</a>
                                </div>
                            @endif
                            @endif

                            <div class="card-reveal">
                                <span class="card-title grey-text text-darken-4">{{ $report->actions }}<i class="material-icons right">close</i></span>
                                @if (!$report->image_action)
                                    <img class="image-reveal" src="{{ asset('images/action/default.png') }}" alt="">
                                @else
                                    <img class="image-reveal" src="{{ asset('images/action/' . $report->id . '.' . $report->image_action) }}" >
                                @endif
                                <p><strong>Aspecto:</strong> {{ $report->aspect }}</p>
                                <p><strong>Potencial:</strong> {{ $report->potential }}</p>
                                <p><strong># inspecciones:</strong> {{ $report->inspections }}</p>
                                <p><strong>Riesgo crítico:</strong> {{ $report->critical_risks->name }}</p>
                                <p><strong>Observación:</strong> {{ $report->observations }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('.tooltip').tooltip({delay: 50});

            $('.datepicker').pickadate({
                selectMonths: true, // Creates a drop down to control month
                selectYears: 15, // Creates a drop down of 15 years
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
