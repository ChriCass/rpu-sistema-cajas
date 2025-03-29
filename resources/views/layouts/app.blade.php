<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/caja-registradora.png') }}" type="image/png">

    @php
        // Obtener el nombre de la ruta actual
        $routeName = Route::currentRouteName();

        // Si la ruta tiene un nombre, formatearla de forma legible
        if ($routeName) {
            // Reemplazar guiones bajos con espacios, y capitalizar cada palabra
            $pageTitle = ucwords(str_replace('_', ' ', $routeName));
        } else {
            // Si la ruta no tiene nombre, usar el nombre de la aplicación por defecto
            $pageTitle = config('app.name', 'Laravel');
        }
    @endphp
    <title>Sistema de Cajas - {{ $pageTitle }}</title>
    <style>
        svg.h-5.w-5.text-pg-primary-300.mr-2.dark\:text-pg-primary-200 {
            display: none;
        }

        div.pointer-events-none.absolute.inset-y-0.right-0.flex.items-center.px-2.text-pg-primary-700.dark\:text-pg-primary-300 {
            display: none;
        }
    </style>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Sistema de Notificaciones -->
        <livewire:notification-system />
    </div>

    @stack('modals')

    @wireUiScripts
    @livewire('livewire-ui-modal')
    @livewireScripts
    
    @stack('scripts')
</body>

</html>
