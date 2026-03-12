<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'itinerary_id'
        ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
