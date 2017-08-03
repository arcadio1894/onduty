@extends('layouts.app')

@section('breadcrumbs')
    <div class="row">
        <div class="navbar-fixed">
            <nav class="light-blue">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="{{ url('/informes') }}" class="breadcrumb">Informes</a>
                        <a href="{{ url('/reports/informe/'.$informe->id) }}" class="breadcrumb">Informe {{ $informe->id }}</a>
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
                    @if(Auth::user()->role_id < 4 AND $informe->active==true AND $informe->user_id == Auth::user()->id)
                        <a data-todate="{{ $informe->to_date }}"
                           data-fromdate="{{ $informe->from_date }}"
                           data-user="{{ $informe->user_id }}"
                           data-location="{{ $informe->location_id }}"
                           data-informe="{{ $informe->id }}"
                           id="edit-informe"
                           data-delay="50" data-tooltip="Editar informe"
                           class="btn-floating btn-large waves-effect waves-light tooltipped teal right"
                           href="#modalEdit">
                            <i class="material-icons">mode_edit</i>
                        </a>
                    @endif

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

        <div class="col s12" >
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Reportes</span>

                    <p>
                        Este informe presenta un total de {{ $informe->reports->count() }} reporte(s).
                        {{ $informe->reports->where('state', 'Abierto')->count() }} abierto(s) y {{ $informe->reports->where('state', 'Cerrado')->count() }} cerrado(s).</p>

                    <div class="fixed-action-btn horizontal">
                        <a class="btn-floating btn-large red">
                            <i class="large material-icons">menu</i>
                        </a>
                        <ul>
                            <li>
                                <a class="btn-floating green tooltipped" href="{{ url('excel/informe/' . $informe->id) }}"
                                   data-tooltip="Exportar excel" data-position="top">
                                    <i class="material-icons">file_download</i>
                                </a>
                            </li>
                            @if ($informe->active)
                                @if (auth()->user()->role_id < 3 OR auth()->user()->role_id == 3 AND $informe->user_id == auth()->user()->id)
                                    <li>
                                        <a href="{{ url('register/report/' . $informe->id) }}"
                                           data-tooltip="Nuevo reporte" data-position="top"
                                           class="btn-floating waves-effect waves-light tooltipped teal">
                                            <i class="material-icons">add</i>
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>

                    {{--<a href="{{ url('excel/informe/' . $informe->id) }}"--}}
                       {{--data-delay="50"--}}
                       {{--data-tooltip="Exportar excel"--}}
                       {{--class="btn-floating btn-large waves-effect waves-light tooltipped teal right">--}}
                        {{--<i class="material-icons">play_for_work</i>--}}
                    {{--</a>--}}
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="row">
                <div class="cards">
                    @foreach ($reports as $report)
                        <div class="col s12 m6 l4" >
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

    {{-- Modal to confirm delete action --}}
    <div id="modal1" class="modal">
        <form class="col s12" id="form-delete" action="{{ url('/report/delete') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id">
            <div class="modal-content">
                <h4>Eliminar informe</h4>

                <input type="hidden" name="id">
                <div class="row">
                    <p>¿Está seguro de eliminar este reporte?</p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <a href="#" class="waves-effect waves-green btn-flat" id="delete-report">Eliminar</a>
            </div>
        </form>
    </div>

    <div id="modalEdit" class="modal">
        <form class="col s12" id="form-edit" action="{{ url('/informe/edit') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id">
            <div class="modal-content">
                <h4>Editar informe</h4>

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
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

    <script>
    	startMasonryWhenAllImagesHaveLoaded();

        $(document).ready(function () {
            // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
            $('.modal').modal();
            $('select').material_select();

            $('.datepicker').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year
                format: 'yyyy-mm-dd'
            });
        });

        function startMasonryWhenAllImagesHaveLoaded() {
        	$(window).on('load', function () {
        		$('.cards').masonry({ itemSelector: '.col' });
        	});
	    }    
    </script>

    <script type="text/javascript" src="{{ asset('js/report/report.js') }}"></script>
@endsection
