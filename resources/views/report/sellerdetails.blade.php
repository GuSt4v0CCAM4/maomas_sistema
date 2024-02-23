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
        <div style="width: 500px; margin: 0 auto; text-align: center;" class="mt-4">
            <h4>Progreso</h4>
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <table class="table mt-5 table-striped-columns">
        <thead class="table-dark">
        <tr>
            <th colspan="1" class="table-light"></th>
            <th colspan="10" class="text-center">VENDEDORES:</th>
        </tr>
        <tr>
            <th colspan="">Fecha: </th>
            @foreach($users as $user)
            <th scope="col">{{$user->name}}</th>
            @endforeach
        </tr>

        </thead>
        <tbody>
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
        // Inyecta los datos de Laravel en tu script de Chart.js
        var matriz_datos = @json($matriz_datos);

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
                    label: vendedor.datos[Object.keys(vendedor.datos)[0]].name, // Obtener el nombre del primer elemento de datos
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

@endsection
