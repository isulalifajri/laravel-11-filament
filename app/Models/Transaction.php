<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code','flight_id','flight_class_id','name','email','phone','number_of_passenger','promo_code_id','payment_status','subtotal','grandtotal'];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    public function classes(): BelongsTo
    {
        return $this->belongsTo(FlightClass::class);
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(TransactionPassenger::class);
    }
}
