<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\InabilityController;
use App\Http\Controllers\InsurerController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PDFPositivaController;
use App\Http\Controllers\PDFDebitoController;
use App\Http\Controllers\PDFLibranzaController;
use App\Http\Controllers\PDFConfianzaController;
use App\Http\Controllers\DocumentsApiController;
use App\Http\Controllers\ReportController;

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

// enlace simbolico
Route::get('storage-link', function () {
    Artisan::call('storage:link');

    return response()->json([
        'status' => 'Correctly Storage.',
    ]);
});

Route::get('clear-caches', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');

    return response()->json([
        'status' => 'Cachés limpiados correctamente.',
    ]);
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
    Route::resource('entidades', EntityController::class);
    Route::resource('aseguradoras', InsurerController::class);
    Route::resource('eps', EpsController::class);
    Route::resource('asesores', AsesorController::class);

    // Documents
    Route::resource('documentos', DocumentController::class);

    // Affiliations
    Route::resource('afiliaciones/incapacidades', InabilityController::class);
    Route::post('afiliaciones/incapacidades/step2', [InabilityController::class, 'formStepTwo'])->name('incapacidades.formStepTwo');
    Route::post('afiliaciones/incapacidades/step3', [InabilityController::class, 'formStepTree'])->name('incapacidades.formStepTree');
    Route::post('afiliaciones/incapacidades/step4', [InabilityController::class, 'formStepFour'])->name('incapacidades.formStepFour');
    Route::post('afiliaciones/incapacidades/step5', [InabilityController::class, 'formStepFive'])->name('incapacidades.formStepFive');
    Route::post('afiliaciones/incapacidades/step6', [InabilityController::class, 'formStepSix'])->name('incapacidades.formStepSix');
    Route::get('afiliaciones/incapacidades/{inabilityId}/pdf', [PDFController::class, 'generarPDF'])->name('incapacidades.generarPDF');

    // PDFs que se generan
    Route::get('afiliaciones/incapacidades/{inabilityId}/pdfPositiva', [PDFPositivaController::class, 'generarPDF'])->name('incapacidades.generarPDFpositiva');
    Route::get('afiliaciones/incapacidades/{inabilityId}/pdfLibranza', [PDFLibranzaController::class, 'generarPDF'])->name('incapacidades.generarPDFLibranza');
    Route::get('afiliaciones/incapacidades/{inabilityId}/pdfDebito', [PDFDebitoController::class, 'generarPDF'])->name('incapacidades.generarPDFdebito');
    Route::get('afiliaciones/incapacidades/{inabilityId}/pdfConfianza', [PDFConfianzaController::class, 'generarPDF'])->name('incapacidades.generarPDFconfianza');

    //conexion via firma
    Route::post('/envio-viafirma', [DocumentsApiController::class, 'sendToViaFirma'])->name('sendToViaFirma');
    //conexion via firma
    Route::post('/consulta-viafirma', [DocumentsApiController::class, 'downloadFile'])->name('downloadFile');

    // Reportes
    Route::resource('reportes', ReportController::class);
    Route::post('/reportes', [ReportController::class, 'index']);
    Route::post('/descargar-pdfs', [ReportController::class, 'descargarPDFs']);
    Route::post('/descargar-plano-focus', [ReportController::class, 'descargarPlanoFocus']);
    Route::post('/descargar-seguimiento-ventas', [ReportController::class, 'descargarSeguimientoVentas']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

