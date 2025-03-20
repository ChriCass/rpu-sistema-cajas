<div>
    @if (session()->has('error'))
    <x-alert title="Error!" negative class="mb-3">
        {{ session('error') }}
    </x-alert>
    @elseif ((session()->has('message')))
    <x-alert title="Success" positive padding="small">
        {{ session('message') }}
    </x-alert>
    @endif

    <div class="flex flex-col items-center mt-6 mb-4">
        <button
            x-data="{ loading: false }"
            x-on:click="loading = true; $wire.Plantilla().then(() => loading = false)"
            :class="loading ? 'bg-yellow-500' : 'bg-blue-500 hover:bg-blue-600'"
            class="relative inline-flex items-center px-6 py-3 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-75 transition-colors duration-300"
            :disabled="loading"
        >
            <!-- Icono antes del texto -->
            <template x-if="!loading">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </template>
            <template x-if="loading">
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16M12 4v16"></path>
                </svg>
            </template>
            <!-- Texto del botón -->
            <span x-text="loading ? 'Procesando...' : 'Descarga la plantilla para procesar la información'"></span>
        </button>
    
        <!-- Pie de nota -->
        <p class="mt-4 text-sm text-gray-600 text-center">
            La plantilla ayuda a hacer el procesamiento y se recomienda <strong>NO</strong> modificar su orden. 
            Por favor, verifique su información antes de procesarla o subirla.
        </p>
    </div>
    
    
    
    <div x-data="{ fileName: '' }" class="flex flex-col items-center justify-center w-full">
        <label for="file-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-400 rounded-lg cursor-pointer hover:border-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!fileName">
                <svg class="w-10 h-10 mb-3 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 2C7.895 2 7 2.895 7 4V5H5C4.447 5 4 5.447 4 6V18C4 18.553 4.447 19 5 19H9.235L10 20.765L10.765 19H19C19.553 19 20 18.553 20 18V8L15 3H9ZM14 10V7.5L17.5 11H15C14.447 11 14 10.553 14 10ZM9 7H11V9H9V7ZM13 7V9H11V7H13ZM9 10H11V12H9V10ZM13 10H11V12H13V10ZM9 13H11V15H9V13ZM13 13H11V15H13V13ZM15 13V12H17.293L14 8.707V10C14 10.553 14.447 11 15 11H17L15 13Z" />
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Click para subir un archivo Excel</span> 
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">XLS, XLSX hasta 10MB</p>
            </div>
            
            <div x-show="fileName" class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-10 h-10 mb-3 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 2C7.895 2 7 2.895 7 4V5H5C4.447 5 4 5.447 4 6V18C4 18.553 4.447 19 5 19H9.235L10 20.765L10.765 19H19C19.553 19 20 18.553 20 18V8L15 3H9ZM14 10V7.5L17.5 11H15C14.447 11 14 10.553 14 10ZM9 7H11V9H9V7ZM13 7V9H11V7H13ZM9 10H11V12H9V10ZM13 10H11V12H13V10ZM9 13H11V15H9V13ZM13 13H11V15H13V13ZM15 13V12H17.293L14 8.707V10C14 10.553 14.447 11 15 11H17L15 13Z" />
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="fileName"></p>
            </div>
    
            <input id="file-upload" type="file" accept=".xls,.xlsx" class="hidden" wire:model="excelFile" @change="fileChosen">
        </label>
   
    </div>
    
    <div class="flex justify-center mt-5">
        <x-button wire:click="Procesar" teal label="Procesar" />
    </div>
</div>

<script>
    function fileChosen(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            this.fileName = input.files[0].name;
        }
    }
</script>