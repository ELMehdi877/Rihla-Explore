<?php

namespace Tests\Feature;

use App\Models\Itinerary;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ItineraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_itinerary()
    {
        // Créer un utilisateur fictif
        $user = User::factory()->create();

        // Les données de l’itinéraire
        $data = [
            'title' => 'Voyage à Chefchaouen',
            'category' => 'Montagne',
            'duration' => 3,
            'destinations' => [
                ['name' => 'Chefchaouen Ville', 'logement' => 'Hotel Bleu', 'activities' => ['Visiter la Médina']],
                ['name' => 'Akchour', 'logement' => 'Gîte local', 'activities' => ['Randonnée']]
            ]
        ];

        // Appel API en simulant l’utilisateur connecté
        $response = $this->actingAs($user, 'sanctum')
                         ->postJson('/api/itineraries', $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Voyage à Chefchaouen']);
    }

    public function test_get_all_itineraries()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                        ->getJson('/api/itineraries');

        $response->assertStatus(200);
    }

    public function test_filter_itineraries_by_category()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        Itinerary::factory()->create([
            'category' => 'plage'
        ]);

        Itinerary::factory()->create([
            'category' => 'montagne'
        ]);

        $response = $this->getJson('/api/itineraries?category=plage');

        $response->assertStatus(200);
    }
}