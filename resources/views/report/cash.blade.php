@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="subtittle mb-3">
            Informe de Caja
        </div>
        <form method="GET" action="{{ route('cashreport') }}" id="customRangeForm">
            <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating">
                        <select class="form-select" id="date" name="date">
                            <option selected disabled>--Seleccione un rango--</option>
                            <option value="2" {{ $selectedDate == '2' ? 'selected' : '' }}>Semana</option>
                            <option value="3" {{ $selectedDate == '3' ? 'selected' : '' }}>Mensual</option>
                        </select>
                        <label for="date">Seleccione la fecha</label>
                    </div>
                </div>
            </div>
        </form>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Fecha Personalizada
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <form method="GET" action="{{ route('cashreport') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control" type="date" value="" id="startDate" name="inicioFecha"
                                           aria-label="Floating label select example">
                                    <label for="startDate">Inicio:</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control" type="date" value="" id="endDate" name="finFecha"
                                           aria-label="Floating label select example">
                                    <label for="endDate">Fin:</label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-2" type="submit"  >Consultar</button>
                    </form>
                </div>
            </div>
        </div>
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
                <h4>Ingresos</h4>
                <canvas id="myChart3"></canvas>
            </div>
        </div>

        <table class="table mt-5 table-striped-columns">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Tienda</th>
                    <th scope="col">Fecha:</th>
                    <th scope="col">Efectivo</th>
                    <th scope="col">Transferencia</th>
                    <th scope="col">Yape</th>
                    <th scope="col">Plin</th>
                    <th scope="col">Tarjeta visa</th>
                    <th scope="col">Gastos</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
            @php
                $totalGanancia = [0.0,0.0,0.0,0.0,0.0];
                $ganancias = [0.0,0.0,0.0,0.0]; //para los graficos
                $expenses = [0.0,0.0,0.0,0.0];//para los graficos
                $profits = [0.0,0.0,0.0,0.0];//para los graficos
                $total_g = 0;
                $total_t = 0;
            @endphp
            @if(isset($tienda1) && $tienda1->count() > 0)
                @php
                    $rowspanValue = count($tienda1);
                    $subsuma = 0;
                    $subsuma_g = 0;
                    $subsuma_t = 0;
                @endphp
                <tr>
                    <th scope="row" rowspan="{{ $rowspanValue }}" style="background-color: rgba(255, 99, 132, 0.8)">{{ $tienda1->first()->name }}</th>
                @foreach($tienda1 as $t1)
                    @php
                        $payment = $t1->payments;
                        $arraypayment = explode('|', $payment);
                        for ($i = 0; $i < 5; $i++) {
                        $totalGanancia[$i] += $arraypayment[$i];
                    }
                        $subsuma += array_sum($arraypayment);
                        $subsuma_g += $t1->expense;
                        $subsuma_t += $t1->profit;
                        $total_g += $t1->expense;
                        $total_t += $t1->profit;
                        $ganancias[0] += $t1->profit;
                        $expenses[0] += $t1->expense;
                        $profits[0] += $t1->sale;
                   //setear la fecha en el formato: lunes 01 de Enero,2024
                            $date = $t1->date;
                            setlocale(LC_TIME, 'es_ES');
                            $fecha_formateada = strftime("%A %d de %B, %Y", strtotime($date));


                    @endphp
                        <td>{{ $fecha_formateada }}</td>
                        <td>S/. {{ $arraypayment[0] }}</td>
                        <td>S/. {{ $arraypayment[1] }}</td>
                        <td>S/. {{ $arraypayment[2] }}</td>
                        <td>S/. {{ $arraypayment[3] }}</td>
                        <td>S/. {{ $arraypayment[4] }}</td>
                        <td>S/. {{$t1->expense}}</td>
                        <td>S/. {{ $t1->profit }} </td>
                </tr>

                @endforeach
                <tr class="table-primary">
                    <th scope="row" >Subsuma</th>
                    <td></td> <!-- Deja las celdas vacías para las columnas que no deben tener valores en esta fila -->
                    <td colspan="5" class="text-center"><b>S/. {{ array_sum($totalGanancia) }}</b></td>
                    <th scope="row">S/. {{ $subsuma_g }}</th>
                    <th scope="row">S/. {{ $subsuma_t }}</th>
                </tr>
            @endif
            @if(isset($tienda2) && $tienda2->count() > 0)
                @php
                    $rowspanValue = count($tienda2);
                    $subsuma = 0;
                    $subsuma_g = 0;
                    $subsuma_t = 0;

                @endphp
                <tr>
                    <th scope="row" rowspan="{{ $rowspanValue }}" style="background-color: rgba(54, 162, 235, 0.8)">{{ $tienda2->first()->name }}</th>
                    @foreach($tienda2 as $t1)
                        @php

                            $payment = $t1->payments;
                            $arraypayment = explode('|', $payment);
                            for ($i = 0; $i < 5; $i++) {
                            $totalGanancia[$i] += $arraypayment[$i];
                        }
                            $subsuma += array_sum($arraypayment);
                            $subsuma_g += $t1->expense;
                            $subsuma_t += $t1->profit;
                            $total_g += $t1->expense;
                            $total_t += $t1->profit;
                            $ganancias[1] += $t1->profit;
                            $expenses[1] += $t1->expense;
                        $profits[1] += $t1->sale;
                        //setear la fecha en el formato: lunes 01 de Enero,2024
                            $date = $t1->date;
                            setlocale(LC_TIME, 'es_ES');
                            $fecha_formateada = strftime("%A %d de %B, %Y", strtotime($date));
                        @endphp
                        <td>{{ $fecha_formateada }}</td>
                        <td>S/. {{ $arraypayment[0] }}</td>
                        <td>S/. {{ $arraypayment[1] }}</td>
                        <td>S/. {{ $arraypayment[2] }}</td>
                        <td>S/. {{ $arraypayment[3] }}</td>
                        <td>S/. {{ $arraypayment[4] }}</td>
                        <td>S/. {{$t1->expense}}</td>
                        <td>S/. {{ $t1->profit }} </td>
                </tr>

                @endforeach
                <tr class="table-primary">
                    <th scope="row" >Subsuma</th>
                    <td></td> <!-- Deja las celdas vacías para las columnas que no deben tener valores en esta fila -->

                        <td colspan="5" class="text-center"><b>S/. {{ array_sum($totalGanancia) }}</b></td>

                    <th scope="row">S/. {{ $subsuma_g }}</th>
                    <th scope="row">S/. {{ $subsuma_t }}</th>
                </tr>
            @endif
            @if(isset($tienda3) && $tienda3->count() > 0)
                @php
                    $rowspanValue = count($tienda3);
                    $subsuma = 0;
                    $subsuma_g = 0;
                    $subsuma_t = 0;
                @endphp
                <tr>
                    <th scope="row" rowspan="{{ $rowspanValue }}" style="background-color: rgba(255, 206, 86, 0.8)">{{ $tienda3->first()->name }}</th>
                    @foreach($tienda3 as $t1)
                        @php

                            $payment = $t1->payments;
                            $arraypayment = explode('|', $payment);
                            for ($i = 0; $i < 5; $i++) {
                            $totalGanancia[$i] += $arraypayment[$i];
                        }
                            $subsuma += array_sum($arraypayment);
                            $subsuma_g += $t1->expense;
                            $subsuma_t += $t1->profit;
                            $total_g += $t1->expense;
                            $total_t += $t1->profit;
                            $ganancias[2] += $t1->profit;
                            $expenses[2] += $t1->expense;
                            $profits[2] += $t1->sale;
                        //setear la fecha en el formato: lunes 01 de Enero,2024
                            $date = $t1->date;
                            setlocale(LC_TIME, 'es_ES');
                            $fecha_formateada = strftime("%A %d de %B, %Y", strtotime($date));
                        @endphp
                        <td>{{ $fecha_formateada }}</td>
                        <td>S/. {{ $arraypayment[0] }}</td>
                        <td>S/. {{ $arraypayment[1] }}</td>
                        <td>S/. {{ $arraypayment[2] }}</td>
                        <td>S/. {{ $arraypayment[3] }}</td>
                        <td>S/. {{ $arraypayment[4] }}</td>
                        <td>S/. {{$t1->expense}}</td>
                        <td>S/. {{ $t1->profit }} </td>
                </tr>

                @endforeach
                <tr class="table-primary">
                    <th scope="row" >Subsuma</th>
                    <td></td> <!-- Deja las celdas vacías para las columnas que no deben tener valores en esta fila -->
                    <td colspan="5" class="text-center"><b>S/. {{ array_sum($totalGanancia) }}</b></td>
                    <th scope="row">S/. {{ $subsuma_g }}</th>
                    <th scope="row">S/. {{ $subsuma_t }}</th>
                </tr>
            @endif
            @if(isset($tienda4) && $tienda4->count() > 0)
                @php
                    $rowspanValue = count($tienda4);
                    $subsuma = 0;
                    $subsuma_g = 0;
                    $subsuma_t = 0;

                @endphp
                <tr>
                    <th scope="row" rowspan="{{ $rowspanValue }}" style="background-color: rgba(75, 192, 192, 0.8)">{{ $tienda4->first()->name }}</th>
                    @foreach($tienda4 as $t1)
                        @php

                            $payment = $t1->payments;
                            $arraypayment = explode('|', $payment); // separar el string en un array
                            for ($i = 0; $i < 5; $i++) {
                            $totalGanancia[$i] += $arraypayment[$i]; //sumamos todos los array
                            //de los registros para obtener la ganancia total por metodo de pago
                        }
                            $subsuma += array_sum($arraypayment); //ahora sumamos el valor de todos los medios de pago
                            $subsuma_g += $t1->expense;
                            $subsuma_t += $t1->profit;
                            $total_g += $t1->expense;
                            $total_t += $t1->profit;
                            // para tenr la ganancia del dia y lo adjuntamos en subsuma que se ira sumando con el arraypayment
                            // de cada dia para tener la subsuma de esa tienda en total
                            //del array para obtener la suma total de lo diferentes metodos de pago
                            $ganancias[3] += $t1->profit; //sumamos las ventas de esta tienda con id 3
                            $expenses[3] += $t1->expense; //sumamos las gastos de esta tienda con id 3
                            $profits[3] += $t1->sale; //sumamos las ganancia de esta tienda con id 3

                            //setear la fecha en el formato: lunes 01 de Enero,2024
                            $date = $t1->date;
                            setlocale(LC_TIME, 'es_ES');
                            $fecha_formateada = strftime("%A %d de %B, %Y", strtotime($date));
                        @endphp
                        <td>{{ $fecha_formateada }}</td>
                        <td>S/. {{ $arraypayment[0] }}</td>
                        <td>S/. {{ $arraypayment[1] }}</td>
                        <td>S/. {{ $arraypayment[2] }}</td>
                        <td>S/. {{ $arraypayment[3] }}</td>
                        <td>S/. {{ $arraypayment[4] }}</td>
                        <!--      <td>S/. array_sum($arraypayment)</td> sumamos el array para obtener la ganancia total de la tienda en un dia -->
                        <td>S/. {{$t1->expense}}</td>
                        <td>S/. {{ $t1->profit }} </td>
                </tr>

                @endforeach
                <tr class="table-primary">
                    <th scope="row" >Subsuma</th>
                    <td></td> <!-- Deja las celdas vacías para las columnas que no deben tener valores en esta fila -->
                    <td colspan="5" class="text-center"><b>S/. {{ array_sum($totalGanancia) }}</b></td>

                    <th scope="row">S/. {{ $subsuma_g }}</th>
                    <th scope="row">S/. {{ $subsuma_t }}</th>
                </tr>
            @endif
                <tr>
                    <th scope="row" colspan="2">TOTAL</th>
                    <td >S/. {{ $totalGanancia[0] }}</td>
                    <td >S/. {{ $totalGanancia[1] }}</td>
                    <td >S/. {{ $totalGanancia[2] }}</td>
                    <td >S/. {{ $totalGanancia[3] }}</td>
                    <td >S/. {{ $totalGanancia[4] }}</td>
                    <td >S/. {{$total_g}}</td>
                    <td >S/. {{$total_t}}</td>
                </tr>
            </tbody>
        </table>

        @php
            $etiquetas = ['San Camilo', 'Maternos', 'Maomas', 'Camana'];
        @endphp

        <script>
            document.getElementById('date').addEventListener('change', function() {
                this.form.submit();
            })
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
