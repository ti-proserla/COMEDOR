<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login','CuentaController@login')->name('cuenta.login');
Route::resource('empresa', 'EmpresaController');
Route::resource('planilla', 'PlanillaController');
Route::post('personal/masivo', 'PersonalController@masivo');
Route::resource('personal', 'PersonalController');
Route::resource('servicio', 'ServicioController');
Route::post('pedido', 'PedidoController@store');
Route::get('reporte/fecha', 'ReporteController@fecha');
Route::get('reporte/personal', 'ReporteController@personal');