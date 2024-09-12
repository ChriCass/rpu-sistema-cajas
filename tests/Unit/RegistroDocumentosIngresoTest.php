<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\RegistroDocumentosIngreso;
use App\Models\User;
use App\Models\Documento;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistroDocumentosIngresoTest extends TestCase
{
    use RefreshDatabase;
    public function test_component_renders_correctly()
    {
        // Check that the component renders correctly
        Livewire::test('registro-documentos-ingreso')
            ->assertSee('T. Doc:');
    }

    public function test_can_submit_form()
    {
        // Test form submission with valid data
        Livewire::test('registro-documentos-ingreso')
            ->set('tipoDocumento', '74')
            ->set('tipoDocDescripcion', 'Factura')
            ->set('serieNumero1', '0001')
            ->set('serieNumero2', '12345')
            ->set('docIdent', '20606566558')
            ->set('monedaId', 'PEN')
            ->set('tasaIgvId', '1')
            ->set('baseImponible', 100)
            ->call('submit')
            ->assertSee('Documento registrado con éxito.');
    }
}
