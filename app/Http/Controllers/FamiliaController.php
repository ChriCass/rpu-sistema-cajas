<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Familia;
class FamiliaController extends Controller
{
    public function edit($id)
    {
        $familia = Familia::findOrFail($id);
        return view('logistica.edit-familia', compact('familia'));
    }

}
