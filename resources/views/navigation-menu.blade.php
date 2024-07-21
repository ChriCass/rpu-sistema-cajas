<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <nav class="bg-white" x-data="{ open: false }">
                    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                        <div class="relative flex items-center justify-between h-16">
                            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                                <!-- Mobile menu button-->
                                <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                                    <span class="sr-only">Open main menu</span>
                                    <svg :class="{'block': !open, 'hidden': open }" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    <svg :class="{'block': open, 'hidden': !open }" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                                <div class="hidden sm:block sm:ml-6">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('dashboard') }}" wire:navigate class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                        <div x-data="{ dropdownOpen: false }" class="relative">
                                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                                                Tesorería
                                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div x-show="dropdownOpen" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" x-cloak>
                                                <a href="{{ route('movimientos') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Movimientos</a>
                                                <a href="{{ route('aplicaciones') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Aplicaciones</a>
                                            </div>
                                        </div>
                                        <div x-data="{ dropdownOpen: false }" class="relative">
                                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                                                Pendientes
                                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div x-show="dropdownOpen" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" x-cloak>
                                                <a href="{{ route('cxc') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CXC</a>
                                                <a href="{{ route('cxp') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CXP</a>
                                            </div>
                                        </div>
                                        <div x-data="{ dropdownOpen: false }" class="relative">
                                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                                                Productos
                                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div x-show="dropdownOpen" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" x-cloak>
                                                <a href="{{ route('familias') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Familias</a>
                                                <a href="{{ route('subfamilias') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Subfamilias</a>
                                                <a href="{{ route('detalle') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Detalle</a>
                                                <a href="{{ route('producto') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Producto</a>
                                            </div>
                                        </div>
                                        <div x-data="{ dropdownOpen: false }" class="relative">
                                            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                                                Configuracion
                                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div x-show="dropdownOpen" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" x-cloak>
                                                <a href="{{ route('familias') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cuentas</a>
                                                <a href="{{ route('subfamilias') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Entidades</a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-show="open" class="sm:hidden mobile-menu-bg fixed inset-0 z-20" x-cloak>
                        <div @click.away="open = false" class="flex flex-col justify-center h-full bg-white p-4 space-y-1">
                            <div class="flex justify-end">
                                <button @click="open = false" type="button" class="p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                    <span class="sr-only">Close menu</span>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <a href="{{ route('dashboard') }}" wire:navigate class="text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                            <div x-data="{ dropdownOpen: false }" class="relative">
                                <button @click="dropdownOpen = !dropdownOpen" class="text-gray-900 block px-3 py-2 rounded-md text-base font-medium flex items-center">
                                    Tesorería
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="dropdownOpen" class="mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" x-cloak>
                                    <a href="{{ route('movimientos') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Movimientos</a>
                                    <a href="{{ route('aplicaciones') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Aplicaciones</a>
                                </div>
                            </div>
                             
                        </div>
                    </div>
                </nav>
                
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
