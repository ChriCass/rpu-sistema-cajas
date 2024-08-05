<?php

namespace App\View\Components;
use WireUi\Components\TextField\Maskable;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class CustomMaskable extends Maskable
{
    protected function getInputMask(): array
    {
        return [
            'mask' => 'AAAAAAAAAAAAAAAAAA', // Permitir hasta 18 caracteres
            'tokens' => [
                'A' => [
                    'pattern' => '[A-Z\s]', // Solo letras mayúsculas y espacios
                    'transform' => function($v) { return strtoupper($v); }
                ]
            ]
        ];
    }
}
