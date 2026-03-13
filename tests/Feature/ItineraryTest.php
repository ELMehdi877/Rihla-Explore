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

    public function test_get_all_itineraries()
{
    $user = User::factory()->create();

    // créer un itinéraire dans la base
    \DB::table('itineraries')->insert([
        'title' => 'Voyage Atlas',
        'category' => 'Montagne',
        'duration' => 4,
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // appel API
    $response = $this->getJson('/api/itineraries');

    $response->assertStatus(200)
             ->assertJsonFragment([
                 'title' => 'Voyage Atlas'
             ]);
}
}