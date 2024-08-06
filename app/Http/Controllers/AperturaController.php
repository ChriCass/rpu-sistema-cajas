<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class AperturaController extends Controller
{
    public function edit($aperturaId)
    {
        return view('apertura.edit', compact('aperturaId'));
    }
}
