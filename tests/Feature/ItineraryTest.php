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

        Itinerary::factory()->count(3)->create();

        $response = $this->actingAs($user, 'sanctum')
                        ->getJson('/api/itineraries');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
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

        $response->assertStatus(200)
                 ->assertJsonFragment(['category' => 'plage'])
                 ->assertJsonMissing(['category' => 'montagne']);
    }

    public function test_filter_itineraries_by_duration()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        Itinerary::factory()->create([
            'duration' => 3
        ]);

        Itinerary::factory()->create([
            'duration' => 7
        ]);

        $response = $this->getJson('/api/itineraries?duration=3');

        $response->assertStatus(200)
                 ->assertJsonFragment(['duration' => 3])
                 ->assertJsonMissing(['duration' => 7]);
    }

    public function test_filter_itineraries_by_keyword()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        Itinerary::factory()->create([
            'title' => 'Voyage à Chefchaouen'
        ]);

        Itinerary::factory()->create([
            'title' => 'Plage d’Agadir'
        ]);

        $response = $this->getJson('/api/itineraries?keyword=Chefchaouen');

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Voyage à Chefchaouen'])
                 ->assertJsonMissing(['title' => 'Plage d’Agadir']);
    }
}