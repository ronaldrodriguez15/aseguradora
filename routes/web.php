<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InabilityController;
use App\Http\Controllers\InsurerController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\EpsController;

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

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/bienvenido', function () {
        return view('dashboard');
    })->name('dashboard');

    // General
    Route::resource('usuarios', UserController::class);
    Route::resource('ciudades', CityController::class);
    Route::resource('bancos', BankController::class);
    Route::resource('empresas', CompanyController::class);
    Route::resource('aseguradoras', InsurerController::class);
    Route::resource('eps', EpsController::class);
    Route::resource('asesores', AsesorController::class);

    // Affiliations
    Route::resource('afiliaciones/incapacidades', InabilityController::class);
    Route::post('afiliaciones/incapacidades/step2', [InabilityController::class, 'formStepTwo'])->name('incapacidades.formStepTwo');
    Route::post('afiliaciones/incapacidades/step3', [InabilityController::class, 'formStepTree'])->name('incapacidades.formStepTree');
    Route::post('afiliaciones/incapacidades/step4', [InabilityController::class, 'formStepFour'])->name('incapacidades.formStepFour');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
