<?php

namespace App\Http\Controllers\Cash;

use App\Http\Controllers\Controller;
use App\Models\CashRegister;
use App\Models\DetailsRegister;
use App\Models\ExpenseProvider;
use App\Models\ExpenseRegister;
use App\Models\OtherExpenseRegister;
use App\Models\PaymentRegister;
use App\Models\PersonalExpense;
use App\Models\ProfitObservation;
use App\Models\ProfitRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CashRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $today = date('Y-m-d');//obtenemos la fecha de hoy
        $Date = $request->input('date', Cookie::get('Date', $today)); //obtenemos la fecha de la cookie
        $selectedStore= $request->input('store', Cookie::get('selectedStore', '0'));
        $selectedUser = $request->input('user', Cookie::get('selectedUser', '0'));
        Cookie::queue('Date', $Date, 1440); //guardamos la fecha en la cookie
        Cookie::queue('selectedStore', $selectedStore, 1440); //guardamos la tienda en la cookie
        Cookie::queue('selectedUser', $selectedUser, 1440);
        $users = DB::table('users')
            ->select('id','name')
            ->get();
        if ($Date != '0' && $selectedStore != '0') { //verificamos si los valores sean diferentes a 0
            $valor_total = DB::table('sales_amount') //consultamos si hay un registro con la fecha y la tienda
                ->select('amount', 'id_reg')
                ->where('id_store', $selectedStore)
                ->whereDate('date', $Date)->get();
            $table_payment = DB::table('payment_methods')
                ->join('cash_details', 'payment_methods.id_cash', '=', 'cash_details.id_reg')
                ->join('users','cash_details.id_user', '=', 'users.id' )
                ->select('payment_methods.*', 'cash_details.amount', 'users.name')
                ->where('id_store', $selectedStore)
                ->whereDate('date', $Date)->get();
            $table_expense = DB::table('expenses')
                ->join('cash_details', 'expenses.id_cash', '=', 'cash_details.id_reg')
                ->join('users','cash_details.id_user', '=', 'users.id' )
                ->select('expenses.*', 'cash_details.amount', 'users.name')
                ->where('id_store', $selectedStore)
                ->whereDate('date', $Date)->get();
            $table_personal = DB::table('personal_expense')
                ->join('cash_details', 'personal_expense.id_cash', '=', 'cash_details.id_reg')
                ->join('users','personal_expense.id_user', '=', 'users.id' )
                ->select('personal_expense.*', 'cash_details.amount', 'users.name')
                ->where('cash_details.id_store', $selectedStore)
                ->whereDate('cash_details.date', $Date)->get();
            $verificar_cierre = DB::table('profits')
                ->select()
                ->where('id_store', $selectedStore)
                ->whereDate('date', $Date)->get();
            $verificar_tiendacerrada = DB::table('profit_observations')
                ->join('profits', 'profit_observations.id_profit', '=', 'profits.id_reg')
                ->select('profit_observations.*')
                ->where('profits.id_store', $selectedStore)
                ->whereDate('profits.date', $Date)->get();
            $cierre = 0;
            if (count($verificar_cierre) > 0) {
                $cierre = 1;
            }
            if (count($valor_total) > 0) {
                $existe = 'existe';
                return view('cash.register', ['selectedUser' => $selectedUser,'Date' => $Date, 'selectedStore' => $selectedStore,
                    'close_store'=> $verificar_tiendacerrada, 'existe' => $existe, 'valor_total' => $valor_total, 'table_payment' => $table_payment,
                    'table_expense' => $table_expense, 'table_personal' => $table_personal, 'cierre_caja' => $verificar_cierre,
                    'cierre' => $cierre, 'users' => $users]);
            } else {
                $noexiste = 'noexiste';
                return view('cash.register', ['selectedUser' => $selectedUser,'Date' => $Date, 'selectedStore' => $selectedStore,
                    'close_store'=> $verificar_tiendacerrada,'noexiste' => $noexiste, 'table_payment' => $table_payment,
                    'table_expense' => $table_expense, 'table_personal' => $table_personal, 'users' => $users]);
            }

        }
        $warning = 'Por favor seleccione una fecha y una tienda';

        return view('cash.register', ['selectedUser' => $selectedUser,'Date' => $Date, 'selectedStore' => $selectedStore, 'warning' => $warning, 'users' => $users]);
    }
    public function inputVenta(Request $request)
    {
        try{
            $selectedStore = request()->cookie('selectedStore', '0');
            $date = $request->cookie('Date', '0');
            $validateData = $request->validate([
                'sale' => 'required',
            ]);
            CashRegister::create([
                'amount' => $request->input('sale'),
                'date' => $date,
                'id_user' => auth()->user()->id,
                'id_store' => $selectedStore,
            ]);
            return redirect()->route('cashregister')->with('success', 'Ventas registradas con exito!');
        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al registrar las ventas!');
        }
    }
    public function editVenta()
    {
        $id = request()->input('id');
        $datos = DB::table('sales_amount')->select()->where('id_reg', $id)->get();
        return view('cash.cashedit', ['datos' => $datos]);
    }
    public function updateSale(Request $request)
    {
        $validatedData = $request->validate([
            'sale' => 'required',
        ]);
        try {
            $amount = $request->input('sale');
            $id = $request->input('id');
            DB::table('sales_amount')->where('id_reg', $id)->update(['amount' => $amount]);
            return redirect()->route('cashregister')->with('success', 'Ventas actualizadas con exito!');
        } catch (\Exception $e) {
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al actualizar las ventas!');
        }
    }
    public function inputPayment(Request $request)
    {
        try{
            $selectedStore = request()->cookie('selectedStore', '0');
            $date = $request->cookie('Date', '0');
            $validateData = $request->validate([
                'payment' => 'required',
                'amount' => 'required',
            ]);
            $detailsRegister = DetailsRegister::create([
                'amount' => $request->input('amount'),
                'date' => $date,
                'id_user' => auth()->user()->id,
                'id_store' => $selectedStore,
            ]);
            $id_cash = $detailsRegister->id;
            PaymentRegister::create([
                'id_cash' => $id_cash,
                'payment_type' => $request->input('payment'),
            ]);
            return redirect()->route('cashregister')->with('success', 'Metodos de pago registrados con exito!');
        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al registrar las metodos de pago!');
        }
    }
    public function inputExpense(Request $request)
    {
        try{
            $selectedStore = request()->cookie('selectedStore', '0');
            $date = $request->cookie('Date', '0');
            $validateData = $request->validate([
                'expense' => 'required',
                'amount' => 'required',
            ]);
            $detailsRegister = DetailsRegister::create([
                'amount' => $request->input('amount'),
                'date' => $date,
                'id_user' => auth()->user()->id,
                'id_store' => $selectedStore,
            ]);
            $id_cash = $detailsRegister->id; //obtenemos el id del ultimo registro hecho en cash_detail
            $expense = $request->input('expense');
            if ($expense > 300 && $expense < 400){
                $u = $expense % 10;
                $d = floor(($expense % 100) / 10);
                $c = floor($expense / 100);
                if ($d == 0){
                    $expense_register = $u;
                } else {
                    $expense_register = $d.$u;
                }
                PersonalExpense::create([
                    'id_cash' => $id_cash,
                    'id_user' => $expense_register,
                ]);
            }else{

                ExpenseRegister::create([
                    'id_cash' => $id_cash,
                    'expense_type' => $request->input('expense'),
                ]);
                if ($request->input('expense')== 99 ){
                    $details_r = $request-> input('otherInput');
                    $other = "Gastos Operativos - ".$details_r;
                    OtherExpenseRegister::create([
                        'id_expense' => $id_cash,
                        'details' => $other,
                    ]);
                } elseif ($request->input('expense')== 199 ){
                    $details_r = $request-> input('otherInput');
                    $other = "Gastos Administrativos - ".$details_r;
                    OtherExpenseRegister::create([
                        'id_expense' => $id_cash,
                        'details' => $other,
                    ]);
                } elseif ($request->input('expense')== 6 ){
                    $details_r = $request-> input('otherInput');
                    ExpenseProvider::create([
                        'id_expense' => $id_cash,
                        'provider' => $details_r
                    ]);
                }
            }
            return redirect()->route('cashregister')->with('success', 'Ventas registradas con exito!');
        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al registrar las ventas!');
        }
    }
    public function editPayment(Request $request)
    {
        $id = request()->input('id');
        $users = DB::table('users')
            ->select('id','name')
            ->get();
        $datos = DB::table('payment_methods')
            ->join('cash_details', 'payment_methods.id_cash', '=', 'cash_details.id_reg')
            ->join('users','cash_details.id_user', '=', 'users.id' )
            ->select('payment_methods.*', 'cash_details.amount', 'cash_details.date', 'cash_details.id_user', 'users.name' , 'users.id')
            ->where('id_cash', $id)
            ->get();
        return view('cash.paymentedit', ['datos' => $datos, 'users' => $users]);
    }
    public function updatePayment(Request $request)
    {
        $validatedData = $request->validate([
            'payment' => 'required',
            'amount' => 'required',
            'seller' => 'required',
        ]);
        try {
            $amount = $request->input('amount');
            $id = $request->input('id');
            DB::table('cash_details')
                ->where('id_reg', $id)
                ->update(['amount' => $amount, 'id_user' => $request->input('seller')]);
            DB::table('payment_methods')
                ->where('id_cash', $id)
                ->update(['payment_type' => $request->input('payment')]);
            return redirect()->route('cashregister')->with('success', 'Metodos de pago actualizados con exito!');
        } catch (\Exception $e) {
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al actualizar los metodos de pago!');
        }
    }
    public function editPersonal(Request $request)
    {
        $id = request()->input('id');
        $users = DB::table('users')
            ->select('id','name')
            ->get();
        $datos = DB::table('personal_expense')
            ->join('cash_details', 'personal_expense.id_cash', '=', 'cash_details.id_reg')
            ->join('users','personal_expense.id_user', '=', 'users.id' )
            ->select('personal_expense.*', 'cash_details.amount', 'cash_details.date', 'personal_expense.id_user', 'users.name' , 'users.id')
            ->where('id_cash', $id)
            ->get();
        return view('cash.personaledit', ['datos' => $datos, 'users' => $users]);
    }
    public function updatePersonal(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required',
            'seller' => 'required',
        ]);
        try {
            $amount = $request->input('amount');
            $id = $request->input('id');
            DB::table('cash_details')
                ->where('id_reg', $id)
                ->update(['amount' => $amount]);
            DB::table('personal_expense')
                ->where('id_cash', $id)
                ->update(['id_user' => $request->input('seller')]);
            return redirect()->route('cashregister')->with('success', 'Gastos Personal actualizados con exito!');
        } catch (\Exception $e) {
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al actualizar los gastos de Personal!');
        }
    }
    public function editExpense(Request $request)
    {
        $id = request()->input('id');
        $users = DB::table('users')
            ->select('id','name')
            ->get();
        $datos = DB::table('expenses')
            ->join('cash_details', 'expenses.id_cash', '=', 'cash_details.id_reg')
            ->join('users','cash_details.id_user', '=', 'users.id' )
            ->select('expenses.*', 'cash_details.amount', 'cash_details.date', 'cash_details.id_user', 'users.name' , 'users.id')
            ->where('id_cash', $id)
            ->get();
        return view('cash.expenseedit', ['datos' => $datos, 'users' => $users]);
    }
    public function updateExpense(Request $request)
    {
        $validatedData = $request->validate([
            'expense' => 'required',
            'amount' => 'required',
            'seller' => 'required',
        ]);
        try {
            $amount = $request->input('amount');
            $id = $request->input('id');
            DB::table('cash_details')
                ->where('id_reg', $id)
                ->update(['amount' => $amount, 'id_user' => $request->input('seller')]);
            DB::table('expenses')
                ->where('id_cash', $id)
                ->update(['expense_type' => $request->input('expense')]);
            return redirect()->route('cashregister')->with('success', 'Gastos actualizados con exito!');
        } catch (\Exception $e) {
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al actualizar los gastos!');
        }
    }

    public function deletePayment(Request $request)
    {
        $id = request()->input('id');
        try{
            DB::table('payment_methods')->where('id_cash', $id)->delete();
            DB::table('cash_details')->where('id_reg', $id)->delete();
        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error a la hora de eliminar el registro');
        }
        return redirect()->route('cashregister')->with('success', 'Se elimino el registro correctamente!!');
    }
    public function deleteExpense(Request $request)
    {
        $id = request()->input('id');
        try{
            DB::table('expense_others')->where('id_expense', $id)->delete();
            DB::table('expense_provider')->where('id_expense', $id)->delete();
            DB::table('expenses')->where('id_cash', $id)->delete();
            DB::table('cash_details')->where('id_reg', $id)->delete();

        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error a la hora de eliminar el registro');
        }
        return redirect()->route('cashregister')->with('success', 'Se elimino el registro correctamente!!');
    }
    public function deletePersonal(Request $request)
    {
        $id = request()->input('id');
        try{
            DB::table('personal_expense')->where('id_cash', $id)->delete();
            DB::table('cash_details')->where('id_reg', $id)->delete();
        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error a la hora de eliminar el registro');
        }
        return redirect()->route('cashregister')->with('success', 'Se elimino el registro correctamente!!');
    }
    public function inputProfits(Request $request)
    {
        try{
            $balance = $request->input('balance');
            $user = $request->input('user', Cookie::get('selectedUser', '0'));
            $date = $request->input('date');
            $store = $request->input('store');
            $profit = $request->input('profit');
            $payment = [0.0, 0.0, 0.0, 0.0, 0.0];
            $sale = DB::table('payment_methods')
                ->join('cash_details', 'payment_methods.id_cash', '=', 'cash_details.id_reg')
                ->select('cash_details.id_store', DB::raw('SUM(cash_details.amount) as total'))
                ->where('cash_details.id_store', $store)
                ->whereDate('cash_details.date', $date)
                ->groupBy('cash_details.id_store')
                ->get();
            $expense = DB::table('expenses')
                ->join('cash_details', 'expenses.id_cash', '=', 'cash_details.id_reg')
                ->select('cash_details.id_store', DB::raw('SUM(cash_details.amount) as total'))
                ->where('cash_details.id_store', $store)
                ->whereDate('cash_details.date', $date)
                ->groupBy('cash_details.id_store')
                ->get();
            $payment_data = DB::table('payment_methods')
                ->join('cash_details', 'payment_methods.id_cash', '=', 'cash_details.id_reg')
                ->select('payment_methods.payment_type', DB::raw('SUM(cash_details.amount) as total'))
                ->where('cash_details.id_store', $store)
                ->whereDate('cash_details.date', $date)
                ->groupBy('payment_methods.payment_type')
                ->get();
            foreach ($payment_data as $p) {
                if ($p->payment_type == 1) {
                    $payment[0] += $p->total;
                } elseif ($p->payment_type == 2) {
                    $payment[1] += $p->total;
                } elseif ($p->payment_type == 3) {
                    $payment[2] += $p->total;
                } elseif ($p->payment_type == 4) {
                    $payment[3] += $p->total;
                } elseif ($p->payment_type == 5) {
                    $payment[4] += $p->total;
                }

            }
            if (count($expense) == 0) {
                $expense = 0;
            } else {
                $expense = $expense->first()->total;
            }
            if (count($sale) == 0) {
                $sale = 0;
            } else {
                $sale = $sale->first()->total;
            }

            $pm = implode('|', $payment);
            ProfitRegister::create([
                'payments' => $pm,
                'sale' => $sale,
                'expense' => $expense,
                'profit' => $profit,
                'date' => $date,
                'id_user' => $user,
                'id_store' => $store,
                'balance' => $balance,
            ]);
            return redirect()->route('cashregister')->with('success', 'Cierre de caja registrado con exito!!');
        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al registrar el cierre de caja, el error es: ' . $e->getMessage() . ' linea: ' . $e->getLine() . 'payment=' . $expense);
        }
    }
    public function deleteProfit(Request $request)
    {
        $id = request()->input('id');
        try{
            DB::table('profit_observations')->where('id_profit', $id)->delete();
            DB::table('profits')->where('id_reg', $id)->delete();
        }catch (\Exception $e){
            return redirect('cashregister')->with('error', 'Ocurrio un error a la hora de eliminar el registro'. $e->getMessage());
        }
        return redirect('cashregister')->with('success', 'Se elimino el registro correctamente!!');
    }
    public function inputObservation(Request $request)
    {
        try{
            $Date = $request->cookie('Date', '0');//obtenemos la fecha de la cookie
            $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
            $obervation = $request->input('observation');
            $registro = DB::table('profits')->where('id_store', $selectedStore)
                ->where('date', $Date)->get();
            $id = $registro->first()->id_reg;
            ProfitObservation::create([
                'id_profit' => $id,
                'observation' => $obervation,
            ]);
            return redirect()->route('cashregister')->with('success', 'Observacion agregado con exito!!');
        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al agregar la observacion ' . $e->getMessage() . ' linea: ' . $e->getLine() . 'payment=' );
        }
    }
    public function closeStore(Request $request)
    {
        try{
            $Date = $request->cookie('Date', '0');//obtenemos la fecha de la cookie
            $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
            $selectedUser = $request->input('user', Cookie::get('selectedUser', '0'));
            $observation = $request->input('observation');
            $obervation = 'CIERRE - ' . $observation;
            $registrando = ProfitRegister::create([
                'payments' => '0|0|0|0|0',
                'sale' => 0,
                'expense' => 0,
                'profit' => 0,
                'date' => $Date,
                'id_user' => $selectedUser,
                'id_store' => $selectedStore,
                'balance' => 0,
            ]);
            $id = $registrando->id;
            ProfitObservation::create([
                'id_profit' => $id,
                'observation' => $obervation,
            ]);
            return redirect()->route('cashregister')->with('success', 'Tienda cerrada con exito!!');
        }catch (\Exception $e){
            return redirect()->route('cashregister')->with('error', 'Ocurrio un error al cerrar la tienda' . $e->getMessage() . ' linea: ' . $e->getLine() . 'payment=' );
        }
    }
    public function deleteCloseStore(Request $request)
    {
        $id = request()->input('id');
        try {
            DB::table('profit_observations')->where('id_profit', $id)->delete();
            DB::table('profits')->where('id_reg', $id)->delete();
        }catch (\Exception $e){
            return redirect('cashregister')->with('error', 'Ocurrio un error a la hora de eliminar el registro');
        }
        return redirect('cashregister')->with('success', 'Se elimino el registro correctamente!!');
    }
}
