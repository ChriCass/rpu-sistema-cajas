<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait WithNotifications
{
    public function notify($type, $message, $duration = 5000)
    {
        // Agregar log para verificar la llamada al método notify
        \Illuminate\Support\Facades\Log::info('Método notify() invocado', [
            'type' => $type,
            'message' => $message,
            'duration' => $duration,
            'component' => get_class($this),
            'session_id' => session()->getId()
        ]);

        // Guardar la notificación en la sesión
        $notifications = session()->get('notifications', []);
        $notificationId = Str::random(10);
        $notifications[] = [
            'id' => $notificationId,
            'type' => $type,
            'message' => $message,
            'duration' => $duration
        ];
        session()->flash('notifications', $notifications);
        
        \Illuminate\Support\Facades\Log::info('Notificación guardada en sesión', [
            'notifications_count' => count($notifications),
            'session_id' => session()->getId()
        ]);

        // También disparar el evento para notificaciones inmediatas
        try {
            \Illuminate\Support\Facades\Log::info('Intentando disparar evento notify', [
                'id' => $notificationId
            ]);
            
            $this->dispatch('notify', [
                'id' => $notificationId,
                'type' => $type,
                'message' => $message,
                'duration' => $duration
            ]);
            
            \Illuminate\Support\Facades\Log::info('Evento notify disparado exitosamente');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al disparar el evento notify', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function getNotifications()
    {
        return session()->get('notifications', []);
    }

    public function clearNotifications()
    {
        session()->forget('notifications');
    }
} 