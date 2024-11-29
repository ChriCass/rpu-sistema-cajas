<div>
   
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __(ucfirst(str_replace('.', ' ', request()->route()->getName()))) }}
    </h2>
    
        
    </x-slot>
  
    <div class="container mx-auto p-4">
      <x-card>
          <!-- Flecha de regresar en la esquina superior derecha -->
          <div class="flex justify-end mb-6">
              <a href="{{ route('dashboard') }}" wire:navigate class="group flex items-center space-x-2">
                  <div
                      class="bg-teal-500 p-3 rounded-full shadow-md transition-all duration-300 transform group-hover:bg-teal-600 group-hover:scale-105">
                      <svg xmlns="http://www.w3.org/2000/svg"
                          class="h-6 w-6 text-white transition-transform duration-300 group-hover:translate-x-1"
                          fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                  </div>
                  <span class="text-teal-600 font-bold transition-colors duration-300 group-hover:text-teal-700">
                      Regresar
                  </span>
              </a>
          </div>

          <!-- Flexbox principal para organizar elementos -->
          <div class="flex flex-wrap -mx-4">
              <div class="w-full md:w-1/3 px-4 mb-6">
                <x-select label="Año" placeholder="Selecc." :options="$años" wire:model="año" />
                <x-select label="Mes" placeholder="Selecc." :options="$meses" wire:model="mes"
                    option-label="descripcion" option-value="id" />
              </div>

              <div class="w-full md:w-1/3 flex flex-col items-center px-4 mb-6">
                  <button wire:click="procesarReporte"
                      class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out flex items-center space-x-2 mb-4">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                          style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                          <path
                              d="M20 8l-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM9 19H7v-9h2v9zm4 0h-2v-6h2v6zm4 0h-2v-3h2v3zM14 9h-1V4l5 5h-4z">
                          </path>
                      </svg>
                      <span>Procesar</span>
                  </button>
                  <div class="bg-white border border-teal-500 rounded-lg p-6 shadow-md w-full max-w-full mx-auto">
                    <h3 class="text-teal-600 font-bold text-lg mb-6">Resumen de Totales</h3>
                    <div class="flex flex-nowrap gap-4 items-center justify-between">
                        <div class="flex-1 text-center">
                            <p class="text-gray-700 font-bold">BAS IMP:</p>
                            <p class="text-gray-700 font-normal">{{ number_format($totales['basImp'] ?? 0, 2, '.', ',') ?? 0.00 }}</p>
                        </div>
                        <div class="flex-1 text-center">
                            <p class="text-gray-700 font-bold">IGV:</p>
                            <p class="text-gray-700 font-normal">{{ number_format($totales['IGV'] ?? 0, 2, '.', ',') ?? 0.00 }}</p>
                        </div>
                        <div class="flex-1 text-center">
                            <p class="text-gray-700 font-bold">NO GRA:</p>
                            <p class="text-gray-700 font-normal">{{ number_format($totales['NoGravado'] ?? 0, 2, '.', ',') ?? 0.00 }}</p>
                        </div>
                        <div class="flex-1 text-center">
                            <p class="text-gray-700 font-bold">OTR TRI:</p>
                            <p class="text-gray-700 font-normal">{{ number_format($totales['Otri'] ?? 0, 2, '.', ',') ?? 0.00 }}</p>
                        </div>
                        <div class="flex-1 text-center">
                            <p class="text-gray-700 font-bold">PRECIO:</p>
                            <p class="text-gray-700 font-normal">{{ number_format($totales['precio'] ?? 0, 2, '.', ',') ?? 0.00 }}</p>
                        </div>
                    </div>
                </div>
              </div>
          </div>


          <div class="flex justify-center space-x-4 mt-6">
              <!-- Botón de Exportar en PDF -->
              <button wire:click="exportarPDF"
                  class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.362 5.214A4 4 0 1012 2v4h4a4.002 4.002 0 00-.638-4.786zM9 5v4a4 4 0 11-4-4h4zm5 8h3a3 3 0 013 3v5H3v-5a3 3 0 013-3h3m6 0v-2a6 6 0 00-12 0v2m12 0v2a6 6 0 0112 0v-2z" />
                  </svg>
                  <span>Exportar en PDF</span>
              </button>
          
              <!-- Botón de Exportar en Excel 
              <button wire:click="exportarCentroCostos"
                  class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 17l-4-4 4-4m-4 4H4" />
                  </svg>
                  <span>Exportar en Excel</span> -->
              </button>
          </div>
          

          <div class="mt-8 overflow-x-auto">
              @if (session()->has('message'))
                  <x-alert title="¡Éxito!" positive class="mb-3">
                      {{ session('message') }}
                  </x-alert>
              @endif

              @if (session()->has('error'))
                  <x-alert title="¡Error!" negative>
                      <x-slot name="slot" class="italic mb-5">
                          {{ session('error') }}
                      </x-slot>
                  </x-alert>
              @endif

              <table class="min-w-full bg-white border border-gray-300">
                  <thead class="bg-gray-200">
                      <tr>
                        <th class="px-4 py-2 border-b">FECHA EMISION</th>
                        <th class="px-4 py-2 border-b">FECHA VENCIMIENTO</th>
                        <th class="px-4 py-2 border-b">TIPO DE DOCUMENTO</th>
                        <th class="px-4 py-2 border-b">SERIE</th>
                        <th class="px-4 py-2 border-b">NUMERO</th>
                        <th class="px-4 py-2 border-b">TIPO DE IDENTIDAD</th>
                        <th class="px-4 py-2 border-b">ID ENTIDAD</th>
                        <th class="px-4 py-2 border-b">ENTIDAD</th>
                        <th class="px-4 py-2 border-b">TASA</th>
                        <th class="px-4 py-2 border-b">BAS IMP</th>
                        <th class="px-4 py-2 border-b">IGV</th>
                        <th class="px-4 py-2 border-b">NO GRAVADAS</th>
                        <th class="px-4 py-2 border-b">OTROS TRIBUTOS</th>
                        <th class="px-4 py-2 border-b">PRECIO</th>
                        <th class="px-4 py-2 border-b">DETRACCION</th>
                        <th class="px-4 py-2 border-b">MONTO NETO</th>
                        <th class="px-4 py-2 border-b">MONEDA</th>
                        <th class="px-4 py-2 border-b">TIPO DOC REFERENCIA</th>
                        <th class="px-4 py-2 border-b">SERIE REFERENCIA</th>
                        <th class="px-4 py-2 border-b">NUMERO REFERENCIA</th>
                        <th class="px-4 py-2 border-b">OBSERVACIONES</th>
                      </tr>
                  </thead>
                  <tbody>
                    @if(!empty($registros) && $registros->count())
                        @foreach ($registros as $registro)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $registro->fechaEmi }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->fechaVen }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->tdoc }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->serie }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->numero }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->id_t02tcom }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->id_entidades }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->rz }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->tasa }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($registro->basImp ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($registro->IGV ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($registro->noGravadas ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($registro->otroTributo ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($registro->precio ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($registro->detraccion ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b">{{ number_format($registro->montoNeto ?? 0, 2, '.', ',') }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->id_t04tipmon }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->descripcion }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->serieMod }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->numeroMod }}</td>
                            <td class="px-4 py-2 border-b">{{ $registro->observaciones }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="px-4 py-2 border-b text-center">No hay movimientos disponibles</td>
                        </tr>
                    @endif
                  </tbody>
              </table>
          </div>
      </x-card>


  </div>
 

</div>
