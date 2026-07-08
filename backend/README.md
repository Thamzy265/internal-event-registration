# Backend — Internal Event Registration API

Laravel 13 API for the event registration app. Exposes event CRUD plus `register` / `cancel` actions that atomically adjust `registration_count`.

## Requirements

- PHP 8.3+
- Composer
- SQLite (default) or any Laravel-supported database

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
```

To seed a test user:

```bash
php artisan db:seed
```

## Run

```bash
php artisan serve
```

The full dev stack (server, queue listener, log tail, Vite) is also wired up:

```bash
composer dev
```

## Structure

- `app/Http/Controllers/Api/EventController.php` — resource controller plus `register` / `cancel`
- `app/Http/Requests/EventRequest.php` — validation for store/update
- `app/Models/Event.php` — `name`, `event_date`, `registration_count`
- `app/Traits/ApiResponse.php` — `{ message, data }` envelope for JSON responses
- `routes/api.php` — API routes
- `database/migrations/` — schema
- `database/factories/EventFactory.php` — test/seed factory

## Endpoints

| Method | Path                          | Description                     |
| ------ | ----------------------------- | ------------------------------- |
| GET    | `/api/events`                 | List events (ordered by date)   |
| POST   | `/api/events`                 | Create event                    |
| GET    | `/api/events/{id}`            | Show event                      |
| PUT    | `/api/events/{id}`            | Update event                    |
| DELETE | `/api/events/{id}`            | Delete event                    |
| POST   | `/api/events/{id}/register`   | Increment registration count    |
| POST   | `/api/events/{id}/cancel`     | Decrement, or 400 if already 0  |

`register` uses an atomic `increment()`. `cancel` runs inside a DB transaction with `lockForUpdate` so the count cannot drop below zero under concurrent requests.

## Tests

Feature tests cover validation, register/cancel behaviour, the zero-floor rule for cancellations, and 404 handling.

```bash
php artisan test
```
