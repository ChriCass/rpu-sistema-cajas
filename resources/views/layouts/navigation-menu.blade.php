<!-- Admin Section -->
@if(auth()->user()->hasRole('admin'))
    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
        <x-jet-nav-link href="{{ route('register-admin') }}" :active="request()->routeIs('register-admin')">
            {{ __('Registrar Usuario') }}
        </x-jet-nav-link>
        <x-jet-nav-link href="{{ route('admin.users') }}" :active="request()->routeIs('admin.users')">
            {{ __('Gestión de Usuarios') }}
        </x-jet-nav-link>
    </div>
@endif 