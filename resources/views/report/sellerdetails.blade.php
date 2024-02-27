@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="subtittle mb-3">
            Detalles de los vendedores

        </div>
        <form method="GET" action="{{ route('sellerdetails') }}" id="customRangeForm">
            <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating">
                        <select class="form-select" id="date" name="date">
                            <option selected disabled>--Seleccione un rango--</option>
                            <option value="1" {{ $selectedDate == '1' ? 'selected' : '' }}>Hoy</option>
                            <option value="2" {{ $selectedDate == '2' ? 'selected' : '' }}>Semana</option>
                            <option value="3" {{ $selectedDate == '3' ? 'selected' : '' }}>Mensual</option>
                            <option value="4" {{ $selectedDate == '4' ? 'selected' : '' }}>Personalizado</option>
                        </select>
                        <label for="date">Seleccione la fecha</label>
                    </div>
                </div>
                <input type="hidden" id="startDateHidden" name="startDate">
                <input type="hidden" id="endDateHidden" name="endDate">
            </div>
        </form>
        <div id="customRange" style="display: none;">
            <form method="GET" action="{{ route('sellerdetails') }}">
                <div class="col-md">
                    <select class="form-select" id="date" name="date" type="hidden">
                        <option selected disabled>--Seleccione un rango--</option>
                        <option value="4" selected>Personalizado</option>
                    </select>
                    <div class="form-floating">
                        <input class="form-control" type="date" value="" id="startDate" name="inicioFecha"
                               aria-label="Floating label select example">
                        <label for="startDate">Inicio:</label>
                    </div>
                </div>

                <div class="col-md">
                    <div class="form-floating">
                        <input class="form-control" type="date" value="" id="endDate" name="finFecha"
                               aria-label="Floating label select example">
                        <label for="endDate">Fin:</label>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit"  >Consultar</button>
            </form>
        </div>
        @php
            $totales = [];
            $nombres = [];
        @endphp
        @if(isset($ranking) && $ranking->count() > 0)
            @foreach($ranking as $r)
                @php
                    $ingreso = abs($r->expense) + abs($r->sale);
                    $totales[] = $ingreso;
                    $nombres[] = $r->name;
                @endphp
            @endforeach
        @endif
        <div class="row">
            <div class="col-md-6">
                <div style="width: 500px; margin: 0 auto; text-align: center;" class="mt-4">
                    <h4>Tendencias de Ventas/Trabajador</h4>
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div style="width: 250px; margin: 0 auto; text-align: center;" class="mt-4">
                    <h4>Distribucion de Ventas</h4>
                    <canvas id="ventapertrabajador"></canvas>
                </div>
            </div>
        </div>

    </div>
@if(isset($matriz_datos, $users))
    @php
        $name_label = [];
        $id_users = [];
    $id = array_keys($matriz_datos);

    foreach ($users as $user) {
        if (in_array($user->id, $id)) {
            $name_label[$user->id] = $user->name;
            $id_users[] = $user->id;
        }
    }

    @endphp
