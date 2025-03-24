<div>
    @if (session()->has('message'))
        <x-alert title="Éxito!" positive>
            {{ session('message') }}
        </x-alert>
    @elseif (session()->has('error'))
        <x-alert title="Error!" negative>
            {{ session('error') }}
        </x-alert>
    @endif

    <x-card title="Editar Usuario">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="updateUser">
            <!-- Campo ID (solo lectura) -->
            <div class="w-full sm:w-3/12 px-2 mb-3">
                <x-input label="ID" wire:model="userId" readonly />
            </div>

            <!-- Nombre -->
            <div class="w-full sm:w-8/12 px-4 mb-3">
                <x-input 
                    label="Nombre" 
                    wire:model="name" 
                />
                @error('name')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="w-full px-4 mb-3">
                <x-input 
                    label="Email" 
                    wire:model="email" 
                    type="email"
                />
                @error('email')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Roles -->
            <div class="w-full px-4 mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                <select 
                    wire:model="selectedRole" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="">Seleccionar Rol</option>
                    @foreach ($availableRoles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('selectedRole')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                <x-button flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button primary type="submit" class="ml-2" label="Guardar Cambios" />
            </div>
        </form>
    </x-card>
</div>
