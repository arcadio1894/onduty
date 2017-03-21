@extends('layouts.app')

@section('styles')
    <style>
        .image {
            width: 144px;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="row">
        <div class="navbar-fixed">
            <nav class="light-blue">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="{{ url('/informes') }}" class="breadcrumb">Informes</a>
                        <a href="{{ url('/reports/informe/'.$informe->id) }}" class="breadcrumb" id="linkBackToList">Informe {{ $informe->id }}</a>
                        <a href="{{ url('/register/report/'.$informe->id) }}" class="breadcrumb">Crear reporte</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card ">
                <div class="card-content">
                    <span class="card-title">Informe - {{ $informe->id }}</span>

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
                            <p class="margin-0 ng-binding">{{ $informe->from_date }}</p>
                        </div>
                        <div class="col s12 m2 l2">
                            <label>Fecha de visita hasta</label>
                            <p class="margin-0 ng-binding">{{ $informe->to_date }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Registrar reporte</span>
                    {{--<div class="progress" id="line-loader" style="display: none">
                        <div class="indeterminate"></div>
                    </div>
                    <div style="display: none" class="center" id="circle-loader"><div  class="preloader-wrapper active">
                            <div class="spinner-layer spinner-blue">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>

                            <div class="spinner-layer spinner-red">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>

                            <div class="spinner-layer spinner-yellow">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>

                            <div class="spinner-layer spinner-green">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div></div>
--}}
                    <form action="{{ url('report/register') }}" enctype="multipart/form-data" id="form-register" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="informe" value="{{ $informe->id }}">
                        <div class="row">
                            <div class="input-field col s4">
                                <select id="workfront" name="workfront">
                                    <option value="" disabled selected>Selecciona un frente</option>
                                    @foreach ($workfronts as $workfront)
                                        <option value="{{ $workfront->id }}">{{ $workfront->name }}</option>
                                    @endforeach
                                </select>
                                <label for="workfront">Frentes de trabajo </label>
                            </div>
                            <div class="input-field col s4">
                                <select id="area" name="area">
                                    <option value="" disabled selected>Selecciona una área</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                <label for="area">Áreas</label>
                            </div>
                            <div class="input-field col s4">
                                <select id="responsible" name="responsible">
                                    <option value="" disabled selected>Selecciona un responsable</option>
                                    @foreach( $users as $user )
                                        <option data-email="{{ $user->email }}" data-position="{{ $user->position->name }}" data-idposition="{{ $user->position->id }}" value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="responsible">Responsables</label>
                            </div>

                        </div>
                        <div class="row">
                            <div class="input-field col s4">
                                <input type="text" readonly class="" id="responsible-position" name="responsible-position" value="">
                                <label for="responsible-position">Cargo del responsable</label>
                            </div>
                            <div class="input-field col s4">
                                <input type="text" readonly class="" id="responsible-email" name="responsible-email" value="">
                                <label for="responsible-email">Email del responsable</label>
                            </div>
                            <div class="input-field col s4">
                                <input type="text" readonly class="" id="responsible-department" name="responsible-department" value="">
                                <label for="responsible-department">Departamento del responsable</label>
                            </div>

                        </div>

                        <div class="row">
                            <div class="input-field col s4">
                                <select id="aspect" name="aspect">
                                    <option value="" disabled selected>Selecciona un aspecto</option>
                                    <option value="Por mejorar">Por mejorar</option>
                                    <option value="Positivo">Positivo</option>
                                </select>
                                <label for="aspect">Aspectos</label>
                            </div>
                            <div class="input-field col s4">
                                <select id="risk" name="risk">
                                    <option value="" disabled selected>Selecciona un riesgo crítico</option>
                                    @foreach( $risks as $risk )
                                        <option value="{{ $risk->id }}">{{ $risk->name }}</option>
                                    @endforeach
                                </select>
                                <label for="risk">Riesgos críticos </label>
                            </div>
                            <div class="input-field col s4">
                                <select id="potencial" name="potencial">
                                    <option value="" disabled selected>Selecciona un potencial</option>
                                    <option value="Alto">Alto</option>
                                    <option value="Medio">Medio</option>
                                    <option value="Bajo">Bajo</option>
                                </select>
                                <label for="potencial">Potencial</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s3">
                                <select id="state" name="state">
                                    <option value="" disabled selected>Selecciona un estado</option>
                                    <option value="Abierto">Abierto</option>
                                    <option value="Cerrado">Cerrado</option>
                                </select>
                                <label for="state">Estados</label>
                            </div>
                            <div class="input-field col s3">
                                <input type="date" class="datepicker" id="planned-date" name="planned-date" required>
                                <label for="planned-date" data-error="Please choose a date " data-success="right">Fecha planeada</label>
                            </div>
                            <div class="input-field col s3">
                                <input type="date" class="datepicker" id="deadline" name="deadline" required>
                                <label for="deadline" data-error="Please choose a date " data-success="right">Fecha de cierre</label>
                            </div>
                            <div class="input-field col s3">
                                <input type="number" min="0" id="inspections" name="inspections" required>
                                <label for="inspections" data-error="Please choose a date " data-success="right">Número de inspecciones</label>
                            </div>

                        </div>

                        <div class="row">
                            <div class="input-field col s4">
                                <textarea rows="2" id="description" name="description" class="materialize-textarea"></textarea>
                                <label for="description">Descripción</label>
                            </div>
                            <div class="input-field col s4">
                                <textarea rows="2" id="actions" name="actions" class="materialize-textarea"></textarea>
                                <label for="actions">Acciones a tomar</label>
                            </div>
                            <div class="input-field col s4">
                                <textarea rows="2" id="observation" name="observation" class="materialize-textarea"></textarea>
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
                                <img style="display: none" class="image" id="preview-image" src="#" alt="Preview image" />

                            </div>
                            <div class="file-field input-field col s6">

                                <div class="btn">
                                    <span>Imagen de accion tomada</span>
                                    <input type="file" name="image-action" accept="image/*" id="image-action">

                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" >

                                </div>
                                <img style="display: none" class="image" id="preview-action" src="#" alt="Preview image" />

                            </div>
                        </div>
                        <div class="progress" id="line-loader" style="display: none;">
                            <div class="indeterminate"></div>
                        </div>
                        {{--<div style="" class="center" id="circle-loader"><div  class="preloader-wrapper active">
                                <div class="spinner-layer spinner-blue">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>

                                <div class="spinner-layer spinner-red">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>

                                <div class="spinner-layer spinner-yellow">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>

                                <div class="spinner-layer spinner-green">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div></div>
--}}
                        <div class="row">
                            <a href="{{ url('reports/informe/'. $informe->id) }}" class="waves-effect waves-green btn-flat right ">Cancelar</a>
                            <button type="submit" class="waves-effect waves-green btn-flat right" id="save-report">Guardar</button>
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
    <script type="text/javascript" src="{{ asset('js/report/show.js') }}"></script>
@endsection
