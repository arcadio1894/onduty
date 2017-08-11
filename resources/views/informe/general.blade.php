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
                        <input type="date" class="datepicker" id="start_date" name="start_date" value="{{ $start_date }}" required>
                        <label for="start_date" data-error="Escoge una fecha" data-success="Bien">Fecha de incio</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="date" class="datepicker" id="end_date" name="end_date" value="{{ $end_date }}" required>
                        <label for="end_date" data-error="Escoge una fecha" data-success="Bien">Fecha de fin</label>
                    </div>
                    <div class="input-field col s4">
                        <button type="submit" class="waves-effect waves-light btn tooltip" data-tooltip="Consultar reportes">
                            <i class="material-icons">visibility</i>
                        </button>
                        <button type="submit" name="excel" value="1" class="waves-effect waves-light btn tooltip" data-tooltip="Exportar excel">
                            <i class="material-icons">file_download</i>
                        </button>
                        <button type="submit" name="charts" value="1" class="waves-effect waves-light btn tooltip" data-tooltip="Ver gráficos">
                            <i class="material-icons">equalizer</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (isset($reports))
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
    @endif

    @if (isset($charts))
        @include('informe.general.charts')
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
    @if (isset($charts))
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>

        <script>
            $(document).ready(function () {
                // Build the chart
                Highcharts.chart('container1', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Reportes según aspecto'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                }
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        name: 'Porcentaje',
                        colorByPoint: true,
                        data: [{
                            name: 'Por Mejorar',
                            y: 0{{ $aspectImprove }}
                        }, {
                            name: 'Positivo',
                            y: 0{{ $aspectPositive }},
                            sliced: true,
                            selected: true
                        }]
                    }]
                });

            });

            Highcharts.chart('container5', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Reportes según estado'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Porcentaje',
                    colorByPoint: true,
                    data: [{
                        name: 'Abierto',
                        y: 0{{ $open }}
                    }, {
                        name: 'Cerrado',
                        y: 0{{ $closed }},
                        sliced: true,
                        selected: true
                    }]
                }]
            });
        </script>
        {{--<script src="/js/informe/general-charts.js"></script>--}}
    @endif
@endsection
