@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="subtittle mb-3">
            Editar Medio de Pago del {{$datos->first()->date}} (Año-mes-dia)
        </div>
        <form method="POST" action="{{route('updateexpense', ['id' => $datos->first()->id_cash])}}">
            @csrf
            <div class="col-md-5 mb-3">
                <label for="validationCustom04" class="label">Medio:</label>
                <select class="form-select" id="validationCustom04" name="expense" required>
                    <option disabled value="0">Elije una opción</option>
                    <option value="1" {{ $datos->first()->expense_type == '1' ? 'selected' : '' }}> Gastos Operativos</option>
                    <option value="2" {{ $datos->first()->expense_type == '2' ? 'selected' : '' }}> Gastos de Personal</option>
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
                           name="amount" aria-describedby="inputGroupPrepend" value="{{$datos->first()->amount}}" required>
                    <div class="invalid-feedback">
                        Escriba el Monto
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-3">
                <label for="validationCustom04" class="label">Trabajador:</label>
                <select class="form-select" id="validationCustom04" name="seller" required>
                    <option disabled value="0">Elije una opción</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{ $user->id == $datos->first()->id_user ? 'selected' : '' }}>{{$user->name}}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Elije un medio de pago
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success"><i class="bi bi-save2-fill"></i></button>
            </div>
        </form>
@endsection
