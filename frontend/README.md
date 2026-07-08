# Frontend — Internal Event Registration

Next.js 16 app that lists events served by the Laravel backend and lets users register or cancel per event.

## Requirements

- Node.js 20+
- The [backend](../backend) running (defaults to `http://127.0.0.1:8000`)

## Setup

```bash
npm install
```

Optionally override the API base URL:

```bash
# .env.local
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000
```

## Run

```bash
npm run dev       # start dev server on http://localhost:3000
npm run build     # production build
npm run start     # serve production build
npm run lint      # eslint
```

## Structure

- `app/page.tsx` — server component that fetches and renders the event list
- `app/components/event-card.tsx` — client component with register / cancel buttons
- `lib/api/events.ts` — API client (`fetchEvents`, `eventAction`)
- `lib/types.ts` — `EventItem` and `ApiResponse<T>` shapes

The event list is rendered on the server; register/cancel actions run on the client and update the visible count from the API's response.
