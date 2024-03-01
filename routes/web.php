<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/cashregister', [App\Http\Controllers\Cash\CashRegisterController::class, 'index'])->name('cashregister');
Route::post('/registrandoventas', [App\Http\Controllers\Cash\CashRegisterController::class, 'inputVenta'])->name('registercash');
Route::get('/editandoventas', [App\Http\Controllers\Cash\CashRegisterController::class, 'editVenta'])->name('editcash');
Route::post('/updatesale', [App\Http\Controllers\Cash\CashRegisterController::class, 'updateSale'])->name('updatesale');
Route::post('/registrandometodos', [App\Http\Controllers\Cash\CashRegisterController::class, 'inputPayment'])->name('registerpayment');
Route::post('/regitrandogastos', [App\Http\Controllers\Cash\CashRegisterController::class, 'inputExpense'])->name('registerexpense');
Route::get('/editandomedios', [App\Http\Controllers\Cash\CashRegisterController::class, 'editPayment'])->name('editpayment');
Route::post('updatepayment', [App\Http\Controllers\Cash\CashRegisterController::class, 'updatePayment'])->name('updatepayment');
Route::get('/editandogastos', [App\Http\Controllers\Cash\CashRegisterController::class, 'editExpense'])->name('editexpense');
Route::post('updateexpense', [App\Http\Controllers\Cash\CashRegisterController::class, 'updateExpense'])->name('updateexpense');
Route::get('/editandopersonal', [App\Http\Controllers\Cash\CashRegisterController::class, 'editPersonal'])->name('editpersonal');
Route::post('updatepersonal', [App\Http\Controllers\Cash\CashRegisterController::class, 'updatePersonal'])->name('updatepersonal');
Route::get('/deletepayment', [App\Http\Controllers\Cash\CashRegisterController::class, 'deletePayment'])->name('paymentdelete');
Route::get('/deleteexpense', [App\Http\Controllers\Cash\CashRegisterController::class, 'deleteExpense'])->name('expensedelete');
Route::get('/deletepersonal', [App\Http\Controllers\Cash\CashRegisterController::class, 'deletePersonal'])->name('personaldelete');
Route::get('/deleteandoprofits', [App\Http\Controllers\Cash\CashRegisterController::class, 'deleteProfit'])->name('deleteprofit');
Route::get('registrandogananciass', [App\Http\Controllers\Cash\CashRegisterController::class, 'inputProfits'])->name('profitregister');

//consultar cierre de caja
Route::get('/cashconsult', [App\Http\Controllers\Cash\CashConsultController::class, 'index'])->name('cashconsult');
Route::get('/editandoganacias', [App\Http\Controllers\Cash\CashConsultController::class, 'editProfit'])->name('editprofit');
Route::post('/updateprofit', [App\Http\Controllers\Cash\CashConsultController::class, 'updateProfit'])->name('updateprofit');
Route::get('/eliminandoganacias', [App\Http\Controllers\Cash\CashConsultController::class, 'deleteProfit'])->name('profitdelete');

//reportes
Route::get('/cashreport', [App\Http\Controllers\Report\CashReportController::class, 'index'])->name('cashreport');
Route::get('/sellerreport', [App\Http\Controllers\Report\SellerReportController::class, 'index'])->name('sellerreport');

//Obervaciones
Route::post('/observacioncaja', [App\Http\Controllers\Cash\CashRegisterController::class, 'inputObservation'])->name('registerobservation');
Route::post('/cerrandotienda', [App\Http\Controllers\Cash\CashRegisterController::class, 'closeStore'])->name('closestore');
Route::get('/elimandocierredetienda', [App\Http\Controllers\Cash\CashRegisterController::class, 'deleteCloseStore'])->name('closestoredelete');

//infoseller
Route::get('/detallevendedores', [App\Http\Controllers\Report\SellerDetailsController::class, 'index'])->name('sellerdetails');

//gastos
Route::get('/reportegastos', [App\Http\Controllers\Report\ExpenseReportController::class, 'index'])->name('expensereport');
