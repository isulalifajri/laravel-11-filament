<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FlightClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['flight_id','class_type','price','total_seats'];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class,'flight_class_facility','flight_class_id','facility_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
