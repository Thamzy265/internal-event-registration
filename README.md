# Internal Event Registration

A small full-stack app for listing internal events and letting people register or cancel their registration. The registration count is tracked per event and updated atomically so counts stay correct under concurrent traffic.

## Stack

- **Backend** — Laravel 13, PHP 8.3, SQLite, Sanctum (`backend/`)
- **Frontend** — Next.js 16, React 19, TypeScript, Tailwind CSS 4 (`frontend/`)

## Layout

```
backend/    Laravel API — event CRUD + register/cancel endpoints
frontend/   Next.js app — event list UI with register/cancel actions
```

## Getting started

Run the backend and frontend in two terminals.

**Backend** (`http://127.0.0.1:8000`)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

**Frontend** (`http://localhost:3000`)

```bash
cd frontend
npm install
npm run dev
```

The frontend reads the API base URL from `NEXT_PUBLIC_API_URL` and falls back to `http://127.0.0.1:8000`.

## API

All responses are wrapped as `{ message, data }`.

| Method | Path                          | Description                     |
| ------ | ----------------------------- | ------------------------------- |
| GET    | `/api/events`                 | List events (ordered by date)   |
| POST   | `/api/events`                 | Create event                    |
| GET    | `/api/events/{id}`            | Show event                      |
| PUT    | `/api/events/{id}`            | Update event                    |
| DELETE | `/api/events/{id}`            | Delete event                    |
| POST   | `/api/events/{id}/register`   | Increment registration count    |
| POST   | `/api/events/{id}/cancel`     | Decrement (blocked at zero)     |

Cancellation runs inside a DB transaction with `lockForUpdate` so the count cannot drop below zero under concurrent requests.

## Tests

```bash
cd backend
php artisan test
```
