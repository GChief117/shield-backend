<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ThreatController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\SimulationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

/*
|--------------------------------------------------------------------------
| Protected Routes (require authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Threats
    Route::get('/threats', [ThreatController::class, 'index']);
    Route::get('/threats/{id}', [ThreatController::class, 'show']);
    Route::post('/threats/{id}/resolve', [ThreatController::class, 'resolve']);
    Route::delete('/threats/{id}', [ThreatController::class, 'destroy']);
    
    // Systems
    Route::get('/systems', [SystemController::class, 'index']);
    Route::get('/systems/{id}', [SystemController::class, 'show']);
    
    // Incidents
    Route::get('/incidents', [IncidentController::class, 'index']);
    Route::get('/incidents/{id}', [IncidentController::class, 'show']);
    Route::post('/incidents', [IncidentController::class, 'store']);
    Route::patch('/incidents/{id}', [IncidentController::class, 'update']);
    Route::delete('/incidents/{id}', [IncidentController::class, 'destroy']);
    
    // Alerts
    Route::get('/alerts', [AlertController::class, 'index']);
    Route::delete('/alerts/{id}', [AlertController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Simulation Routes (no auth required - for demo purposes)
|--------------------------------------------------------------------------
*/
Route::prefix('simulate')->group(function () {
    Route::post('/threat', [SimulationController::class, 'generateThreat']);
    Route::post('/alert', [SimulationController::class, 'generateAlert']);
    Route::post('/incident', [SimulationController::class, 'generateIncident']);
    Route::post('/random', [SimulationController::class, 'generateRandom']);
    Route::post('/systems', [SimulationController::class, 'seedSystems']);
});
