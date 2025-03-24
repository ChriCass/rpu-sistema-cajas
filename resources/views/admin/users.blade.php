<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @livewire('user-table')
                </div>
            </div>
        </div>
    </div>

    <!-- Notificación de éxito -->
    <div id="notification" 
         class="fixed top-4 right-4 bg-green-500 text-white rounded-lg p-4 shadow-lg transform transition-transform duration-300 translate-x-full opacity-0" 
         style="z-index: 9999; transform: translateX(100%);">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p id="notification-message">Usuario actualizado correctamente</p>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', function() {
            Livewire.on('show-notification', (event) => {
                const notification = document.getElementById('notification');
                const message = document.getElementById('notification-message');
                
                // Actualizar el mensaje
                message.textContent = event.message;
                
                // Mostrar la notificación
                notification.classList.remove('translate-x-full');
                notification.classList.remove('opacity-0');
                notification.style.transform = 'translateX(0)';
                
                // Ocultar después de 3 segundos
                setTimeout(function() {
                    notification.classList.add('translate-x-full');
                    notification.classList.add('opacity-0');
                    notification.style.transform = 'translateX(100%)';
                }, 3000);
            });
        });
    </script>
</x-app-layout> 