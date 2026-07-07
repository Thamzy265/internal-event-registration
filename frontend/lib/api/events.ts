import { EventItem, ApiResponse } from "@/lib/types";

const BASE_URL = process.env.NEXT_PUBLIC_API_URL ?? "http://127.0.0.1:8000";

class EventsApi {
  private baseUrl = `${BASE_URL}/api/events`;

  async fetchEvents(): Promise<EventItem[]> {
    const res = await fetch(`${this.baseUrl}`);
    const json = await res.json();
    return json.data;
  }

  async eventAction(
    id: number,
    action: "register" | "cancel",
  ): Promise<EventItem> {
    const res = await fetch(`${this.baseUrl}/${id}/${action}`, {
      method: "POST",
    });
    if (!res.ok)
      throw new Error(`Failed to ${action} registration: ${res.status}`);
    const json: ApiResponse<EventItem> = await res.json();
    return json.data;
  }
}

export const eventsApi = new EventsApi();
