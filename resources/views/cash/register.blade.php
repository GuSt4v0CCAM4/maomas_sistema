@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="subtittle mb-3">
            Registro de Caja
        </div>
            <form id="cash-register-form" method="GET" action="{{ route('cashregister') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input class="form-control" type="date" name="date" id="date"
                                   value="{{ $Date }}" aria-label="Floating label select example">
                            <label for="store">Fecha:</label>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" id="user" name="user">
                                <option selected disabled value="0">--Seleccione un Usuario--</option>
                                @if(isset($users) && $users->count() > 0)
                                    @foreach($users as $u)
                                        <option value="{{$u->id}}" {{ $selectedUser == $u->id ? 'selected' : '' }}>{{$u->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="floatingSelectGrid">Trabajador:</label>
                        </div>
                    </div>
                </div>
            </form>
        @php
        $cieerdetienda = 0;
        @endphp
        @if(isset($close_store) && $close_store->count() > 0)
            @php
            $observation = $close_store->first()->observation;
            $palabras = explode(' ', $observation);
            $primerapalabra = $palabras[0];
            if ($primerapalabra == 'CIERRE'){
                $cieerdetienda = 1;
            }
            @endphp
        @endif
            @if($cieerdetienda == 1)
            <h2 class="mt-3">NO SE ABRIO LA TIENDA</h2>
            <h3>{{$close_store->first()->observation}}</h3>
            <button type="button" class="btn btn-danger" onclick="location.href='{{route('closestoredelete',['id'=>$close_store->first()->id_profit])}}'"  >Eliminar</button>
        @endif
        @if($cieerdetienda == 0)
            @if(isset($warning))
                <div class="warning_m mt-3">
                    <i class="bi bi-patch-exclamation-fill"></i>  {{$warning}}
                </div>
            @endif
            <div class="mt-3">
                @if(isset($existe)) <!-- Si existe la venta -->
                    <label class="text_form">
                        Monto Registrado: S/. {{$valor_total->first()->amount}}

                        @php
                        $id_valor = $valor_total->first()->id_reg;
                        $venta = $valor_total->first()->amount;
                        @endphp
                        <button type="submit" onclick="location.href='{{route('editcash',['id' => $id_valor])}}'" class="btn" ><i class="bi bi-pencil"></i></button>
                    </label>
                @endif
                @if(isset($noexiste)) <!-- No xiste la venta
style="background-color: #f0f0f0;"-->

                        <form method="POST" class="mb-2" action="{{route('registercash')}}">
                            @csrf
                            <label class="text_form mb-2" for="venta_total">
                                Registro de la Venta Total
                            </label>
                            <div class="row">
                                <div class="col-md-6" >
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                                        <input type="number" step="any" min="1"  pattern="^[0-9]" class="input_maoma" id="venta_total"
                                               name="sale" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalCloseStore" class="btn btn-danger" >TIENDA CERRADA</button>
                                </div>
                            </div>
                        </form>
                    <div class="modal fade" id="modalCloseStore" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Motivo del cierre de la tienda:</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="form_obervation" method="POST" action="{{ route('closestore') }}">
                                        @csrf
                                        <label for="exampleFormControlTextarea1" class="form-label">Escriba aqui el motivo:</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" name="observation" rows="3" required></textarea>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-warning">GUARDAR</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                @endif

                    <div class="container ">
                        <div class="row">
                            <div class="col-md-6" style="background-color: #cdb4db; border-radius: 15px; padding: 20px;">
                                <form method="POST"  action="{{ route('registerpayment') }}">
                                    @csrf
                                    <h4 class="text-center mb-2 mt-2">Registro de Pago</h4>
                                    <div class="row g-2">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom04" class="label">Medio:</label>
                                            <select class="form-select" id="validationCustom04" name="payment" required>
                                                <option selected disabled value="">Elije una opción</option>
                                                <option value="1">Efectivo</option>
                                                <option value="2">Transferencia</option>
                                                <option value="3">Yape</option>
                                                <option value="4">Plin</option>
                                                <option value="5">Tarjeta Visa</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Elije un medio de pago
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustomUsername" class="label">Monto</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                                                <input type="number" step="any" min="1" pattern="^[0-9]" class="form-control" id="validationCustomUsername"
                                                       name="amount" aria-describedby="inputGroupPrepend" required>
                                                <div class="invalid-feedback">
                                                    Escriba el Monto
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6" style="background-color: #a2d2ff; border-radius: 15px; padding: 20px; ">
                                <form method="POST" action="{{ route('registerexpense') }}">
                                    @csrf
                                    <h4 class="text-center mb-2 mt-2">Registro de Gasto</h4>
                                    <div class="row g-2">
                                        <div class="col-md-6 mb-3">
                                            <label for="cash" class="label">Tipo:</label>
                                            <select class="form-select" id="cash" name="expense" required onchange="showHideOtherInput(this)">
                                                <option selected disabled value="0">--Seleccione una opción--</option>
                                                <optgroup label="Gastos Operativos">
                                                    <option value="1">Luz/Agua/Telefono</option>
                                                    <option value="2">Alquileres</option>
                                                    <option value="3">Transporte</option>
                                                    <option value="4">Limpieza</option>
                                                    <option value="5">Devoluciones</option>
                                                    <option value="6">Proveedores</option>
                                                    <option value="99">Otros</option>
                                                    <!-- Agrega más subtipos si es necesario -->
                                                </optgroup>
                                                <optgroup label="Gastos Adminsitrativos">
                                                    <option value="101">Banco</option>
                                                    <option value="199">Otros</option>
                                                    <!-- Agrega más subtipos si es necesario -->
                                                </optgroup>
                                                <optgroup label="Gastos Personal">
                                                    @foreach($users as $user)
                                                        <option value="@if($user->id < 10) 30{{$user->id}} @endif @if($user->id > 9 ) 3{{$user->id}} @endif" >{{$user->name}}</option>
                                                    @endforeach
                                                    <!-- Agrega más subtipos si es necesario -->
                                                </optgroup>
                                            </select>
                                            <div class="invalid-feedback">
                                                Elije un medio de pago
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustomUsername" class="label">Monto</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                                                <input type="number" step="any" min="1" pattern="^[0-9]" class="form-control" id="validationCustomUsername"
                                                       name="amount" aria-describedby="inputGroupPrepend" required>
                                                <div class="invalid-feedback">
                                                    Escriba el Monto
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3" id="otherInputContainer" style="display: none;">
                                            <label for="otherInput" class="label">Otros Detalles:</label>
                                            <input type="text" class="form-control" id="otherInput" name="otherInput">
                                        </div>
                                        <button type="submit" class="btn btn-primary" style="display: none;">Enviar</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


            </div>
            <div class=" mt-4">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Tipo:</th>
                        <th scope="col">Monto: </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                    $total_payment = 0;
                    $total_expense = 0;
                    $total_personal = 0;
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
                                $expense = 'Gastos Operativos - Luz/Agua/Telefono';
                            } elseif ($e->expense_type == 2){
                                $expense = 'Gastos Operativos - Alquileres';
                            } elseif ($e->expense_type == 3){
                                $expense = 'Gastos Operativos - Transporte';
                            } elseif ($e->expense_type == 4){
                                $expense = 'Gastos Operativos - Limpieza';
                            } elseif ($e->expense_type == 5){
                                $expense = 'Gastos Operativos - Devoluciones';
                            } elseif ($e->expense_type == 6){
                                $expense = 'Gastos Operativos - Proveedores';
                            } elseif ($e->expense_type == 99){
                                $expense = 'Gastos Operativos - Otros';
                            } elseif ($e->expense_type == 101){
                                $expense = 'Gastos Administrativos - Banco';
                            } elseif ($e->expense_type == 199){
                                $expense = 'Gastos Administrativos - Otros';
                            }
                            @endphp
                            <tr>
                                <td>{{$expense}}</td>
                                <td>S/. {{$e->amount}}</td>
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
                    @if(isset($table_personal))
                        @foreach($table_personal as $p)
                            @php
                                $total_personal += $p->amount;
                            @endphp
                            <tr>
                                <td>Gasto Personal - {{$p->name}}</td>
                                <td>S/. {{$p->amount}}</td>
                                <td>
                                    <button type="submit" onclick="location.href='{{route('editpersonal',['id' => $p->id_cash])}}'"
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
                    @if(isset($table_expense, $table_payment, $table_personal))
                        @php
                            $totalPrice = $total_payment + $total_expense + $total_personal;
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
                @if(isset($table_personal))
                    @foreach($table_personal as $p)
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
                                        <a type="button" class="btn btn-danger" href="{{route('personaldelete', ['id' => $p->id_cash])}}">Si, ELIMINAR</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            @if(isset($table_expense, $table_payment, $venta, $table_personal))
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
                                    <br><small>-Ya registrado-</small> <button type="button" data-bs-toggle="modal" data-bs-target="#modald{{$cierre_caja->first()->id_reg}}"
                                                                               class="btn btn-danger" style="padding: 0.1rem 0.2rem; font-size: 0.8rem;">
                                        Eliminar
                                    </button>
                                    @if(isset($close_store) && $close_store->count() > 0)
                                        <h5>Ya se anoto una observacion en este dia</h5>
                                    @else
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalObservation" class="btn btn-warning" >OBSERVACION</button>
                                    @endif
                            @endif
                        @endif
                    </div>


                </div>
            <div class="modal fade" id="modalObservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Escribir la Observacion de este dia</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form_obervation" method="POST" action="{{ route('registerobservation') }}">
                                @csrf
                                <label for="exampleFormControlTextarea1" class="form-label">Escriba aqui la observación:</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" name="observation" rows="3" required></textarea>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-warning">GUARDAR</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        @if(isset($cierre_caja) && $cierre_caja->count() > 0)

                <div class="modal fade" id="modald{{$cierre_caja->first()->id_reg}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar el cierre de caja</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Esta seguro que quiere eliminar el cierre de caja de este dia? No se podra recuperar.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a type="button" class="btn btn-danger" href="{{route('deleteprofit', ['id' => $cierre_caja->first()->id_reg])}}">Si, ELIMINAR</a>
                            </div>
                        </div>
                    </div>
                </div>
                    @endif

        @endif
                <div>
                    @if(session('success'))
                        <div class="col-md-4 alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
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
                document.getElementById('user').addEventListener('change', function (){
                    this.form.submit();
                })
            </script>
        <script>
            function showHideOtherInput(selectElement) {
                var otherInputContainer = document.getElementById('otherInputContainer');
                var otherInput = document.getElementById('otherInput');

                if (selectElement.value === '99' || selectElement.value === '199') {
                    otherInputContainer.style.display = 'block';
                    otherInput.required = true;
                } else {
                    otherInputContainer.style.display = 'none';
                    otherInput.required = false;
                }
            }
        </script>
@endsection
