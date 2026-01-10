<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncidentController extends Controller
{
    /**
     * List all incidents
     */
    public function index()
    {
        $incidents = Incident::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($incidents);
    }

    /**
     * Get a specific incident
     */
    public function show($id)
    {
        $incident = Incident::findOrFail($id);
        return response()->json($incident);
    }

    /**
     * Create a new incident
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'required|in:low,medium,high,critical',
            'type' => 'required|string',
        ]);

        $incident = Incident::create([
            ...$validated,
            'status' => 'open',
        ]);

        Log::info('Incident created', ['incident_id' => $incident->id, 'title' => $incident->title]);

        return response()->json($incident, 201);
    }

    /**
     * Update an incident
     */
    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'severity' => 'sometimes|in:low,medium,high,critical',
            'status' => 'sometimes|in:open,investigating,resolved,closed',
        ]);

        $incident->update($validated);

        Log::info('Incident updated', ['incident_id' => $id, 'changes' => $validated]);

        return response()->json($incident);
    }

    /**
     * Delete/resolve an incident
     */
    public function destroy($id)
    {
        $incident = Incident::findOrFail($id);
        $incident->delete();

        Log::info('Incident resolved/deleted', ['incident_id' => $id]);

        return response()->json([
            'message' => 'Incident resolved successfully'
        ]);
    }
}
