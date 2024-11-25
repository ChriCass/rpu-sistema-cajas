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
                          <th class="px-4 py-2 border-b">FAMILIA</th>
                          <th class="px-4 py-2 border-b">SUBFAMILIA</th>
                          <th class="px-4 py-2 border-b">DETALLE</th>
                          <th class="px-4 py-2 border-b">ENERO</th>
                          <th class="px-4 py-2 border-b">FEBRERO</th>
                          <th class="px-4 py-2 border-b">MARZO</th>
                          <th class="px-4 py-2 border-b">ABRIL</th>
                          <th class="px-4 py-2 border-b">MAYO</th>
                          <th class="px-4 py-2 border-b">JUNIO</th>
                          <th class="px-4 py-2 border-b">JULIO</th>
                          <th class="px-4 py-2 border-b">AGOSTO</th>
                          <th class="px-4 py-2 border-b">SETIEMBRE</th>
                          <th class="px-4 py-2 border-b">OCTUBRE</th>
                          <th class="px-4 py-2 border-b">NOVIEMBRE</th>
                          <th class="px-4 py-2 border-b">DICIEMBRE</th>
                      </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="px-4 py-2 border-b"> aqui </td>
                      <td class="px-4 py-2 border-b"> ira </td>
                      <td class="px-4 py-2 border-b"> el bucle</td>
                      <td class="px-4 py-2 border-b"> que consideres</td>
                      <td class="px-4 py-2 border-b"> adecuado </td>
                      <td class="px-4 py-2 border-b"> para </td>
                      <td class="px-4 py-2 border-b"> este  </td>
                      <td class="px-4 py-2 border-b">reporte</td>
                      <td class="px-4 py-2 border-b"> </td>
                      <td class="px-4 py-2 border-b"> </td>
                      <td class="px-4 py-2 border-b"> </td>
                      <td class="px-4 py-2 border-b"> </td>
                      <td class="px-4 py-2 border-b"> </td>
                      <td class="px-4 py-2 border-b"> </td>
                      <td class="px-4 py-2 border-b"> </td>
                  </tr>
                  </tbody>
              </table>
          </div>
      </x-card>


  </div>
 

</div>
