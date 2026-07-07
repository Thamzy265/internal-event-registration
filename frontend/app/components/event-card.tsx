"use client";

import { eventsApi } from "@/lib/api/events";
import { EventItem } from "@/lib/types";
import { useState } from "react";

export default function EventCard({ event }: { event: EventItem }) {
  const [loading, setLoading] = useState<"register" | "cancel" | null>(null);
  const [count, setCount] = useState(event.registration_count);
  const [error, setError] = useState<string | null>(null);

  const handleCount = async (action: "register" | "cancel") => {
    setLoading(action);
    setError(null);

    try {
      const updated = await eventsApi.eventAction(event.id, action);

      setCount(updated.registration_count);
    } catch (err) {
      console.error(`Error ${action} for event:`, err);
      setError(`Couldn't ${action} — please try again.`);
    } finally {
      setLoading(null);
    }
  };

  return (
    <div key={event.id} className="border border-zinc-300 p-4">
      <h2 className="text-xl font-bold text-zinc-800">{event.name}</h2>
      <p className="text-zinc-600">Date: {event.event_date}</p>
      <p className="text-zinc-600">Registrations: {count}</p>
      <div className="mt-2 flex gap-2">
        <button
          onClick={() => handleCount("register")}
          disabled={loading !== null}
          className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50 cursor-pointer"
        >
          Register
        </button>
        <button
          onClick={() => handleCount("cancel")}
          disabled={loading !== null || count <= 0}
          className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50 cursor-pointer"
        >
          Cancel
        </button>
      </div>

      {error && <p className="text-red-500 text-sm mt-1">{error}</p>}
    </div>
  );
}
