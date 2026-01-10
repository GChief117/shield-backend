<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    /**
     * List all systems
     */
    public function index()
    {
        $systems = System::orderBy('name')->get();
        return response()->json($systems);
    }

    /**
     * Get a specific system
     */
    public function show($id)
    {
        $system = System::findOrFail($id);
        return response()->json($system);
    }
}
