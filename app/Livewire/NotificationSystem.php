<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class NotificationSystem extends Component
{
    public $notifications = [];

    // Definición explícita de los listeners
    protected $listeners = ['notify', 'showNotification'];

    public function mount()
    {
        Log::info('Componente NotificationSystem montado', [
            'session_id' => session()->getId(),
            'session_notifications' => session()->get('notifications', []),
            'initial_notifications' => $this->notifications
        ]);
        
        // Cargar notificaciones de la sesión al iniciar
        $sessionNotifications = session()->get('notifications', []);
        if (!empty($sessionNotifications)) {
            $this->notifications = $sessionNotifications;
            Log::info('Notificaciones cargadas de la sesión al montar', [
                'count' => count($this->notifications)
            ]);
        }
    }

    // Método explícito para recibir el evento notify
    public function notify($notification)
    {
        Log::info('Evento notify recibido directamente en NotificationSystem', [
            'notification' => $notification
        ]);
        
        $this->notifications[] = $notification;
        
        Log::info('Notificación agregada desde evento notify', [
            'notification_id' => $notification['id'] ?? 'sin ID',
            'new_count' => count($this->notifications)
        ]);
        
        // Forzar actualización de la vista
        $this->dispatch('notificationAdded');
    }

    // Método para recibir el evento showNotification
    public function showNotification($notification)
    {
        Log::info('Evento showNotification recibido en NotificationSystem', [
            'notification' => $notification
        ]);
        
        $this->notifications[] = $notification;
        
        Log::info('Notificación agregada desde evento showNotification', [
            'notification_id' => $notification['id'] ?? 'sin ID',
            'new_count' => count($this->notifications)
        ]);
        
        // Forzar actualización de la vista
        $this->dispatch('notificationAdded');
    }

    public function addNotification($notification)
    {
        Log::info('Método addNotification() invocado en NotificationSystem', [
            'notification' => $notification,
            'existing_notifications_count' => count($this->notifications)
        ]);
        
        $this->notifications[] = $notification;
        
        Log::info('Notificación agregada al componente NotificationSystem', [
            'new_notifications_count' => count($this->notifications),
            'notification_id' => $notification['id'] ?? 'sin ID'
        ]);
        
        // Forzar actualización de la vista
        $this->dispatch('notificationAdded');
    }

    public function removeNotification($id)
    {
        Log::info('Removiendo notificación', ['id' => $id]);
        
        $this->notifications = array_filter($this->notifications, function($notification) use ($id) {
            return $notification['id'] !== $id;
        });
        
        Log::info('Notificación removida', [
            'id' => $id,
            'remaining_count' => count($this->notifications)
        ]);
    }

    public function render()
    {
        Log::info('Renderizando NotificationSystem', [
            'notifications_count' => count($this->notifications)
        ]);
        
        return view('livewire.notification-system');
    }
} 