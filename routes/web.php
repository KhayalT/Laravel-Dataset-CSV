<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [CustomerController::class, 'index'])->name('customer.index');

Route::group(['prefix' => 'customer'], function (){
    Route::post('/import', [CustomerController::class, 'import'])->name('import.customer');
    Route::post('/export', [CustomerController::class, 'export'])->name('export.customer');
});
