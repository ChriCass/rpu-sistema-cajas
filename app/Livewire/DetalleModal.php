<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Familia;
use App\Models\SubFamilia;
use App\Models\Cuenta;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\Detalle;
use Livewire\Attributes\On;
use App\Models\Producto;


class DetalleModal extends Component
{
    public $openModal = false;
    public $cuentas = [];
    public $familias = [];
    public $subfamilias = [];
    public $familia_id = null;
    public $subfamilia_id = null;
    public $cuenta_id;
    public $nuevo_producto;
    public $nuevo_id;

    protected $rules = [
        'familia_id' => 'required',
        'subfamilia_id' => 'required',
        'nuevo_producto' => 'required|string|max:255',
        'cuenta_id' => 'required',
    ];

    protected $messages = [
        'nuevo_producto.required' => 'El campo descripción es obligatorio.',
        'nuevo_producto.max' => 'La descripción no puede tener más de 255 caracteres.',
        'familia_id.required' => 'Debe seleccionar un tipo de familia.',
        'subfamilia_id.required' => 'Debe seleccionar un tipo de subfamilia.',
        'cuenta_id.required' => 'Debe seleccionar un tipo de cuenta',
    ];

    public function mount()
    {
        // Cargar las cuentas y familias al iniciar el componente
        $this->cuentas = Cuenta::all();
        $this->familias = Familia::all();
        $this->subfamilias = collect();  // Inicialmente vacío
    }

    // Cuando la familia cambia, actualizamos las subfamilias correspondientes
    public function updatedFamiliaId($value)
    {
        $this->subfamilias = SubFamilia::where('id_familias', $value)->get();
        $this->subfamilia_id = null;  // Resetear la subfamilia seleccionada
    }

    public function insertNewProducto()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $fam = (string) $this->familia_id;
            $subfam = str_pad((string) $this->subfamilia_id, 3, '0', STR_PAD_LEFT);  // Asegura que subfamilia tenga 3 caracteres

            Log::info('Valores de familia y subfamilia', ['fam' => $fam, 'subfam' => $subfam]);

            // Obtener el último ID existente para la combinación de familia y subfamilia
            $lastId = Detalle::lockForUpdate()
                ->where('id_familias', $fam)
                ->where('id_subfamilia', $subfam)
                ->orderByRaw('CAST(id AS UNSIGNED) DESC')
                ->value('id');

            Log::info('Último ID obtenido', ['lastId' => $lastId]);

            if ($lastId) {
                $newIdNumber = (int) substr($lastId, strlen($fam . $subfam)) + 1;
                $this->nuevo_id = $fam . $subfam . str_pad($newIdNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $this->nuevo_id = $fam . $subfam . "001";
            }

            Log::info('Nuevo ID generado', ['nuevo_id' => $this->nuevo_id]);

            // Crear el nuevo producto en la tabla detalle
            Detalle::create([
                'id_familias' => $this->familia_id,
                'id_subfamilia' => $subfam,
                'id' => $this->nuevo_id,
                'descripcion' => $this->nuevo_producto,
                'id_cuenta' => $this->cuenta_id,
            ]);

                // Generar un código hexadecimal único
        do {
            $codigoHex = strtoupper(str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT));

            // Verificar si el código ya existe en la tabla 'productos'
            $existeCodigo = Producto::where('id', $codigoHex)->exists();
            Log::info('Verificación de código hexadecimal', ['codigoHex' => $codigoHex, 'existe' => $existeCodigo]);
        } while ($existeCodigo);  // Sigue generando códigos hasta encontrar uno único
            $descripcion = 'GENERAL';
            Producto::create([
                'id' => $codigoHex,
                'id_detalle' => $this->nuevo_id,
                'descripcion' =>  $descripcion,
            ]);

            Log::info('Producto insertado en la tabla producto', ['id' => $codigoHex, 'id_detalle' => $this->nuevo_id]);

            DB::commit();

            // Emitir un evento para refrescar la tabla
            $this->dispatch('producto-created');

            // Limpiar los campos después de crear el producto
            $this->reset(['familia_id', 'subfamilia_id', 'nuevo_id', 'nuevo_producto', 'cuenta_id']);
            session()->flash('message', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear el producto', ['exception' => $e]);
            session()->flash('error', 'Ocurrió un error al crear el producto.');
        }
    }
 

    public function render()
    {
         
        return view('livewire.detalle-modal'  );
    }
}
