@extends('layouts.app')


@section('breadcrumbs')
    <div class="row">
        <div class="navbar-fixed">
            <nav class="light-blue">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="{{ url('/informes') }}" class="breadcrumb">Informes</a>
                        <a href="{{ url('/reports/informe/'.$informe->id) }}" class="breadcrumb">Informe {{ $informe->id }}</a>
                        <a href="{{ url('/edit/informe/report/'.$informe->id.'/'.$report->id) }}" class="breadcrumb">Editar reporte</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <br>
        <div class="col s12">
            <div class="row card padding-1">
                <div class="col s5">
                    <span class="flow-text ng-binding">Informe - {{ $informe->id }}</span>
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
            <div class="row card padding-3">
                <div class="col s12">
                    <div class="col s4">
                        <span class="flow-text">Editar reporte</span>
                    </div>
                    <div class="col s8">
                        <div class="right">

                        </div>
                    </div>
                </div>
                <div class="col s12">
                    <form action="{{ url('report/edit') }}" enctype="multipart/form-data" id="form-edit" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="informe" value="{{ $informe->id }}">
                        <input type="hidden" name="reporte" value="{{ $report->id }}">
                        <div class="row">
                            <div class="input-field col s3">
                                <select id="workfront" name="workfront">
                                    <option value="" disabled>Selecciona un frente de trabajo</option>
                                    @foreach( $workfronts as $workfront )
                                        @if($workfront->id == $report->work_front_id)
                                            <option value="{{ $workfront->id }}" selected>{{ $workfront->name }}</option>
                                        @else
                                            <option value="{{ $workfront->id }}">{{ $workfront->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="workfront">Frentes de trabajo </label>
                            </div>
                            <div class="input-field col s3">
                                <select id="area" name="area">
                                    <option value="" disabled>Selecciona una área</option>
                                    @foreach( $areas as $area )
                                        @if($area->id == $report->area_id)
                                            <option value="{{ $area->id }}" selected>{{ $area->name }}</option>
                                        @else
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endif

                                    @endforeach
                                </select>
                                <label for="area">Áreas </label>
                            </div>
                            <div class="input-field col s3">
                                <select id="responsible" name="responsible">
                                    <option value="" disabled selected>Selecciona un responsable</option>
                                    @foreach( $users as $user )
                                        @if($user->id == $report->responsible_id)
                                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                        @else
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif

                                    @endforeach
                                </select>
                                <label for="responsible">Responsables </label>
                            </div>
                            <div class="input-field col s3">
                                <select id="aspect" name="aspect">
                                    <option value="" disabled>Selecciona un aspecto</option>
                                    @if($report->aspect == "Por mejorar")
                                        <option value="Por mejorar" selected>Por mejorar</option>
                                        <option value="Positivo">Positivo</option>
                                    @else
                                        <option value="Por mejorar" >Por mejorar</option>
                                        <option value="Positivo" selected>Positivo</option>
                                    @endif


                                </select>
                                <label for="aspect">Aspectos </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s3">
                                <select id="risk" name="risk">
                                    <option value="" disabled selected>Selecciona un riesgo crítico</option>
                                    @foreach( $risks as $risk )
                                        @if($risk->id == $report->critical_risks_id)
                                            <option value="{{ $risk->id }}" selected>{{ $risk->name }}</option>
                                        @else
                                            <option value="{{ $risk->id }}">{{ $risk->name }}</option>
                                        @endif

                                    @endforeach
                                </select>
                                <label for="risk">Riesgos críticos </label>
                            </div>
                            <div class="input-field col s3">
                                <select id="potencial" name="potencial">
                                    <option value="" disabled>Selecciona un potencial</option>
                                    @if($report->potential == "Alto")
                                        <option value="Alto" selected>Alto</option>
                                        <option value="Medio">Medio</option>
                                        <option value="Bajo">Bajo</option>
                                    @elseif($report->potential == "Medio")
                                        <option value="Alto" >Alto</option>
                                        <option value="Medio" selected>Medio</option>
                                        <option value="Bajo">Bajo</option>
                                    @else
                                        <option value="Alto" >Alto</option>
                                        <option value="Medio" >Medio</option>
                                        <option value="Bajo" selected>Bajo</option>
                                    @endif

                                </select>
                                <label for="potencial">Potencial </label>
                            </div>
                            <div class="input-field col s3">
                                <select id="state" name="state">
                                    <option value="" disabled >Selecciona un estado</option>
                                    @if($report->state == "Abierto")
                                        <option value="Abierto" selected>Abierto</option>
                                        <option value="Cerrado">Cerrado</option>
                                    @else
                                        <option value="Abierto">Abierto</option>
                                        <option value="Cerrado" selected>Cerrado</option>
                                    @endif

                                </select>
                                <label for="state">Estados </label>
                            </div>
                            <div class="input-field col s3">
                                <input type="date" value="{{ $report->planned_date }}" class="datepicker" id="planned-date" name="planned-date" required>
                                <label for="planned-date" data-error="Please choose a date " data-success="right">Fecha planeada</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="input-field col s4">
                                <input type="date" value="{{ $report->deadline }}" class="datepicker" id="deadline" name="deadline" required>
                                <label for="deadline" data-error="Please choose a date " data-success="right">Fecha de cierre</label>
                            </div>
                            <div class="input-field col s4">
                                <input type="number" value="{{ $report->inspections }}" min="0" id="inspections" name="inspections" required>
                                <label for="inspections" data-error="Please choose a date " data-success="right">Número de inspecciones</label>
                            </div>
                            <div class="input-field col s4">
                                <input type="text" value="{{ $report->description }}" id="description" name="description" >
                                <label for="description" data-success="right">Descripción</label>
                            </div>

                        </div>

                        <div class="row">
                            <div class="input-field col s6">
                                <textarea rows="2" id="actions" name="actions" class="materialize-textarea">{{ $report->actions }}</textarea>
                                <label for="actions">Acciones a tomar</label>
                            </div>
                            <div class="input-field col s6">
                                <textarea rows="2" id="observation" name="observation" class="materialize-textarea">{{ $report->observations }}</textarea>
                                <label for="observation">Observación</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="file-field input-field col s6">

                                <div class="btn">
                                    <span>Imagen</span>
                                    <input type="file" name="image" accept="image/*" id="image">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" >
                                </div>

                            </div>
                            <div class="file-field input-field col s6">

                                <div class="btn">
                                    <span>Imagen de accion tomada</span>
                                    <input type="file" name="image-action" accept="image/*" id="image-action">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" >
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <a href="{{ url('reports/informe/'. $informe->id) }}" class="waves-effect waves-green btn-flat right ">Cancelar</a>
                            <button type="submit" class="waves-effect waves-green btn-flat right" id="edit-report">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->

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
    <script type="text/javascript" src="{{ asset('js/report/showEdit.js') }}"></script>
@endsection
