@extends('layouts.app')

@section('breadcrumbs')
    <div class="row">
        <div class="navbar-fixed">
            <nav class="light-blue">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="{{ url('/informes') }}" class="breadcrumb">Informes</a>
                        <a href="{{ url('/graphics/informe/'.$informe->id) }}" class="breadcrumb">Informe {{ $informe->id }}</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card .image-reveal {
            max-width: 82%;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="row">

        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title flow-text ng-binding">Informe - {{ $informe->id }}</span>

                    <div class="row">
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
                            <p class="margin-0 ng-binding">{{ $informe->from_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col s12 m2 l2">
                            <label>Fecha de visita hasta</label>
                            <p class="margin-0 ng-binding">{{ $informe->to_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Gráficas</span>
                    <input type="hidden" id="informe-id" value="{{ $informe->id }}">
                </div>
            </div>
        </div>


        <div class="col s12 m6 xl4">
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <div id="container1"></div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">
                        SEGÚN ASPECTOS
                        <i class="material-icons right">more_vert</i>
                    </span>
                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">
                        Datos en tabla
                        <i class="material-icons right">close</i>
                    </span>
                    <table>
                        <thead>
                        <tr>
                            <th data-field="id">Aspecto</th>
                            <th data-field="name">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Por mejorar</td>
                                <td>{{ $porMejorar }}</td>
                            </tr>
                            <tr>
                                <td>Positivo</td>
                                <td>{{ $positivo }}</td>
                            </tr>
                            <tr>
                                <td>TOTAL</td>
                                <td>{{ $porMejorar+$positivo }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col s12 m6 xl4" >
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <div id="container2"></div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">SEGÚN FRENTES DE TRABAJO<i class="material-icons right">more_vert</i></span>

                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                    <table id="">
                        <thead>
                        <tr>
                            <th data-field="id">Frente de trabajo</th>
                            <th data-field="name">Cantidad</th>
                        </tr>
                        </thead>

                        <template id="template-work">
                            <tr>
                                <td data-text></td>
                                <td data-number></td>
                            </tr>
                        </template>

                        <tbody id="table-work-fronts">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col s12 m6 xl4" >
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <div id="container3"></div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">SEGÚN RIESGO CRÍTICO<i class="material-icons right">more_vert</i></span>

                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                    <table id="">
                        <thead>
                        <tr>
                            <th data-field="id">Riesgo crítico</th>
                            <th data-field="name">Cantidad</th>
                        </tr>
                        </thead>

                        <template id="template-risk">
                            <tr>
                                <td data-text></td>
                                <td data-number></td>
                            </tr>
                        </template>

                        <tbody id="table-risk">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col s12 m6 xl4" >
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <div id="container4"></div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">SEGÚN ÁREAS<i class="material-icons right">more_vert</i></span>

                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                    <table id="">
                        <thead>
                        <tr>
                            <th data-field="id">Área</th>
                            <th data-field="name">Cantidad</th>
                        </tr>
                        </thead>

                        <template id="template-area">
                            <tr>
                                <td data-text></td>
                                <td data-number></td>
                            </tr>
                        </template>

                        <tbody id="table-area">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col s12 m6 xl4" >
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <div id="container5"></div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">SEGÚN ESTADO<i class="material-icons right">more_vert</i></span>

                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                    <table>
                        <thead>
                        <tr>
                            <th data-field="id">Estado</th>
                            <th data-field="name">Cantidad</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>Abiertos</td>
                            <td>{{ $opens }}</td>
                        </tr>
                        <tr>
                            <td>Cerrados</td>
                            <td>{{ $closed }}</td>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td>{{ $opens+$closed }}</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col s12 m6 xl4" >
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <div id="container6"></div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">SEGÚN RESPONSABLE<i class="material-icons right">more_vert</i></span>

                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                    <table id="">
                        <thead>
                        <tr>
                            <th data-field="id">Responsable</th>
                            <th data-field="name">Cantidad</th>
                        </tr>
                        </thead>

                        <template id="template-responsible">
                            <tr>
                                <td data-text></td>
                                <td data-number></td>
                            </tr>
                        </template>

                        <tbody id="table-responsible">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col s12 m6 xl4" >
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <div id="container7"></div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">SEGÚN FRENTES DE TRABAJO<i class="material-icons right">more_vert</i></span>

                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                    <table id="">
                        <thead>
                        <tr>
                            <th data-field="id">Frente de trabajo</th>
                            <th data-field="name">Cantidad</th>
                        </tr>
                        </thead>

                        <template id="template-work-open">
                            <tr>
                                <td data-text></td>
                                <td data-number></td>
                            </tr>
                        </template>

                        <tbody id="table-work-open">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="col s12 m6 xl4" >
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <div id="container8"></div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4">SEGÚN RESPONSABLES<i class="material-icons right">more_vert</i></span>

                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                    <table id="">
                        <thead>
                        <tr>
                            <th data-field="id">Responsable</th>
                            <th data-field="name">Cantidad</th>
                        </tr>
                        </thead>

                        <template id="template-responsible-open">
                            <tr>
                                <td data-text></td>
                                <td data-number></td>
                            </tr>
                        </template>

                        <tbody id="table-responsible-open">

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

@endsection

@section('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script>
        $(document).ready(function(){
            // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
            $('select').material_select();

            $('.datepicker').pickadate({
                selectMonths: true,
                selectYears: 15,
                format: 'yyyy-mm-dd'
            });
        });
    </script>

    <script src="{{ asset('js/informe/graficos.js') }}"></script>

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
                        y: 0{{ $porMejorar  }}
                    }, {
                        name: 'Positivo',
                        y: 0{{ $positivo  }},
                        sliced: true,
                        selected: true
                    }]
                }]
            });

            // Build the chart
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
                        y: 0{{ $opens  }}
                    }, {
                        name: 'Cerrado',
                        y: 0{{ $closed  }},
                        sliced: true,
                        selected: true
                    }]
                }]
            });
        });
    </script>
@endsection
