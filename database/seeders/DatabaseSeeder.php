<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\System;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default operator user
        User::create([
            'name' => 'Operator',
            'email' => 'operator@shield.io',
            'password' => Hash::make('demo123'),
        ]);

        // Seed default systems
        $systems = [
            ['name' => 'Primary Firewall', 'type' => 'firewall', 'status' => 'online'],
            ['name' => 'Core Router', 'type' => 'network', 'status' => 'online'],
            ['name' => 'Database Server', 'type' => 'server', 'status' => 'online'],
            ['name' => 'Web Application Server', 'type' => 'server', 'status' => 'online'],
            ['name' => 'Authentication Service', 'type' => 'service', 'status' => 'online'],
            ['name' => 'Backup System', 'type' => 'storage', 'status' => 'online'],
            ['name' => 'Load Balancer', 'type' => 'network', 'status' => 'online'],
            ['name' => 'VPN Gateway', 'type' => 'security', 'status' => 'online'],
            ['name' => 'Monitoring System', 'type' => 'service', 'status' => 'online'],
            ['name' => 'Email Server', 'type' => 'server', 'status' => 'offline'],
        ];

        foreach ($systems as $system) {
            System::create([
                ...$system,
                'ip_address' => rand(10, 192) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 254),
                'last_seen' => now(),
            ]);
        }
    }
}
