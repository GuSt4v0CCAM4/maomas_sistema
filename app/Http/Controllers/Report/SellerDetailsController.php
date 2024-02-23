<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class SellerDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Cookie::get('selectedDate', '0'));
        Cookie::queue('selectedDate', $selectedDate, 1440);
        // ---- FUNCIONES DATE
        $currentDate = date('Y-m-d');
        $currentDayWeek = date('N', strtotime($currentDate));
        //primer dia de la semana
        $firstDayWeek = date('Y-m-d', strtotime('-' . $currentDayWeek . ' days', strtotime($currentDate)));
        //ultimo dia de la semana
        $lastDayWeek = date('Y-m-d', strtotime('+' . (7 - $currentDayWeek) . ' days', strtotime($currentDate)));
        $firstDayMonth = date('Y-m-01');
        $lastDayMont = date('Y-m-t');
        // ---- FUNCIONES DATE
        $users = DB::table('users')->select()->get();
        if ($selectedDate == '0') {

            return view('report.sellerdetails', ['selectedDate'=>$selectedDate, 'users'=>$users]);
        }else{
            if ($selectedDate == '1'){
                $data_profit = DB::table('profits')
                    ->join('users', 'users.id', '=', 'profits.id_user')
                    ->select('users.id', 'users.name',
                        DB::raw('SUM(profits.sale) as sale'),
                        DB::raw('SUM(profits.expense) as expense'),
                        'profits.profit', 'profits.date', 'profits.id_store')                    ->whereDate('profits.date',  $currentDate)
                    ->groupBy('profits.id_user', 'profits.date')
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $matriz_datos = [];
                $fechas_unicas = $data_profit->pluck('date')->unique();
                foreach ($data_profit as  $value) {
                    $id_user = $value->id;
                    $date = $value->date;

                    if (!isset($matriz_datos[$id_user]['datos'][$date])) {
                        $matriz_datos[$id_user]['datos'][$date] = [
                            'name' => $value->name,
                            'date' => $date,
                            'profit' => 0, // Inicializar con 0
                            'store' => 0,
                        ];
                    }

                    // Actualizar el valor de profit para ese id_user y fecha
                    $total_ventas = $value->sale + $value->expense;
                    $matriz_datos[$id_user]['datos'][$date]['profit'] += $total_ventas;
                    $matriz_datos[$id_user]['datos'][$date]['store'] = $value->id_store;
                }
                foreach ($matriz_datos as &$usuario) {
                    $usuario['datos'] = $usuario['datos'] + $fechas_unicas->mapWithKeys(function ($fecha) {
                            return [$fecha => ['name' => '', 'date' => $fecha, 'profit' => 0, 'store' => 0]];
                        })->all();
                }
            } else if ($selectedDate == '2'){
                $data_profit = DB::table('profits')
                    ->join('users', 'users.id', '=', 'profits.id_user')
                    ->select('users.id', 'users.name',
                        DB::raw('SUM(profits.sale) as sale'),
                        DB::raw('SUM(profits.expense) as expense'),
                        'profits.profit', 'profits.date', 'profits.id_store')                    ->whereBetween('profits.date', [$firstDayWeek, $lastDayWeek])
                    ->groupBy('profits.id_user', 'profits.date')
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $matriz_datos = [];
                $fechas_unicas = $data_profit->pluck('date')->unique();
                foreach ($data_profit as  $value) {
                    $id_user = $value->id;
                    $date = $value->date;

                    if (!isset($matriz_datos[$id_user]['datos'][$date])) {
                        $matriz_datos[$id_user]['datos'][$date] = [
                            'name' => $value->name,
                            'date' => $date,
                            'profit' => 0, // Inicializar con 0
                            'store' => 0,
                        ];
                    }

                    // Actualizar el valor de profit para ese id_user y fecha
                    $total_ventas = $value->sale + $value->expense;
                    $matriz_datos[$id_user]['datos'][$date]['profit'] += $total_ventas;
                    $matriz_datos[$id_user]['datos'][$date]['store'] = $value->id_store;
                }
                foreach ($matriz_datos as &$usuario) {
                    $usuario['datos'] = $usuario['datos'] + $fechas_unicas->mapWithKeys(function ($fecha) {
                            return [$fecha => ['name' => '', 'date' => $fecha, 'profit' => 0, 'store' => 0]];
                        })->all();
                }

            } else if ($selectedDate == '3'){
                $data_profit = DB::table('profits')
                    ->join('users', 'users.id', '=', 'profits.id_user')
                    ->select('users.id', 'users.name',
                        DB::raw('SUM(profits.sale) as sale'),
                        DB::raw('SUM(profits.expense) as expense'),
                        'profits.profit', 'profits.date', 'profits.id_store')
                    ->whereBetween('profits.date', [$firstDayMonth, $lastDayMont])
                    ->groupBy('profits.id_user', 'profits.date')
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $matriz_datos = [];
                $fechas_unicas = $data_profit->pluck('date')->unique();
                foreach ($data_profit as  $value) {
                    $id_user = $value->id;
                    $date = $value->date;

                    if (!isset($matriz_datos[$id_user]['datos'][$date])) {
                        $matriz_datos[$id_user]['datos'][$date] = [
                            'name' => $value->name,
                            'date' => $date,
                            'profit' => 0, // Inicializar con 0
                            'store' => 0,
                        ];
                    }

                    // Actualizar el valor de profit para ese id_user y fecha
                    $total_ventas = $value->sale + $value->expense;
                    $matriz_datos[$id_user]['datos'][$date]['profit'] += $total_ventas;
                    $matriz_datos[$id_user]['datos'][$date]['store'] = $value->id_store;
                }
                foreach ($matriz_datos as &$usuario) {
                    $usuario['datos'] = $usuario['datos'] + $fechas_unicas->mapWithKeys(function ($fecha) {
                            return [$fecha => ['name' => '', 'date' => $fecha, 'profit' => 0, 'store' => 0]];
                        })->all();
                }

            } else if ($request->date == '4') {
                if (@isset($request->inicioFecha) && isset($request->finFecha)) {
                    $startDate = $request->inicioFecha;
                    $endDate = $request->finFecha;
                    $data_profit = DB::table('profits')
                        ->join('users', 'users.id', '=', 'profits.id_user')
                        ->select('users.id', 'users.name',
                            DB::raw('SUM(profits.sale) as sale'),
                            DB::raw('SUM(profits.expense) as expense'),
                            'profits.profit', 'profits.date', 'profits.id_store')
                        ->whereBetween('profits.date', [$startDate, $endDate])
                        ->groupBy('profits.id_user', 'profits.date')
                        ->orderBy('profits.date', 'asc')
                        ->get();
                    $matriz_datos = [];
                    $fechas_unicas = $data_profit->pluck('date')->unique();
                    foreach ($data_profit as  $value) {
                        $id_user = $value->id;
                        $date = $value->date;

                        if (!isset($matriz_datos[$id_user]['datos'][$date])) {
                            $matriz_datos[$id_user]['datos'][$date] = [
                                'name' => $value->name,
                                'date' => $date,
                                'profit' => 0, // Inicializar con 0
                                'store' => 0,
                            ];
                        }

                        // Actualizar el valor de profit para ese id_user y fecha
                        $total_ventas = $value->sale + $value->expense;
                        $matriz_datos[$id_user]['datos'][$date]['profit'] += $total_ventas;
                        $matriz_datos[$id_user]['datos'][$date]['store'] = $value->id_store;
                    }
                    foreach ($matriz_datos as &$usuario) {
                        $usuario['datos'] = $usuario['datos'] + $fechas_unicas->mapWithKeys(function ($fecha) {
                                return [$fecha => ['name' => '', 'date' => $fecha, 'profit' => 0, 'store' => 0]];
                            })->all();
                    }
                }
            } else {
                $data_profit = DB::table('profits')
                    ->join('users', 'users.id', '=', 'profits.id_user')
                    ->select('users.id', 'users.name',
                        DB::raw('SUM(profits.sale) as sale'),
                        DB::raw('SUM(profits.expense) as expense'),
                        'profits.profit', 'profits.date', 'profits.id_store')
                    ->groupBy('profits.id_user', 'profits.date')
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $matriz_datos = [];
                $fechas_unicas = $data_profit->pluck('date')->unique();
                foreach ($data_profit as  $value) {
                    $id_user = $value->id;
                    $date = $value->date;

                    if (!isset($matriz_datos[$id_user]['datos'][$date])) {
                        $matriz_datos[$id_user]['datos'][$date] = [
                            'name' => $value->name,
                            'date' => $date,
                            'profit' => 0, // Inicializar con 0
                            'store' => 0,
                        ];
                    }

                    // Actualizar el valor de profit para ese id_user y fecha
                    $total_ventas = $value->sale + $value->expense;
                    $matriz_datos[$id_user]['datos'][$date]['profit'] += $total_ventas;
                    $matriz_datos[$id_user]['datos'][$date]['store'] = $value->id_store;
                }
                foreach ($matriz_datos as &$usuario) {
                    $usuario['datos'] = $usuario['datos'] + $fechas_unicas->mapWithKeys(function ($fecha) {
                            return [$fecha => ['name' => '', 'date' => $fecha, 'profit' => 0, 'store' => 0]];
                        })->all();
                }
            }
            foreach ($matriz_datos as &$usuario) {
                ksort($usuario['datos']);
            }
            return view('report.sellerdetails', ['selectedDate'=>$selectedDate, 'users'=>$users, 'matriz_datos' => $matriz_datos]);
        }

    }
}
