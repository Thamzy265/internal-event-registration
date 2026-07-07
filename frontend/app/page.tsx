import EventCard from "./components/event-card";
import { eventsApi } from "@/lib/api/events";

export default async function Home() {
  const events = await eventsApi.fetchEvents();

  return (
    <div className=" min-h-screen m-16">
      <h1 className="text-3xl font-bold text-zinc-800">Event List</h1>
      <div className="mt-4 flex flex-col gap-4 bg-zinc-50 p-4 rounded-md">
        {events.map((event) => (
          <EventCard key={event.id} event={event} />
        ))}
      </div>
    </div>
  );
}
