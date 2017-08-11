<div class="row">

    {{-- 1 --}}
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
                        <td>{{ $aspectImprove }}</td>
                    </tr>
                    <tr>
                        <td>Positivo</td>
                        <td>{{ $aspectPositive }}</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ $aspectImprove+$aspectPositive }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 2 --}}
    <div class="col s12 m6 xl4" >
        <div class="card">
            <div class="card-image waves-effect waves-block waves-light">
                <div id="container2"></div>
            </div>
            <div class="card-content">
                <span class="card-title activator grey-text text-darken-4">SEGÚN LOCALIZACIÓN<i class="material-icons right">more_vert</i></span>
            </div>
            <div class="card-reveal">
                <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                <table id="">
                    <thead>
                    <tr>
                        <th data-field="id">Localización</th>
                        <th data-field="name">Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                        <tr>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->y }}</td>
                        </tr>
                        @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ $locations->sum('y') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 3 --}}
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
                    <tbody>
                    @foreach ($risks as $risk)
                        <tr>
                            <td>{{ $risk->name }}</td>
                            <td>{{ $risk->y }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ $risks->sum('y') }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- 4 --}}
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
                <table>
                    <thead>
                    <tr>
                        <th data-field="id">Área</th>
                        <th data-field="name">Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($areas as $area)
                        <tr>
                            <td>{{ $area->name }}</td>
                            <td>{{ $area->y }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ $areas->sum('y') }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- 5 --}}
    <div class="col s12 m6 xl4">

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
                        <td>{{ $open }}</td>
                    </tr>
                    <tr>
                        <td>Cerrados</td>
                        <td>{{ $closed }}</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ $open+$closed }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- 6 --}}
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
                    <tbody>
                    @foreach ($responsibleItems as $responsible)
                        <tr>
                            <td>{{ $responsible->name }}</td>
                            <td>{{ $responsible->y }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ $responsibleItems->sum('y') }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- 7 --}}
    <div class="col s12 m6 xl4" >
        <div class="card">
            <div class="card-image waves-effect waves-block waves-light">
                <div id="container7"></div>
            </div>
            <div class="card-content">
                <span class="card-title activator grey-text text-darken-4">SEGÚN LOCALIZACIÓN<i class="material-icons right">more_vert</i></span>

            </div>
            <div class="card-reveal">
                <span class="card-title grey-text text-darken-4">Datos en tabla<i class="material-icons right">close</i></span>
                <table id="">
                    <thead>
                    <tr>
                        <th data-field="id">Localización</th>
                        <th data-field="name">Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($openLocations as $location)
                        <tr>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->y }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ $openLocations->sum('y') }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- 8 --}}
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
                    <tbody>
                    @foreach ($byResponsibleOpenReports as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->y }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ $byResponsibleOpenReports->sum('y') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
