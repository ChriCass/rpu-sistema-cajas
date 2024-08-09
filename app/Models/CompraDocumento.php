<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraDocumento extends Model
{
    use HasFactory;

      // Define el nombre de la tabla si no sigue la convención de pluralización
      protected $table = 'compras_documentos';

      // Si la clave primaria no es 'id' o no es autoincremental, especifica su nombre y tipo
      protected $primaryKey = 'id';
  
      // Desactiva la autoincrementalidad si la clave primaria no es un entero autoincremental
      public $incrementing = true;
  
      // Define el tipo de la clave primaria si no es un entero
      protected $keyType = 'int';
  
      // Desactiva timestamps si no tienes columnas `created_at` y `updated_at`
      public $timestamps = false;
  
      // Define qué columnas son asignables en masa (fillable)
      protected $fillable = [
          'id_detalle',
          'fechaEmi',
          'fechaVen',
          'id_t10tdoc',
          'id_t02tcom',
          'id_entidades',
          'id_t04tipmon',
          'id_tasasIgv',
          'serie',
          'numero',
          'totalBi',
          'descuentoBi',
          'recargoBi',
          'basImp',
          'IGV',
          'totalNg',
          'descuentoNg',
          'recargoNg',
          'noGravadas',
          'otroTributo',
          'precio',
          'detraccion',
          'montoNeto',
          'id_t10tdocMod',
          'serieMon',
          'observaciones',
          'numeroMod',
          'id_Usuario',
          'fecha_Registro',
          'id_dest_tipcaja'
      ];
  
      // Define las columnas que deberían ser tratadas como fechas
      protected $dates = [
          'fechaEmi',
          'fechaVen',
          'fecha_Registro',
      ];
  
}
