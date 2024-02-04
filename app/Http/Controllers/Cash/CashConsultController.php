<?php

namespace App\Http\Controllers\Cash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CashConsultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $selectedStore = $request->input('store', Cookie::get('selectedStore', '0'));
        Cookie::queue('selectedStore', $selectedStore, 1440);
        $consult = DB::table('profits')
            ->join('users', 'profits.id_user', '=', 'users.id')
            ->select()->where('profits.id_store', $selectedStore)
            ->orderBy('profits.date', 'desc')->get();
        return view('cash.consult',['selectedStore' => $selectedStore, 'consult' => $consult]);
    }
    public function editProfit(Request $request)
    {
        $id = request()->input('id');
        $users = DB::table('users')
            ->select('id','name')
            ->get();
        $datos = DB::table('profits')
            ->select()->where('id_reg', $id)->get();
        return view('cash.editprofit', ['users' => $users, 'datos' => $datos]);
    }
    public function updateProfit(Request $request)
    {
        $validatedData = $request->validate([
            'seller' => 'required',
        ]);

        try{
            $id = request()->input('id');
            $vendedor = request()->input('seller');
            DB::table('profits')
                ->where('id_reg', $id)
                ->update(['id_user' => $vendedor]);

        } catch (Exception $e) {
            return redirect('cashconsult')->with('error', 'Ocurrio un error al actualizar el vendedor!');
        }
        return redirect('cashconsult')->with('success', 'Vendedor actualizado con exito!');
    }
    public function deleteProfit(Request $request)
    {
        $id = request()->input('id');
        try{
            DB::table('profits')->where('id_reg', $id)->delete();
        }catch (\Exception $e){
            return redirect('cashconsult')->with('error', 'Ocurrio un error a la hora de eliminar el registro');
        }
        return redirect('cashconsult')->with('success', 'Se elimino el registro correctamente!!');
    }
}
