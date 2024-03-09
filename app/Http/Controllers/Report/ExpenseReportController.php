<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ExpenseReportController extends Controller
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
        $expense_label = [];
        $amount_label = [];
        $fin = 0;
        $inicio = 0;
        $users = DB::table('users')->select()->get();
        if (isset($request->inicioFecha) && isset($request->finFecha)) {
            $fin = $request->finFecha;
            $inicio = $request->inicioFecha;
        }
        else{
            if ($selectedDate == '0') {

                return view('report.expense', ['selectedDate' => $selectedDate, 'users' => $users]);
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
        $expense = DB::table('expenses')
            ->join('cash_details', 'expenses.id_cash', '=', 'cash_details.id_reg')
            ->join('users', 'cash_details.id_user', '=', 'users.id')
            ->select('cash_details.*', 'expenses.*', 'users.name',
                DB::raw('SUM(cash_details.amount) as total'))
            ->whereBetween('cash_details.date', [$inicio, $fin])
            ->groupBy('expenses.expense_type')
            ->orderBy('expenses.expense_type','asc')
            ->get();
        $expense_others = DB::table('expense_others')
            ->join('cash_details', 'expense_others.id_expense', '=', 'cash_details.id_reg')
            ->join('users', 'cash_details.id_user', '=', 'users.id')
            ->select('cash_details.*', 'expense_others.*', 'users.name',
                DB::raw('SUM(cash_details.amount) as total'))
            ->whereBetween('cash_details.date', [$inicio, $fin])
            ->groupBy('details')
            ->orderBy('expense_others.id_expense','asc')
            ->get();
        $expense_provider = DB::table('expense_provider')
            ->join('cash_details', 'expense_provider.id_expense', '=', 'cash_details.id_reg')
            ->join('users', 'cash_details.id_user', '=', 'users.id')
            ->select('cash_details.*', 'expense_provider.*', 'users.name',
                DB::raw('SUM(cash_details.amount) as total'))
            ->whereBetween('cash_details.date', [$inicio, $fin])
            ->groupBy('provider')
            ->orderBy('expense_provider.id_expense','asc')
            ->get();
        $i = 0;
        $expense_o = '';
        foreach ($expense as $e){
            if ($e->expense_type == 1) {
                $expense_o = 'Luz/Agua/Telefono';
            } elseif ($e->expense_type == 2){
                $expense_o = 'Alquileres';
            } elseif ($e->expense_type == 3){
                $expense_o = 'Transporte';
            } elseif ($e->expense_type == 4){
                $expense_o = 'Limpieza';
            } elseif ($e->expense_type == 5){
                $expense_o = 'Devoluciones';
            } elseif ($e->expense_type == 6){
                $expense_o = 'Proveedores';
            } elseif ($e->expense_type == 99){
                $expense_o = 'Gastos Operativos - Otros';
            } elseif ($e->expense_type == 101){
                $expense_o = 'Banco';
            } elseif ($e->expense_type == 199){
                $expense_o = 'Gastos Administrativos - Otros';
            }
            $amount_label[$i] = $e->total;
            $expense_label[$i] = $expense_o;
            $i++;
        }
        return view('report.expense', ['selectedDate' => $selectedDate, 'expense_table' => $expense,
            'amount_label' => $amount_label, 'expense_label' => $expense_label, 'others' => $expense_others,
            'provider' => $expense_provider, 'users' => $users]);
    }
}
