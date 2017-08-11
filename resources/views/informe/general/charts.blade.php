<div class="row">
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
</div>
