"use client";

import { useState } from "react";

export type EventItem = {
  id: number;
  name: string;
  event_date: string;
  registration_count: number;
};

export default function EventCard({ event }: { event: EventItem }) {
  const [loading, setLoading] = useState<"register" | "cancel" | null>(null);
  const [count, setCount] = useState(event.registration_count);

  const handleRegister = async () => {
    setLoading("register");
    // Simulate API call
    try {
      console.log("Fetching events from API...");
      const res = await fetch(
        `http://127.0.0.1:8000/api/events/${event.id}/register`,
        {
          method: "POST",
        },
      );

      if (!res.ok) {
        throw new Error(`Failed to fetch events: ${res.status}`);
      }

      console.log("Events fetched successfully.", res);
      const json = await res.json();
      setCount(json.data.registration_count);
    } catch (error) {
      console.error("Error registering for event:", error);
    } finally {
      setLoading(null);
    }
  };

  const handleCancel = async () => {
    setLoading("cancel");
    // Simulate API call
    await new Promise((resolve) => setTimeout(resolve, 1000));
    setLoading(null);
  };

  return (
    <div key={event.id} className="border border-zinc-300 p-4">
      <h2 className="text-xl font-bold text-zinc-800">{event.name}</h2>
      <p className="text-zinc-600">Date: {event.event_date}</p>
      <p className="text-zinc-600">Registrations: {count}</p>
      <div className="mt-2 flex gap-2">
        <button
          onClick={handleRegister}
          disabled={loading !== null}
          className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50"
        >
          {loading === "register" ? "Registering..." : "Register"}
        </button>
        <button
          onClick={handleCancel}
          disabled={loading !== null}
          className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 disabled:opacity-50"
        >
          {loading === "cancel" ? "Canceling..." : "Cancel"}
        </button>
      </div>
    </div>
  );
}
