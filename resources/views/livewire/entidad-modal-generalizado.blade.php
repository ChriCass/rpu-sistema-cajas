<div>
    <x-modal name="persistentModal" wire:model="openModal" persistent>
        <x-card title="Registro de entidades">
            <div class="flex flex-wrap justify-center -mx-4">
                @if (session()->has('message'))
                    <x-alert title="Felicidades!" positive>
                        {{ session('message') }}
                    </x-alert>
                @elseif (session()->has('error'))
                    <x-alert title="Error!" negative>
                        {{ session('error') }}
                    </x-alert>
                @endif

                <!-- Toggle para desconocer tipo de documento -->
                <div class="w-full sm:w-12/12 px-4 mb-3">
                    <x-toggle label="Desconozco tipo documento" wire:model.live="desconozcoTipoDocumento" />
                </div>

                <!-- Fila con select, input y botón -->
                <div class="w-full sm:w-12/12 px-4 flex gap-2 mb-3">
                    <!-- Select tipo documento -->
                    <div class="w-3/12">
                        <x-select label="Tipo documento" placeholder="Seleccione..."
                                  :options="$docs" option-label="abreviado" option-value="id" :disabled="$desconozcoTipoDocumento" 
                                  wire:model.live="tipoDocId" />
                        @error('tipoDocId')
                            <x-alert title="Error!" negative>
                                {{ $message }}
                            </x-alert>
                        @enderror
                    </div>

                    <!-- Input documento -->
                    <div class="w-6/12">
                        <x-input wire:model.live="docIdent" 
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1').slice(0, 11)" 
                                label="Documento de Identidad"
                                class="w-full" 
                                :disabled="$desconozcoTipoDocumento" />
                        @error('docIdent')
                            <x-alert title="Error!" negative>
                                {{ $message }}
                            </x-alert>
                        @enderror
                    </div>

                    <!-- Botón buscar -->
                    <div class="w-3/12">
                        <x-button wire:click="processDocIdent" :disabled="$desconozcoTipoDocumento" class="w-full bg-teal-600 hover:bg-teal-700 focus:ring-teal-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center gap-2 mt-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            <span>Buscar</span>
                        </x-button>
                    </div>
                </div>

                <!-- Input de nueva entidad -->
                <div class="w-full sm:w-12/12 px-4">
                    <x-input wire:model.live="entidad" 
                            label="Nueva entidad" 
                            class="w-full" 
                            :disabled="$desconozcoTipoDocumento1" 
                            oninput="this.value = this.value.toUpperCase()" />
                    @error('entidad')
                        <x-alert title="Error!" negative>
                            {{ $message }}
                        </x-alert>
                    @enderror
                </div>
            
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button wire:click="$set('openModal', false)" class="bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Cancelar</span>
                    </x-button>
                    <x-button wire:click='submitEntidad' class="bg-teal-600 hover:bg-teal-700 focus:ring-teal-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Aceptar</span>
                    </x-button>
                </x-slot>
            </div>
        </x-card>
    </x-modal>
</div>

<script>
    Livewire.on('close-modal', () => {
        setTimeout(() => {
            @this.set('openModal', false);
        }, 5000);
    });
</script> 