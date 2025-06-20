<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['image','name','description'];

    public function facilites(): BelongsToMany
    {
        return $this->belongsToMany(FlightClass::class,'flight_class_facility','facility_id','flight_class_id');
    }
}
