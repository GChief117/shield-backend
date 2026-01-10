<?php

namespace App\Http\Controllers;

use App\Models\Threat;
use App\Models\Alert;
use App\Models\Incident;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SimulationController extends Controller
{
    private $threatTypes = [
        'Malware Detected',
        'Intrusion Attempt',
        'DDoS Attack',
        'Phishing Attempt',
        'Unauthorized Access',
        'Data Exfiltration',
        'Ransomware',
        'SQL Injection',
        'XSS Attack',
        'Brute Force Attack'
    ];

    private $alertTypes = [
        'Firewall Breach',
        'Anomalous Traffic',
        'Failed Login Attempts',
        'Certificate Expiring',
        'High CPU Usage',
        'Memory Threshold Exceeded',
        'Disk Space Low',
        'Service Unavailable',
        'Configuration Change',
        'New Device Detected'
    ];

    private $incidentTypes = [
        'Security Breach',
        'System Outage',
        'Data Leak',
        'Policy Violation',
        'Compliance Issue',
        'Hardware Failure',
        'Network Disruption',
        'Authentication Failure'
    ];

    private $severities = ['low', 'medium', 'high', 'critical'];

    private $systems = [
        ['name' => 'Primary Firewall', 'type' => 'firewall'],
        ['name' => 'Core Router', 'type' => 'network'],
        ['name' => 'Database Server', 'type' => 'server'],
        ['name' => 'Web Application Server', 'type' => 'server'],
        ['name' => 'Authentication Service', 'type' => 'service'],
        ['name' => 'Backup System', 'type' => 'storage'],
        ['name' => 'Load Balancer', 'type' => 'network'],
        ['name' => 'VPN Gateway', 'type' => 'security'],
        ['name' => 'Monitoring System', 'type' => 'service'],
        ['name' => 'Email Server', 'type' => 'server']
    ];

    /**
     * Generate a random threat
     */
    public function generateThreat()
    {
        $threat = Threat::create([
            'type' => $this->threatTypes[array_rand($this->threatTypes)],
            'severity' => $this->severities[array_rand($this->severities)],
            'source_ip' => $this->randomIp(),
            'target_system' => $this->systems[array_rand($this->systems)]['name'],
            'status' => 'active',
            'description' => 'Automatically generated threat for simulation purposes.',
        ]);

        Log::info('Simulated threat generated', ['threat_id' => $threat->id, 'type' => $threat->type]);

        return response()->json($threat, 201);
    }

    /**
     * Generate a random alert
     */
    public function generateAlert()
    {
        $alert = Alert::create([
            'type' => $this->alertTypes[array_rand($this->alertTypes)],
            'severity' => $this->severities[array_rand($this->severities)],
            'source' => $this->systems[array_rand($this->systems)]['name'],
            'status' => 'active',
            'message' => 'Automatically generated alert for simulation purposes.',
        ]);

        Log::info('Simulated alert generated', ['alert_id' => $alert->id, 'type' => $alert->type]);

        return response()->json($alert, 201);
    }

    /**
     * Generate a random incident
     */
    public function generateIncident()
    {
        $type = $this->incidentTypes[array_rand($this->incidentTypes)];
        
        $incident = Incident::create([
            'title' => $type . ' - ' . now()->format('H:i:s'),
            'type' => $type,
            'severity' => $this->severities[array_rand($this->severities)],
            'status' => 'open',
            'description' => 'Automatically generated incident for simulation purposes.',
        ]);

        Log::info('Simulated incident generated', ['incident_id' => $incident->id, 'type' => $incident->type]);

        return response()->json($incident, 201);
    }

    /**
     * Generate a random event (threat, alert, or incident)
     */
    public function generateRandom()
    {
        $choice = rand(1, 3);
        
        switch ($choice) {
            case 1:
                return $this->generateThreat();
            case 2:
                return $this->generateAlert();
            case 3:
                return $this->generateIncident();
        }
    }

    /**
     * Seed systems if none exist
     */
    public function seedSystems()
    {
        if (System::count() > 0) {
            return response()->json([
                'message' => 'Systems already seeded',
                'count' => System::count()
            ]);
        }

        foreach ($this->systems as $system) {
            System::create([
                'name' => $system['name'],
                'type' => $system['type'],
                'status' => rand(1, 10) > 2 ? 'online' : 'offline',
                'ip_address' => $this->randomIp(),
                'last_seen' => now(),
            ]);
        }

        Log::info('Systems seeded', ['count' => count($this->systems)]);

        return response()->json([
            'message' => 'Systems seeded successfully',
            'count' => count($this->systems)
        ], 201);
    }

    /**
     * Generate a random IP address
     */
    private function randomIp()
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 254);
    }
}
