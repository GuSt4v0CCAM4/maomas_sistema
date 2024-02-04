@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="subtittle mb-3">
            Lista de Cierres de Caja
        </div>
        <div class="mb-4">
        <form method="GET" action="{{route('cashconsult')}}">
            <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating">
                        <select class="form-select" id="store" name="store">
                            <option selected disabled value="0">--Seleccione una Tienda--</option>
                            <option value="1" {{ $selectedStore == '1' ? 'selected' : '' }}>San Camilo</option>
                            <option value="2" {{ $selectedStore == '2' ? 'selected' : '' }}>Maternos</option>
                            <option value="3" {{ $selectedStore == '3' ? 'selected' : '' }}>Maomas</option>
                            <option value="4" {{ $selectedStore == '4' ? 'selected' : '' }}>Camana</option>
                        </select>
                        <label for="floatingSelectGrid">Tienda:</label>
                    </div>
                </div>
            </div>

        </form>
        </div>
        @php
            $total_profit =0;
            @endphp
        @if(isset($consult))
        @foreach($consult as $c)
            @php
                $total_profit += $c->profit;
            @endphp
        @endforeach
        @endif
        <div class="cardGanancias">
            <button class="buttonGanacias">
                <i class="bi bi-cash-coin" style="font-size: 2rem;"></i>
            </button>
            <label class="textGanancias">
                <span class="spanGanancias"> Ganancias Totales</span> <br>
                S/.{{$total_profit}}
            </label>
        </div>
        <table class="table table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ventas:</th>
                    <th scope="col">Gastos:</th>
                    <th scope="col">Ganancias:</th>
                    <th scope="col">Balance:</th>
                    <th scope="col">Fecha (Y-M-D):</th>
                    <th scope="col">Vendedor:</th>
                    <th></th>
                </tr>

            </thead>
            <tbody>

                    @if(isset($consult))
                        @php
                            $i = 0;;
                        @endphp
                        @foreach($consult as $c)
                            @php
                                $i++;;
                                $total_profit += $c->profit;
                            @endphp
                                <tr>
                                <td>{{$i}}</td>
                                    <td>S/. {{$c->profit}}</td>
                                <td>S/. {{$c->expense}}</td>
                                    <td>S/. {{$c->sale}}</td>

                                <td class="{{($c->balance > 0) ? 'text-danger' : 'text-success'}}">{{abs($c->balance)}}</td>
                                <td>{{$c->date}}</td>
                                <td>{{$c->name}}</td>
                                    <td>
                                        <button type="submit" onclick="location.href='{{route('editprofit',['id' => $c->id_reg])}}'"
                                                class="btn btn-primary" style="padding: 0.1rem 0.2rem; font-size: 0.8rem;" >
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#modal{{$c->id_reg}}"
                                                class="btn btn-danger" style="padding: 0.1rem 0.2rem; font-size: 0.8rem;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </td>
                                </tr>
                        @endforeach
                    @endif

            </tbody>
        </table>
        @if(isset($consult))
            @foreach($consult as $c)
                <div class="modal fade" id="modal{{$c->id_reg}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar esta venta?</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Esta seguro que quiere eliminar el registro de esta venta? No se podra recuperar.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a type="button" class="btn btn-danger" href="{{route('profitdelete', ['id' => $c->id_reg])}}">Si, ELIMINAR</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <script>

        document.getElementById('store').addEventListener('change', function() {
            this.form.submit();
        });


    </script>
@endsection
