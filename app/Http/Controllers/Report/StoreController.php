<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Queue\RedisQueue;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Cookie::get('selectedDate', '0'));
        Cookie::queue('selectedDate', $selectedDate, 1440);
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        Cookie::queue('selectedStore', $selectedStore, 1440);
        // ---- FUNCIONES DATE
        $currentDate = date('Y-m-d');
        $currentDayWeek = date('N', strtotime($currentDate));
        // ---- FUNCIONES DATE
        $expense_label = [];
        $amount_label = [];
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
                return view('report.store', ['selectedDate' => $selectedDate, 'selectedStore' => $selectedStore]);
            }else{
                if ($selectedDate == '2') {
                    //primer dia de la semana
                    $inicio = date('Y-m-d', strtotime('-' . $currentDayWeek . ' days', strtotime($currentDate)));
                    //ultimo dia de la semana
                    $fin = date('Y-m-d', strtotime('+' . (7 - $currentDayWeek) . ' days', strtotime($currentDate)));
                }elseif ($selectedDate == '3') {
                    $inicio = date('Y-m-01');
                    $fin = date('Y-m-t');
                }
            }

        }
        $ranking = DB::table('users')
            ->join('profits', 'users.id', '=', 'profits.id_user')
            ->select('users.*', 'profits.profit', 'profits.balance', DB::raw('SUM(profits.sale) as sale, SUM(profits.expense) as expense, SUM(profits.balance) as balance'))
            ->where('profits.id_store', $selectedStore)
            ->whereBetween('profits.date', [$inicio, $fin])
            ->groupBy('users.id')
            ->get();
        $data_profit = DB::table('profits')
            ->join('users', 'users.id', '=', 'profits.id_user')
            ->join('stores', 'stores.id', '=', 'profits.id_store')
            ->select('users.id', 'users.name', 'stores.name as store',
                DB::raw('SUM(profits.sale) as sale'),
                DB::raw('SUM(profits.expense) as expense'),
                'profits.profit', 'profits.date', 'profits.id_store')
            ->where('profits.id_store', $selectedStore)
            ->whereBetween('profits.date', [$inicio, $fin])
            ->groupBy('profits.id_user', 'profits.date')
            ->orderBy('profits.date', 'asc')
            ->get();

        $fechas_unicas = $data_profit->pluck('date')->unique();
        foreach ($data_profit as $value){

            $id_user = $value->id;
            $id_store = $value->id_store;
            $date = $value->date;
            $total_ventas = $value->sale + $value->expense;
            $matriz_store[$id_user]['datos'][$date] = [
                'name' => $value->name,
                'date' => $value->date,
                'profit' => $total_ventas,
                'store' => $value->id_store,
            ];
            if (!isset($matriz_store[$id_user]['datos'][$date])) {
                $matriz_store[$id_user]['datos'][$date] = [
                    'name' => $value->name,
                    'date' => $date,
                    'profit' => 0, // Inicializar con 0
                    'store' => 0,
                ];
            }
            $matriz_store[$id_user]['datos'][$date]['profit'] = $total_ventas;
            $matriz_store[$id_user]['datos'][$date]['store'] = $value->id_store;
        }
        foreach ($matriz_store as &$usuario) {
            $usuario['datos'] = $usuario['datos'] + $fechas_unicas->mapWithKeys(function ($fecha) {
                    return [$fecha => ['name' => '', 'date' => $fecha, 'profit' => 0, 'store' => 0]];
                })->all();
        }
        foreach ($matriz_store as &$usuario) {
            ksort($usuario['datos']);
        }
        $expense = DB::table('expenses')
            ->join('cash_details', 'expenses.id_cash', '=', 'cash_details.id_reg')
            ->join('users', 'cash_details.id_user', '=', 'users.id')
            ->select('cash_details.*', 'expenses.*', 'users.name',
                DB::raw('SUM(cash_details.amount) as total'))
            ->where('cash_details.id_store', $selectedStore)
            ->whereBetween('cash_details.date', [$inicio, $fin])
            ->groupBy('expenses.expense_type')
            ->orderBy('expenses.expense_type','asc')
            ->get();
        $expense_others = DB::table('expense_others')
            ->join('cash_details', 'expense_others.id_expense', '=', 'cash_details.id_reg')
            ->join('users', 'cash_details.id_user', '=', 'users.id')
            ->select('cash_details.*', 'expense_others.*', 'users.name',
                DB::raw('SUM(cash_details.amount) as total'))
            ->where('cash_details.id_store', $selectedStore)
            ->whereBetween('cash_details.date', [$inicio, $fin])
            ->groupBy('details')
            ->orderBy('expense_others.id_expense','asc')
            ->get();
        $expense_provider = DB::table('expense_provider')
            ->join('cash_details', 'expense_provider.id_expense', '=', 'cash_details.id_reg')
            ->join('users', 'cash_details.id_user', '=', 'users.id')
            ->select('cash_details.*', 'expense_provider.*', 'users.name',
                DB::raw('SUM(cash_details.amount) as total'))
            ->where('cash_details.id_store', $selectedStore)
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
        return view('report.store', ['selectedDate' => $selectedDate, 'selectedStore' => $selectedStore,
            'matriz_store' => $matriz_store, 'users' => $users, 'inicio' => $inicio, 'fin' => $fin, 'ranking' => $ranking,
            'data_profit' => $data_profit, 'expense_table' => $expense,
            'amount_label' => $amount_label, 'expense_label' => $expense_label, 'others' => $expense_others,
            'provider' => $expense_provider]);
    }
}
