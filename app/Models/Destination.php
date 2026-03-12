<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    /** @use HasFactory<\Database\Factories\DestinationsFactory> */
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'logement',
        'activities'
    ];

    public function itinerary() {
        return $this->belongsTo(Itinerary::class);
    }
}
