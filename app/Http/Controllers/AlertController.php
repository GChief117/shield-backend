<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlertController extends Controller
{
    /**
     * List all active alerts
     */
    public function index()
    {
        $alerts = Alert::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        return response()->json($alerts);
    }

    /**
     * Dismiss an alert
     */
    public function destroy($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->delete();

        Log::info('Alert dismissed', ['alert_id' => $id, 'type' => $alert->type]);

        return response()->json([
            'message' => 'Alert dismissed successfully'
        ]);
    }
}
