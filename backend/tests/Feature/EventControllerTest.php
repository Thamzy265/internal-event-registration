<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_requires_name_and_event_date()
    {
        $response = $this->postJson('/api/events', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'event_date']);
    }

    public function test_register_increments_registration_count()
    {
        $event = Event::factory()->create(['registration_count' => 0]);

        $response = $this->postJson("/api/events/{$event->id}/register");

        $response->assertOk()
            ->assertJsonPath('data.registration_count', 1);
        $this->assertEquals(1, $event->fresh()->registration_count);
    }

    public function test_cancel_decrements_registration_count()
    {
        $event = Event::factory()->create(['registration_count' => 3]);

        $response = $this->postJson("/api/events/{$event->id}/cancel");

        $response->assertOk()
            ->assertJsonPath('data.registration_count', 2);
    }

    public function test_cancel_cannot_reduce_count_below_zero()
    {
        //  most likely thing to break in a refactor
        $event = Event::factory()->create(['registration_count' => 0]);

        $response = $this->postJson("/api/events/{$event->id}/cancel");

        $response->assertStatus(400);
        $this->assertEquals(0, $event->fresh()->registration_count);
    }

    public function test_sequential_registrations_accumulate_correctly()
    {
        // proves the atomic increment() works correctly in a sequential context
        $event = Event::factory()->create(['registration_count' => 0]);

        for ($i = 0; $i < 10; $i++) {
            $this->postJson("/api/events/{$event->id}/register");
        }

        $this->assertEquals(10, $event->fresh()->registration_count);
    }

    public function test_show_returns_404_for_nonexistent_event()
    {
        $response = $this->getJson('/api/events/999');

        $response->assertStatus(404);
    }
}
