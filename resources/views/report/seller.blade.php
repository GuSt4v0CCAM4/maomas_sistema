@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="subtittle mb-3">
            Informe de Trabajadores
        </div>
        <form method="GET" action="{{ route('sellerreport') }}">
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
        <div style="width: 220px; margin: 0 auto; text-align: center;" class="mt-4">
            <h4>Ventas totales</h4>
            <canvas id="myChart"></canvas>
        </div>
        <table class="table mt-5 table-striped-columns">
            <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Vendedor</th>
                <th scope="col">Ventas</th>
                <th scope="col">Balance</th>
                <th scope="col"><a href="{{route('sellerdetails')}}">Ver Detalles</a></th>
            </tr>

            </thead>
            <tbody>
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
                    <tr>
                        <td>{{$r->id}}</td>
                        <td>{{$r->name}}</td>
                        <td>S/. {{abs($r->expense) + abs($r->sale)}}</td>
                        @if($r->balance > 0)
                            <td class="bg-danger">S/. {{abs($r->balance)}}</td>
                        @else
                        <td>S/. {{abs($r->balance)}}</td>
                        @endif

                    </tr>
                @endforeach
             @endif
            </tbody>
        </table>
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
            function getRandomColor() {
                const red = Math.floor(Math.random() * 256);
                const green = Math.floor(Math.random() * 256);
                const blue = Math.floor(Math.random() * 256);
                return `rgba(${red}, ${green}, ${blue}, 0.8)`;
            }
            var etiquetasVentas = @json($nombres);
            var ganancias = @json($totales);
            const ctxVentas = document.getElementById('myChart');

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
