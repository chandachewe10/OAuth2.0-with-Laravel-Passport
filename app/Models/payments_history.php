<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments_history extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'currency',
        'externalId',
        'partyIdType',
        'partyId',
        'payerMessage',
        'payeeMessage',
        
    ];
}
