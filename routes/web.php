<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AperturaEditParent;
use App\Livewire\RegistroDocumentosIngreso;
use App\Livewire\RegistroDocumentosEgreso;
use App\Livewire\VaucherPagoCompras;
use App\Livewire\VaucherPagoVentas;
use App\Livewire\CuadroAplicaciones;
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
Route::redirect('/', '/login');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', ['routeName' => 'dashboard']);
    })->name('dashboard');

    Route::get('/tesoreria/movimientos', function () {
        return view('tesoreria.movimientos', ['routeName' => 'movimientos']);
    })->name('movimientos');

    Route::get('/tesoreria/aplicaciones', function () {
        return view('tesoreria.aplicaciones', ['routeName' => 'aplicaciones']);
    })->name('aplicaciones');

    Route::get('/pendientes/cxc', function () {
        return view('deudas.documentos-cxc', ['routeName' => 'cxc']);
    })->name('cxc');

    Route::get('/pendientes/cxp', function () {
        return view('deudas.documentos-cxp', ['routeName' => 'cxp']);
    })->name('cxp');

    Route::get('/productos/familias', function () {
        return view('logistica.familia', ['routeName' => 'familias']);
    })->name('familias');
     
    Route::get('/productos/subfamilias', function () {
        return view('logistica.subfamilia', ['routeName' => 'subfamilias']);
    })->name('subfamilias');

    Route::get('/productos/detalle', function () {
        return view('logistica.detalle', ['routeName' => 'detalle']);
    })->name('detalle');

    Route::get('/productos/producto', function () {
        return view('logistica.producto', ['routeName' => 'producto']);
    })->name('producto');

    Route::prefix('apertura/{aperturaId}/edit')->group(function () {
        Route::get('/', AperturaEditParent::class)->name('apertura.edit');
        Route::get('/registodocumentosingreso', RegistroDocumentosIngreso::class)->name('apertura.edit.registodocumentosingreso');
        Route::get('/registodocumentosegreso', RegistroDocumentosEgreso::class)->name('apertura.edit.registodocumentosegreso');
        Route::get('/vaucherdepagos', VaucherPagoCompras::class)->name('apertura.edit.vaucherdepagos');
        Route::get('/vaucherdepagosventas', VaucherPagoVentas::class)->name('apertura.edit.vaucherdepagosventas');
        Route::get('/cuadroaplicaciones', CuadroAplicaciones::class)->name('apertura.edit.cuadroaplicaciones');
    });
   /// Route::get('/apertura/{aperturaId}/edit', [AperturaController::class, 'edit'])->name('apertura.edit');
    /// usar el aperturaController si hay futuros problemas en el manejo de layouts y uso de rutas con livewire
});