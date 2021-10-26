<?php

use App\Http\Controllers\CommissionCalculator;
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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/',[CommissionCalculator::class,'csvImport'])
    ->name('commission.calculator.csv.import');

Route::POST('/commission-calculator',[CommissionCalculator::class,'calculate'])
    ->name('commission.calculator.csv.calculate');
