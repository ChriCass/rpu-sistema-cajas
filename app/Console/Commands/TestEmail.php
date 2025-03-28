<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {to? : Dirección de correo del destinatario}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía un correo de prueba para verificar la configuración';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Obtener la dirección de correo del argumento o usar un valor por defecto
            $to = $this->argument('to') ?: 'test@test.com';
            
            $this->info("Enviando correo de prueba a: {$to}");
            
            Mail::raw('Este es un correo de prueba desde el Sistema de Cajas. Si ves este mensaje, la configuración de correo está funcionando correctamente.', function($message) use ($to) {
                $message->to($to)
                        ->subject('Prueba de Configuración de SMTP');
            });

            $this->info('✅ Correo enviado correctamente a través de Hostinger. Por favor, verifica la bandeja de entrada del destinatario.');
        } catch (\Exception $e) {
            $this->error('❌ Error al enviar el correo: ' . $e->getMessage());
        }
    }
}
