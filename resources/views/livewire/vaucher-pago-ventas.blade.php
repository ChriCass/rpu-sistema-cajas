<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vaucher de pago ventas
        </h2>
    </x-slot>

    <div class="p-4">
        <x-card title="VaucherDePago">
          <div class="flex flex-wrap -mx-2 mt-4">
            <div class="w-full md:w-4/12 px-2">
              <x-input readonly label="Fecha:" wire:model="fechaApertura" />
            </div>
            <div class="w-full md:w-4/12 px-2">
              <x-select readonly label="Moneda:" placeholder="Selecciona..." :options="[]" option-label="descripcion" option-value="id" />
            </div>
            <div class="w-full md:w-4/12 px-2 flex items-center justify-end">
              <x-button label="Nuevo" />
            </div>
          </div>
          <div class="flex flex-wrap -mx-2 mt-4">
            <div class="w-full flex justify-end gap-5 px-2">
              <div class="space-x-2">
                <x-button label="Registro CXC" />
                <x-button label="Ingreso" />
                <x-button label="Gasto" />
              </div>
              <div class="space-x-2 flex items-center">
                <x-input readonly label="" value="" />
                <x-input readonly label="" value="" />
              </div>
            </div>
          </div>
          <div class="overflow-x-auto mt-5">
            <table class="min-w-full bg-white">
              <thead>
                <tr>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Id</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">T.doc</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Entidades</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Descripcion</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Num</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Moneda</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Cuenta</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Monto</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Debe</th>
                  <th class="py-2 px-4 bg-gray-200 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Haber</th>
                </tr>
              </thead>
              <tbody>
                <!-- Aquí puedes agregar filas de datos -->
              </tbody>
            </table>
          </div>
          <div class="flex justify-end mt-4 space-x-2">
            <x-button label="Cancelar" wire:navigate outline secondary href="{{ route('apertura.edit', ['aperturaId' => $aperturaId]) }}" />
            <x-button label="Aceptar" primary />
          </div>
        </x-card>
      </div>
</div>
