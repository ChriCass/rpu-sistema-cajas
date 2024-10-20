<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Caja</title>
    <style>
        @media print {
            @page {
                size: A3 landscape;
                /* Orientación horizontal */
                margin: 15mm;
            }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 2px solid #14b8a6;
            padding-bottom: 10px;
        }

        .header img {
            height: 50px;
            /* Ajusta el tamaño del logo según necesites */
        }

        .header h2 {
            flex-grow: 1;
            text-align: center;
            font-size: 1.8rem;
            color: #14b8a6;
        }

        .header p {
            font-size: 1rem;
            color: #555;
            margin-top: 5px;
            text-align: right;
        }

        .table-container {
            margin-top: 10px;
            overflow-x: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        table {
            width: 100%;
            min-width: 1200px;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        thead {
            background-color: #14b8a6;
            color: #fff;
        }

        th,
        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            text-align: left;
            white-space: nowrap;
        }

        th {
            font-weight: 600;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .totals {
            background: #f9fafb;
            border-top: 2px solid #14b8a6;
            padding: 10px 15px;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .totals p {
            margin: 0;
            padding: 5px 0;
        }

        .totals strong {
            color: #14b8a6;
        }

        .note {
            margin-top: 10px;
            font-size: 0.75rem;
            color: #777;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('img/single-logo.png') }}" alt="Logo" style="height: 100px;">
            <!-- Ruta de la imagen del logo -->
            <h2>Resultado <strong>POR CENTRO DE COSTO</strong> </h2>
            <p class="report-date">Reporte exportado en fecha: {{ $fecha_exportacion }}</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>FAMILIA</th>
                        <th>SUBFAMILIA</th>
                        <th>DETALLE</th>
                        <th>ENERO</th>
                        <th>FEBRERO</th>
                        <th>MARZO</th>
                        <th>ABRIL</th>
                        <th>MAYO</th>
                        <th>JUNIO</th>
                        <th>JULIO</th>
                        <th>AGOSTO</th>
                        <th>SETIEMBRE</th>
                        <th>OCTUBRE</th>
                        <th>NOVIEMBRE</th>
                        <th>DICIEMBRE</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($movimientos) && $movimientos->count())
                        @foreach ($movimientos as $movimiento)
                            <tr>
                                <td>{{ $movimiento->familia_descripcion }}</td>
                                <td>{{ $movimiento->subfamilia_descripcion }}</td>
                                <td>{{ $movimiento->detalle_descripcion }}</td>
                                <td>{{ number_format($movimiento->enero ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->febrero ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->marzo ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->abril ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->mayo ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->junio ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->julio ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->agosto ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->septiembre ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->octubre ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->noviembre ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->diciembre ?? 0, 2, '.', ',') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td>TOTAL INGRESOS</td>
                            <td>{{ number_format($totalesIngresos['enero'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['febrero'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['marzo'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['abril'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['mayo'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['junio'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['julio'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['agosto'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['septiembre'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['octubre'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['noviembre'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesIngresos['diciembre'] ?? 0, 2, '.', ',') }}</td>
                        </tr>
                        @foreach ($movimientos1 as $movimiento)
                            <tr>
                                <td>{{ $movimiento->familia_descripcion }}</td>
                                <td>{{ $movimiento->subfamilia_descripcion }}</td>
                                <td>{{ $movimiento->detalle_descripcion }}</td>
                                <td>{{ number_format($movimiento->enero ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->febrero ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->marzo ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->abril ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->mayo ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->junio ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->julio ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->agosto ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->septiembre ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->octubre ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->noviembre ?? 0, 2, '.', ',') }}</td>
                                <td>{{ number_format($movimiento->diciembre ?? 0, 2, '.', ',') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td>TOTAL SALIDAS</td>
                            <td>{{ number_format($totalesEgresos['enero'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['febrero'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['marzo'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['abril'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['mayo'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['junio'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['julio'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['agosto'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['septiembre'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['octubre'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['noviembre'] ?? 0, 2, '.', ',') }}</td>
                            <td>{{ number_format($totalesEgresos['diciembre'] ?? 0, 2, '.', ',') }}</td>

                        </tr>
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td>UTILIDAD/PERDIDA</td>
                            <td>{{ number_format(($totalesIngresos['enero'] ?? 0) + ($totalesEgresos['enero'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['febrero'] ?? 0) + ($totalesEgresos['febrero'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['marzo'] ?? 0) + ($totalesEgresos['marzo'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['abril'] ?? 0) + ($totalesEgresos['abril'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td
                                {{ number_format(($totalesIngresos['mayo'] ?? 0) + ($totalesEgresos['mayo'] ?? 0), 2, '.', ',') }}</td>
                            <td>{{ number_format(($totalesIngresos['junio'] ?? 0) + ($totalesEgresos['junio'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['julio'] ?? 0) + ($totalesEgresos['julio'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['agosto'] ?? 0) + ($totalesEgresos['agosto'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['septiembre'] ?? 0) + ($totalesEgresos['septiembre'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['octubre'] ?? 0) + ($totalesEgresos['octubre'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['noviembre'] ?? 0) + ($totalesEgresos['noviembre'] ?? 0), 2, '.', ',') }}
                            </td>
                            <td>{{ number_format(($totalesIngresos['diciembre'] ?? 0) + ($totalesEgresos['diciembre'] ?? 0), 2, '.', ',') }}
                            </td>

                        </tr>
                    @else
                        <tr>
                            <td colspan="10" class="px-4 py-2 border-b text-center">No hay movimientos disponibles
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>



        <div class="note">
            <p>Reporte generado automáticamente. Verifique la información antes de su uso oficial.</p>
        </div>
    </div>
</body>

</html>
