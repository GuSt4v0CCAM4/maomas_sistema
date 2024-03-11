@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 100%;
            height: auto;
        }

        .btn-menu {
            width: 100%;
            margin-bottom: 10px;
            font-size: 18px;
            border-radius: 10px;
        }
    </style>
<div class="container">
    <div id="bienvenido">
        Bienvenid@ {{ Auth::user()->name }}
    </div>
    <div class="row justify-content-center">
        <div class="logo-container">
            <img src="{{ asset('images/maomas.png') }}" alt="Logo" style="height: 220px" class="logo">
        </div>

        <!-- Menú de Botones -->
        <div class="btn-group-vertical">
            <button type="button" class="btn btn-primary btn-menu" onclick=" window.location.href = '{{ url('/cashregister') }}'">Registrar Caja</button>
            <button type="button" class="btn btn-primary btn-menu" onclick=" window.location.href = '{{ url('/detallevendedores') }}'">Reporte General</button>
            <button type="button" class="btn btn-primary btn-menu" onclick=" window.location.href = '{{ url('/reportetienda') }}'">Reporte por Tienda</button>
            <button type="button" class="btn btn-primary btn-menu" onclick=" window.location.href = '{{ url('/reportegastos') }}'">Informe de Gastos</button>
            <button type="button" class="btn btn-primary btn-menu" onclick=" window.location.href = '{{ url('/cashreport') }}'">Informe de Ingresos</button>
        </div>
    </div>
</div>
@endsection
