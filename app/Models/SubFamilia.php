<?php
 namespace App\Models;

 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 class SubFamilia extends Model
 {
     use HasFactory;
 
     // Especifica el nombre de la tabla incluyendo el esquema
     protected $table = 'Logistica.subfamilias';
 
     // Especifica los campos que pueden ser llenados masivamente
     protected $fillable = ['id_familias', 'id', 'desripcion' ];
 
     // Desactiva las timestamps si no existen en la tabla
     public $timestamps = false;
 
     // Ensure the primary key and foreign keys are treated as strings
     protected $casts = [
        'id_familias' => 'string',
        'id_subfamilia' => 'string',
    ];

     // Define las relaciones
     public function familia()
     {
         return $this->belongsTo(Familia::class, 'id_familias', 'id');
     }
 
     public function detalles()
     {
         return $this->hasMany(Detalle::class, 'id_subfamilia', 'id');
     }
 }
 
