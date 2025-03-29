<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Mostrar la página principal de gráficos
     */
    public function graficoVentas()
    {
        return view('reportes.grafico-ventas');
    }
}
