<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

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
}