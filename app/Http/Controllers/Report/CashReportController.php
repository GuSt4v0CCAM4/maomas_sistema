<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CashReportController extends Controller
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
    if (isset($request->inicioFecha) && isset($request->finFecha)) {
        $fin = $request->finFecha;
        $inicio = $request->inicioFecha;
    }
    else{
            if ($selectedDate == '0') {
                return view('report.cash', ['selectedDate' => $selectedDate]);
            } else {
                if ($selectedDate == '2') {
                    $inicio = $firstDayWeek;
                    $fin = $lastDayWeek;
                } else if ($selectedDate == '3') {
                    $inicio = $firstDayMonth;
                    $fin = $lastDayMont;
                }

            }
        }

        $tienda1 = DB::table('profits')
            ->join('stores', 'stores.id', '=', 'profits.id_store')
            ->select('profits.*', 'stores.*')
            ->where('profits.id_store', 1)
            ->whereBetween('profits.date', [$inicio, $fin])
            ->orderBy('profits.date', 'asc')
            ->get();
        $tienda2 = DB::table('profits')
            ->join('stores', 'stores.id', '=', 'profits.id_store')
            ->select('profits.*', 'stores.*')
            ->where('profits.id_store', 2)
            ->whereBetween('profits.date', [$inicio, $fin])
            ->orderBy('profits.date', 'asc')
            ->get();
        $tienda3 = DB::table('profits')
            ->join('stores', 'stores.id', '=', 'profits.id_store')
            ->select('profits.*', 'stores.*')
            ->where('profits.id_store', 3)
            ->whereBetween('profits.date', [$inicio, $fin])
            ->orderBy('profits.date', 'asc')
            ->get();
        $tienda4 = DB::table('profits')
            ->join('stores', 'stores.id', '=', 'profits.id_store')
            ->select('profits.*', 'stores.*')
            ->where('profits.id_store', 4)
            ->whereBetween('profits.date', [$inicio, $fin])
            ->orderBy('profits.date', 'asc')
            ->get();
        return view('report.cash', ['tienda1' => $tienda1, 'tienda2' => $tienda2, 'tienda3' => $tienda3, 'tienda4' => $tienda4, 'selectedDate' => $selectedDate]);
    }
}
