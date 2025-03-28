<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos Pendientes</title>
    <style>
        /* Estilos base */
        body { font-family: 'Figtree', Arial, sans-serif; line-height: 1.6; color: #374151; background-color: #f3f4f6; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px 8px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background-color: #f9fafb; color: #0d9488; font-weight: 600; }
        .header { background-color: #0d9488; color: white; padding: 20px; }
        .content { padding: 20px; }
        .footer { background-color: #f9fafb; padding: 15px; font-size: 12px; color: #6b7280; text-align: center; }
        .monto { text-align: right; }
        .vencido { color: #dc2626; font-weight: bold; }
        .documento-id { font-weight: bold; color: #0d9488; }
        h2 { margin-top: 0; font-weight: 700; font-size: 20px; }
        p { margin-bottom: 16px; }
        
        /* Estilos responsive */
        @media screen and (max-width: 600px) {
            .container { width: 100% !important; max-width: 100% !important; }
            .content, .header { padding: 15px !important; }
            h2 { font-size: 18px !important; }
            
            /* Adaptación de tabla para móviles */
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { position: absolute; top: -9999px; left: -9999px; }
            tr { border: 1px solid #e5e7eb; margin-bottom: 10px; }
            td { border: none; border-bottom: 1px solid #eee; position: relative; padding-left: 50% !important; text-align: right !important; }
            td:before { position: absolute; top: 12px; left: 8px; width: 45%; white-space: nowrap; content: attr(data-label); font-weight: 600; text-align: left; color: #0d9488; }
            
            /* Ajustes adicionales para la tabla en móviles */
            .monto { text-align: right !important; }
            tfoot th { text-align: right !important; padding-right: 8px !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>REPORTE DE DOCUMENTOS PENDIENTES</h2>
            <p>Fecha del reporte: {{ date('d/m/Y H:i') }}</p>
        </div>
        
        <div class="content">
            <p>A continuación se detallan los documentos pendientes registrados en el sistema:</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Tipo Doc.</th>
                        <th>Serie</th>
                        <th>Número</th>
                        <th>Cliente</th>
                        <th>Fecha Venc.</th>
                        <th>Estado</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($data['documentos'] as $doc)
                        @php 
                            $vencido = \Carbon\Carbon::parse($doc->fechaVen)->isPast();
                            $total += $doc->monto;
                            $nombreEntidad = isset($data['entidades'][$doc->id_entidades]) 
                                ? $data['entidades'][$doc->id_entidades]->descripcion 
                                : 'No especificado';
                                
                            // Obtener tipo de documento
                            $tipoDoc = isset($data['tiposDocumento'][$doc->id_t10tdoc]) 
                                ? $data['tiposDocumento'][$doc->id_t10tdoc]->descripcion 
                                : 'No especificado';
                        @endphp
                        <tr class="{{ $vencido ? 'vencido' : '' }}">
                            <td data-label="Tipo Doc.">{{ $tipoDoc }}</td>
                            <td data-label="Serie">{{ $doc->serie }}</td>
                            <td data-label="Número">{{ $doc->numero }}</td>
                            <td data-label="Cliente">{{ $nombreEntidad }}</td>
                            <td data-label="Fecha Venc.">{{ \Carbon\Carbon::parse($doc->fechaVen)->format('d/m/Y') }}</td>
                            <td data-label="Estado">{{ $vencido ? 'VENCIDO' : 'PENDIENTE' }}</td>
                            <td data-label="Monto" class="monto">S/ {{ number_format($doc->monto, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" style="text-align: right;">TOTAL PENDIENTE:</th>
                        <th class="monto">S/ {{ number_format($total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
            
            <p>Este es un correo automático generado por el sistema. Por favor no responda a este mensaje.</p>
        </div>
        
        <div class="footer">
            <p>Sistema de Cajas &copy; {{ date('Y') }} - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html> 