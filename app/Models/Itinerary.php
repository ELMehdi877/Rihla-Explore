<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    /** @use HasFactory<\Database\Factories\ItinerariesFactory> */
    use HasFactory;

    public $timestamps = true;


    protected $fillable = [
        'title',
        'category',
        'duration',
        'image',
        'user_id'
    ];


    public function destinations(){ 
        return $this->hasMany(Destination::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
