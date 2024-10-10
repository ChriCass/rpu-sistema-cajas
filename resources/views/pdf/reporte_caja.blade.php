<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Caja</title>
    <style>
        @media print {
            @page {
                size: A3 landscape; /* Orientación horizontal */
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
            height: 50px; /* Ajusta el tamaño del logo según necesites */
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

        th, td {
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
            <h2>Reporte de Caja</h2>
            <p><strong>Año:</strong> {{ $año }} | <strong>Mes:</strong> {{ $mes }} | <strong>Caja:</strong> {{ $id_caja }}</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID DOCUMENTOS</th>
                        <th>FAMILIA</th>
                        <th>SUBFAMILIA</th>
                        <th>DETALLE</th>
                        <th>ENTIDAD</th>
                        <th>NUMERO</th>
                        <th>MONTO</th>
                        <th>GLOSA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movimientos as $movimiento)
                        <tr>
                            <td>{{ $movimiento->id_documentos }}</td>
                            <td>{{ $movimiento->familia_descripcion ?? 'MOVIMIENTOS' }}</td>
                            <td>{{ $movimiento->subfamilia_descripcion ?? '' }}</td>
                            <td>{{ $movimiento->detalle_descripcion ?? '' }}</td>
                            <td>{{ $movimiento->descripcion ?? '' }}</td>
                            <td>{{ $movimiento->numero ?? '' }}</td>
                            <td>{{ number_format($movimiento->monto, 2) }}</td>
                            <td>{{ $movimiento->glosa }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <p><strong>SALDO INICIAL:</strong> {{ number_format($saldo_inicial, 2) }}</p>
            <p><strong>VARIACIÓN:</strong> {{ number_format($variacion, 2) }}</p>
            <p><strong>SALDO FINAL:</strong> {{ number_format($saldo_final, 2) }}</p>
        </div>

        <div class="note">
            <p>Reporte generado automáticamente. Verifique la información antes de su uso oficial.</p>
        </div>
    </div>
</body>
</html>
