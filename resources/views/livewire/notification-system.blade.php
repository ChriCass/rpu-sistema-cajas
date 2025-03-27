<div 
    class="fixed top-4 right-4 z-50 space-y-4"
    x-data="{ notifications: @entangle('notifications').live }"
    @notificationAdded.window="$wire.$refresh()"
    @showNotification.window="
        console.log('Evento showNotification recibido', $event.detail);
        notifications.push($event.detail);
    "
>
    <!-- Imprimir para depuración -->
    <div x-show="false">
        <pre x-text="JSON.stringify(notifications, null, 2)"></pre>
    </div>
    
    <template x-for="(notification, index) in notifications" :key="notification.id">
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            x-init="setTimeout(() => { 
                show = false; 
                setTimeout(() => $wire.removeNotification(notification.id), 300);
            }, notification.duration || 5000)"
            :class="{
                'rounded-lg shadow-lg p-4 max-w-sm w-full': true,
                'bg-red-50 border-l-4 border-red-500': notification.type === 'error',
                'bg-green-50 border-l-4 border-green-500': notification.type === 'success',
                'bg-yellow-50 border-l-4 border-yellow-500': notification.type === 'warning',
                'bg-blue-50 border-l-4 border-blue-500': notification.type === 'info'
            }"
        >
            <div class="flex items-start">
                <div class="flex-shrink-0 text-2xl" x-text="
                    notification.type === 'error' ? '😿' :
                    notification.type === 'success' ? '😺' :
                    notification.type === 'warning' ? '😾' : '😸'
                "></div>
                <div class="ml-3">
                    <p :class="{
                        'text-sm font-medium': true,
                        'text-red-800': notification.type === 'error',
                        'text-green-800': notification.type === 'success',
                        'text-yellow-800': notification.type === 'warning',
                        'text-blue-800': notification.type === 'info'
                    }" x-text="notification.message"></p>
                </div>
                <button 
                    @click="show = false; setTimeout(() => $wire.removeNotification(notification.id), 300);"
                    class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex items-center justify-center h-8 w-8"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </template>
</div> 