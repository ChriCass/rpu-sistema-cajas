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
use App\Livewire\BalanceCuentaAnalisis;
use App\Livewire\BalanceCuentasView;
use App\Livewire\EdRegistroDocumentosCxc;


use App\Livewire\MatrizDeCobrosView;
use App\Livewire\MatrizDePagosView;
use App\Livewire\ReporteCajaView;
use App\Livewire\ReporteCajaXMesView;
use App\Livewire\ReporteCajaXAnioView;
use App\Livewire\ResultadoPorCentroDeCostos;
use App\Livewire\TraspasoDetail;
use App\Livewire\RegistroGeneralAvanz;
use App\Livewire\ReporteAnaliticoCostoView;
use App\Livewire\ReporteDiarioMatrizView;
use App\Livewire\ReporteInconcistenciasView;
use App\Livewire\ReporteRegistroComprasView;
use App\Livewire\ReporteRegistroVentasView;

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

    Route::get('/tesoreria/traspasos', function () {
        return view('tesoreria.traspasos', ['routeName' => 'traspasos']);
    })->name('traspasos');

    
    Route::get('/importador', function () {
        return view('importadores.importador', ['routeName' => 'importador']);
    })->name('importador');

    Route::prefix('pendientes')->group(function () {
        Route::get('/cxc', function () {
            return view('deudas.documentos-cxc', ['routeName' => 'cxc']);
        })->name('cxc');
    
        Route::get('/cxc/avanzado', RegistroGeneralAvanz::class)->name('cxc.avanzado');
    
        Route::get('/cxp', function () {
            return view('deudas.documentos-cxp', ['routeName' => 'cxp']);
        })->name('cxp');
    
        Route::get('/cxp/avanzado', RegistroGeneralAvanz::class)->name('cxp.avanzado');

        Route::get('/importar', function () {
            return view('deudas.importar', ['routeName' => 'importador-avanzado']);
        })->name('importar');

        Route::get('/borrar-masivo', function () {
            return view('deudas.borrar-masivo', ['routeName' => 'Borrar Masivo']);
        })->name('borrar-masivo');
    });

    Route::get('/importar', function () {
        return view('importadores.importador', ['routeName' => 'importador-general']);
    })->name('importador-general');
    
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

    Route::get('/configuracion/cuentas', function () {
        return view('configuracion.cuentas', ['routeName' => 'cuentas']);
    })->name('cuentas');

    
    Route::get('/configuracion/cajas', function () {
        return view('configuracion.cajas', ['routeName' => 'cajas']);
    })->name('cajas');

    Route::get('/configuracion/centro-costos', function () {
        return view('configuracion.centro-costos', ['routeName' => 'centro-costos']);
    })->name('centro-costos');

    Route::prefix('apertura/{aperturaId}/edit')->group(function () {
        Route::get('/', AperturaEditParent::class)->name('apertura.edit');
        Route::get('/avanzado',  RegistroGeneralAvanz::class)->name('apertura.avanzado');
     
     

    });
   /// Route::get('/apertura/{aperturaId}/edit', [AperturaController::class, 'edit'])->name('apertura.edit');
    /// usar el aperturaController si hay futuros problemas en el manejo de layouts y uso de rutas con livewire

    Route::prefix('aplicaciones/{aplicacionesId}')->group(function (){
        Route::get('/', AplicacionDetail::class)->name('aplicacion.show');
    });

    Route::prefix('traspasos/{traspasoId}')->group(function (){
        Route::get('/', TraspasoDetail::class)->name('traspaso.show');
    }); 
    

    Route::get('/reportes/matriz-cobros', MatrizDeCobrosView::class)->name('reportes.matriz.cobros');
    Route::get('/reportes/matriz-pagos', MatrizDePagosView::class)->name('reportes.matriz.pagos');

    Route::get('/reportes/reporte-caja', ReporteCajaView::class)->name('reportes.reporte.caja');
    Route::get('/reportes/reporte-caja-mes', ReporteCajaXMesView::class)->name('reportes.reporte.caja.mes');
    Route::get('/reportes/reporte-caja-anio', ReporteCajaXAnioView::class)->name('reportes.reporte.caja.anio');
    Route::get('/reportes/Resultado-Por-Centro-De-Costos', ResultadoPorCentroDeCostos::class)->name('resultado.por.centro.de.costos');
    Route::get('/reportes/reporte-registro-compras', ReporteRegistroComprasView::class)->name('reporte.registro.compras');
Route::get('/reportes/reporte-registro-ventas', ReporteRegistroVentasView::class)->name('reporte.registro.ventas');

Route::get('/reportes/balance-cuentas', BalanceCuentasView::class)->name('balance.cuentas');

Route::prefix('/reportes/balance-cuentas/{tipoDeCuenta}')->group(function (){
    Route::get('/', BalanceCuentaAnalisis::class)->name('balance.cuenta.analisis');
}); 




Route::get('/reportes/reporte-analitico-costo', ReporteAnaliticoCostoView::class)->name('reporte.analitico.costo');
Route::get('/reportes/reporte-inconsistencias', ReporteInconcistenciasView::class)->name('reporte.inconsistencias');
Route::get('/reportes/reporte-diario-matriz', ReporteDiarioMatrizView::class)->name('reporte.diario.matriz');
 
});