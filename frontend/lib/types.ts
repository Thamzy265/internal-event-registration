export type EventItem = {
  id: number;
  name: string;
  event_date: string;
  registration_count: number;
};

export type ApiResponse<T> = {
  message: string;
  data: T;
};
