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

    <x-card title="Nuevo Usuario">
        <form class="flex flex-wrap justify-center -mx-4" wire:submit.prevent="createUser">
            <!-- Nombre -->
            <div class="w-full px-4 mb-3">
                <x-input 
                    label="Nombre" 
                    wire:model="name" 
                    placeholder="Ingrese el nombre completo"
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
                    placeholder="Ingrese el correo electrónico"
                />
                @error('email')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="w-full sm:w-6/12 px-4 mb-3">
                <x-input 
                    label="Contraseña" 
                    wire:model="password" 
                    type="password"
                    placeholder="Mínimo 8 caracteres"
                />
                @error('password')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div class="w-full sm:w-6/12 px-4 mb-3">
                <x-input 
                    label="Confirmar Contraseña" 
                    wire:model="password_confirmation" 
                    type="password"
                    placeholder="Confirme la contraseña"
                />
            </div>

            <!-- Roles -->
            <div class="w-full px-4 mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                <div class="border rounded-md p-3 bg-gray-50">
                    @foreach ($availableRoles as $role)
                        <label class="inline-flex items-center mr-4 mb-2">
                            <input 
                                type="checkbox" 
                                value="{{ $role->id }}" 
                                wire:model="roles" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            >
                            <span class="ml-2">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botones -->
            <div class="mt-4 flex justify-end w-full px-4">
                <x-button flat label="Cancelar" wire:click="$dispatch('closeModal')" />
                <x-button primary type="submit" class="ml-2" label="Crear Usuario" />
            </div>
        </form>
    </x-card>
</div>
