<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreitinerariesRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateitinerariesRequest;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $itineraries = Itinerary::with('destinations')
            ->when($request->filled('category'), function($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->filled('duration'), function($query) use ($request) {
                $query->where('duration', $request->duration);
            })
            ->when($request->filled('keyword'), function($query) use ($request) {
                $query->where('title', 'like', '%' . $request->keyword . '%');
            })
            ->get();

        return response()->json($itineraries);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'duration' => 'required|integer',
            'destinations' => 'required|array|min:2',
            'destinations.*.name' => 'required|string',
        ]);

        $itinerary = Itinerary::create([
            'title' => $request->title,
            'category' => $request->category,
            'duration' => $request->duration,
            'image' => $request->image ?? null,
            'user_id' => $request->user()->id,
        ]);

        foreach ($request->destinations as $dest) {
            $itinerary->destinations()->create([
                'name' => $dest['name'],
                'logement' => $dest['logement'] ?? null,
                'activities' => isset($dest['activities']) ? json_encode($dest['activities']) : null,
            ]);
        }

        return response()->json($itinerary->load('destinations'));
    }

    /**
     * Display the specified resource.
     */
    public function show(itineraries $itineraries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(itineraries $itineraries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateitinerariesRequest $request, itineraries $itineraries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(itineraries $itineraries)
    {
        //
    }

    //Ajouter ou Retiré un itinéraire aux favoris
    public function toggleFavorite($id, Request $request)
    {
        $user = $request->user();
        
        $favorite = DB::table('favorites')
        ->where('user_id', $user->id)
        ->where('itinerary_id', $id)
        ->first();

        if ($favorite) {
            DB::table('favorites')
            ->where('id', $favorite->id)
            ->delete();

            return response()->json(['message', 'Retiré des favoris']);
        }
        else {
            Db::table('favorites')
            ->insert([
                'user_id' => $user->id,
                'itinerary_id' => $id,
            ]);
            
            return response()->json(['message', 'Ajouté aux favoris']);
        }
    }

    //Itinéraires les plus populaires
    public function mostPopular()
    {
        $itineraries = DB::table('itineraries')
            ->leftJoin('favorites', 'itineraries.id', '=', 'favorites.itinerary_id')
            ->select('itineraries.*', DB::raw('COUNT(favorites.id) as favorites_count'))
            ->groupBy('itineraries.id')
            ->orderByDesc('favorites_count')
            ->get();

        

           

        return response()->json([$itineraries]);
    }

    public function itinerariesByCategory()
    {
        $stats = DB::table('itineraries')
        ->select('category', DB::raw('COUNT(*) as total'))
        ->groupBy('category')
        ->get();
        
        return response()->json($stats);
    }

}
