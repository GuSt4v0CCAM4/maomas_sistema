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
        if ($selectedDate == '0') {
            return view('report.cash', ['selectedDate' => $selectedDate]);
        } else {
            if ($selectedDate == '1') {
                $tienda1 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 1)
                    ->whereDate('profits.date', $currentDate)
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda2 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 2)
                    ->whereDate('profits.date', $currentDate)
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda3 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 3)
                    ->whereDate('profits.date', $currentDate)
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda4 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 4)
                    ->whereDate('profits.date', $currentDate)
                    ->orderBy('profits.date', 'asc')
                    ->get();

            } else if ($selectedDate == '2') {
                $tienda1 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 1)
                    ->whereBetween('profits.date', [$firstDayWeek, $lastDayWeek])
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda2 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 2)
                    ->whereBetween('profits.date', [$firstDayWeek, $lastDayWeek])
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda3 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 3)
                    ->whereBetween('profits.date', [$firstDayWeek, $lastDayWeek])
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda4 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 4)
                    ->whereBetween('profits.date', [$firstDayWeek, $lastDayWeek])
                    ->orderBy('profits.date', 'asc')
                    ->get();
            } else if ($selectedDate == '3') {
                $tienda1 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 1)
                    ->whereBetween('profits.date', [$firstDayMonth, $lastDayMont])
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda2 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 2)
                    ->whereBetween('profits.date', [$firstDayMonth, $lastDayMont])
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda3 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 3)
                    ->whereBetween('profits.date', [$firstDayMonth, $lastDayMont])
                    ->orderBy('profits.date', 'asc')
                    ->get();
                $tienda4 = DB::table('profits')
                    ->join('stores', 'stores.id', '=', 'profits.id_store')
                    ->select('profits.*', 'stores.*')
                    ->where('profits.id_store', 4)
                    ->whereBetween('profits.date', [$firstDayMonth, $lastDayMont])
                    ->orderBy('profits.date', 'asc')
                    ->get();
            } elseif ($selectedDate == '4') {
                if (@isset($request->startDate, $request->endDate)) {
                    $tienda1 = DB::table('profits')
                        ->join('stores', 'stores.id', '=', 'profits.id_store')
                        ->select('profits.*', 'stores.*')
                        ->where('profits.id_store', 1)
                        ->whereBetween('profits.date', [$request->startDate, $request->endDate])
                        ->orderBy('profits.date', 'asc')
                        ->get();
                    $tienda2 = DB::table('profits')
                        ->join('stores', 'stores.id', '=', 'profits.id_store')
                        ->select('profits.*', 'stores.*')
                        ->where('profits.id_store', 2)
                        ->whereBetween('profits.date', [$request->startDate, $request->endDate])
                        ->orderBy('profits.date', 'asc')
                        ->get();
                    $tienda3 = DB::table('profits')
                        ->join('stores', 'stores.id', '=', 'profits.id_store')
                        ->select('profits.*', 'stores.*')
                        ->where('profits.id_store', 3)
                        ->whereBetween('profits.date', [$request->startDate, $request->endDate])
                        ->orderBy('profits.date', 'asc')
                        ->get();
                    $tienda4 = DB::table('profits')
                        ->join('stores', 'stores.id', '=', 'profits.id_store')
                        ->select('profits.*', 'stores.*')
                        ->where('profits.id_store', 4)
                        ->whereBetween('profits.date', [$request->startDate, $request->endDate])
                        ->orderBy('profits.date', 'asc')
                        ->get();
                } else {
                    $tienda1 = DB::table('profits')
                        ->join('stores', 'stores.id', '=', 'profits.id_store')
                        ->select('profits.*', 'stores.*')
                        ->where('profits.id_store', 1)
                        ->orderBy('profits.date', 'asc')
                        ->get();
                    $tienda2 = DB::table('profits')
                        ->join('stores', 'stores.id', '=', 'profits.id_store')
                        ->select('profits.*', 'stores.*')
                        ->where('profits.id_store', 2)
                        ->orderBy('profits.date', 'asc')
                        ->get();
                    $tienda3 = DB::table('profits')
                        ->join('stores', 'stores.id', '=', 'profits.id_store')
                        ->select('profits.*', 'stores.*')
                        ->where('profits.id_store', 3)
                        ->orderBy('profits.date', 'asc')
                        ->get();
                    $tienda4 = DB::table('profits')
                        ->join('stores', 'stores.id', '=', 'profits.id_store')
                        ->select('profits.*', 'stores.*')
                        ->where('profits.id_store', 4)
                        ->orderBy('profits.date', 'asc')
                        ->get();
                }
            }
            return view('report.cash', ['tienda1' => $tienda1, 'tienda2' => $tienda2, 'tienda3' => $tienda3, 'tienda4' => $tienda4, 'selectedDate' => $selectedDate]);
        }
    }
}
