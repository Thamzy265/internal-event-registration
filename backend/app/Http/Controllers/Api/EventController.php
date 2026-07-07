<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return response()->json(Event::orderBy('date')->get());
        return $this->success(Event::orderBy('event_date')->get(), 'Event list retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
        ]);

        return $this->success(Event::create($validatedData), 'Event created successfully', 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::find($id);

        if (! $event) {
            return $this->error('Event not found', 404);
        }

        return $this->success($event, 'Event retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'event_date' => 'date',
        ]);

        $event->update($validatedData);

        return response()->json($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return $this->success(null, 'Event deleted', 200);
    }

    public function register(Event $event)
    {
        $event->increment('registration_count');

        return $this->success($event->fresh(), 'Registration successful');
    }

    public function cancel(Event $event)
    {
        return DB::transaction(function () use ($event) {
            $locked = Event::lockForUpdate()->find($event->id);
            if ($locked->registration_count <= 0) {
                return $this->error('No registrations to cancel.', 400);
            }

            $locked->decrement('registration_count');

            return $this->success($locked->fresh(), 'Registration canceled successfully');
        });
    }
}
