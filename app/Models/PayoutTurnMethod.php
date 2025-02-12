<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutTurnMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'benefits'
    ];

    protected $table = 'payout_turn_methods';

    public function savings(){
        return $this->hasMany(Saving::class);
    }
}
