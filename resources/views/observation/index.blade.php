@extends('layouts.app')

@section('breadcrumbs')
    <div class="row">
        <div class="navbar-fixed">
            <nav class="light-blue">
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a href="{{ url('/informes') }}" class="breadcrumb">Informes</a>
                        <a href="{{ url('/observations/informe/'.$inform->id) }}" class="breadcrumb">Informe {{ $inform->id }}</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card .image {
            width: 280px;
            height: 280px;
        }
        .card .image-reveal {
            width: 235px;
            height: 235px;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title flow-text ng-binding">Informe - {{ $inform->id }}</span>

                    <div class="row">
                        <div class="col s12 m2 l2">
                            <label>Localización</label>
                            <p class="margin-0 ng-binding">{{ $inform->location->name }}</p>
                        </div>
                        <div class="col s12 m3 l3">
                            <label>Onduty</label>
                            <p class="margin-0 ng-binding">{{ $inform->user->name }}</p>
                        </div>
                        <div class="col s12 m2 l2">
                            <label>Fecha de registro</label>
                            <p class="margin-0 ng-binding">{{ $inform->updated_at }}</p>
                        </div>
                        <div class="col s12 m2 l2">
                            <label>Fecha de visita de</label>
                            <p class="margin-0 ng-binding">{{ $inform->from_date }}</p>
                        </div>
                        <div class="col s12 m2 l2">
                            <label>Fecha de visita hasta</label>
                            <p class="margin-0 ng-binding">{{ $inform->to_date }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12" >
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Observaciones</span>

                    @if (Auth::user()->role_id < 4)
                        <a href="#modal2"
                           data-delay="50"
                           data-tooltip="Nueva observación"
                           class="btn-floating btn-large waves-effect waves-light tooltipped teal right modal-trigger">
                            <i class="material-icons">add</i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <table class="highlight centered responsive-table">
                        <thead>
                        <tr>
                            <th data-field="turn">Turno</th>
                            <th data-field="supervisor">Supervisor en turno</th>
                            <th data-field="hse">HSE en turno</th>
                            <th data-field="man">N° de Hombres</th>
                            <th data-field="woman">N° de Mujeres</th>
                            <th data-field="people">Total de personas</th>
                            <th data-field="hour">Horas en el turno</th>
                            <th data-field="total-hours">Horas de trabajo</th>
                            <th data-field="observation">Observación</th>
                            @if (Auth::user()->role_id <3)
                                <th data-field="">Acciones</th>
                            @endif
                        </tr>
                        </thead>

                        <tbody>
                        @foreach( $observations as $observation )
                            <tr>
                                <td>{{ $observation->turn }}</td>
                                <td>{{ $observation->supervisor->name }}</td>
                                <td>{{ $observation->hse->name }}</td>
                                <td>{{ $observation->man }}</td>
                                <td>{{ $observation->woman }}</td>
                                <td>{{ $observation->total_people }}</td>
                                <td>{{ $observation->turn_hours }}</td>
                                <td>{{ $observation->work_hours }}</td>
                                <td>{{ $observation->observation }}</td>
                                <td>
                                    <a data-observation="{{ $observation->observation }}" data-turn_hours="{{ $observation->turn_hours }}" data-woman="{{ $observation->woman }}" data-man="{{ $observation->man }}" data-hse="{{ $observation->hse_id }}" data-supervisor="{{ $observation->supervisor_id }}" data-turn="{{ $observation->turn }}" data-edit="{{ $observation->id }}" class="waves-effect waves-light btn" href="#modalEdit"><i class="material-icons">mode_edit</i></a>
                                    <a data-delete="{{ $observation->id }}"  class="waves-effect waves-light btn" href="#modal1"><i class="material-icons">delete</i></a>
                                </td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>

    <!-- Modal Structure -->
    <div id="modal2" class="modal">
        <form class="col s12" id="form-register" method="post" action="{{ url('/observation/register') }}">
            {{ csrf_field() }}
            <input type="hidden" name="informe_id" value="{{ $inform->id }}">
            <div class="modal-content">
                <div class="row">
                    <div class="input-field col s4">
                        <select id="turn" name="turn">
                            <option value="" disabled selected>Selecciona un turno</option>
                            <option value="Diurno">Diurno</option>
                            <option value="Nocturno">Nocturno</option>
                        </select>
                        <label for="turn">Turnos</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="supervisor" name="supervisor">
                            <option value="" disabled selected>Selecciona un supervisor</option>
                            @foreach( $users as $user )
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach

                        </select>
                        <label for="supervisor">Supervisor de turno</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="hse" name="hse">
                            <option value="" disabled selected>Selecciona un HSE</option>
                            @foreach( $users as $user )
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <label for="hse">HSE de turno</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s4">
                        <input type="number" min="0" step="1" id="man" name="man" required>
                        <label for="man" data-error="Please choose a numbers man " data-success="right">Número de hombres</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="number" min="0" step="1" id="woman" name="woman" required>
                        <label for="woman" data-error="Please choose a numbers woman " data-success="right">Número de mujeres</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="number" min="0" step="1" id="turn_hours" name="turn_hours" required>
                        <label for="turn_hours" data-error="Please enter hours " data-success="right">Horas en el turno</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <textarea rows="2" id="observation" name="observation" class="materialize-textarea"></textarea>
                        <label for="observation">Observación</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat" id="">Guardar</button>
            </div>
        </form>
    </div>

    <div id="modal1" class="modal">
        <form class="col s12" id="form-delete" method="post" action="{{ url('/observation/delete') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id">
            <div class="modal-content">
                <h4>Eliminar observación</h4>
                <div class="row">
                    <p>¿Está seguro de eliminar esta observación?</p>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat" >Eliminar</button>
            </div>
        </form>
    </div>

    <div id="modalEdit" class="modal">
        <form class="col s12" type="post" id="form-edit" action="{{ url('/observation/edit') }}">
            {{ csrf_field() }}
            <input type="hidden" name="id">
            <div class="modal-content">

                <div class="row">
                    <div class="input-field col s4">
                        <select id="turn_edit" name="turn_edit">
                            <option value="" disabled selected>Selecciona un turno</option>
                            <option value="Mañana">Mañana</option>
                            <option value="Tarde">Tarde</option>
                        </select>
                        <label for="turn_edit">Turnos</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="supervisor_edit" name="supervisor_edit">
                            <option value="" disabled selected>Selecciona un supervisor</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach

                        </select>
                        <label for="supervisor_edit">Supervisor de turno</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="hse_edit" name="hse_edit">
                            <option value="" disabled selected>Selecciona un HSE</option>
                            @foreach( $users as $user )
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <label for="hse_edit">HSE de turno</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s4">
                        <input type="number" min="0" step="1" id="man_edit" name="man_edit" required>
                        <label for="man_edit" data-error="Please choose a numbers man " data-success="right">Número de hombres</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="number" min="0" step="1" id="woman_edit" name="woman_edit" required>
                        <label for="woman_edit" data-error="Please choose a numbers woman " data-success="right">Número de mujeres</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="number" min="0" step="1" id="turn_hours_edit" name="turn_hours_edit" required>
                        <label for="turn_hours_edit" data-error="Please enter hours " data-success="right">Horas en el turno</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <textarea rows="2" id="observation_edit" name="observation_edit" class="materialize-textarea"></textarea>
                        <label for="observation_edit">Observación</label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                <button type="submit" href="#" class="waves-effect waves-green btn-flat" >Guardar</button>
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

    <script src="{{ asset('js/observation/index.js') }}"></script>
@endsection