@endif

    <div class="row mt-5">
        <div class="col-md-6">
            <h3 class="text-center">Total de Ventas/Trabajador</h3>
            {{-- Punto 1: Total de Ventas por Trabajador --}}
            <table class="table mt-2 table-striped-columns">
                <caption>Total de Ventas por Trabajador</caption>
                <thead class="table-dark">
                <tr>
                    <th>Trabajador</th>
                    <th>Total de Ventas</th>
                </tr>
                </thead>
                <tbody>
                @foreach($matriz_datos as $id_user => $vendedor)
                    <tr>
                        <td>{{ $name_label[$id_user] }}</td>
                        <td>S/. {{ array_sum(array_column($vendedor['datos'], 'profit')) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3 class="text-center">Promedio de Ventas/dia laborable</h3>
            {{-- Punto 2: Promedio de Ventas por Trabajador --}}
            <table class="table mt-2 table-striped-columns">
                <caption>Promedio de Ventas por Trabajador</caption>
                <thead class="table-dark">
                <tr>
                    <th>Trabajador</th>
                    <th>Promedio de Ventas</th>
                </tr>
                </thead>
                <tbody>
                @foreach($matriz_datos as $id_user => $vendedor)
                    @php
                        $datosConNombre = array_filter($vendedor['datos'], function($dato) {
                            return isset($dato['name']) && $dato['name'] !== '';
                        });
                    @endphp
                    <tr>
                        <td>{{ $name_label[$id_user] }}</td>
                        <td>S/. {{ array_sum(array_column($vendedor['datos'], 'profit')) / count($datosConNombre) }} al dia</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3 class="text-center">Ventas por Fecha (4 tiendas)</h3>
            {{-- Punto 3: Ventas Totales y Promedio por Fecha --}}
            <table class="table mt-2 table-striped-columns">
                <caption>Ventas Totales y Promedio por Fecha</caption>
                <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Ventas Totales </th>
                    <th>Promedio de Ventas</th>
                </tr>
                </thead>
                <tbody>
                @foreach($matriz_datos[1]['datos'] as $fecha => $datos)
                    <tr>
                        <td>{{ $fecha }}</td>
                        @php
                            $ventasFecha = 0;
                            for ($i = 0; $i < count($matriz_datos); $i++) {
                                $id = $id_users[$i];
                                if (isset($matriz_datos[$id]['datos'][$fecha]['name']))
                                {
                                    $ventasFecha += $matriz_datos[$id]['datos'][$fecha]['profit'];
                                }
                            }
                                // Calcular el promedio de ventas para la fecha
                                $promedioVentasFecha = $ventasFecha / 4;
                        @endphp
                        <td>S/. {{ $ventasFecha }}</td>
                        <td>S/. {{ $promedioVentasFecha }} por Tienda</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table>
                <caption>Días de Máximas y Mínimas Ventas por Trabajador</caption>
                <thead>
                <tr>
                    <th>Trabajador</th>
                    <th>Fecha Máxima</th>
                    <th>Ventas Máximas</th>
                    <th>Fecha Mínima</th>
                    <th>Ventas Mínimas</th>
                </tr>
                </thead>
                <tbody>
                @foreach($matriz_datos as $id_user => $vendedor)
                    <tr>
                        <td>{{ $name_label[$id_user] }}</td>
                        @php
                            $ventas = array_column(
                              array_filter($vendedor['datos'], function ($dato) {
                                return isset($dato['name']);
                              }),
                              'profit'
                            );
                            $maxVentasIndex = array_search(max($ventas), $ventas);
                            $minVentasIndex = array_search(min($ventas), $ventas);
                            
                            $profitMax = $ventas[$maxVentasIndex];
                            $profitMin = $ventas[$minVentasIndex];
                        @endphp
                        <td></td>
                        <td>{{ $profitMax }}</td>
                        <td></td>
                        <td>{{ $profitMin }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <script>

        if (document.getElementById('date').value === '4') {
            document.getElementById('customRange').style.display = 'flex';
        }
        document.getElementById('date').addEventListener('change', function() {

            if (this.value === '4') {
                document.getElementById('customRange').style.display = 'flex';
            } else {
                document.getElementById('customRange').style.display = 'none';
                this.form.submit();
            }

        });

        document.getElementById('store').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('customRange').addEventListener('change', function () {
            // Obtener los valores de startDate y endDate
            var startDateValue = document.getElementById('startDate').value;
            var endDateValue = document.getElementById('endDate').value;

            // Asignar los valores a campos ocultos en el formulario
            document.getElementById('startDateHidden').value = startDateValue;
            document.getElementById('endDateHidden').value = endDateValue;

            // Enviar el formulario
            this.form.submit();
        });



        // Muestra u oculta el campo de rango de fechas según la selección del usuari
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Inyecta los datos de Laravel en tu script de Chart.js
        var matriz_datos = @json($matriz_datos);
        var name_labels = @json($name_label);
        function getRandomColor() {
            const red = Math.floor(Math.random() * 256);
            const green = Math.floor(Math.random() * 256);
            const blue = Math.floor(Math.random() * 256);
            return `rgba(${red}, ${green}, ${blue}, 0.8)`;
        }

        const ctx = document.getElementById('myChart');

        const data = {
            labels: [],
            datasets: []
        };

        for (const id_user in matriz_datos) {
            if (matriz_datos.hasOwnProperty(id_user)) {
                const vendedor = matriz_datos[id_user];
                const datosVendedor = [];

                for (const fecha in vendedor.datos) {
                    if (vendedor.datos.hasOwnProperty(fecha)) {
                        if (!data.labels.includes(fecha)) {
                            data.labels.push(fecha);
                        }

                        const indiceFecha = data.labels.indexOf(fecha);

                        while (datosVendedor.length < indiceFecha) {
                            datosVendedor.push(0);
                        }

                        datosVendedor[indiceFecha] = vendedor.datos[fecha].profit;
                    }
                }

                data.datasets.push({
                    label: name_labels[id_user],// Obtener el nombre del primer elemento de datos
                    data: datosVendedor,
                    borderColor: getRandomColor(),
                    borderWidth: 2,
                    fill: false
                });
            }
        }

        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        function getRandomColor() {
            const red = Math.floor(Math.random() * 256);
            const green = Math.floor(Math.random() * 256);
            const blue = Math.floor(Math.random() * 256);
            return `rgba(${red}, ${green}, ${blue}, 0.8)`;
        }
        var etiquetasVentas = @json($nombres);
        var ganancias = @json($totales);
        const ctxVentas = document.getElementById('ventapertrabajador');

        const coloresAleatorios = Array.from({ length: etiquetasVentas.length }, () => getRandomColor());

        new Chart(ctxVentas, {
            type: 'doughnut',
            data: {
                labels: etiquetasVentas,
                datasets: [{
                    label: '# de ventas',
                    backgroundColor: coloresAleatorios,
                    data: ganancias,
                    borderColor: 'rgb(255, 99, 132)',
                }]
            },
            options: {}
        });
    </script>

@endsection
