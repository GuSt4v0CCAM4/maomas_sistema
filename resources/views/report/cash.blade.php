@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="subtittle mb-3">
            Informe de Caja
        </div>
        <form method="GET" action="{{ route('cashreport') }}">
            <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating">
                        <select class="form-select" id="date" name="date" >
                            <option selected disabled>--Seleccione un rango--</option>
                            <option value="1" {{ $selectedDate == '1' ? 'selected' : '' }}>Hoy</option>
                            <option value="2" {{ $selectedDate == '2' ? 'selected' : '' }}>Semana</option>
                            <option value="3" {{ $selectedDate == '3' ? 'selected' : '' }}>Mensual</option>
                            <option value="4" {{ $selectedDate == '4' ? 'selected' : '' }}>Personalizado</option>

                        </select>
                        <label for="date">Seleccione la fecha</label>
                        <div id="customRange" style="display: none;">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input class="form-control" type="date" value="" id="startDate" name="startDate"
                                           aria-label="Floating label select example">
                                    <label for="startDate">Inicio:</label>
                                </div>
                            </div>

                            <div class="col-md">
                                <div class="form-floating">
                                    <input class="form-control" type="date" value="" id="endDate" name="endDate"
                                           aria-label="Floating label select example">
                                    <label for="endDate">Fin:</label>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Consultar</button>
                        </div>
                    </div>

                </div>

                <input type="hidden" id="startDateHidden" name="startDate">
                <input type="hidden" id="endDateHidden" name="endDate">
            </div>

        </form>

        <br>
        <div style="display: flex; justify-content: space-between; width: 100%; margin: auto;">
            <div style="width: 220px; text-align: center;">
                <h4>Ventas</h4>
                <canvas id="myChart"></canvas>
            </div>

            <div style="width: 220px; text-align: center;">
                <h4>Gastos</h4>
                <canvas id="myChart2"></canvas>
            </div>

            <div style="width: 220px; text-align: center;">
                <h4>Ganancias</h4>
                <canvas id="myChart3"></canvas>
            </div>
        </div>

        <table class="table mt-5">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Tienda</th>
                    <th scope="col">Fecha:</th>
                    <th scope="col">Efectivo</th>
                    <th scope="col">Transferencia</th>
                    <th scope="col">Yape</th>
                    <th scope="col">Plin</th>
                    <th scope="col">Tarjeta visa</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
            @php
                $totalGanancia = [0.0,0.0,0.0,0.0,0.0,0.0];
                $ganancias = [0.0,0.0,0.0,0.0];
                $expenses = [0.0,0.0,0.0,0.0];
                $profits = [0.0,0.0,0.0,0.0];
            @endphp
            @if(isset($tienda1) && $tienda1->count() > 0)
                @php
                    $rowspanValue = count($tienda1);
                    $subsuma = 0;
                @endphp
                <tr>
                    <th scope="row" rowspan="{{ $rowspanValue }}">{{ $tienda1->first()->name }}</th>
                @foreach($tienda1 as $t1)
                    @php
                        $payment = $t1->payments;
                        $arraypayment = explode('|', $payment);
                        for ($i = 0; $i < 5; $i++) {
                        $totalGanancia[$i] += $arraypayment[$i];
                    }
                        $subsuma += array_sum($arraypayment);
                        $ganancias[0] += $t1->profit;
                        $expenses[0] += $t1->expense;
                        $profits[0] += $t1->sale;
                    @endphp
                        <td>{{ $t1->date }}</td></td>
                        <td>S/. {{ $arraypayment[0] }}</td>
                        <td>S/. {{ $arraypayment[1] }}</td>
                        <td>S/. {{ $arraypayment[2] }}</td>
                        <td>S/. {{ $arraypayment[3] }}</td>
                        <td>S/. {{ $arraypayment[4] }}</td>
                        <td>S/. {{array_sum($arraypayment)}}</td>
                </tr>

                @endforeach
                <tr>
                    <th scope="row">Subsuma</th>
                    <td></td> <!-- Deja las celdas vacías para las columnas que no deben tener valores en esta fila -->
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th scope="row">S/. {{ $subsuma }}</th>
                </tr>
            @endif
            @if(isset($tienda2) && $tienda2->count() > 0)
                @php
                    $rowspanValue = count($tienda2);
                    $subsuma = 0;

                @endphp
                <tr>
                    <th scope="row" rowspan="{{ $rowspanValue }}">{{ $tienda2->first()->name }}</th>
                    @foreach($tienda2 as $t1)
                        @php

                            $payment = $t1->payments;
                            $arraypayment = explode('|', $payment);
                            for ($i = 0; $i < 5; $i++) {
                            $totalGanancia[$i] += $arraypayment[$i];
                        }
                            $subsuma += array_sum($arraypayment);
                            $ganancias[1] += $t1->profit;
                            $expenses[1] += $t1->expense;
                        $profits[1] += $t1->sale;
                        @endphp
                        <td>{{ $t1->date }}</td>
                        <td>S/. {{ $arraypayment[0] }}</td>
                        <td>S/. {{ $arraypayment[1] }}</td>
                        <td>S/. {{ $arraypayment[2] }}</td>
                        <td>S/. {{ $arraypayment[3] }}</td>
                        <td>S/. {{ $arraypayment[4] }}</td>
                        <td>S/. {{array_sum($arraypayment)}}</td>
                </tr>

                @endforeach
                <tr>
                    <th scope="row">Subsuma</th>
                    <td></td> <!-- Deja las celdas vacías para las columnas que no deben tener valores en esta fila -->
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th scope="row">S/. {{ $subsuma }}</th>
                </tr>
            @endif
            @if(isset($tienda3) && $tienda3->count() > 0)
                @php
                    $rowspanValue = count($tienda3);
                    $subsuma = 0;

                @endphp
                <tr>
                    <th scope="row" rowspan="{{ $rowspanValue }}">{{ $tienda3->first()->name }}</th>
                    @foreach($tienda3 as $t1)
                        @php

                            $payment = $t1->payments;
                            $arraypayment = explode('|', $payment);
                            for ($i = 0; $i < 5; $i++) {
                            $totalGanancia[$i] += $arraypayment[$i];
                        }
                            $subsuma += array_sum($arraypayment);
                            $ganancias[2] += $t1->profit;
                            $expenses[2] += $t1->expense;
                            $profits[2] += $t1->sale;
                        @endphp
                        <td>{{ $t1->date }}</td>
                        <td>S/. {{ $arraypayment[0] }}</td>
                        <td>S/. {{ $arraypayment[1] }}</td>
                        <td>S/. {{ $arraypayment[2] }}</td>
                        <td>S/. {{ $arraypayment[3] }}</td>
                        <td>S/. {{ $arraypayment[4] }}</td>
                        <td>S/. {{array_sum($arraypayment)}}</td>
                </tr>

                @endforeach
                <tr>
                    <th scope="row">Subsuma</th>
                    <td></td> <!-- Deja las celdas vacías para las columnas que no deben tener valores en esta fila -->
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th scope="row">S/. {{ $subsuma }}</th>
                </tr>
            @endif
            @if(isset($tienda4) && $tienda4->count() > 0)
                @php
                    $rowspanValue = count($tienda4);
                    $subsuma = 0;

                @endphp
                <tr>
                    <th scope="row" rowspan="{{ $rowspanValue }}">{{ $tienda4->first()->name }}</th>
                    @foreach($tienda4 as $t1)
                        @php

                            $payment = $t1->payments;
                            $arraypayment = explode('|', $payment);
                            for ($i = 0; $i < 5; $i++) {
                            $totalGanancia[$i] += $arraypayment[$i];
                        }
                            $subsuma += array_sum($arraypayment);
                            $ganancias[3] += $t1->profit;
                            $expenses[3] += $t1->expense;
                            $profits[3] += $t1->sale;
                        @endphp
                        <td>{{ $t1->date }}></td>
                        <td>S/. {{ $arraypayment[0] }}</td>
                        <td>S/. {{ $arraypayment[1] }}</td>
                        <td>S/. {{ $arraypayment[2] }}</td>
                        <td>S/. {{ $arraypayment[3] }}</td>
                        <td>S/. {{ $arraypayment[4] }}</td>
                        <td>S/. {{array_sum($arraypayment)}}</td>
                </tr>

                @endforeach
                <tr>
                    <th scope="row">Subsuma</th>
                    <td></td> <!-- Deja las celdas vacías para las columnas que no deben tener valores en esta fila -->
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th scope="row">S/. {{ $subsuma }}</th>
                </tr>
            @endif
                <tr>
                    <th scope="row" colspan="2">TOTAL</th>
                    <td >S/. {{ $totalGanancia[0] }}</td>
                    <td >S/. {{ $totalGanancia[1] }}</td>
                    <td >S/. {{ $totalGanancia[2] }}</td>
                    <td >S/. {{ $totalGanancia[3] }}</td>
                    <td >S/. {{ $totalGanancia[4] }}</td>
                    <td >S/. {{array_sum($totalGanancia)}}</td>
                </tr>
            </tbody>
        </table>

        @php
            $etiquetas = ['San Camilo', 'Maternos', 'Maomas', 'Camana'];
        @endphp

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
            var etiquetasVentas = @json($etiquetas);
            var ganancias = @json($ganancias);
            const ctxVentas = document.getElementById('myChart');

            new Chart(ctxVentas, {
                type: 'doughnut',
                data: {
                    labels: etiquetasVentas,
                    datasets: [{
                        label: '# de ventas',
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                        ],
                        data: ganancias,
                        borderColor: 'rgb(255, 99, 132)',
                    }]
                },
                options: {}
            });
        </script>

        <script>
            var etiquetasGastos = @json($etiquetas);
            var expenses = @json($expenses);
            const ctxGastos = document.getElementById('myChart2');

            new Chart(ctxGastos, {
                type: 'doughnut',
                data: {
                    labels: etiquetasGastos,
                    datasets: [{
                        label: '# de gastos',
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                        ],
                        data: expenses,
                        borderColor: 'rgb(255, 99, 132)',
                    }]
                },
                options: {}
            });
        </script>
        <script>
            var etiquetasGanancias = @json($etiquetas);
            var profits = @json($profits);
            const ctxGanancias = document.getElementById('myChart3');

            new Chart(ctxGanancias, {
                type: 'doughnut',
                data: {
                    labels: etiquetasGanancias,
                    datasets: [{
                        label: '# de ganancias',
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                        ],
                        data: profits,
                        borderColor: 'rgb(255, 99, 132)',
                    }]
                },
                options: {}
            });
        </script>
@endsection
