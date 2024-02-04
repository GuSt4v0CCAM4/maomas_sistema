@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="subtittle mb-3">
            Registro de Caja
        </div>
        <div class="row">
            <form method="GET" action="{{ route('cashregister') }}">
                @csrf
                <div class="row g-2">
                    <div class="col-md">
                        <div class="form-floating">
                            <input class="form-control" type="date" name="date" id="date"
                                   value="{{ $Date }}" aria-label="Floating label select example">
                            <label for="store">Fecha:</label>
                        </div>
                    </div>
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
            @if(isset($warning))
            <div class="warning_m mt-3">
                <i class="bi bi-patch-exclamation-fill"></i>  {{$warning}}
            </div>
            @endif
            <div class="col-md-6 mt-5">
                @if(isset($existe))
                    <label class="text_form">
                        Monto Registrado: S/. {{$valor_total->first()->amount}}
                        @php
                        $id_valor = $valor_total->first()->id_reg;
                        $venta = $valor_total->first()->amount;
                        @endphp
                        <button type="submit" onclick="location.href='{{route('editcash',['id' => $id_valor])}}'" class="btn" ><i class="bi bi-pencil"></i></button>
                    </label>
                @endif
                @if(isset($noexiste))
                        <form method="POST" action="{{route('registercash')}}">
                            @csrf
                            <label class="text_form mb-2" for="venta_total">
                                Registro de la Venta Total
                            </label>
                            <div class="input-group has-validation">
                                <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                                <input type="number" step="any" min="1"  pattern="^[0-9]" class="input_maoma" id="venta_total"
                                       name="sale" required>
                                <button type="submit" class="btn btn-success"><i class="bi bi-save2-fill"></i></button>

                            </div>
                        </form>

                @endif
                    <div class="text_form mt-3 mb-3">
                        Registrar Detalles de la Caja
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item" >
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"
                                        style="background-color: #f0f0f0;">
                                   <label class="label">Medios de Pago</label>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample" >
                                <div class="accordion-body">
                                    <form method="POST" action="{{route('registerpayment')}}">
                                        @csrf
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom04" class="label">Medio:</label>
                                            <select class="form-select" id="validationCustom04" name="payment" required>
                                                <option selected disabled value="">Elije una opción</option>
                                                <option value="1"> Efectivo</option>
                                                <option value="2"> Transferencia</option>
                                                <option value="3"> Yape</option>
                                                <option value="4"> Plin</option>
                                                <option value="5"> Tarjeta Visa</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Elije un medio de pago
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustomUsername" class="label">Monto</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                                                <input type="number" step="any" min="1"  pattern="^[0-9]" class="form-control" id="validationCustomUsername"
                                                       name="amount" aria-describedby="inputGroupPrepend" required>
                                                <div class="invalid-feedback">
                                                    Escriba el Monto
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success"><i class="bi bi-save2-fill"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"
                                        style="background-color: #f0f0f0;">
                                    <label class="label">Gastos</label>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form method="POST" action="{{route('registerexpense')}}">
                                        @csrf

                                        <div class="col md-5 mb-3">
                                            <label for="cash" class="label">Tipo:</label>
                                            <select class="form-select" id="cash" name="expense" required>
                                                <option selected disabled value="0">--Seleccione una opción--</option>
                                                <option value="1" >Gastos Operativos</option>
                                                <option value="2" >Gastos de Personal</option>
                                                <option value="Otro" >Otro</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Elije un medio de pago
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustomUsername" class="label">Monto</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                                                <input type="number" step="any" min="1"  pattern="^[0-9]" class="form-control" id="validationCustomUsername"
                                                       name="amount" aria-describedby="inputGroupPrepend" required>
                                                <div class="invalid-feedback">
                                                    Escriba el Monto
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success"><i class="bi bi-save2-fill"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="col-md-6 mt-5">
                <div class="subtittle mb-3">
                    Detalles de la Caja:
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Tipo:</th>
                        <th scope="col">Monto: </th>
                        <th scope="col">Trabajador:</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                    $total_payment = 0;
                    $total_expense = 0;
                    @endphp
                    @if(isset($table_payment))
                        @foreach($table_payment as $p)
                            @php
                                $total_payment += $p->amount;
                                $medio = "";
                            if ($p->payment_type == 1) {
                                $medio = 'Efectivo';
                            } elseif ($p->payment_type == 2) {
                                $medio = 'Transferencia';
                            } elseif ($p->payment_type == 3) {
                                $medio = 'Yape';
                            } elseif ($p->payment_type == 4) {
                                $medio = 'Plin';
                            } elseif ($p->payment_type == 5) {
                                $medio = 'Tarjeta Visa';
                            } else {
                                $medio = 'Otro';
                            }
                            @endphp
                            <tr>
                                <td>{{$medio}}</td>
                                <td>S/. {{$p->amount}}</td>
                                <td>
                                    {{$p->name}}

                                </td>
                                <td>
                                    <button type="submit" onclick="location.href='{{route('editpayment',['id' => $p->id_cash])}}'"
                                            class="btn btn-primary" style="padding: 0.1rem 0.2rem; font-size: 0.8rem;" >
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal{{$p->id_cash}}"
                                            class="btn btn-danger" style="padding: 0.1rem 0.2rem; font-size: 0.8rem;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if(isset($table_expense))
                        @foreach($table_expense as $e)
                            @php
                                $total_expense += $e->amount;
                                $expense = "";
                            if ($e->expense_type == 1) {
                                $expense = 'Gastos Operativos';
                            } elseif ($e->expense_type == 2){
                                $expense = 'Gastos Personal';
                            }
                            @endphp
                            <tr>
                                <td>{{$expense}}</td>
                                <td>S/. {{$e->amount}}</td>
                                <td>{{$e->name}}</td>
                                <td>
                                    <button type="submit" onclick="location.href='{{route('editexpense',['id' => $e->id_cash])}}'"
                                            class="btn btn-primary" style="padding: 0.1rem 0.2rem; font-size: 0.8rem;" >
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal{{$e->id_cash}}"
                                            class="btn btn-danger" style="padding: 0.1rem 0.2rem; font-size: 0.8rem;">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if(isset($table_expense, $table_payment))
                        @php
                            $totalPrice = $total_payment + $total_expense;
                        @endphp
                        <tr>
                            <td colspan="1"><strong>TOTAL:</strong></td>
                            <td><strong>S/. {{ $totalPrice }}</strong></td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                @if(isset($table_payment))
                    @foreach($table_payment as $p)
                        <div class="modal fade" id="modal{{$p->id_cash}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <a type="button" class="btn btn-danger" href="{{route('paymentdelete', ['id' => $p->id_cash])}}">Si, ELIMINAR</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if(isset($table_expense))
                    @foreach($table_expense as $e)
                        <div class="modal fade" id="modal{{$e->id_cash}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <a type="button" class="btn btn-danger" href="{{route('expensedelete', ['id' => $e->id_cash])}}">Si, ELIMINAR</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            @if(isset($table_expense, $table_payment, $venta))
                <div class="cardCaja mb-3">
                    <div class="subtittle_t mt-4">
                        @php
                            $diferencia = $venta - $totalPrice;
                        @endphp
                        @if($diferencia > 0)
                            Hay falta de S/. {{ $diferencia }}
                        @endif
                        @if($diferencia < 0)
                            Hay sobrante de S/. {{ abs($diferencia) }}
                        @endif
                        @if($diferencia == 0)
                            Cuadre Perfectamente
                        @endif
                        @if(isset($cierre))

                            @if($cierre == 0)
                                <button type="button" class="btn"
                                        onclick="window.location.href='{{route('profitregister',
                                            ['balance' => $diferencia, 'date'=>$Date, 'store' => $selectedStore,
                                            'profit'=>$venta])}}'">
                                    <i class="bi bi-save2-fill"></i>
                                </button>
                            @endif
                            @if($cierre == 1)
                                    <br><small>-Ya registrado-</small>
                            @endif
                        @endif
                    </div>
                </div>
                <div>
                    @if(session('success'))
                        <div class="col-md-4 alert alert-success">
                            {{ session('success_s') }}
                        </div>
                    @endif
                    @if(session('error_s'))
                        <div class="col-md-4 alert alert-success">
                            {{ session('error') }}
                        </div>
                    @endif

                </div>
            @endif
            <script>
                document.getElementById('date').addEventListener('change', function() {
                    this.form.submit();
                })
                document.getElementById('store').addEventListener('change', function() {
                    this.form.submit();
                })
            </script>
@endsection
