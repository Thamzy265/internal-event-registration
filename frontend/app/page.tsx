import EventCard, { EventItem } from "./components/event-card";

async function getEvents(): Promise<EventItem[]> {
  console.log("Fetching events from API...");
  const res = await fetch("http://127.0.0.1:8000/api/events", {
    cache: "no-store",
  });

  if (!res.ok) {
    throw new Error(`Failed to fetch events: ${res.status}`);
  }

  console.log("Events fetched successfully.", res);
  const json = await res.json();
  return json.data;
}

export default async function Home() {
  const events = await getEvents();

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
