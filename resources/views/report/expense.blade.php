@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="subtittle mb-3">
            Informe de Gastos
        </div>
        <form method="GET" action="{{ route('expensereport') }}" id="customRangeForm">
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
                <input type="hidden" id="startDateHidden" name="startDate">
                <input type="hidden" id="endDateHidden" name="endDate">
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
                    <form method="GET" action="{{ route('expensereport') }}">
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
        <div style="width: 300px; margin: 0 auto; text-align: center;" class="mt-4">
            <h4>Proporcion de Gastos</h4>
            <canvas id="myChart"></canvas>
        </div>
        <script>

            document.getElementById('date').addEventListener('change', function() {
                this.form.submit();
            })
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @if(isset($amount_label, $expense_label))
        <script>
            function getRandomColor() {
                const red = Math.floor(Math.random() * 256);
                const green = Math.floor(Math.random() * 256);
                const blue = Math.floor(Math.random() * 256);
                return `rgba(${red}, ${green}, ${blue}, 0.8)`;
            }
            var etiquetasVentas = @json($expense_label);
            var ganancias = @json($amount_label);
            const ctxGastos= document.getElementById('myChart');

            const coloresAleatorios = Array.from({ length: etiquetasVentas.length }, () => getRandomColor());

            new Chart(ctxGastos, {
                type: 'doughnut',
                data: {
                    labels: etiquetasVentas,
                    datasets: [{
                        label: '# de total',
                        backgroundColor: coloresAleatorios,
                        data: ganancias,
                        borderColor: 'rgb(255, 99, 132)',
                    }]
                },
                options: {}
            });
        </script>
    @endif
@endsection
