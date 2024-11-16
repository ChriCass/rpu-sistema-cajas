<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AperturaEditParent;
use App\Livewire\RegistroDocumentosIngreso;
use App\Livewire\RegistroDocumentosEgreso;
use App\Livewire\VaucherPagoCompras;
use App\Livewire\VaucherPagoVentas;
use App\Livewire\CuadroAplicaciones;
use App\Livewire\RegistroCxp;
use App\Livewire\FormRegistroCxp;
use App\Livewire\RegistroCxc;
use App\Livewire\FormRegistroCxc;
use App\Livewire\EditRegistroDocumentosEgreso;
use App\Livewire\EditRegistroDocumentosIngreso;
use App\Livewire\EditVaucherDePago;
use App\Livewire\EditVaucherDePagoVentas;
use App\Livewire\RegistroDeIngresoAvanz;
use App\Livewire\AplicacionDetail;
use App\Livewire\EdRegistroDocumentosCxc;


use App\Livewire\MatrizDeCobrosView;
use App\Livewire\MatrizDePagosView;
use App\Livewire\ReporteCajaView;
use App\Livewire\ReporteCajaXMesView;
use App\Livewire\ReporteCajaXAnioView;
use App\Livewire\ResultadoPorCentroDeCostos;

use App\Livewire\RegistroGeneralAvanz;

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

    Route::prefix('pendientes')->group(function () {
        Route::get('/cxc', function () {
            return view('deudas.documentos-cxc', ['routeName' => 'cxc']);
        })->name('cxc');
    
        Route::get('/cxc/avanzado', RegistroGeneralAvanz::class)->name('cxc.avanzado');
    
        Route::get('/cxp', function () {
            return view('deudas.documentos-cxp', ['routeName' => 'cxp']);
        })->name('cxp');
    
        Route::get('/cxp/avanzado', RegistroGeneralAvanz::class)->name('cxp.avanzado');
    });
    
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

    Route::get('/configuracion/entidades', function () {
        return view('configuracion.entidades', ['routeName' => 'entidades']);
    })->name('entidades');


    Route::prefix('apertura/{aperturaId}/edit')->group(function () {
        Route::get('/', AperturaEditParent::class)->name('apertura.edit');
        Route::get('/avanzado',  RegistroGeneralAvanz::class)->name('apertura.avanzado');
     
     

    });
   /// Route::get('/apertura/{aperturaId}/edit', [AperturaController::class, 'edit'])->name('apertura.edit');
    /// usar el aperturaController si hay futuros problemas en el manejo de layouts y uso de rutas con livewire

    Route::prefix('aplicaciones/{aplicacionesId}')->group(function (){
        Route::get('/', AplicacionDetail::class)->name('aplicacion.show');
    });

    Route::get('/reportes/matriz-cobros', MatrizDeCobrosView::class)->name('reportes.matriz.cobros');
    Route::get('/reportes/matriz-pagos', MatrizDePagosView::class)->name('reportes.matriz.pagos');

    Route::get('/reportes/reporte-caja', ReporteCajaView::class)->name('reportes.reporte.caja');
    Route::get('/reportes/reporte-caja-mes', ReporteCajaXMesView::class)->name('reportes.reporte.caja.mes');
    Route::get('/reportes/reporte-caja-anio', ReporteCajaXAnioView::class)->name('reportes.reporte.caja.anio');
    Route::get('/reportes/Resultado-Por-Centro-De-Costos', ResultadoPorCentroDeCostos::class)->name('resultado.por.centro.de.costos');

 
});