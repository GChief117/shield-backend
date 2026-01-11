# Shield Cybersecurity Dashboard - Backend

Laravel 11 PHP Backend API for the Shield Cybersecurity Monitoring Dashboard.

This respository is used to have them be hosted on Railway:
<img width="947" height="1090" alt="Screenshot 2026-01-10 at 10 03 35 PM" src="https://github.com/user-attachments/assets/502d58b8-855a-40d2-8f26-e7b3c266395e" />


<img width="701" height="66" alt="Screenshot 2026-01-10 at 10 07 36 PM" src="https://github.com/user-attachments/assets/cd7b24a7-1392-4ee2-bb4c-cf1a7d36239e" />



## Setup Instructions

### Step 1: Download and place backend folder
Download the `backend` folder and place it in the same directory as the frontend.
```bash
cd backend
```

### Step 2: Install dependencies
```bash
composer install
```

### Step 3: Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Create database
```bash
touch database/database.sqlite
```

### Step 5: Run migrations and seed data
```bash
php artisan migrate --seed
```

### Step 6: Start the server
```bash
php artisan serve --port=8000
```

## Default Login
- Email: `operator@shield.io`
- Password: `demo123`

## API Endpoints

### Authentication
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/auth/login` | Login and receive token | No |
| POST | `/api/auth/logout` | Logout and invalidate token | Yes |
| GET | `/api/auth/me` | Get current authenticated user | Yes |

### Dashboard
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/dashboard/stats` | Get dashboard statistics | Yes |

### Threats
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/threats` | List all threats | Yes |
| GET | `/api/threats/{id}` | Get threat details | Yes |
| POST | `/api/threats/{id}/resolve` | Resolve a threat | Yes |
| DELETE | `/api/threats/{id}` | Delete a threat | Yes |

### Systems
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/systems` | List all systems | Yes |
| GET | `/api/systems/{id}` | Get system details | Yes |

### Incidents
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/incidents` | List all incidents | Yes |
| GET | `/api/incidents/{id}` | Get incident details | Yes |
| POST | `/api/incidents` | Create incident | Yes |
| PATCH | `/api/incidents/{id}` | Update incident | Yes |
| DELETE | `/api/incidents/{id}` | Resolve/delete incident | Yes |

### Alerts
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/alerts` | List all alerts | Yes |
| DELETE | `/api/alerts/{id}` | Dismiss alert | Yes |

### Simulation (Development Only)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/simulate/threat` | Generate random threat | No |
| POST | `/api/simulate/alert` | Generate random alert | No |
| POST | `/api/simulate/incident` | Generate random incident | No |
| POST | `/api/simulate/random` | Generate random event | No |
| POST | `/api/simulate/systems` | Seed systems | No |

## Project Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Authentication logic
│   │   │   ├── DashboardController.php  # Dashboard stats
│   │   │   ├── ThreatController.php     # Threat CRUD
│   │   │   ├── SystemController.php     # System monitoring
│   │   │   ├── IncidentController.php   # Incident management
│   │   │   ├── AlertController.php      # Alert handling
│   │   │   └── SimulationController.php # Demo data generation
│   │   └── Middleware/
│   │       └── Cors.php                 # CORS handling
│   └── Models/
│       ├── User.php
│       ├── Threat.php
│       ├── System.php
│       ├── Incident.php
│       └── Alert.php
├── artisan                              # Laravel CLI entry point
├── bootstrap/
│   └── app.php                          # Application bootstrap
├── composer.json
├── composer.lock
├── config/
│   ├── app.php
│   ├── cors.php                         # CORS configuration
│   └── sanctum.php                      # API authentication
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_threats_table.php
│   │   ├── create_systems_table.php
│   │   ├── create_incidents_table.php
│   │   └── create_alerts_table.php
│   └── seeders/
│       └── DatabaseSeeder.php           # Demo data
├── public/
│   └── index.php                        # Web entry point
├── routes/
│   ├── api.php                          # API routes
│   ├── web.php                          # Web routes
│   └── console.php                      # Console commands
├── storage/
│   └── logs/
│       └── laravel.log                  # Activity logs
├── .env.example                         # Environment template
└── vendor/                              # Composer dependencies
```

## Frontend-Backend Coordination

**Frontend (Vue.js)**
- Vue components render the UI—threat panels, system health, incident logs
- User actions trigger the API service (`api.js`), which sends HTTP requests via Axios
- Pinia stores manage state (current user, threats, alerts) so components stay in sync

**Backend (Laravel)**
- Receives requests from frontend, processes them, returns JSON responses
- Routes in `api.php` map URLs to controller methods (e.g., `/api/threats` → `ThreatController@index`)
- Controllers handle validation, permissions, and business logic—models handle database access

**Database & External Resources**
- Models map to database tables (`Threat.php` → `threats` table, etc.)
- Laravel Resources transform model data into consistent JSON responses
- Laravel Sanctum manages token-based authentication on protected routes

**Threat Simulation (Demo Data)**
- `SimulationController` generates randomized security events for demo purposes
- Frontend calls `/api/simulate/threat`, `/api/simulate/alert`, or `/api/simulate/incident` to create fake data
- Each simulation randomly assigns severity levels, threat types, timestamps, and affected systems
- No authentication required on simulation routes—useful for testing the dashboard without real data

**Authentication Flow**
- User submits credentials via the login form, frontend sends POST to `/api/auth/login`
- `AuthController` validates credentials against the `users` table using Laravel's Hash facade
- On success, Sanctum generates a bearer token and returns it to the frontend
- Frontend stores the token in localStorage and attaches it to all subsequent requests via Axios interceptor
- Protected routes check for valid token via `auth:sanctum` middleware—invalid tokens return 401

## Authentication

The backend uses Laravel Sanctum for API token authentication.

### Login Flow
1. Frontend sends POST to `/api/auth/login` with credentials
2. Backend validates and returns a token
3. Frontend stores token in Pinia store
4. All subsequent requests include `Authorization: Bearer {token}` header
5. Backend middleware validates token on protected routes

### Frontend Token Handling (api.js)
```javascript
// Axios interceptor attaches token to all requests
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

## Logging

All significant actions are logged to `storage/logs/laravel.log`:

- Incident created/updated/resolved
- Alert dismissed
- Threat generated/resolved
- Systems seeded
- Authentication events

View logs:
```bash
tail -f storage/logs/laravel.log
```

## Environment Variables

Key `.env` settings:

```env
APP_NAME=Shield
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost

# CORS - Allow Vue.js frontend
CORS_ALLOWED_ORIGINS=http://localhost:5173
```

## Troubleshooting

### CORS Errors
Ensure `config/cors.php` includes your frontend URL:
```php
'allowed_origins' => ['http://localhost:5173'],
```

### Missing Routes
If routes aren't loading, ensure `bootstrap/app.php` includes:
```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
)
```

### Database Errors
Reset the database:
```bash
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate:fresh --seed
```

### Cache Issues
Clear all caches:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## License

MIT License
