<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::orderBy('date')->get();
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

        return response()->json(Event::create($validatedData), 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Event::findOrFail($id);
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

        return response()->json(null, 204);
    }

    public function register(Event $event)
    {
        $event->increment('registration_count');

        return $event->fresh();
    }

    public function cancel(Event $event)
    {
        return DB::transaction(function () use ($event) {
            $locked = Event::lockForUpdate()->find($event->id);
            if ($locked->registration_count <= 0) {
                return response()->json(['error' => 'No registrations to cancel.'], 400);
            }

            $locked->decrement('registration_count');

            return $locked->fresh();
        });
    }
}
