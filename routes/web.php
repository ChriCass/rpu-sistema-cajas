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

    Route::get('/configuracion/entidades', function () {
        return view('configuracion.entidades', ['routeName' => 'entidades']);
    })->name('entidades');


    Route::prefix('apertura/{aperturaId}/edit')->group(function () {
        Route::get('/', AperturaEditParent::class)->name('apertura.edit');
        
        Route::get('/registodocumentosingreso/avanzado', RegistroDeIngresoAvanz::class)->name('apertura.edit.registodocumentosingreso.avanzado');
      /** 
        Route::get('/registodocumentosingreso/edit', EditRegistroDocumentosIngreso::class)->name('apertura.edit.editregistodocumentosingreso');
        Route::get('/registodocumentosegreso', RegistroDocumentosEgreso::class)->name('apertura.edit.registodocumentosegreso');
        Route::get('/registodocumentosegreso/edit', EditRegistroDocumentosEgreso::class)->name('apertura.edit.editregistodocumentosegreso');
        Route::get('/vaucherdepagos', VaucherPagoCompras::class)->name('apertura.edit.vaucherdepagos');
        Route::get('/vaucherdepagos/edit', EditVaucherDePago::class)->name('apertura.edit.editvaucherdepagos');
        Route::get('/vaucherdepagos/registrocxp', RegistroCxp::class)->name('apertura.edit.vaucherdepagos.registrocxp');
        Route::get('/vaucherdepagos/registrocxp/nuevo', FormRegistroCxp::class)->name('apertura.edit.vaucherdepagos.registrocxp.formregistrocxp');
        Route::get('/vaucherdepagosventas', VaucherPagoVentas::class)->name('apertura.edit.vaucherdepagosventas');
        Route::get('/vaucherdepagosventas/edit', EditVaucherDePagoVentas::class)->name('apertura.edit.editvaucherdepagosventas');
        Route::get('/vaucherdepagosventas/registrocxc', RegistroCxc::class)->name('apertura.edit.vaucherdepagos.registrocxc');
        Route::get('/vaucherdepagosventas/registrocxc/nuevo', FormRegistroCxc::class)->name('apertura.edit.vaucherdepagos.registrocxp.formregistrocxc');
        Route::get('/cuadroaplicaciones', CuadroAplicaciones::class)->name('apertura.edit.cuadroaplicaciones'); 
        Route::get('/registodocumentosingreso', RegistroDocumentosIngreso::class)->name('apertura.edit.registodocumentosingreso');
        */

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

 
});