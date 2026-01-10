<?php

namespace App\Http\Controllers;

use App\Models\Threat;
use App\Models\System;
use App\Models\Incident;
use App\Models\Alert;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        $activeThreats = Threat::where('status', 'active')->count();
        $criticalThreats = Threat::where('severity', 'critical')->where('status', 'active')->count();
        $systemsOnline = System::where('status', 'online')->count();
        $totalSystems = System::count();
        $openIncidents = Incident::whereIn('status', ['open', 'investigating'])->count();
        $activeAlerts = Alert::where('status', 'active')->count();

        return response()->json([
            'threats' => [
                'active' => $activeThreats,
                'critical' => $criticalThreats,
            ],
            'systems' => [
                'online' => $systemsOnline,
                'total' => $totalSystems,
                'health_percentage' => $totalSystems > 0 ? round(($systemsOnline / $totalSystems) * 100) : 0,
            ],
            'incidents' => [
                'open' => $openIncidents,
            ],
            'alerts' => [
                'active' => $activeAlerts,
            ],
        ]);
    }
}
