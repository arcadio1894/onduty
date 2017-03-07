@extends('layouts.app')

@section('breadcrumbs')
    <div class="row">
        <div class="navbar-fixed">
            <nav class="light-blue">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="{{ url('/informes') }}" class="breadcrumb">Informes</a>
                        <a href="{{ url('/informe/'.$informe->id) }}" class="breadcrumb">Informe {{ $informe->id }}</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .caja_table{
            display: block;
            max-height: 53%;
            min-width: 1000px!important;
            overflow-y: auto;
        }
        .imagen{
            width: 40px;
            height: 40px;
        }
        .image{
            width: 280px;
            height: 280px;
        }

        .imagen:hover{
            cursor: -moz-zoom-in;
            cursor: -webkit-zoom-in;
            cursor: zoom-in;
        }

    </style>
@endsection

@section('content')

    <div class="row">
        <br>
        <div class="col s12">
            <div class="row card padding-1">
                <div class="col s12">
                    <div class="col s5">
                        <span class="flow-text ng-binding">Informe - {{ $informe->id }}</span>
                    </div>
                    <div class="col s7">
                        <a data-todate="{{ $informe->to_date }}" data-fromdate="{{ $informe->from_date }}" data-user="{{ $informe->user_id }}" data-location="{{ $informe->location_id }}" data-informe="{{ $informe->id }}" id="edit-informe" data-position="bottom" data-delay="50" data-tooltip="Editar informe" class="waves-effect waves-light btn tooltipped right teal margin-1 ng-hide" data-tooltip-id="82dd755b-da34-def9-871a-21cf68abe1de" href="#modal4"><i class="material-icons">mode_edit</i></a>
                    </div>
                </div>
                <br><br><br>
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

        <div class="col s12" >
            <div class="row card padding-1" >
                <div class="col s12">
                    <div class="col s5">
                        <span class="flow-text">Reportes</span>
                    </div>
                    <div class="col s7">
                        <div class="right">
                            @if (Auth::user()->role_id < 3)
                                <a href="{{ url('register/report/' . $informe->id) }}" data-position="" data-delay="50" data-tooltip="Registrar nuevo reporte" class="waves-effect waves-light btn tooltipped left teal margin-1 ng-hide" data-tooltip-id="82dd755b-da34-def9-871a-21cf68abe1de">Nuevo reporte</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($reports as $report)
                <div class="col s12 m6 l4" >
                    <div class="card">
                        <div class="card-image waves-effect waves-block waves-light">
                            @if(!$report->image)
                                <img class="activator image"  src="{{ asset('images/report/default.png') }}" alt="">
                            @else
                                <img class="activator image"  src="{{ asset('images/report/' . $report->id . '.' . $report->image) }}" alt="">
                            @endif
                        </div>
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4">{{ $report->actions }}<i class="material-icons right">more_vert</i></span>
                            <p>Fecha de cierre: {{ $report->deadline }}</p>
                            <p>Observación: {{ $report->observations }}</p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
                            <p>Here is some more information about this product that is only revealed once clicked on.</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        {{--<div class="col s12" >
            <div class="row card padding-1" >
                <div class="col s12">
                    <div class="col s5">
                        <span class="flow-text">Reportes</span>
                    </div>
                    <div class="col s7">
                        <div class="right">
                            @if (Auth::user()->role_id < 3)
                                <a href="{{ url('register/report/' . $informe->id) }}" data-position="" data-delay="50" data-tooltip="Registrar nuevo reporte" class="waves-effect waves-light btn tooltipped left teal margin-1 ng-hide" data-tooltip-id="82dd755b-da34-def9-871a-21cf68abe1de">Nuevo reporte</a>
                            @endif
                        </div>
                    </div>
                </div>
                <br><br><br>
                <div class="col s12">
                    <div style="overflow-x:auto; height: 400px !important; overflow-y: scroll !important;">
                    <table class="responsive-table highlight centered striped">
                    <thead>
                    <tr>
                        <th data-field="id">Sujeto</th>
                        <th data-field="name">Frente de trabajo</th>
                        <th data-field="id">Área</th>
                        <th data-field="name">Responsable</th>
                        <th data-field="id">Aspecto</th>
                        <th data-field="name">Riesgo crítico</th>
                        <th data-field="id">Potencial</th>
                        <th data-field="name">Estado</th>
                        <th data-field="id">Imagen</th>
                        <th data-field="name">Imagen de accion</th>
                        <th data-field="id">Fecha planeada</th>
                        <th data-field="name">Fecha de cierre</th>
                        <th data-field="id">Numero de inspecciones</th>
                        <th data-field="name">Descripción</th>
                        <th data-field="id">Acciones a tomar</th>
                        <th data-field="name">Observación</th>
                        @if (Auth::user()->role_id <3)
                            <th data-field="">Acciones</th>
                        @endif
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $report->user->name }}</td>
                            <td>{{ $report->work_front->name }}</td>
                            <td>{{ $report->area->name }}</td>
                            <td>{{ $report->responsible->name }}</td>
                            <td>{{ $report->aspect }}</td>
                            <td>{{ $report->critical_risks->name }}</td>
                            <td>{{ $report->potential }}</td>
                            <td>{{ $report->state }}</td>
                            @if(!$report->image)
                                <td><a class="modal-trigger" href="#modal2"><img data-img="default.png" class="imagen"  src="{{ asset('images/report/default.png') }}" alt=""></a> </td>
                            @else
                                <td><a class="modal-trigger" href="#modal2"><img data-img="{{ $report->id.'.'.$report->image }}" class="imagen"  src="{{ asset('images/report/' . $report->id . '.' . $report->image) }}" alt=""></a> </td>
                            @endif
                            @if(!$report->image_action)
                                <td><a class="modal-trigger" href="#modal3"><img data-action="default.png" class="imagen"  src="{{ asset('images/action/default.png') }}" alt=""></a> </td>
                            @else
                                <td><a class="modal-trigger" href="#modal3"><img data-action="{{ $report->id.'.'.$report->image_action }}" class="imagen"  src="{{ asset('images/action/' . $report->id . '.' . $report->image_action) }}" alt=""></a> </td>
                            @endif
                                               <td>{{ $report->planned_date }}</td>
                            <td>{{ $report->deadline }}</td>
                            <td>{{ $report->inspections }}</td>
                            <td>{{ $report->description }}</td>
                            <td>{{ $report->actions }}</td>
                            <td>{{ $report->observations }}</td>
                            <td>
                                <a class="waves-effect waves-light tooltipped btn" data-delay="50" data-tooltip="Editar reporte" href="{{ url('edit/informe/report/'. $informe->id.'/'.$report->id) }}"><i class="material-icons">mode_edit</i></a>
                                @if (Auth::user()->role_id < 3)
                                    <a class="waves-effect waves-light tooltipped btn" data-delay="50" data-tooltip="Eliminar reporte" data-delete="{{ $report->id }}" href="#modal1" ><i class="material-icons">delete</i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                </div>
                </div>
            </div>
        </div>--}}
    </div>

    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/report/delete') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id">
            <div class="modal-content">
                <h4>Eliminar informe</h4>

                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar éste reporte? </p>
                    <p>Si este informe (alguna validación) no podrá eliminarlo </p>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-report">Eliminar</a>
            </div>
        </form>
    </div>

    <div id="modal2" class="modal">
        <div class="modal-content">
            <h4>Imagen</h4>

            <div class="row">
                <div class="col s6 offset-s5 ">
                    <img id="verImage" src="" alt="">
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>

        </div>
    </div>

    <div id="modal3" class="modal">
        <div class="modal-content">
            <h4>Imagen</h4>
            <div class="row">
                <div class="col s6 offset-s5 ">
                    <img class="" id="verAction" src="" alt="">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
        </div>
    </div>

    <div id="modal4" class="modal">
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
                <a href="#" class="waves-effect waves-green btn-flat" id="save-edit">Guardar</a>
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
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 15, // Creates a dropdown of 15 years to control year
            format: 'yyyy-mm-dd'
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/report/report.js') }}"></script>
@endsection
