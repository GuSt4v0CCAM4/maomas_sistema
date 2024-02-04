@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="subtittle mb-3">
            Editar Vendedor del Cierre del dia {{$datos->first()->date}} (Año-mes-dia)
        </div>
        <form method="POST" action="{{route('updateprofit', ['id' => $datos->first()->id_reg])}}">
            @csrf

            <div class="col-md-5 mb-3">
                <label for="validationCustom04" class="label">Trabajador:</label>
                <select class="form-select" id="validationCustom04" name="seller" required>
                    <option disabled value="0">Elije una opción</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{ $user->id == $datos->first()->id_user ? 'selected' : '' }}>{{$user->name}}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Elije un trabajador
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success"><i class="bi bi-save2-fill"></i></button>
            </div>
        </form>
@endsection
