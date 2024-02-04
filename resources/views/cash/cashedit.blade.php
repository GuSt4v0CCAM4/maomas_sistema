@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="subtittle mb-3">
            Editar Monto de Venta del {{$datos->first()->date}} (Año-mes-dia)
        </div>
        <form method="POST" action="{{route('updatesale',['id' => $datos->first()->id_reg])}}">
            @csrf
            <br>
            <div class="input-group has-validation">
                <span class="input-group-text" id="inputGroupPrepend">S/.</span>
                <input value="{{$datos->first()->amount}}" type="number" step="any" min="1"  pattern="^[0-9]" class="input_maoma" id="venta_total"
                       name="sale" required>
                <button type="submit" class="btn btn-success"><i class="bi bi-save2-fill"></i></button>

            </div>
        </form>
@endsection
