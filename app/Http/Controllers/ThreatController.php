<?php

namespace App\Http\Controllers;

use App\Models\Threat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ThreatController extends Controller
{
    /**
     * List all threats
     */
    public function index()
    {
        $threats = Threat::orderBy('created_at', 'desc')->get();
        return response()->json($threats);
    }

    /**
     * Get a specific threat
     */
    public function show($id)
    {
        $threat = Threat::findOrFail($id);
        return response()->json($threat);
    }

    /**
     * Resolve a threat
     */
    public function resolve($id)
    {
        $threat = Threat::findOrFail($id);
        $threat->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        Log::info('Threat resolved', ['threat_id' => $id, 'type' => $threat->type]);

        return response()->json([
            'message' => 'Threat resolved successfully',
            'threat' => $threat
        ]);
    }

    /**
     * Delete a threat
     */
    public function destroy($id)
    {
        $threat = Threat::findOrFail($id);
        $threat->delete();

        Log::info('Threat deleted', ['threat_id' => $id]);

        return response()->json([
            'message' => 'Threat deleted successfully'
        ]);
    }
}
