<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class SellerReportController extends Controller
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
            return view('report.seller', ['selectedDate' => $selectedDate]);
        }else{
            if ($selectedDate == '1'){
                $ranking = DB::table('users')
                    ->join('profits', 'users.id', '=', 'profits.id_user')
                    ->select('users.*', 'profits.profit', 'profits.balance', DB::raw('SUM(profits.profit) as total, SUM(profits.balance) as balance'))
                    ->whereDate('profits.date', $currentDate)
                    ->groupBy('users.id')
                    ->get();
            } else if ($selectedDate == '2'){
                $ranking = DB::table('users')
                    ->join('profits', 'users.id', '=', 'profits.id_user')
                    ->select('users.*', 'profits.profit', 'profits.balance', DB::raw('SUM(profits.profit) as total, SUM(profits.balance) as balance'))
                    ->whereBetween('profits.date', [$firstDayWeek, $lastDayWeek])
                    ->groupBy('users.id')
                    ->get();
            } else if ($selectedDate == '3'){
                $ranking = DB::table('users')
                    ->join('profits', 'users.id', '=', 'profits.id_user')
                    ->select('users.*', 'profits.profit', 'profits.balance', DB::raw('SUM(profits.profit) as total, SUM(profits.balance) as balance'))
                    ->whereBetween('profits.date', [$firstDayMonth, $lastDayMont])
                    ->groupBy('users.id')
                    ->get();
            } else if ($selectedDate == '4'){
                if (@isset($request->startDate, $request->endDate)) {
                    $startDate = $request->startDate;
                    $endDate = $request->endDate;
                    $ranking = DB::table('users')
                        ->join('profits', 'users.id', '=', 'profits.id_user')
                        ->select('users.*', 'profits.profit', 'profits.balance', DB::raw('SUM(profits.profit) as total, SUM(profits.balance) as balance'))
                        ->whereBetween('profits.date', [$startDate, $endDate])
                        ->groupBy('users.id')
                        ->get();
                } else {
                    $ranking = DB::table('users')
                    ->join('profits', 'users.id', '=', 'profits.id_user')
                        ->select('users.*', 'profits.profit', 'profits.balance', DB::raw('SUM(profits.profit) as total, SUM(profits.balance) as balance'))
                        ->groupBy('users.id')
                        ->get();
                }
            }
            return view('report.seller', ['selectedDate' => $selectedDate, 'ranking' => $ranking]);
        }
    }
}
