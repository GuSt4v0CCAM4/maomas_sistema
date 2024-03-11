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
        // ---- FUNCIONES DATE
        $matriz_datos = [];
        $matriz_profit = [];
        $matriz_store = [];
        $fin = 0;
        $inicio = 0;
        $users = DB::table('users')->select()->get();
        if (isset($request->inicioFecha) && isset($request->finFecha)) {
            $fin = $request->finFecha;
            $inicio = $request->inicioFecha;
        }
        else{
            if ($selectedDate == '0') {

                return view('report.sellerdetails', ['selectedDate' => $selectedDate, 'users' => $users]);
            } else {
                if ($selectedDate == '2') {
                    //primer dia de la semana
                    $inicio = date('Y-m-d', strtotime('-' . $currentDayWeek . ' days', strtotime($currentDate)));
                    //ultimo dia de la semana
                    $fin = date('Y-m-d', strtotime('+' . (7 - $currentDayWeek) . ' days', strtotime($currentDate)));

                } else if ($selectedDate == '3') {
                    $inicio = date('Y-m-01');
                    $fin = date('Y-m-t');

                }

            }
        }
        $ranking = DB::table('users')
            ->join('profits', 'users.id', '=', 'profits.id_user')
            ->select('users.*', 'profits.profit', 'profits.balance', DB::raw('SUM(profits.sale) as sale, SUM(profits.expense) as expense, SUM(profits.balance) as balance'))
            ->whereBetween('profits.date', [$inicio, $fin])
            ->groupBy('users.id')
            ->get();
        $data_profit = DB::table('profits')
            ->join('users', 'users.id', '=', 'profits.id_user')
            ->select('users.id', 'users.name',
                DB::raw('SUM(profits.sale) as sale'),
                DB::raw('SUM(profits.expense) as expense'),
                'profits.profit', 'profits.date', 'profits.id_store')
            ->whereBetween('profits.date', [$inicio, $fin])
            ->groupBy('profits.id_user', 'profits.date')
            ->orderBy('profits.date', 'asc')
            ->get();
        $fechas_unicas = $data_profit->pluck('date')->unique();
        foreach ($data_profit as  $value) {

            $id_user = $value->id;
            $id_store = $value->id_store;
            $date = $value->date;
            $total_ventas = $value->sale + $value->expense;

            $matriz_profit[$id_user]['datos'][$date] = [
                'name' => $value->name,
                'date' => $value->date,
                'profit' => $total_ventas,
                'store' => $value->id_store,
            ];
            $matriz_store[$id_store]['datos'][$date] = [
                'name' => $value->name,
                'date' => $value->date,
                'profit' => $total_ventas,
            ];


            if (!isset($matriz_datos[$id_user]['datos'][$date])) {
                $matriz_datos[$id_user]['datos'][$date] = [
                    'name' => $value->name,
                    'date' => $date,
                    'profit' => 0, // Inicializar con 0
                    'store' => 0,
                ];
            }

            // Actualizar el valor de profit para ese id_user y fecha

            $matriz_datos[$id_user]['datos'][$date]['profit'] = $total_ventas;
            $matriz_datos[$id_user]['datos'][$date]['store'] = $value->id_store;
        }
        foreach ($matriz_datos as &$usuario) {
            $usuario['datos'] = $usuario['datos'] + $fechas_unicas->mapWithKeys(function ($fecha) {
                    return [$fecha => ['name' => '', 'date' => $fecha, 'profit' => 0, 'store' => 0]];
                })->all();
        }
        foreach ($matriz_datos as &$usuario) {
            ksort($usuario['datos']);
        }
        return view('report.sellerdetails', ['selectedDate'=>$selectedDate, 'users'=>$users, 'matriz_datos' => $matriz_datos,
            'ranking' => $ranking, 'matriz_profit'=>$matriz_profit, 'matriz_store'=>$matriz_store, 'fin' => $fin, 'inicio' => $inicio]);

    }
}
